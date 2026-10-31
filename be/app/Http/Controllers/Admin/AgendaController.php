<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Http\Requests\Agenda\StoreAgendaRequest;
use App\Http\Requests\Agenda\UpdateAgendaRequest;
use App\Models\Agenda;
use App\Models\AgendaImage;
use App\Support\ExcelSafe;
use App\Models\Category;
use App\Models\User;
use App\Notifications\AgendaApprovedNotification;
use App\Notifications\AgendaRejectedNotification;
use App\Notifications\AgendaSubmittedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\HtmlSanitizer;
use App\Services\WhatsappGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgendaController extends Controller
{
    
    use HandlesTransactions;
private const IMAGE_DISK = 'public';

    private const IMAGE_DIRECTORY = 'images';

    private const ATTACHMENT_DIRECTORY = 'agenda-attachments';

    private const ATTACHMENT_DISK = 'local';

    private const ATTACHMENT_MAX_TOTAL = 25 * 1024 * 1024; // 25 MB gabungan

    /**
     * Admin/super-admin boleh publish langsung & menyetujui agenda OPD.
     * Role lain (mis. opd) hanya boleh input -> status dikunci 'pending'.
     */
    private function isApprover(?\App\Models\User $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) $user?->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Display a listing of the agendas.
     */
    public function index(Request $request): View
    {
        $isApprover = $this->isApprover($request->user());

        $baseQuery = $this->filteredAgendas($request);

        // Statistik dihitung dari query yang SUDAH difilter (tanpa paginasi),
        // sehingga angka pada card selalu sesuai dengan data hasil filter.
        $stats = [
            'total'     => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'draft'     => (clone $baseQuery)->where('status', 'draft')->count(),
            'featured'  => (clone $baseQuery)->where('is_featured', true)->count(),
            'popular'   => (clone $baseQuery)->where('is_popular', true)->count(),
            'trash'     => $isApprover ? Agenda::onlyTrashed()->count() : 0,
        ];

        $this->applySort($baseQuery, $request->get('sort', 'terbaru'));

        $agendas = $baseQuery
            ->with(['user', 'category', 'images:uuid,agenda_uuid,image_path,sort_order'])
            ->paginate(10)->withQueryString();

        $search = $request->get('search');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $status = $request->get('status');
        $trash = $request->boolean('trash');
        $isApprover = $this->isApprover($request->user());
        $waktu = $request->get('waktu', 'semua');
        $jenis = $request->get('jenis', '');
        $sort = $request->get('sort', 'terbaru');
        $flag = $request->get('flag', '');
        $view = $request->get('view', 'daftar');

        return view('pages.agendas.index', compact('agendas', 'stats', 'start_date', 'end_date', 'status', 'search', 'isApprover', 'trash', 'waktu', 'jenis', 'sort', 'flag', 'view'));
    }

    /**
     * Query agenda dengan seluruh filter daftar (dipakai index, kalender, ekspor).
     */
    private function filteredAgendas(Request $request)
    {
        $status = $request->get('status');
        $trash = $request->boolean('trash');
        $waktu = $request->get('waktu', 'semua');
        $jenis = $request->get('jenis', '');
        $flag = $request->get('flag', '');
        $isApprover = $this->isApprover($request->user());

        // Arsip (trash) hanya untuk admin/superadmin (approver)
        // OPD tidak boleh melihat arsip sama sekali
        if ($trash && ! $isApprover) {
            $trash = false;
        }

        return Agenda::query()
            ->when($trash, fn ($query) => $query->onlyTrashed())
            // OPD hanya melihat & mengelola agenda miliknya sendiri
            ->when(! $isApprover, fn ($query) => $query->where('user_uuid', $request->user()->uuid))
            ->when($request->get('start_date'), fn ($query, $v) => $query->whereDate('created_at', '>=', $v))
            ->when($request->get('end_date'), fn ($query, $v) => $query->whereDate('created_at', '<=', $v))
            ->when($status, fn ($query, $v) => $query->where('status', $v))
            ->when($jenis, fn ($query, $v) => $query->where('jenis_agenda', $v))
            ->when($flag === 'featured', fn ($query) => $query->where('is_featured', true))
            ->when($flag === 'popular', fn ($query) => $query->where('is_popular', true))
            ->when($waktu === 'hari_ini', fn ($query) => $query->whereDate('waktu_mulai', today()))
            ->when($waktu === 'minggu_ini', fn ($query) => $query->whereBetween('waktu_mulai', [now()->copy()->startOfWeek(), now()->copy()->endOfWeek()]))
            ->when($waktu === 'bulan_ini', fn ($query) => $query->whereYear('waktu_mulai', now()->year)->whereMonth('waktu_mulai', now()->month))
            ->when($waktu === 'terlewat', function ($query) {
                $query->where(function ($q) {
                    $q->where('waktu_akhir', '<', now())->orWhere(function ($qq) {
                        $qq->whereNull('waktu_akhir')->where('waktu_mulai', '<', now());
                    });
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Terapkan urutan sesuai pilihan pengguna.
     */
    private function applySort($query, ?string $sort): void
    {
        match ($sort) {
            'terdekat' => $query->orderByRaw('waktu_mulai IS NULL, waktu_mulai ASC'),
            'populer'  => $query->orderByDesc('views'),
            'judul'    => $query->orderBy('title'),
            default    => $query->latest('created_at'),
        };
    }

    /**
     * Show the form for creating a new agenda.
     */
    public function create(): View
    {
        $categories = Cache::remember('agenda_categories', 3600, fn () => Category::orderBy('slug')->get());

        return view('pages.agendas.create', compact('categories'));
    }

    /**
     * Store a newly created agenda.
     */
    public function store(StoreAgendaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $storedFiles = [];

        DB::beginTransaction();

        try {
            $agenda = new Agenda();
            $agenda->user_uuid = $request->user()->uuid;
            $validated['content'] = HtmlSanitizer::clean($validated['content']);
            // OPD wajib lewat approval: paksa 'pending' walau form mengirim status lain
            $status = $this->isApprover($request->user()) ? $validated['status'] : 'pending';
            $agenda->fill([
                'category_uuid' => $validated['category_uuid'],
                'title'         => $validated['title'],
                'slug'          => $this->uniqueSlug(Str::slug($validated['title'])),
                'excerpt'       => $validated['excerpt'] ?? null,
                'content'       => $validated['content'],
                'scheduled_at'  => $validated['scheduled_at'] ?? now(),
                'jenis_agenda'  => $validated['jenis_agenda'],
                'lokasi'        => $validated['jenis_agenda'] === 'offline' ? ($validated['lokasi'] ?? null) : null,
                'waktu_mulai'   => $validated['waktu_mulai'] ?? null,
                'waktu_akhir'   => $validated['waktu_akhir'] ?? null,
                'url'           => $validated['url'] ?? null,
                'tagging'       => $validated['tagging'] ?? null,
                'video'         => $validated['video'] ?? null,
                'status'        => $status,
                'search_engine' => $validated['search_engine'],
                'is_featured'   => $this->isApprover($request->user()) && $request->boolean('is_featured'),
                'is_popular'    => $this->isApprover($request->user()) && $request->boolean('is_popular'),
                'is_urgent'     => $this->isApprover($request->user()) && $request->boolean('is_urgent'),
            ]);

            if ($request->hasFile('featured_image')) {
                $agenda->featured_image = $request->file('featured_image')->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
                $storedFiles[] = $agenda->featured_image;
            }

            $agenda->save();

            // storeImages akan catat file, tapi kita track manual agar bisa rollback file jika DB gagal
            $this->storeImages($agenda, $request, $storedFiles);
            $this->storeAttachments($agenda, $request, $storedFiles);
            $this->saveSeoData($agenda, $request);

            // Event berulang: generate turunan (maks 12) dari jadwal induk.
            $generated = $this->generateRecurrence($agenda, $validated, $status, $request, $storedFiles);

            DB::commit();

            $this->logAgenda($agenda, 'dibuat', 'Status awal: ' . $status . ($generated > 0 ? ', +' . $generated . ' agenda berulang' : ''));

            if ($status === 'pending') {
                DB::afterCommit(fn () => $this->notifyApprovers($agenda));
            }
        } catch (\App\Exceptions\UserFacingException $e) {
            DB::rollBack();
            foreach ($storedFiles as $f) {
                if ($f && Storage::disk(self::IMAGE_DISK)->exists($f)) {
                    Storage::disk(self::IMAGE_DISK)->delete($f);
                } elseif ($f && Storage::disk(self::ATTACHMENT_DISK)->exists($f)) {
                    Storage::disk(self::ATTACHMENT_DISK)->delete($f);
                }
            }

            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($storedFiles as $f) {
                if ($f && Storage::disk(self::IMAGE_DISK)->exists($f)) {
                    Storage::disk(self::IMAGE_DISK)->delete($f);
                } elseif ($f && Storage::disk(self::ATTACHMENT_DISK)->exists($f)) {
                    Storage::disk(self::ATTACHMENT_DISK)->delete($f);
                }
            }
            Log::error('Gagal menyimpan agenda: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', 'Gagal menyimpan agenda. Silakan coba lagi.');
        }

        $pesan = $this->isApprover($request->user()) ? 'Agenda berhasil disimpan.' : 'Agenda dikirim dan menunggu persetujuan admin.';

        if (($generated ?? 0) > 0) {
            $pesan .= ' ' . $generated . ' jadwal lanjutan dibuat otomatis.';
        }

        return redirect()->route('agendas.index')->with('success', $pesan);
    }

    /**
     * Display the specified agenda (halaman detail / pratinjau).
     */
    public function show(string $agenda): View
    {
        // Dukung akses via uuid maupun slug; OPD hanya boleh lihat miliknya.
        $item = Agenda::with(['user', 'category', 'images', 'attachments'])
            ->withTrashed()
            ->where(function ($query) use ($agenda) {
                $query->where('uuid', $agenda)->orWhere('slug', $agenda);
            })
            ->when(! $this->isApprover(), fn ($query) => $query->where('user_uuid', auth()->user()->uuid))
            ->firstOrFail();

        $item->load(['logs.user']);

        $seri = collect();
        if ($item->parent_uuid) {
            $item->load('parent');
            $seri = Agenda::where('parent_uuid', $item->parent_uuid)
                ->where('uuid', '!=', $item->uuid)
                ->orderBy('waktu_mulai')
                ->limit(12)
                ->get(['uuid', 'title', 'waktu_mulai', 'status']);
            if ($item->parent) {
                $seri->prepend($item->parent);
            }
        } else {
            $seri = $item->children()->limit(12)->get(['uuid', 'title', 'waktu_mulai', 'status']);
        }

        return view('pages.agendas.show', ['agenda' => $item, 'seri' => $seri]);
    }

    /**
     * Show the form for editing the specified agenda.
     */
    public function edit(Agenda $agenda): View
    {
        // OPD hanya boleh ubah agenda miliknya sendiri
        if (! $this->isApprover() && $agenda->user_uuid !== auth()->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke agenda ini.');
        }

        $categories = Cache::remember('agenda_categories', 3600, fn () => Category::orderBy('slug')->get());
        $agenda->load(['images', 'attachments']);

        return view('pages.agendas.edit', compact('agenda', 'categories'));
    }

    /**
     * Update the specified agenda.
     */
    public function update(UpdateAgendaRequest $request, Agenda $agenda): RedirectResponse
    {
        // OPD hanya boleh ubah agenda miliknya sendiri; edit OPD kembali ke 'pending'
        if (! $this->isApprover($request->user()) && $agenda->user_uuid !== $request->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke agenda ini.');
        }

        $validated = $request->validated();
        $oldFeatured = $agenda->featured_image;
        $newFeatured = null;
        $storedFiles = [];
        $removedImagePaths = [];

        DB::beginTransaction();

        try {
            $validated['content'] = HtmlSanitizer::clean($validated['content']);
            if ($request->hasFile('featured_image')) {
                $newFeatured = $request->file('featured_image')->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
                $storedFiles[] = $newFeatured;
                $agenda->featured_image = $newFeatured;
            }

            // OPD yang mengubah agenda (miliknya) otomatis kembali antre approval
            $status = $this->isApprover($request->user()) ? $validated['status'] : 'pending';

            $agenda->fill([
                'category_uuid' => $validated['category_uuid'],
                'title'         => $validated['title'],
                'slug'          => $agenda->title === $validated['title']
                    ? $agenda->slug
                    : $this->uniqueSlug(Str::slug($validated['title']), $agenda->uuid),
                'excerpt'       => $validated['excerpt'] ?? null,
                'content'       => $validated['content'],
                'scheduled_at'  => $validated['scheduled_at'] ?? $agenda->scheduled_at ?? now(),
                'jenis_agenda'  => $validated['jenis_agenda'],
                'lokasi'        => $validated['jenis_agenda'] === 'offline' ? ($validated['lokasi'] ?? null) : null,
                'waktu_mulai'   => $validated['waktu_mulai'] ?? null,
                'waktu_akhir'   => $validated['waktu_akhir'] ?? null,
                'url'           => $validated['url'] ?? null,
                'tagging'       => $validated['tagging'] ?? null,
                'video'         => $validated['video'] ?? null,
                'status'        => $status,
                'search_engine' => $validated['search_engine'],
                'is_featured'   => $this->isApprover($request->user()) ? $request->boolean('is_featured') : $agenda->is_featured,
                'is_popular'    => $this->isApprover($request->user()) ? $request->boolean('is_popular') : $agenda->is_popular,
                'is_urgent'     => $this->isApprover($request->user()) ? $request->boolean('is_urgent') : $agenda->is_urgent,
            ])->save();

            // kumpulkan path yang akan dihapus dulu, hapus file setelah commit
            $removeUuids = (array) $request->input('remove_images', []);
            if (!empty($removeUuids)) {
                $removedImagePaths = $agenda->images()->whereIn('uuid', $removeUuids)->pluck('image_path')->all();
                $agenda->images()->whereIn('uuid', $removeUuids)->delete();
            }
            $removeAttachmentUuids = (array) $request->input('remove_attachments', []);
            if (! empty($removeAttachmentUuids)) {
                $removedAttachmentPaths = $agenda->attachments()->whereIn('uuid', $removeAttachmentUuids)->pluck('file_path')->all();
                $agenda->attachments()->whereIn('uuid', $removeAttachmentUuids)->delete();
            }
            $this->storeImages($agenda, $request, $storedFiles);
            $this->storeAttachments($agenda, $request, $storedFiles);
            $this->saveSeoData($agenda, $request);

            $imageOrder = (array) $request->input('image_order', []);
            if (! empty($imageOrder)) {
                foreach ($imageOrder as $i => $uuid) {
                    $agenda->images()->where('uuid', $uuid)->update(['sort_order' => $i + 1]);
                }
            }

            DB::commit();

            $this->logAgenda($agenda, 'diperbarui', 'Status: ' . $status);

            // Edit OPD kembali pending -> beri tahu admin agar direview ulang
            if ($status === 'pending' && ! $this->isApprover($request->user())) {
                $this->notifyApprovers($agenda);
            }
            // baru hapus file lama setelah DB sukses (atomic)
            if ($newFeatured && $oldFeatured && $oldFeatured !== $newFeatured) {
                $this->deleteFile($oldFeatured);
            }
            foreach ($removedImagePaths as $p) {
                $this->deleteFile($p);
            }
            foreach ($removedAttachmentPaths ?? [] as $p) {
                if ($p && Storage::disk(self::ATTACHMENT_DISK)->exists($p)) {
                    Storage::disk(self::ATTACHMENT_DISK)->delete($p);
                }
            }
        } catch (\App\Exceptions\UserFacingException $e) {
            DB::rollBack();
            foreach ($storedFiles as $f) {
                if ($f && Storage::disk(self::IMAGE_DISK)->exists($f)) {
                    Storage::disk(self::IMAGE_DISK)->delete($f);
                } elseif ($f && Storage::disk(self::ATTACHMENT_DISK)->exists($f)) {
                    Storage::disk(self::ATTACHMENT_DISK)->delete($f);
                }
            }

            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($storedFiles as $f) {
                if ($f && Storage::disk(self::IMAGE_DISK)->exists($f)) {
                    Storage::disk(self::IMAGE_DISK)->delete($f);
                } elseif ($f && Storage::disk(self::ATTACHMENT_DISK)->exists($f)) {
                    Storage::disk(self::ATTACHMENT_DISK)->delete($f);
                }
            }
            Log::error('Gagal memperbarui agenda: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', 'Gagal memperbarui agenda. Silakan coba lagi.');
        }

        return redirect()->route('agendas.index')->with('success', $this->isApprover($request->user()) ? 'Agenda berhasil diperbarui.' : 'Perubahan dikirim dan menunggu persetujuan admin.');
    }

    /**
     * Catat riwayat audit agenda. Kegagalan logging tidak boleh menggagalkan aksi utama.
     */
    private function logAgenda(Agenda $agenda, string $aksi, ?string $catatan = null, ?string $approvedBy = null): void
    {
        try {
            $agenda->logs()->create([
                'user_uuid'   => auth()->user()?->uuid,
                'aksi'        => $aksi,
                'catatan'     => $catatan,
                'approved_by' => $approvedBy,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal mencatat riwayat agenda: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi ke admin/super-admin (kecuali penulis sendiri).
     */
    private function notifyApprovers(Agenda $agenda): void
    {
        try {
            $approvers = User::role(['super-admin', 'admin'])
                ->where('uuid', '!=', $agenda->user_uuid)
                ->get();

            foreach ($approvers as $approver) {
                $approver->notify(new AgendaSubmittedNotification(
                    $agenda->title,
                    $agenda->user?->name ?? 'OPD',
                    $agenda->uuid,
                ));

                WhatsappGateway::send($approver->no_hp ?? null,
                    'Pengajuan agenda baru "' . $agenda->title . '" oleh ' . ($agenda->user?->name ?? 'OPD') . '. Mohon direview: ' . route('agendas.show', $agenda->uuid));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim notifikasi agenda ke admin: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi hasil review ke penulis agenda.
     */
    private function notifyAuthor(Agenda $agenda, object $notification, ?string $waMessage = null): void
    {
        try {
            $author = $agenda->user;

            if ($author) {
                $author->notify($notification);

                if ($waMessage !== null) {
                    WhatsappGateway::send($author->no_hp ?? null, $waMessage);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim notifikasi agenda ke penulis: ' . $e->getMessage());
        }
    }

    /**
     * Setujui agenda OPD (pending -> published). Khusus admin.
     */
    public function approve(Agenda $agenda): RedirectResponse
    {
        abort_unless($this->isApprover(), 403, 'Hanya admin yang dapat menyetujui agenda.');

        if ($agenda->status !== 'pending') {
            return back()->with('error', 'Hanya agenda berstatus pending yang bisa disetujui.');
        }

        $agenda->update(['status' => 'published']);

        $this->logAgenda($agenda, 'disetujui', null, auth()->user()->uuid);
        $this->notifyAuthor($agenda, new AgendaApprovedNotification($agenda->title, $agenda->uuid),
            'Agenda "' . $agenda->title . '" telah disetujui & dipublikasikan.');

        return back()->with('success', "Agenda \"{$agenda->title}\" disetujui dan dipublikasikan.");
    }

    /**
     * Tolak agenda OPD (pending -> draft). Khusus admin. Wajib sertakan alasan.
     */
    public function reject(Request $request, Agenda $agenda): RedirectResponse
    {
        abort_unless($this->isApprover(), 403, 'Hanya admin yang dapat menolak agenda.');

        if ($agenda->status !== 'pending') {
            return back()->with('error', 'Hanya agenda berstatus pending yang bisa ditolak.');
        }

        $alasan = trim((string) $request->input('catatan', ''));

        if ($alasan === '') {
            return back()->with('error', 'Alasan penolakan wajib diisi agar OPD tahu yang harus diperbaiki.');
        }

        if (mb_strlen($alasan) > 500) {
            return back()->with('error', 'Alasan penolakan maksimal 500 karakter.');
        }

        $agenda->update(['status' => 'draft']);

        $this->logAgenda($agenda, 'ditolak', $alasan, auth()->user()->uuid);
        $this->notifyAuthor($agenda, new AgendaRejectedNotification($agenda->title, $agenda->uuid, $alasan),
            'Agenda "' . $agenda->title . '" dikembalikan sebagai draft. Alasan: ' . $alasan);

        return back()->with('success', "Agenda \"{$agenda->title}\" dikembalikan sebagai draft.");
    }

    /**
     * Minta revisi: agenda tetap pending, penulis diberi catatan perbaikan.
     */
    public function mintaRevisi(Request $request, Agenda $agenda): RedirectResponse
    {
        abort_unless($this->isApprover($request->user()), 403, 'Hanya admin yang dapat meminta revisi.');

        if ($agenda->status !== 'pending') {
            return back()->with('error', 'Hanya agenda berstatus pending yang bisa dimintai revisi.');
        }

        $catatan = trim((string) $request->input('catatan', ''));

        if ($catatan === '') {
            return back()->with('error', 'Catatan revisi wajib diisi.');
        }

        if (mb_strlen($catatan) > 500) {
            return back()->with('error', 'Catatan revisi maksimal 500 karakter.');
        }

        $this->logAgenda($agenda, 'revisi_diminta', $catatan);
        $this->notifyAuthor($agenda, new AgendaRejectedNotification($agenda->title, $agenda->uuid, 'Revisi diminta: ' . $catatan),
            'Revisi diminta untuk agenda "' . $agenda->title . '": ' . $catatan);

        return back()->with('success', "Catatan revisi dikirim ke penulis agenda \"{$agenda->title}\".");
    }

    /**
     * Remove the specified agenda along with its files (soft delete / archive).
     */
    public function destroy(Agenda $agenda): RedirectResponse
    {
        if (! $this->isApprover() && $agenda->user_uuid !== auth()->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke agenda ini.');
        }

        $alasan = trim((string) request()->input('alasan_arsip', ''));
        if (! in_array($alasan, ['terlaksana', 'dibatalkan', 'lainnya'], true)) {
            $alasan = 'lainnya';
        }

        // Soft delete / archive: set deleted_at instead of permanent delete
        $agenda->alasan_arsip = $alasan;
        $agenda->save();
        $agenda->delete();
        $this->logAgenda($agenda, 'diarsipkan', 'Alasan: ' . $alasan);

        return back()->with('success', 'Agenda berhasil diarsipkan.');
    }

    /**
     * Restore the specified agenda from archive.
     */
    public function restore(string $uuid): RedirectResponse
    {
        abort_unless($this->isApprover(), 403, 'Hanya admin yang dapat memulihkan agenda.');

        $agenda = Agenda::withTrashed()->where('uuid', $uuid)->firstOrFail();

        $agenda->restore();
        $this->logAgenda($agenda, 'dipulihkan');

        return back()->with('success', "Agenda \"{$agenda->title}\" telah dipulihkan dari arsip.");
    }

    /**
     * Hapus permanen agenda dari arsip: bersihkan semua file fisik + baris gambar/lampiran/log.
     */
    public function forceDestroy(string $uuid): RedirectResponse
    {
        abort_unless($this->isApprover(), 403, 'Hanya admin yang dapat menghapus permanen.');

        $agenda = Agenda::withTrashed()->with(['images', 'attachments'])->where('uuid', $uuid)->firstOrFail();

        if ($agenda->featured_image) {
            $this->deleteFile($agenda->featured_image);
        }
        foreach ($agenda->images as $image) {
            $this->deleteFile($image->image_path);
        }
        foreach ($agenda->attachments as $lampiran) {
            if ($lampiran->file_path && Storage::disk(self::ATTACHMENT_DISK)->exists($lampiran->file_path)) {
                Storage::disk(self::ATTACHMENT_DISK)->delete($lampiran->file_path);
            }
        }

        $agenda->images()->delete();
        $agenda->attachments()->delete();
        $agenda->logs()->delete();
        $agenda->forceDelete();

        return back()->with('success', 'Agenda beserta seluruh file berhasil dihapus permanen.');
    }

    /**
     * Remove the selected agendas along with their files.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada agenda yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $agendas = Agenda::with('images')
            ->whereIn('uuid', $ids)
            ->when(! $this->isApprover($request->user()), fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->get();

        if ($agendas->isEmpty()) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        }

        $filePaths = [];
        foreach ($agendas as $agenda) {
            if ($agenda->featured_image) {
                $filePaths[] = $agenda->featured_image;
            }
            foreach ($agenda->images as $image) {
                $filePaths[] = $image->image_path;
            }
        }

        DB::beginTransaction();

        try {
            AgendaImage::whereIn('agenda_uuid', $agendas->pluck('uuid')->all())->delete();
            Agenda::whereIn('uuid', $agendas->pluck('uuid')->all())->delete();

            DB::commit();

            foreach ($agendas as $agenda) {
                $this->logAgenda($agenda, 'diarsipkan');
            }

            // hapus file setelah DB commit (atomic)
            foreach ($filePaths as $path) {
                $this->deleteFile($path);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menghapus agenda terpilih: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Gagal menghapus agenda terpilih. Silakan coba lagi.');
        }

        return back()->with('success', $agendas->count() . ' agenda berhasil dihapus.');
    }

    /**
     * Restore the selected agendas from archive.
     */
    public function bulkRestore(Request $request): RedirectResponse
    {
        abort_unless($this->isApprover($request->user()), 403, 'Hanya admin yang dapat memulihkan agenda.');

        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada agenda yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $agendas = Agenda::onlyTrashed()
            ->whereIn('uuid', $ids)
            ->get();

        if ($agendas->isEmpty()) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan di arsip.');
        }

        DB::beginTransaction();

        try {
            Agenda::whereIn('uuid', $agendas->pluck('uuid')->all())->restore();

            DB::commit();

            foreach ($agendas as $agenda) {
                $this->logAgenda($agenda, 'dipulihkan');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memulihkan agenda terpilih: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Gagal memulihkan agenda terpilih. Silakan coba lagi.');
        }

        return back()->with('success', $agendas->count() . ' agenda berhasil dipulihkan.');
    }

    /**
     * Setujui banyak agenda pending sekaligus. Khusus admin.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        abort_unless($this->isApprover($request->user()), 403, 'Hanya admin yang dapat menyetujui agenda.');

        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada agenda yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $agendas = Agenda::whereIn('uuid', $ids)->where('status', 'pending')->get();

        if ($agendas->isEmpty()) {
            return back()->with('error', 'Tidak ada agenda pending pada data yang dipilih.');
        }

        $aksi = $request->input('aksi', 'publish');
        if (! in_array($aksi, ['publish', 'draft'], true)) {
            $aksi = 'publish';
        }

        Agenda::whereIn('uuid', $agendas->pluck('uuid')->all())->where('status', 'pending')->update(['status' => $aksi === 'publish' ? 'published' : 'draft']);

        $grouped = $agendas->groupBy('user_uuid');

        foreach ($grouped as $userUuid => $userAgendas) {
            $first = $userAgendas->first();
            $author = $first->user;

            foreach ($userAgendas as $agenda) {
                $this->logAgenda($agenda, $aksi === 'publish' ? 'disetujui_massal' : 'ditolak_massal', $aksi === 'draft' ? 'Dikembalikan ke draft massal' : null, auth()->user()->uuid);
            }

            if ($author) {
                $judulAgenda = $userAgendas->pluck('title')->take(3)->implode(', ');
                if ($userAgendas->count() > 3) {
                    $judulAgenda .= ' dan ' . ($userAgendas->count() - 3) . ' lainnya';
                }

                $pesan = $aksi === 'publish'
                    ? $userAgendas->count() . ' agenda (' . $judulAgenda . ') telah disetujui & dipublikasikan.'
                    : $userAgendas->count() . ' agenda (' . $judulAgenda . ') dikembalikan ke draft.';

                $notification = $aksi === 'publish'
                    ? new AgendaApprovedNotification($judulAgenda, $first->uuid)
                    : new AgendaRejectedNotification($judulAgenda, $first->uuid, 'Dikembalikan ke draft (approval massal)');

                $this->notifyAuthor($first, $notification, $pesan);
            }
        }

        return back()->with('success', $agendas->count() . ($aksi === 'publish' ? ' agenda disetujui dan dipublikasikan.' : ' agenda dikembalikan ke draft.'));
    }

    public function bulkStatus(Request $request): RedirectResponse
    {
        abort_unless($this->isApprover($request->user()), 403, 'Hanya admin yang dapat mengubah status masal.');

        $ids = $request->input('ids', []);
        $status = $request->input('status', '');

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada agenda yang dipilih.');
        }

        if (! in_array($status, ['draft', 'published', 'scheduled', 'pending'], true)) {
            return back()->with('error', 'Status tujuan tidak valid.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $agendas = Agenda::whereIn('uuid', $ids)->get();

        if ($agendas->isEmpty()) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        }

        if ($status === 'scheduled' && ! $request->filled('scheduled_at')) {
            return back()->with('error', 'Tanggal terbit wajib diisi untuk status scheduled.');
        }

        if ($status === 'published' && $agendas->contains(fn ($a) => $a->status === 'scheduled')) {
            return back()->with('error', 'Agenda scheduled hanya bisa dipublikasikan lewat approve.');
        }

        DB::beginTransaction();

        try {
            $labelStatus = ['draft' => 'Draft', 'published' => 'Published', 'scheduled' => 'Scheduled', 'pending' => 'Pending'];

            foreach ($agendas as $agenda) {
                $data = ['status' => $status];

                if ($status === 'scheduled') {
                    $scheduled = Carbon::parse($request->input('scheduled_at'));
                    if ($scheduled->isPast()) {
                        throw new \App\Exceptions\UserFacingException('Tanggal terbit harus di masa depan.');
                    }
                    $data['scheduled_at'] = $scheduled;
                }

                $agenda->update($data);
                $this->logAgenda($agenda, 'diperbarui', 'Status diubah massal ke ' . $labelStatus[$status]);
            }

            DB::commit();
        } catch (\App\Exceptions\UserFacingException $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal ubah status masal: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Gagal mengubah status massal. Silakan coba lagi.');
        }

        return back()->with('success', $agendas->count() . ' agenda diubah ke status ' . $labelStatus[$status] . '.');
    }

    /**
     * Duplikat agenda (untuk event rutin). Dibuka di halaman edit agar jadwal bisa disesuaikan.
     */
    public function duplicate(Request $request, Agenda $agenda): RedirectResponse
    {
        abort_unless($this->isApprover($request->user()), 403, 'Hanya admin yang dapat menduplikat agenda.');

        $agenda->load(['images', 'attachments']);
        $copiedFiles = [];

        DB::beginTransaction();

        try {
            $copy = $agenda->replicate(['uuid', 'slug', 'title', 'views', 'created_at', 'updated_at', 'deleted_at']);
            $copy->user_uuid = $request->user()->uuid;
            $copy->title = Str::limit($agenda->title . ' (Salinan)', 255, '');
            $copy->slug = $this->uniqueSlug(Str::slug($copy->title));
            $copy->views = 0;
            $copy->is_featured = false;
            $copy->is_popular = false;
            $copy->status = 'draft';
            $copy->parent_uuid = null;
            $copy->recurrence_rule = null;
            $copy->recurrence_end = null;

            if ($agenda->featured_image && Storage::disk(self::IMAGE_DISK)->exists($agenda->featured_image)) {
                $newPath = self::IMAGE_DIRECTORY . '/' . (string) Str::uuid() . '.' . pathinfo($agenda->featured_image, PATHINFO_EXTENSION);
                Storage::disk(self::IMAGE_DISK)->copy($agenda->featured_image, $newPath);
                $copiedFiles[] = $newPath;
                $copy->featured_image = $newPath;
            } else {
                $copy->featured_image = null;
            }

            $copy->save();

            foreach ($agenda->images as $image) {
                $newPath = $image->image_path;
                if (Storage::disk(self::IMAGE_DISK)->exists($image->image_path)) {
                    $newPath = self::IMAGE_DIRECTORY . '/' . (string) Str::uuid() . '.' . pathinfo($image->image_path, PATHINFO_EXTENSION);
                    Storage::disk(self::IMAGE_DISK)->copy($image->image_path, $newPath);
                    $copiedFiles[] = $newPath;
                }
                $copy->images()->create([
                    'image_path' => $newPath,
                    'caption'    => $image->caption,
                    'sort_order' => $image->sort_order,
                ]);
            }

            foreach ($agenda->attachments as $lampiran) {
                $newPath = $lampiran->file_path;
                if (Storage::disk(self::ATTACHMENT_DISK)->exists($lampiran->file_path)) {
                    $ext = pathinfo($lampiran->file_path, PATHINFO_EXTENSION);
                    $newPath = self::ATTACHMENT_DIRECTORY . '/' . (string) Str::uuid() . ($ext ? '.' . $ext : '');
                    Storage::disk(self::ATTACHMENT_DISK)->copy($lampiran->file_path, $newPath);
                    $copiedFiles[] = $newPath;
                }
                $copy->attachments()->create([
                    'file_path' => $newPath,
                    'nama_asli' => $lampiran->nama_asli,
                    'ukuran'    => $lampiran->ukuran,
                ]);
            }

            $this->saveSeoData($copy, $request);

            DB::commit();

            $this->logAgenda($copy, 'diduplikat', 'Dari: ' . Str::limit($agenda->title, 100));
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($copiedFiles as $f) {
            if ($f && Storage::disk(self::IMAGE_DISK)->exists($f)) {
                Storage::disk(self::IMAGE_DISK)->delete($f);
            } elseif ($f && Storage::disk(self::ATTACHMENT_DISK)->exists($f)) {
                Storage::disk(self::ATTACHMENT_DISK)->delete($f);
            }
            }
            Log::error('Gagal menduplikat agenda: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Gagal menduplikat agenda. Silakan coba lagi.');
        }

        return redirect()->route('agendas.edit', $copy->uuid)->with('success', 'Agenda diduplikat. Sesuaikan jadwal lalu simpan.');
    }

    public function logAudit(Request $request): View
    {
        abort_unless($this->isApprover($request->user()), 403, 'Hanya admin yang dapat melihat log audit.');

        $query = \App\Models\AgendaLog::with(['agenda', 'user'])
            ->whereIn('aksi', ['disetujui', 'ditolak', 'disetujui_massal', 'ditolak_massal', 'revisi_diminta'])
            ->when($request->filled('admin'), fn ($q) => $q->where(function ($qq) use ($request) {
                $qq->where('approved_by', $request->get('admin'))
                    ->orWhere('rejected_by', $request->get('admin'))
                    ->orWhere('user_uuid', $request->get('admin'));
            }))
            ->when($request->filled('aksi'), fn ($q) => $q->where('aksi', $request->get('aksi')))
            ->latest();

        $logs = $query->paginate(20)->withQueryString();

        $admins = \App\Models\User::role(['super-admin', 'admin'])->orderBy('name')->get(['uuid', 'name']);

        $aksiLabel = [
            'disetujui'        => 'Disetujui',
            'ditolak'          => 'Ditolak',
            'disetujui_massal' => 'Disetujui (massal)',
            'ditolak_massal'   => 'Ditolak (massal)',
            'revisi_diminta'   => 'Revisi diminta',
        ];

        $aksiColor = [
            'disetujui'        => 'success',
            'ditolak'          => 'danger',
            'disetujui_massal' => 'success',
            'ditolak_massal'   => 'danger',
            'revisi_diminta'   => 'warning',
        ];

        return view('pages.agendas.log-audit', compact('logs', 'admins', 'aksiLabel', 'aksiColor'));
    }

    /**
     * Buat slug unik untuk hasil duplikat.
     */
    private function uniqueSlug(string $base, ?string $ignoreUuid = null): string
    {
        $slug = $base !== '' ? $base : (string) Str::uuid();
        $candidate = $slug;
        $i = 2;

        while (Agenda::withTrashed()->where('slug', $candidate)->when($ignoreUuid, fn ($q) => $q->where('uuid', '!=', $ignoreUuid))->exists()) {
            $candidate = $slug . '-' . $i++;
        }

        return $candidate;
    }

    /**
     * Data JSON untuk tampilan kalender (menghormati filter + hak akses yang sama dengan daftar).
     */
    public function pendingCount(Request $request): JsonResponse
    {
        $isApprover = $this->isApprover($request->user());

        $count = Agenda::where('status', 'pending')
            ->when(! $isApprover, fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Ekspor kalender agenda sebagai file iCal (.ics) untuk Google Calendar dsb.
     */
    public function ical(Request $request)
    {
        $isApprover = $this->isApprover($request->user());

        $agendas = Agenda::where('status', 'published')
            ->when(! $isApprover, fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->whereNotNull('waktu_mulai')
            ->where('waktu_mulai', '>=', now()->subMonths(1))
            ->orderBy('waktu_mulai')
            ->limit(1000)
            ->get();

        $lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//Pemkot Bekasi//Agenda//ID', 'CALSCALE:GREGORIAN'];

        foreach ($agendas as $agenda) {
            $mulai = $agenda->waktu_mulai;
            $akhir = $agenda->waktu_akhir ?? $mulai->copy()->addHour();
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . $agenda->uuid . '@agenda.bekasikota.go.id';
            $lines[] = 'DTSTAMP:' . now()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART:' . $mulai->format('Ymd\THis');
            $lines[] = 'DTEND:' . $akhir->format('Ymd\THis');
            $lines[] = 'SUMMARY:' . $this->icsEscape($agenda->title);
            if ($agenda->lokasi) {
                $lines[] = 'LOCATION:' . $this->icsEscape($agenda->lokasi);
            }
            $lines[] = 'DESCRIPTION:' . $this->icsEscape(strip_tags((string) $agenda->excerpt));
            $lines[] = 'URL:' . route('agendas.show', $agenda->uuid);
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines), 200, [
            'Content-Type' => 'text/calendar; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="agenda.ics"',
        ]);
    }

    private function icsEscape(string $value): string
    {
        return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\\;', '\\,', '\\n'], $value);
    }

    public function calendarData(Request $request): JsonResponse
    {
        $query = $this->filteredAgendas($request);

        if ($request->filled('start') && $request->filled('end')) {
            $start = $request->get('start');
            $end = $request->get('end');
            $query->where(function ($q) use ($start, $end) {
                $q->whereBetween('waktu_mulai', [$start, $end])
                    ->orWhereBetween('scheduled_at', [$start, $end]);
            });
        }

        $items = $query->with(['user', 'category'])->limit(500)->get();

        $events = $items->map(function (Agenda $agenda) {
            $date = $agenda->waktu_mulai ?? $agenda->scheduled_at ?? $agenda->created_at;

            return [
                'id'         => $agenda->uuid,
                'title'      => $agenda->title,
                'date'       => $date ? $date->toDateString() : null,
                'start_time' => $agenda->waktu_mulai ? $agenda->waktu_mulai->format('H:i') : null,
                'end_time'   => $agenda->waktu_akhir ? $agenda->waktu_akhir->format('H:i') : null,
                'status'     => $agenda->status,
                'jenis'      => $agenda->jenis_agenda ?? 'offline',
                'lokasi'     => $agenda->lokasi,
                'url'        => route('agendas.show', $agenda->uuid),
            ];
        })->filter(fn ($e) => $e['date'] !== null)->values();

        return response()->json(['events' => $events]);
    }

    /**
     * Halaman import agenda (upload + template).
     */
    public function importForm(): View
    {
        return view('pages.agendas.import');
    }

    /**
     * Unduh template CSV import agenda.
     */
    public function importTemplate()
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['judul', 'ringkasan', 'isi', 'kategori', 'jenis', 'lokasi', 'waktu_mulai', 'waktu_akhir', 'url', 'tanggal_terbit', 'status', 'tags']);
            fputcsv($out, ['Rapat Koordinasi OPD', 'Ringkasan singkat agenda', 'Isi lengkap agenda...', 'rapat', 'offline', 'Aula Lt. 4 Pemkot Bekasi', '2026-11-01 09:00', '2026-11-01 11:00', '', '2026-11-01', 'draft', 'rapat, opd']);
            fclose($out);
        }, 'template-import-agenda.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Parse file upload (xlsx/csv) lalu tampilkan pratinjau validasi per baris.
     */
    public function importPreview(Request $request): View
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Pilih file dulu (xlsx/csv, maks 5 MB).',
        ]);

        $sheets = \Maatwebsite\Excel\Facades\Excel::toArray([], $request->file('file'));
        $rows = $sheets[0] ?? [];
        $rows = array_slice($rows, 0, 200);

        $categories = Cache::remember('agenda_categories', 3600, fn () => Category::orderBy('slug')->get());
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[strtolower(trim($cat->slug))] = $cat;
            $catMap[strtolower(trim($cat->name))] = $cat;
        }

        $isApprover = $this->isApprover($request->user());
        $preview = [];
        $validCount = 0;

        foreach ($rows as $i => $row) {
            // Lewati baris header template bila ikut terunggah ulang.
            if ($i === 0 && strtolower(trim((string) ($row[0] ?? ''))) === 'judul') {
                continue;
            }

            $data = [
                'judul'          => trim((string) ($row[0] ?? '')),
                'excerpt'        => trim((string) ($row[1] ?? '')),
                'content'        => trim((string) ($row[2] ?? '')),
                'kategori'       => trim((string) ($row[3] ?? '')),
                'jenis_agenda'   => strtolower(trim((string) ($row[4] ?? 'offline'))) ?: 'offline',
                'lokasi'         => trim((string) ($row[5] ?? '')),
                'waktu_mulai'    => trim((string) ($row[6] ?? '')),
                'waktu_akhir'    => trim((string) ($row[7] ?? '')),
                'url'            => trim((string) ($row[8] ?? '')),
                'scheduled_at'   => trim((string) ($row[9] ?? '')),
                'status'         => strtolower(trim((string) ($row[10] ?? ''))),
                'tagging'        => trim((string) ($row[11] ?? '')),
            ];

            $errors = $this->validateImportRow($data, $catMap);

            if (empty($errors)) {
                $validCount++;
            }

            $preview[] = ['line' => $i + 1, 'data' => $data, 'errors' => $errors];
        }

        $token = (string) Str::uuid();
        \Illuminate\Support\Facades\Cache::store('database')->put('agenda_import_' . $token, [
            'rows'      => $preview,
            'user_uuid' => $request->user()->uuid,
        ], 600);

        return view('pages.agendas.import', [
            'preview'    => $preview,
            'validCount' => $validCount,
            'token'      => $token,
        ]);
    }

    /**
     * Validasi satu baris import, kembalikan daftar pesan error (kosong = valid).
     */
    private function validateImportRow(array $data, array $catMap): array
    {
        $errors = [];

        if ($data['judul'] === '') {
            $errors[] = 'Judul wajib diisi.';
        } elseif (mb_strlen($data['judul']) > 255) {
            $errors[] = 'Judul maksimal 255 karakter.';
        }

        if ($data['content'] === '') {
            $errors[] = 'Isi wajib diisi.';
        }

        if ($data['kategori'] === '' || ! isset($catMap[strtolower($data['kategori'])])) {
            $errors[] = 'Kategori "' . ($data['kategori'] !== '' ? $data['kategori'] : '-') . '" tidak dikenal.';
        }

        if (! in_array($data['jenis_agenda'], ['offline', 'online'], true)) {
            $errors[] = 'Jenis harus offline/online.';
        } elseif ($data['jenis_agenda'] === 'offline' && $data['lokasi'] === '') {
            $errors[] = 'Lokasi wajib untuk offline.';
        } elseif ($data['jenis_agenda'] === 'online' && $data['url'] === '') {
            $errors[] = 'URL wajib untuk online.';
        }

        if ($data['url'] !== '' && ! filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'URL tidak valid.';
        }

        foreach (['waktu_mulai', 'waktu_akhir', 'scheduled_at'] as $field) {
            if ($data[$field] !== '' && ! $this->parseImportDate($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' tidak dikenali (contoh: 2026-11-01 09:00).';
            }
        }

        $mulai = $this->parseImportDate($data['waktu_mulai']);
        $akhir = $this->parseImportDate($data['waktu_akhir']);
        if ($mulai && $akhir && $akhir->lt($mulai)) {
            $errors[] = 'Waktu akhir lebih awal dari waktu mulai.';
        }

        if ($data['status'] !== '' && ! in_array($data['status'], ['draft', 'published', 'scheduled'], true)) {
            $errors[] = 'Status harus draft/published/scheduled (kosongkan = otomatis).';
        }

        return $errors;
    }

    public function importEdit(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'line' => ['required', 'integer', 'min:1'],
            'field' => ['required', 'string', 'in:judul,excerpt,isi,kategori,jenis_agenda,lokasi,waktu_mulai,waktu_akhir,url,scheduled_at,status,tagging'],
            'value' => ['required', 'string', 'max:1000'],
        ]);

        $cached = Cache::store('database')->get('agenda_import_' . $request->get('token'));

        if (! $cached || ($cached['user_uuid'] ?? null) !== $request->user()->uuid) {
            return redirect()->route('agendas.import')->with('error', 'Sesi import kedaluwarsa. Silakan unggah ulang.');
        }

        $line = (int) $request->get('line');
        $field = $request->get('field');
        $value = $request->get('value');
        $key = $field === 'isi' ? 'content' : $field;

        foreach ($cached['rows'] as &$row) {
            if ($row['line'] === $line) {
                $row['data'][$key] = $value;
                $row['errors'] = $this->validateImportRow($row['data'], $this->getCategoryMap());
                break;
            }
        }
        unset($row);

        Cache::store('database')->put('agenda_import_' . $request->get('token'), $cached, 600);

        return back()->with('success', 'Baris ' . $line . ' diperbarui.');
    }

    private function getCategoryMap(): array
    {
        $categories = Cache::remember('agenda_categories', 3600, fn () => Category::orderBy('slug')->get());
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[strtolower(trim($cat->slug))] = $cat;
            $catMap[strtolower(trim($cat->name))] = $cat;
        }

        return $catMap;
    }

    private function parseImportDate(string $value): ?Carbon
    {
        if (trim($value) === '') {
            return null;
        }

        // Excel sering mengirim tanggal sebagai angka serial.
        if (is_numeric($value)) {
            try {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value));
            } catch (\Throwable) {
                return null;
            }
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Simpan baris-baris valid dari pratinjau ke database.
     */
    public function importConfirm(Request $request): RedirectResponse
    {
        $request->validate(['token' => ['required', 'string']]);

        $cached = \Illuminate\Support\Facades\Cache::store('database')->pull('agenda_import_' . $request->get('token'));

        if (! $cached || ($cached['user_uuid'] ?? null) !== $request->user()->uuid) {
            return redirect()->route('agendas.import')->with('error', 'Sesi import kedaluwarsa. Silakan unggah ulang.');
        }

        $categories = Cache::remember('agenda_categories', 3600, fn () => Category::orderBy('slug')->get());
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[strtolower(trim($cat->slug))] = $cat;
            $catMap[strtolower(trim($cat->name))] = $cat;
        }

        $isApprover = $this->isApprover($request->user());
        $created = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            foreach ($cached['rows'] as $row) {
                if (! empty($row['errors'])) {
                    $skipped++;
                    continue;
                }

                $data = $row['data'];
                $category = $catMap[strtolower($data['kategori'])];
                $status = $isApprover ? ($data['status'] !== '' ? $data['status'] : 'draft') : 'pending';

                $agenda = new Agenda();
                $agenda->user_uuid = $request->user()->uuid;
                $agenda->fill([
                    'category_uuid' => $category->uuid,
                    'title'         => mb_substr($data['judul'], 0, 255),
                    'slug'          => $this->uniqueSlug(Str::slug($data['judul'])),
                    'excerpt'       => $data['excerpt'] !== '' ? mb_substr($data['excerpt'], 0, 255) : null,
                    'content'       => HtmlSanitizer::clean($data['content']),
                    'scheduled_at'  => $this->parseImportDate($data['scheduled_at']) ?? now(),
                    'jenis_agenda'  => $data['jenis_agenda'],
                    'lokasi'        => $data['jenis_agenda'] === 'offline' ? ($data['lokasi'] !== '' ? $data['lokasi'] : null) : null,
                    'waktu_mulai'   => $this->parseImportDate($data['waktu_mulai']),
                    'waktu_akhir'   => $this->parseImportDate($data['waktu_akhir']),
                    'url'           => $data['url'] !== '' ? $data['url'] : null,
                    'tagging'       => $data['tagging'] !== '' ? $data['tagging'] : null,
                    'status'        => $status,
                    'search_engine' => 'index',
                    'is_featured'   => false,
                    'is_popular'    => false,
                ]);
                $agenda->save();

                $this->saveSeoData($agenda, $request);
                $this->logAgenda($agenda, 'dibuat', 'Import file, status awal: ' . $status);

                if ($status === 'pending') {
                    $this->notifyApprovers($agenda);
                }

                $created++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal import agenda: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('agendas.import')->with('error', 'Gagal menyimpan hasil import. Silakan coba lagi.');
        }

        return redirect()->route('agendas.index')->with('success', $created . ' agenda berhasil diimport' . ($skipped > 0 ? ', ' . $skipped . ' baris dilewati karena tidak valid.' : '.'));
    }

    /**
     * Laporan PDF bulanan: rekap + daftar agenda pada bulan berjalan.
     */
    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));

        try {
            $periode = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        } catch (\Throwable) {
            $periode = now()->startOfMonth();
            $bulan = $periode->format('Y-m');
        }

        $isApprover = $this->isApprover($request->user());

        $items = Agenda::with(['user', 'category', 'attachments'])
            ->when(! $isApprover, fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->whereYear('waktu_mulai', $periode->year)
            ->whereMonth('waktu_mulai', $periode->month)
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_uuid', $request->get('kategori')))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis_agenda', $request->get('jenis')))
            ->when($request->filled('opd'), fn ($q) => $q->where('user_uuid', $request->get('opd')))
            ->orderBy('waktu_mulai')
            ->limit(500)
            ->get();

        $total = $items->count();
        $perStatus = $items->groupBy('status')->map->count();
        $perJenis = $items->groupBy(fn ($a) => $a->jenis_agenda ?? 'offline')->map->count();
        $perKategori = $items->groupBy(fn ($a) => $a->category?->name ?? '-')->map->count()->sortDesc();
        $perOpd = $items->groupBy(fn ($a) => $a->user?->name ?? '-')->map->count()->sortDesc()->take(10);
        $totalViews = $items->sum('views');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.agendas.laporan', [
            'items'       => $items,
            'total'       => $total,
            'perStatus'   => $perStatus,
            'perJenis'    => $perJenis,
            'perKategori' => $perKategori,
            'perOpd'      => $perOpd,
            'totalViews'  => $totalViews,
            'periode'     => $periode,
            'isApprover'  => $isApprover,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-agenda-' . $periode->format('Y-m') . '.pdf');
    }

    /**
     * Ekspor laporan bulanan ke Excel (XLSX), termasuk jumlah lampiran.
     */
    public function laporanExcel(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));

        try {
            $periode = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        } catch (\Throwable) {
            $periode = now()->startOfMonth();
            $bulan = $periode->format('Y-m');
        }

        $isApprover = $this->isApprover($request->user());

        $items = Agenda::with(['user', 'category', 'attachments'])
            ->when(! $isApprover, fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->whereYear('waktu_mulai', $periode->year)
            ->whereMonth('waktu_mulai', $periode->month)
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_uuid', $request->get('kategori')))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis_agenda', $request->get('jenis')))
            ->when($request->filled('opd'), fn ($q) => $q->where('user_uuid', $request->get('opd')))
            ->orderBy('waktu_mulai')
            ->limit(2000)
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['No', 'Judul', 'Kategori', 'Jenis', 'Status', 'Waktu Mulai', 'Waktu Akhir', 'Lokasi/URL', 'Dilihat', 'Lampiran', 'Penulis'], null, 'A1');

        $row = 2;
        foreach ($items as $i => $a) {
            $sheet->fromArray(ExcelSafe::row([
                $i + 1,
                $a->title,
                $a->category?->name,
                $a->jenis_agenda,
                $a->status,
                $a->waktu_mulai?->format('Y-m-d H:i'),
                $a->waktu_akhir?->format('Y-m-d H:i'),
                $a->jenis_agenda === 'online' ? $a->url : $a->lokasi,
                $a->views,
                $a->attachments->count(),
                $a->user?->name,
            ]), null, 'A' . $row++);
        }

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'laporan-agenda-' . $periode->format('Y-m') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    public function laporanPptx(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));

        try {
            $periode = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        } catch (\Throwable) {
            $periode = now()->startOfMonth();
            $bulan = $periode->format('Y-m');
        }

        $isApprover = $this->isApprover($request->user());

        $items = Agenda::with(['user', 'category', 'attachments'])
            ->when(! $isApprover, fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->whereYear('waktu_mulai', $periode->year)
            ->whereMonth('waktu_mulai', $periode->month)
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_uuid', $request->get('kategori')))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis_agenda', $request->get('jenis')))
            ->when($request->filled('opd'), fn ($q) => $q->where('user_uuid', $request->get('opd')))
            ->orderBy('waktu_mulai')
            ->limit(500)
            ->get();

        $perStatus = $items->groupBy('status')->map->count();
        $perKategori = $items->groupBy(fn ($a) => $a->category?->name ?? '-')->map->count()->sortDesc();
        $perOpd = $items->groupBy(fn ($a) => $a->user?->name ?? '-')->map->count()->sortDesc()->take(10);
        $totalViews = $items->sum('views');
        $labelPeriode = ucfirst($periode->translatedFormat('F Y'));
        $center = \PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER;

        $ppt = new \PhpOffice\PhpPresentation\PhpPresentation();
        $ppt->getDocumentProperties()->setTitle('Laporan Agenda ' . $labelPeriode);
        $ppt->getDocumentProperties()->setCreator(config('app.name'));

        $slide = $ppt->getActiveSlide();
        $this->pptText($slide, config('app.name'), ['x' => 0, 'y' => 150, 'w' => 960, 'h' => 30, 'size' => 15, 'bold' => true, 'color' => 'FF5E72E4', 'align' => $center]);
        $this->pptText($slide, 'Laporan Agenda', ['x' => 0, 'y' => 195, 'w' => 960, 'h' => 60, 'size' => 40, 'bold' => true, 'align' => $center]);
        $this->pptText($slide, 'Periode ' . $labelPeriode, ['x' => 0, 'y' => 265, 'w' => 960, 'h' => 36, 'size' => 20, 'align' => $center]);
        $this->pptText($slide, 'Dicetak ' . now()->translatedFormat('l, j F Y H:i'), ['x' => 0, 'y' => 315, 'w' => 960, 'h' => 26, 'size' => 12, 'color' => 'FF6E84A3', 'align' => $center]);
        $this->pptText($slide, $items->count() >= 500 ? 'Menampilkan 500 agenda pertama' : $items->count() . ' agenda pada periode ini', ['x' => 0, 'y' => 345, 'w' => 960, 'h' => 26, 'size' => 11, 'color' => 'FF6E84A3', 'align' => $center]);

        $slide = $ppt->createSlide();
        $this->pptText($slide, 'Ringkasan', ['y' => 30, 'size' => 24, 'bold' => true]);
        $this->pptTable($slide, ['Metrik', 'Nilai'], [
            ['Total agenda', number_format($items->count())],
            ['Published', number_format($perStatus->get('published', 0))],
            ['Menunggu persetujuan', number_format($perStatus->get('pending', 0))],
            ['Draft', number_format($perStatus->get('draft', 0))],
            ['Terjadwal', number_format($perStatus->get('scheduled', 0))],
            ['Total dilihat', number_format($totalViews)],
        ], 40, 90, 480);

        $slide = $ppt->createSlide();
        $this->pptText($slide, 'Agenda per Kategori', ['y' => 30, 'size' => 24, 'bold' => true]);
        $this->pptTable(
            $slide,
            ['Kategori', 'Jumlah'],
            $perKategori->take(12)->map(fn ($jumlah, $nama) => [$nama, number_format($jumlah)])->values()->all(),
            40, 90, 480
        );

        $slide = $ppt->createSlide();
        $this->pptText($slide, 'OPD Teraktif', ['y' => 30, 'size' => 24, 'bold' => true]);
        $this->pptTable(
            $slide,
            ['OPD', 'Jumlah Agenda'],
            $perOpd->map(fn ($jumlah, $nama) => [$nama, number_format($jumlah)])->values()->all(),
            40, 90, 640
        );

        foreach ($items->chunk(12)->values() as $chunkIndex => $chunk) {
            $slide = $ppt->createSlide();
            $this->pptText($slide, 'Daftar Agenda', ['y' => 30, 'size' => 24, 'bold' => true]);
            $this->pptText($slide, $labelPeriode . ' — bagian ' . ($chunkIndex + 1), ['y' => 62, 'size' => 11, 'color' => 'FF6E84A3']);

            $rows = $chunk->map(fn ($a, $i) => [
                $i + 1,
                Str::limit($a->title, 40),
                Str::limit($a->category?->name ?? '-', 18),
                $a->jenis_agenda ?? 'offline',
                $a->status,
                $a->waktu_mulai?->format('d M Y H:i') ?? '-',
                Str::limit($a->jenis_agenda === 'online' ? ($a->url ?? '-') : ($a->lokasi ?? '-'), 30),
                Str::limit($a->user?->name ?? '-', 20),
            ])->values()->all();

            $this->pptTable($slide, ['No', 'Judul', 'Kategori', 'Jenis', 'Status', 'Mulai', 'Lokasi/URL', 'Penulis'], $rows, 30, 95, 900);
        }

        $filename = 'laporan-agenda-' . $periode->format('Y-m') . '.pptx';

        return response()->streamDownload(function () use ($ppt) {
            $writer = \PhpOffice\PhpPresentation\IOFactory::createWriter($ppt, 'PowerPoint2007');
            $writer->save('php://output');
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation']);
    }

    private function pptText($slide, string $text, array $opts = [])
    {
        $shape = new \PhpOffice\PhpPresentation\Shape\RichText();
        $shape->setOffsetX((int) ($opts['x'] ?? 40));
        $shape->setOffsetY((int) ($opts['y'] ?? 40));
        $shape->setWidth((int) ($opts['w'] ?? 880));
        $shape->setHeight((int) ($opts['h'] ?? 40));

        $run = $shape->createTextRun($text);
        $run->getFont()
            ->setName('Arial')
            ->setSize($opts['size'] ?? 14)
            ->setBold((bool) ($opts['bold'] ?? false))
            ->setColor(new \PhpOffice\PhpPresentation\Style\Color($opts['color'] ?? 'FF172B4D'));

        if (! empty($opts['align'])) {
            $shape->getActiveParagraph()->getAlignment()->setHorizontal($opts['align']);
        }

        $slide->addShape($shape);

        return $shape;
    }

    private function pptTable($slide, array $header, array $rows, int $x = 40, int $y = 90, int $width = 880)
    {
        $table = new \PhpOffice\PhpPresentation\Shape\Table(count($header));
        $table->setOffsetX($x);
        $table->setOffsetY($y);
        $table->setWidth($width);

        $headRow = $table->createRow();
        $headRow->setHeight(26);
        foreach ($header as $label) {
            $cell = $headRow->nextCell();
            $cell->getFill()
                ->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)
                ->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF5E72E4'));
            $run = $cell->createTextRun($label);
            $run->getFont()
                ->setName('Arial')
                ->setSize(11)
                ->setBold(true)
                ->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));
        }

        foreach ($rows as $rowData) {
            $row = $table->createRow();
            $row->setHeight(22);
            foreach ($rowData as $value) {
                $cell = $row->nextCell();
                $run = $cell->createTextRun((string) $value);
                $run->getFont()
                    ->setName('Arial')
                    ->setSize(10)
                    ->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF344767'));
            }
        }

        $slide->addShape($table);

        return $table;
    }

    /**
     * Ekspor CSV hasil filter saat ini (ramah Excel, pakai BOM UTF-8).
     */
    public function export(Request $request)
    {
        $query = $this->filteredAgendas($request)->with(['user', 'category'])->withCount('attachments');
        $this->applySort($query, $request->get('sort', 'terbaru'));

        $filename = 'agendas-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Judul', 'Jenis', 'Lokasi', 'Waktu Mulai', 'Waktu Akhir', 'URL', 'Kategori', 'Penulis', 'Status', 'Dilihat', 'Tanggal Terbit', 'Lampiran']);

            foreach ($query->cursor() as $agenda) {
                fputcsv($out, [
                    $agenda->title,
                    $agenda->jenis_agenda ?? 'offline',
                    $agenda->lokasi,
                    $agenda->waktu_mulai?->format('Y-m-d H:i'),
                    $agenda->waktu_akhir?->format('Y-m-d H:i'),
                    $agenda->url,
                    $agenda->category?->name,
                    $agenda->user?->name,
                    $agenda->status,
                    $agenda->views,
                    $agenda->scheduled_at?->format('Y-m-d'),
                    $agenda->attachments_count,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Generate agenda turunan untuk event berulang (harian/mingguan/bulanan, maks 12).
     * Dipanggil di dalam transaksi store; melempar exception bila gagal.
     *
     * @return int jumlah turunan yang dibuat
     */
    private function generateRecurrence(Agenda $parent, array $validated, string $status, Request $request, array &$storedFiles): int
    {
        $rule = $validated['recurrence_rule'] ?? null;
        $endRaw = $validated['recurrence_end'] ?? null;
        $mulaiRaw = $validated['waktu_mulai'] ?? null;

        if (! $rule || ! $endRaw || ! $mulaiRaw) {
            return 0;
        }

        $mulai = Carbon::parse($mulaiRaw);
        $end = Carbon::parse($endRaw)->endOfDay();
        $akhir = ! empty($validated['waktu_akhir']) ? Carbon::parse($validated['waktu_akhir']) : null;
        $durasiMenit = $akhir ? $mulai->diffInMinutes($akhir) : null;

        $tanggal = $mulai->copy();
        $dibuat = 0;

        while ($dibuat < 12) {
            $tanggal = match ($rule) {
                'harian'   => $tanggal->copy()->addDay(),
                'mingguan' => $tanggal->copy()->addWeek(),
                'bulanan'  => $tanggal->copy()->addMonth(),
                default    => null,
            };

            if ($tanggal === null || $tanggal->gt($end)) {
                break;
            }

            $child = $parent->replicate(['uuid', 'slug', 'views', 'created_at', 'updated_at', 'deleted_at']);
            $child->user_uuid = $request->user()->uuid;
            $child->slug = $this->uniqueSlug(Str::slug($parent->title));
            $child->views = 0;
            $child->is_featured = false;
            $child->is_popular = false;
            $child->status = $status;
            $child->parent_uuid = $parent->uuid;
            $child->recurrence_rule = null;
            $child->recurrence_end = null;
            $child->waktu_mulai = $tanggal->copy();
            $child->waktu_akhir = $durasiMenit !== null ? $tanggal->copy()->addMinutes($durasiMenit) : null;
            $child->featured_image = null;

            if ($parent->featured_image && Storage::disk(self::IMAGE_DISK)->exists($parent->featured_image)) {
                $newPath = self::IMAGE_DIRECTORY . '/' . (string) Str::uuid() . '.' . pathinfo($parent->featured_image, PATHINFO_EXTENSION);
                Storage::disk(self::IMAGE_DISK)->copy($parent->featured_image, $newPath);
                $storedFiles[] = $newPath;
                $child->featured_image = $newPath;
            }

            $child->save();

            foreach ($parent->images as $image) {
                $newPath = $image->image_path;
                if (Storage::disk(self::IMAGE_DISK)->exists($image->image_path)) {
                    $newPath = self::IMAGE_DIRECTORY . '/' . (string) Str::uuid() . '.' . pathinfo($image->image_path, PATHINFO_EXTENSION);
                    Storage::disk(self::IMAGE_DISK)->copy($image->image_path, $newPath);
                    $storedFiles[] = $newPath;
                }
                $child->images()->create([
                    'image_path' => $newPath,
                    'caption'    => $image->caption,
                    'sort_order' => $image->sort_order,
                ]);
            }

            foreach ($parent->attachments as $lampiran) {
                $newPath = $lampiran->file_path;
                if (Storage::disk(self::ATTACHMENT_DISK)->exists($lampiran->file_path)) {
                    $ext = pathinfo($lampiran->file_path, PATHINFO_EXTENSION);
                    $newPath = self::ATTACHMENT_DIRECTORY . '/' . (string) Str::uuid() . ($ext ? '.' . $ext : '');
                    Storage::disk(self::ATTACHMENT_DISK)->copy($lampiran->file_path, $newPath);
                    $storedFiles[] = $newPath;
                }
                $child->attachments()->create([
                    'file_path' => $newPath,
                    'nama_asli' => $lampiran->nama_asli,
                    'ukuran'    => $lampiran->ukuran,
                ]);
            }

            $this->saveSeoData($child, $request);
            $this->logAgenda($child, 'dibuat', 'Event berulang dari seri "' . Str::limit($parent->title, 80) . '"');
            $dibuat++;
        }

        if ($dibuat > 0) {
            $parent->recurrence_rule = $rule;
            $parent->recurrence_end = Carbon::parse($endRaw)->toDateString();
            $parent->save();
        }

        return $dibuat;
    }

    /**
     * Store the uploaded slider photos for the given agenda.
     */
    public function downloadAttachment(Request $request, Agenda $agenda, \App\Models\AgendaAttachment $attachment)
    {
        if (! $this->isApprover($request->user()) && $agenda->user_uuid !== $request->user()->uuid) {
            abort(403);
        }

        if ($attachment->agenda_uuid !== $agenda->uuid) {
            abort(404);
        }

        $disk = Storage::disk(self::ATTACHMENT_DISK);

        if (! $disk->exists($attachment->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return $disk->download($attachment->file_path, $attachment->nama_asli);
    }

    public function destroyAttachment(Request $request, Agenda $agenda, \App\Models\AgendaAttachment $attachment): \Illuminate\Http\JsonResponse
    {
        if (! $this->isApprover($request->user()) && $agenda->user_uuid !== $request->user()->uuid) {
            abort(403);
        }

        if ($attachment->agenda_uuid !== $agenda->uuid) {
            abort(404);
        }

        $nama = $attachment->nama_asli;
        $path = $attachment->file_path;
        $attachment->delete();

        if ($path && Storage::disk(self::ATTACHMENT_DISK)->exists($path)) {
            Storage::disk(self::ATTACHMENT_DISK)->delete($path);
        }

        $this->logAgenda($agenda, 'diperbarui', 'Lampiran dihapus: ' . Str::limit($nama, 100));

        return response()->json(['ok' => true]);
    }

    private function storeAttachments(Agenda $agenda, Request $request, array &$storedFiles = []): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        $lock = Cache::lock('agenda_attachments_' . $agenda->uuid, 10);

        try {
            $lock->block(5);

            $totalBaru = array_sum(array_map(fn ($f) => $f && $f->isValid() ? $f->getSize() : 0, $request->file('attachments')));
            $totalLama = (int) $agenda->attachments()->sum('ukuran');
            if ($totalLama + $totalBaru > self::ATTACHMENT_MAX_TOTAL) {
                throw new \App\Exceptions\UserFacingException('Total lampiran tidak boleh melebihi 25 MB.');
            }

            $allowedMimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/zip',
                'application/x-zip-compressed',
                'image/jpeg',
                'image/png',
                'image/webp',
            ];

            foreach ($request->file('attachments') as $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                $mime = (string) $file->getMimeType();
                if (! in_array($mime, $allowedMimes, true)) {
                    continue;
                }

                $path = $file->store(self::ATTACHMENT_DIRECTORY, self::ATTACHMENT_DISK);
                $storedFiles[] = $path;
                $namaAsli = htmlspecialchars(basename($file->getClientOriginalName()), ENT_QUOTES, 'UTF-8');
                $agenda->attachments()->create([
                    'file_path' => $path,
                    'nama_asli' => mb_substr($namaAsli, 0, 255),
                    'ukuran'    => $file->getSize(),
                ]);

                if ($file->getSize() > 5 * 1024 * 1024) {
                    Log::notice('Lampiran agenda berukuran besar (>5MB): ' . $agenda->uuid . ' ' . $file->getClientOriginalName() . ' (' . (int) ($file->getSize() / 1048576) . 'MB)');
                }
            }
        } finally {
            $lock?->release();
        }
    }

    private function storeImages(Agenda $agenda, Request $request, array &$storedFiles = []): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $order = (int) $agenda->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $path = $file->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
            $storedFiles[] = $path;
            $agenda->images()->create([
                'image_path' => $path,
                'sort_order' => ++$order,
            ]);
        }
    }

    /**
     * Persist the SEO metadata for the given agenda.
     */
    private function saveSeoData(Agenda $agenda, Request $request): void
    {
        $agenda->seo()->updateOrCreate([], [
            'title'         => $agenda->title,
            'description'   => $agenda->excerpt,
            'image'         => $agenda->featured_image,
            'author'        => $request->user()->name,
            'robots'        => $agenda->search_engine ?? 'index, follow',
            'canonical_url' => route('agendas.show', $agenda->slug),
        ]);
    }

    /**
     * Delete a stored file when it exists.
     */
    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk(self::IMAGE_DISK)->exists($path)) {
            Storage::disk(self::IMAGE_DISK)->delete($path);
        }
    }
}
