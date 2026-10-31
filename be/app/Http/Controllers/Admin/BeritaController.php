<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\User;
use App\Notifications\BeritaSubmittedNotification;
use App\Notifications\BeritaReviewNotification;
use App\Support\ExcelSafe;
use App\Services\HtmlSanitizer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaController extends Controller
{
    private function isAdmin(?\App\Models\User $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) $user?->hasAnyRole(['super-admin', 'admin']);
    }

    public function index(Request $request): View
    {
        $isAdmin = $this->isAdmin($request->user());
        $trash = $request->boolean('trash') && $isAdmin;
        $categories = Category::orderBy('name')->get(['uuid', 'name']);
        $query = ($trash ? Berita::onlyTrashed() : Berita::query())
            ->when(! $this->isAdmin($request->user()), fn ($builder) => $builder->where('user_uuid', $request->user()->uuid))
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = $request->string('search')->trim()->toString();
                $builder->where(function ($builder) use ($search) {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->input('status')))
            ->when($request->boolean('featured'), fn ($builder) => $builder->where('is_featured', true))
            ->when($request->filled('category_uuid'), fn ($builder) => $builder->where('category_uuid', $request->input('category_uuid')))
            ->when($request->filled('start_date'), fn ($builder) => $builder->whereDate('scheduled_at', '>=', $request->input('start_date')))
            ->when($request->filled('end_date'), fn ($builder) => $builder->whereDate('scheduled_at', '<=', $request->input('end_date')));

        $stats = [
            'total' => (clone $query)->count(),
            'published' => (clone $query)->where('status', 'published')->count(),
            'draft' => (clone $query)->where('status', 'draft')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'featured' => (clone $query)->where('is_featured', true)->count(),
            'trash' => $isAdmin ? Berita::onlyTrashed()->count() : 0,
        ];

        $sort = $request->input('sort', 'terbaru');
        match ($sort) {
            'terlama' => $query->orderBy('created_at'),
            'judul' => $query->orderBy('title'),
            'populer' => $query->orderByDesc('views'),
            default => $query->latest(),
        };
        $beritas = $query->with(['user', 'category'])->paginate(10)->withQueryString();

        return view('pages.beritas.index', compact('beritas', 'stats', 'isAdmin', 'trash', 'sort', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('pages.beritas.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $user = $request->user();
        $storedMedia = [];
        $imagePath = null;
        $pendingBerita = null;

        try {
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('images', 'public');
                $storedMedia[] = $imagePath;
            }

            DB::transaction(function () use ($validated, $user, $imagePath, $request, &$storedMedia, &$pendingBerita) {
                $berita = Berita::create([
                    'user_uuid' => $user->uuid,
                    'category_uuid' => $validated['category_uuid'],
                    'title' => $validated['title'],
                    'slug' => $this->uniqueSlug(Str::slug($validated['title'])),
                    'excerpt' => $validated['excerpt'] ?? null,
                    'content' => HtmlSanitizer::clean($validated['content']),
                    'featured_image' => $imagePath,
                    'video' => $validated['video'] ?? null,
                    'scheduled_at' => $validated['scheduled_at'] ?? null,
                    'tagging' => $validated['tagging'] ?? null,
                    'status' => $this->isAdmin($user) ? $validated['status'] : 'pending',
                    'search_engine' => $validated['search_engine'],
                    'is_featured' => $request->boolean('is_featured'),
                    'is_popular' => $request->boolean('is_popular'),
                    'is_urgent' => $request->boolean('is_urgent'),
                ]);

                if ($berita->status === 'pending') {
                    $pendingBerita = $berita;
                }
                $this->storeImages($berita, $request, $storedMedia);
                $this->storeAttachments($berita, $request, $storedMedia);
            });
        } catch (\Throwable $exception) {
            foreach ($storedMedia as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            throw $exception;
        }

        if ($pendingBerita) {
            $this->notifyApprovers($pendingBerita);
        }

        return redirect()->route('beritas.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function show(Berita $berita): View
    {
        $this->authorizeOwnership($berita);
        $berita->load(['user', 'category', 'images', 'attachments']);
        $logs = AuditLog::query()
            ->where('auditable_type', Berita::class)
            ->where('auditable_id', $berita->uuid)
            ->latest()
            ->limit(20)
            ->get();

        return view('pages.beritas.show', compact('berita', 'logs'));
    }

    public function approve(Berita $berita): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang dapat menyetujui berita.');
        if ($berita->status !== 'pending') {
            return back()->with('error', 'Hanya berita berstatus pending yang dapat disetujui.');
        }

        $berita->update(['status' => 'published']);
        $this->notifyAuthorOfReview($berita, 'approved');

        return back()->with('success', "Berita \"{$berita->title}\" disetujui dan dipublikasikan.");
    }

    public function reject(Request $request, Berita $berita): RedirectResponse
    {
        abort_unless($this->isAdmin($request->user()), 403, 'Hanya admin yang dapat menolak berita.');
        if ($berita->status !== 'pending') {
            return back()->with('error', 'Hanya berita berstatus pending yang dapat ditolak.');
        }

        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
            'catatan.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);
        $berita->update(['status' => 'draft']);
        $this->notifyAuthorOfReview($berita, 'rejected', trim($validated['catatan']));

        return back()->with('success', "Berita \"{$berita->title}\" dikembalikan sebagai draft.");
    }

    public function mintaRevisi(Request $request, Berita $berita): RedirectResponse
    {
        abort_unless($this->isAdmin($request->user()), 403, 'Hanya admin yang dapat meminta revisi berita.');
        if ($berita->status !== 'pending') {
            return back()->with('error', 'Hanya berita berstatus pending yang dapat dimintai revisi.');
        }

        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ], [
            'catatan.required' => 'Catatan revisi wajib diisi.',
            'catatan.max' => 'Catatan revisi maksimal 500 karakter.',
        ]);
        $this->notifyAuthorOfReview($berita, 'revision', trim($validated['catatan']));

        return back()->with('success', "Catatan revisi dikirim kepada penulis berita \"{$berita->title}\".");
    }

    public function edit(Berita $berita): View
    {
        $this->authorizeOwnership($berita);
        $berita->load(['images', 'attachments']);
        $categories = Category::orderBy('name')->get();

        return view('pages.beritas.edit', compact('berita', 'categories'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $this->authorizeOwnership($berita);
        $validated = $request->validate($this->rules($berita));
        $oldImage = $berita->featured_image;
        $newImage = null;
        $storedMedia = [];
        $removedImagePaths = [];
        $removedAttachmentPaths = [];
        $previousStatus = $berita->status;

        try {
            if ($request->hasFile('featured_image')) {
                $newImage = $request->file('featured_image')->store('images', 'public');
                $storedMedia[] = $newImage;
            }

            DB::transaction(function () use ($berita, $validated, $request, $newImage, &$storedMedia, &$removedImagePaths, &$removedAttachmentPaths) {
                $berita->update([
                    'category_uuid' => $validated['category_uuid'],
                    'title' => $validated['title'],
                    'slug' => $this->uniqueSlug(Str::slug($validated['title']), $berita->uuid),
                    'excerpt' => $validated['excerpt'] ?? null,
                    'content' => HtmlSanitizer::clean($validated['content']),
                    'featured_image' => $newImage ?? $berita->featured_image,
                    'video' => $validated['video'] ?? null,
                    'scheduled_at' => $validated['scheduled_at'] ?? null,
                    'tagging' => $validated['tagging'] ?? null,
                    'status' => $this->isAdmin($request->user()) ? $validated['status'] : 'pending',
                    'search_engine' => $validated['search_engine'],
                    'is_featured' => $request->boolean('is_featured'),
                    'is_popular' => $request->boolean('is_popular'),
                    'is_urgent' => $request->boolean('is_urgent'),
                ]);

                $removeImageUuids = (array) $request->input('remove_images', []);
                if ($removeImageUuids !== []) {
                    $removedImagePaths = $berita->images()
                        ->whereIn('uuid', $removeImageUuids)
                        ->pluck('image_path')
                        ->all();
                    $berita->images()->whereIn('uuid', $removeImageUuids)->delete();
                }

                $removeAttachmentUuids = (array) $request->input('remove_attachments', []);
                if ($removeAttachmentUuids !== []) {
                    $removedAttachmentPaths = $berita->attachments()
                        ->whereIn('uuid', $removeAttachmentUuids)
                        ->pluck('file_path')
                        ->all();
                    $berita->attachments()->whereIn('uuid', $removeAttachmentUuids)->delete();
                }

                $this->storeImages($berita, $request, $storedMedia);
                $this->storeAttachments($berita, $request, $storedMedia);
            });
        } catch (\Throwable $exception) {
            foreach ($storedMedia as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            throw $exception;
        }

        $pathsToDelete = array_filter([
            ...$removedImagePaths,
            ...$removedAttachmentPaths,
            $newImage ? $oldImage : null,
        ]);
        foreach (array_unique($pathsToDelete) as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        if ($berita->status === 'pending' && (! $this->isAdmin($request->user()) || $previousStatus !== 'pending')) {
            $this->notifyApprovers($berita);
        }

        return redirect()->route('beritas.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $this->authorizeOwnership($berita);
        $berita->delete();

        return back()->with('success', 'Berita berhasil dipindahkan ke arsip.');
    }

    public function restore(string $uuid): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang dapat memulihkan berita.');

        $berita = Berita::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $berita->restore();

        return back()->with('success', "Berita \"{$berita->title}\" berhasil dipulihkan.");
    }

    public function forceDestroy(string $uuid): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang dapat menghapus berita permanen.');

        $berita = Berita::onlyTrashed()->with(['images', 'attachments'])->where('uuid', $uuid)->firstOrFail();
        $paths = array_filter([
            $berita->featured_image,
            ...$berita->images->pluck('image_path')->all(),
            ...$berita->attachments->pluck('file_path')->all(),
        ]);

        DB::transaction(function () use ($berita): void {
            $berita->images()->delete();
            $berita->attachments()->delete();
            $berita->forceDelete();
        });

        foreach (array_unique($paths) as $path) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Berita dan seluruh medianya berhasil dihapus permanen.');
    }

    public function bulkRestore(Request $request): RedirectResponse
    {
        abort_unless($this->isAdmin($request->user()), 403, 'Hanya admin yang dapat memulihkan berita.');
        $ids = $request->input('ids', []);
        if (! is_array($ids) || $ids === []) {
            return back()->with('error', 'Pilih berita yang akan dipulihkan.');
        }

        $ids = array_slice(array_unique(array_filter($ids, 'is_string')), 0, 100);
        $beritas = Berita::onlyTrashed()->whereIn('uuid', $ids)->get();
        foreach ($beritas as $berita) {
            $berita->restore();
        }

        return back()->with('success', "{$beritas->count()} berita berhasil dipulihkan.");
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if (! is_array($ids) || $ids === []) {
            return back()->with('error', 'Pilih berita yang akan diarsipkan.');
        }

        $ids = array_slice(array_unique(array_filter($ids, 'is_string')), 0, 100);
        $query = Berita::whereIn('uuid', $ids)
            ->when(! $this->isAdmin($request->user()), fn ($builder) => $builder->where('user_uuid', $request->user()->uuid));
        $beritas = $query->get();
        foreach ($beritas as $berita) {
            $berita->delete();
        }

        return back()->with('success', "{$beritas->count()} berita berhasil dipindahkan ke arsip.");
    }

    public function importForm(): View
    {
        return view('pages.beritas.import');
    }

    public function logAudit(Request $request): View
    {
        abort_unless($this->isAdmin($request->user()), 403, 'Hanya admin yang dapat melihat log audit berita.');

        $events = ['created' => 'Ditambahkan', 'updated' => 'Diperbarui', 'deleted' => 'Diarsipkan', 'restored' => 'Dipulihkan'];
        $logs = AuditLog::query()
            ->where('auditable_type', Berita::class)
            ->when($request->filled('event'), fn ($query) => $query->where('event', $request->input('event')))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('created_at', '>=', $request->input('start_date')))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('created_at', '<=', $request->input('end_date')))
            ->when($request->filled('user_uuid'), fn ($query) => $query->where('user_uuid', $request->input('user_uuid')))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();
        $users = AuditLog::query()
            ->where('auditable_type', Berita::class)
            ->whereNotNull('user_uuid')
            ->select('user_uuid', 'user_name')
            ->distinct()
            ->orderBy('user_name')
            ->get();

        return view('pages.beritas.log-audit', compact('logs', 'events', 'users'));
    }

    public function laporanPdf(Request $request)
    {
        $items = $this->reportQuery($request)->limit(1000)->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.beritas.laporan', [
            'items' => $items,
            'total' => $items->count(),
            'totalViews' => $items->sum('views'),
            'perStatus' => $items->groupBy('status')->map->count(),
            'perCategory' => $items->groupBy(fn (Berita $berita) => $berita->category?->name ?? '-')->map->count()->sortDesc(),
            'startDate' => $request->input('start_date'),
            'endDate' => $request->input('end_date'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-berita-' . now()->format('Ymd-His') . '.pdf');
    }

    public function laporanExcel(Request $request)
    {
        $items = $this->reportQuery($request)->limit(2000)->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['No', 'Judul', 'Ringkasan', 'Kategori', 'Penulis', 'Status', 'Tanggal Publikasi', 'Jumlah Dilihat'], null, 'A1');

        foreach ($items as $index => $berita) {
            $sheet->fromArray(ExcelSafe::row([
                $index + 1,
                $berita->title,
                $berita->excerpt,
                $berita->category?->name,
                $berita->user?->name,
                $berita->status,
                $berita->scheduled_at?->format('Y-m-d H:i'),
                $berita->views,
            ]), null, 'A' . ($index + 2));
        }
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet): void {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        }, 'laporan-berita-' . now()->format('Ymd-His') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function laporanPptx(Request $request)
    {
        $items = $this->reportQuery($request)->limit(500)->get();
        $presentation = new \PhpOffice\PhpPresentation\PhpPresentation();
        $presentation->getDocumentProperties()->setTitle('Laporan Berita');
        $presentation->getDocumentProperties()->setCreator(config('app.name'));
        $slide = $presentation->getActiveSlide();

        $addText = static function ($slide, string $text, int $y, int $size, bool $bold = false): void {
            $shape = $slide->createRichTextShape();
            $shape->setOffsetX(40)->setOffsetY($y)->setWidth(880)->setHeight(36);
            $shape->createTextRun($text)->getFont()->setName('Arial')->setSize($size)->setBold($bold);
            $slide->addShape($shape);
        };
        $addText($slide, 'Laporan Berita', 60, 30, true);
        $addText($slide, $this->reportPeriodLabel($request), 110, 18);
        $addText($slide, 'Jumlah berita: ' . $items->count() . '   |   Total dilihat: ' . number_format($items->sum('views')), 160, 16);

        foreach ($items->chunk(15) as $page => $chunk) {
            $slide = $presentation->createSlide();
            $addText($slide, 'Daftar Berita' . ($items->count() > 15 ? ' - Bagian ' . ($page + 1) : ''), 30, 22, true);
            foreach ($chunk->values() as $index => $berita) {
                $addText($slide, ($page * 15 + $index + 1) . '. ' . Str::limit($berita->title, 90) . ' | ' . ucfirst($berita->status) . ' | ' . number_format($berita->views) . ' dilihat', 80 + ($index * 34), 12);
            }
        }

        return response()->streamDownload(function () use ($presentation): void {
            \PhpOffice\PhpPresentation\IOFactory::createWriter($presentation, 'PowerPoint2007')->save('php://output');
        }, 'laporan-berita-' . now()->format('Ymd-His') . '.pptx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ]);
    }

    private function reportQuery(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'scheduled', 'pending'])],
            'category_uuid' => ['nullable', 'exists:categories,uuid'],
        ]);

        return Berita::query()
            ->when(! $this->isAdmin($request->user()), fn ($query) => $query->where('user_uuid', $request->user()->uuid))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(fn ($nested) => $nested->where('title', 'like', "%{$search}%")->orWhere('excerpt', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->boolean('featured'), fn ($query) => $query->where('is_featured', true))
            ->when($request->filled('category_uuid'), fn ($query) => $query->where('category_uuid', $request->input('category_uuid')))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('scheduled_at', '>=', $request->input('start_date')))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('scheduled_at', '<=', $request->input('end_date')))
            ->with(['user', 'category'])
            ->orderByDesc('scheduled_at')
            ->orderByDesc('created_at');
    }

    private function reportPeriodLabel(Request $request): string
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            return $request->input('start_date') . ' s/d ' . $request->input('end_date');
        }

        return $request->filled('start_date')
            ? 'Sejak ' . $request->input('start_date')
            : ($request->filled('end_date') ? 'Sampai ' . $request->input('end_date') : 'Semua Periode');
    }

    public function importTemplate()
    {
        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['judul', 'ringkasan', 'isi', 'kategori', 'tanggal_terbit', 'status', 'tags']);
            fputcsv($output, ['Contoh Berita', 'Ringkasan berita', 'Isi lengkap berita...', 'umum', now()->format('Y-m-d'), 'draft', 'berita, pemkot']);
            fclose($output);
        }, 'template-import-berita.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importPreview(Request $request): View
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:5120'],
        ]);

        $rows = \Maatwebsite\Excel\Facades\Excel::toArray([], $request->file('file'))[0] ?? [];
        if (count($rows) > 201) {
            return back()->withInput()->withErrors(['file' => 'File maksimal berisi 200 baris data.']);
        }

        $categoryMap = $this->categoryMap();
        $preview = [];
        foreach ($rows as $index => $row) {
            if ($index === 0 && strtolower(trim((string) ($row[0] ?? ''))) === 'judul') {
                continue;
            }

            $data = [
                'title' => trim((string) ($row[0] ?? '')),
                'excerpt' => trim((string) ($row[1] ?? '')),
                'content' => trim((string) ($row[2] ?? '')),
                'category' => trim((string) ($row[3] ?? '')),
                'scheduled_at' => trim((string) ($row[4] ?? '')),
                'status' => strtolower(trim((string) ($row[5] ?? 'draft'))) ?: 'draft',
                'tagging' => trim((string) ($row[6] ?? '')),
            ];
            $errors = $this->validateImportRow($data, $categoryMap);
            $preview[] = ['line' => $index + 1, 'data' => $data, 'errors' => $errors];
        }

        $token = (string) Str::uuid();
        Cache::store('database')->put('berita_import_' . $token, [
            'rows' => $preview,
            'user_uuid' => $request->user()->uuid,
        ], 600);

        return view('pages.beritas.import', [
            'preview' => $preview,
            'validCount' => count(array_filter($preview, fn (array $row): bool => $row['errors'] === [])),
            'token' => $token,
        ]);
    }

    public function importConfirm(Request $request): RedirectResponse
    {
        $request->validate(['token' => ['required', 'string']]);
        $key = 'berita_import_' . $request->input('token');
        $cached = Cache::store('database')->get($key);
        if (! $cached || ($cached['user_uuid'] ?? null) !== $request->user()->uuid) {
            return redirect()->route('beritas.import')->with('error', 'Sesi import kedaluwarsa. Silakan unggah ulang.');
        }

        $categoryMap = $this->categoryMap();
        $created = 0;
        $pendingBeritas = [];
        DB::transaction(function () use ($cached, $categoryMap, $request, &$created, &$pendingBeritas): void {
            foreach ($cached['rows'] as $row) {
                $data = $row['data'];
                if ($this->validateImportRow($data, $categoryMap) !== []) {
                    continue;
                }

                $category = $categoryMap[strtolower($data['category'])];
                $berita = Berita::create([
                    'user_uuid' => $request->user()->uuid,
                    'category_uuid' => $category->uuid,
                    'title' => $data['title'],
                    'slug' => $this->uniqueSlug(Str::slug($data['title'])),
                    'excerpt' => $data['excerpt'] ?: null,
                    'content' => HtmlSanitizer::clean($data['content']),
                    'scheduled_at' => $this->parseImportDate($data['scheduled_at']),
                    'tagging' => $data['tagging'] ?: null,
                    'status' => $this->isAdmin($request->user()) ? $data['status'] : 'pending',
                    'search_engine' => 'index',
                ]);
                if ($berita->status === 'pending') {
                    $pendingBeritas[] = $berita;
                }
                $created++;
            }
        });
        Cache::store('database')->forget($key);

        foreach ($pendingBeritas as $berita) {
            $this->notifyApprovers($berita);
        }

        return redirect()->route('beritas.index')->with('success', "{$created} berita berhasil diimpor.");
    }

    private function notifyApprovers(Berita $berita): void
    {
        try {
            $approvers = User::role(['super-admin', 'admin'])
                ->when($berita->user_uuid, fn ($query) => $query->where('uuid', '!=', $berita->user_uuid))
                ->get();
            $authorName = $berita->user?->name ?? 'Pengguna';
        } catch (\Throwable $exception) {
            report($exception);
            \Illuminate\Support\Facades\Log::warning('Gagal mencari penerima notifikasi berita pending.', [
                'berita_uuid' => $berita->uuid,
                'exception' => $exception->getMessage(),
            ]);

            return;
        }

        foreach ($approvers as $approver) {
            try {
                $approver->notify(new BeritaSubmittedNotification(
                    $berita->title,
                    $authorName,
                    $berita->uuid,
                ));
            } catch (\Throwable $exception) {
                report($exception);
                \Illuminate\Support\Facades\Log::warning('Gagal mengirim notifikasi berita pending kepada admin.', [
                    'berita_uuid' => $berita->uuid,
                    'admin_uuid' => $approver->uuid,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }
    }

    private function notifyAuthorOfReview(Berita $berita, string $action, ?string $note = null): void
    {
        $author = $berita->user;
        if (! $author) {
            return;
        }

        try {
            $author->notify(new BeritaReviewNotification($berita->title, $berita->uuid, $action, $note));
        } catch (\Throwable $exception) {
            report($exception);
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim notifikasi hasil review berita.', [
                'berita_uuid' => $berita->uuid,
                'author_uuid' => $author->uuid,
                'review_action' => $action,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    public function export(Request $request)
    {
        $isAdmin = $this->isAdmin($request->user());
        $query = ($request->boolean('trash') && $isAdmin ? Berita::onlyTrashed() : Berita::query())
            ->when(! $isAdmin, fn ($builder) => $builder->where('user_uuid', $request->user()->uuid))
            ->when($request->filled('search'), function ($builder) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $builder->where(fn ($nested) => $nested->where('title', 'like', "%{$search}%")->orWhere('excerpt', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->input('status')))
            ->when($request->boolean('featured'), fn ($builder) => $builder->where('is_featured', true))
            ->when($request->filled('category_uuid'), fn ($builder) => $builder->where('category_uuid', $request->input('category_uuid')))
            ->when($request->filled('start_date'), fn ($builder) => $builder->whereDate('scheduled_at', '>=', $request->input('start_date')))
            ->when($request->filled('end_date'), fn ($builder) => $builder->whereDate('scheduled_at', '<=', $request->input('end_date')))
            ->with(['user', 'category']);

        match ($request->input('sort', 'terbaru')) {
            'terlama' => $query->orderBy('created_at'),
            'judul' => $query->orderBy('title'),
            'populer' => $query->orderByDesc('views'),
            default => $query->latest(),
        };
        $filename = 'berita-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Judul', 'Ringkasan', 'Kategori', 'Penulis', 'Status', 'Tanggal Terbit', 'Dilihat', 'Tags', 'Arsip']);
            foreach ($query->cursor() as $berita) {
                fputcsv($output, ExcelSafe::row([
                    $berita->title,
                    $berita->excerpt,
                    $berita->category?->name,
                    $berita->user?->name,
                    $berita->status,
                    $berita->scheduled_at?->format('Y-m-d H:i'),
                    $berita->views,
                    $berita->tagging,
                    $berita->deleted_at?->format('Y-m-d H:i'),
                ]));
            }
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function categoryMap(): array
    {
        $map = [];
        foreach (Category::orderBy('name')->get() as $category) {
            $map[strtolower(trim($category->name))] = $category;
            $map[strtolower(trim($category->slug))] = $category;
        }

        return $map;
    }

    private function validateImportRow(array $data, array $categoryMap): array
    {
        $errors = [];
        if ($data['title'] === '' || mb_strlen($data['title']) > 255) {
            $errors[] = 'Judul wajib diisi dan maksimal 255 karakter.';
        }
        if ($data['content'] === '') {
            $errors[] = 'Isi berita wajib diisi.';
        }
        if ($data['category'] === '' || ! isset($categoryMap[strtolower($data['category'])])) {
            $errors[] = 'Kategori tidak dikenal.';
        }
        if (! in_array($data['status'], ['draft', 'published', 'scheduled', 'pending'], true)) {
            $errors[] = 'Status harus draft, published, scheduled, atau pending.';
        }
        if ($data['scheduled_at'] !== '' && ! $this->parseImportDate($data['scheduled_at'])) {
            $errors[] = 'Tanggal terbit tidak valid.';
        }

        return $errors;
    }

    private function parseImportDate(string $value): ?Carbon
    {
        if (trim($value) === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value));
            }

            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function rules(?Berita $berita = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('beritas', 'title')->ignore($berita?->uuid, 'uuid')],
            'category_uuid' => ['required', 'exists:categories,uuid'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png,webp', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['uuid'],
            'remove_attachments' => ['nullable', 'array'],
            'remove_attachments.*' => ['uuid'],
            'video' => ['nullable', 'string', 'max:255'],
            'tagging' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'scheduled', 'pending'])],
            'search_engine' => ['required', Rule::in(['index', 'noindex'])],
            'is_featured' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'is_urgent' => ['nullable', 'boolean'],
        ];
    }

    private function storeImages(Berita $berita, Request $request, array &$storedMedia): void
    {
        $order = (int) $berita->images()->max('sort_order');

        foreach ($request->file('images', []) as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store('berita/images', 'public');
            $storedMedia[] = $path;
            $berita->images()->create([
                'image_path' => $path,
                'sort_order' => ++$order,
            ]);
        }
    }

    private function storeAttachments(Berita $berita, Request $request, array &$storedMedia): void
    {
        foreach ($request->file('attachments', []) as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store('berita/attachments', 'public');
            $storedMedia[] = $path;
            $berita->attachments()->create([
                'file_path' => $path,
                'nama_asli' => mb_substr(htmlspecialchars(basename($file->getClientOriginalName()), ENT_QUOTES, 'UTF-8'), 0, 255),
                'ukuran' => $file->getSize(),
            ]);
        }
    }

    private function uniqueSlug(string $slug, ?string $ignoreUuid = null): string
    {
        $baseSlug = $slug !== '' ? $slug : 'berita';
        $candidate = $baseSlug;
        $suffix = 1;

        while (Berita::withTrashed()
            ->where('slug', $candidate)
            ->when($ignoreUuid, fn ($query) => $query->where('uuid', '!=', $ignoreUuid))
            ->exists()) {
            $candidate = $baseSlug . '-' . $suffix++;
        }

        return $candidate;
    }

    private function authorizeOwnership(Berita $berita): void
    {
        if (! $this->isAdmin() && $berita->user_uuid !== auth()->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke berita ini.');
        }
    }
}
