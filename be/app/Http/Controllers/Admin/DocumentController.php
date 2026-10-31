<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    
    use HandlesTransactions;
/**
     * Display a listing of the documents.
     */

    private function isAdmin(?\App\Models\User $user = null): bool
    {
        $user ??= auth()->user();

        return (bool) $user?->hasAnyRole(['super-admin', 'admin']);
    }

    public function index(Request $request)
    {
        // OPD hanya melihat dokumen buatannya sendiri; admin melihat semua.
        $baseQuery = Document::query()
            ->when(! $this->isAdmin($request->user()), fn ($q) => $q->where('user_uuid', $request->user()->uuid));

        // Searching
        if ($request->filled('search')) {
            $search = $request->search;

            $baseQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori
        if ($request->filled('category_uuid')) {
            $baseQuery->where('category_uuid', $request->category_uuid);
        }

        // Filter status
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        // Statistik dihitung dari query yang SUDAH difilter (tanpa paginasi),
        // sehingga angka pada card selalu sesuai dengan data hasil filter.
        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'active'     => (clone $baseQuery)->where('status', 'active')->count(),
            'inactive'   => (clone $baseQuery)->where('status', 'inactive')->count(),
            'categories' => (clone $baseQuery)->distinct()->count('category_uuid'),
        ];

        $documents = $baseQuery
            ->with('category')
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        $categories = DocumentCategory::with('parent')->orderBy('name')->get();

        return view(
            'pages.documents.index',
            compact('documents', 'categories', 'stats')
        );
    }


    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $categories = DocumentCategory::where('status', 'active')
            ->whereNull('parent_uuid')
            ->orderBy('name')
            ->get();

        return view('pages.documents.create', compact('categories'));
    }

    public function getSubcategories(Request $request)
    {
        $categoryUuid = $request->input('category_uuid');
        $subcategories = DocumentCategory::where('parent_uuid', $categoryUuid)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json($subcategories);
    }

    /**
     * Store a newly created.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_uuid' => ['required', Rule::exists('document_categories', 'uuid')->whereNull('parent_uuid')],
            'subcategory_uuid' => 'nullable|exists:document_categories,uuid|uuid',
            'file_source'   => 'required|in:url,upload',
            'file_url'      => 'required_if:file_source,url|nullable|url|max:2048',
            'title'         => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:documents,slug',
            'excerpt'       => 'nullable|string|max:500',
            'description'   => 'nullable|string',
            'published_at'  => 'nullable|date',
            'file'          => 'required_if:file_source,upload|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt,odt,ods|max:5120',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'required|in:active,inactive',
        ]);

        if ($request->filled('subcategory_uuid')) {
            $subcategory = DocumentCategory::where('uuid', $request->subcategory_uuid)->first();
            if ($subcategory && $subcategory->parent_uuid !== $request->category_uuid) {
                return back()->withInput()->with('error', 'Subkategori tidak valid untuk kategori yang dipilih.');
            }
        }

        $storedFilePath = null;
        $fileUrl = $request->file_source === 'url'
            ? $request->file_url
            : null;
        $thumbPath = null;
        try {
            if ($request->file_source === 'upload') {
                $storedFilePath = $request->file('file')->store('documents/files', 'public');
            }
            if ($request->hasFile('thumbnail')) {
                $thumbPath = $request->file('thumbnail')->store('documents/thumbnails', 'public');
            }
            \Illuminate\Support\Facades\DB::beginTransaction();
            Document::create([
                'user_uuid' => $request->user()->uuid,
                'category_uuid' => $request->category_uuid,
                'title'        => $request->title,
                'slug'         => $request->slug ? Str::slug($request->slug) : Str::slug($request->title),
                'excerpt'      => $request->excerpt,
                'description'  => HtmlSanitizer::clean($request->description),
                'published_at' => $request->published_at,
                'file'         => $storedFilePath,
                'file_url'     => $fileUrl,
                'thumbnail'    => $thumbPath,
                'status'       => $request->status,
            ]);
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('documents.index')->with('success', 'Dokumen berhasil disimpan.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if ($storedFilePath && Storage::disk('public')->exists($storedFilePath)) Storage::disk('public')->delete($storedFilePath);
            if ($thumbPath && Storage::disk('public')->exists($thumbPath)) Storage::disk('public')->delete($thumbPath);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified studio.
     */
    public function edit(Document $document)
    {
        if (! $this->isAdmin() && $document->user_uuid !== auth()->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        $document->load(['versions.user', 'category']);
        $categories = DocumentCategory::where('status', 'active')
            ->whereNull('parent_uuid')
            ->orderBy('name')
            ->get();

        return view(
            'pages.documents.edit',
            compact('document', 'categories')
        );
    }

    /**
     * Update the specified .
     */
    public function update(Request $request, Document $document)
    {
        if (! $this->isAdmin($request->user()) && $document->user_uuid !== $request->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        $request->validate([
            'category_uuid' => ['required', Rule::exists('document_categories', 'uuid')->whereNull('parent_uuid')],
            'subcategory_uuid' => 'nullable|exists:document_categories,uuid|uuid',
            'file_source'   => 'required|in:url,upload',
            'file_url'      => 'required_if:file_source,url|nullable|url|max:2048',
            'title'         => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:documents,slug,' . $document->uuid . ',uuid',
            'excerpt'       => 'nullable|string|max:500',
            'description'   => 'nullable|string',
            'published_at'  => 'nullable|date',
            'file'          => [
                ! $document->file && $request->input('file_source') === 'upload'
                    ? 'required'
                    : 'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt,odt,ods',
                'max:5120',
            ],
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'        => 'required|in:active,inactive',
        ]);

        if ($request->filled('subcategory_uuid')) {
            $subcategory = DocumentCategory::where('uuid', $request->subcategory_uuid)->first();
            if ($subcategory && $subcategory->parent_uuid !== $request->category_uuid) {
                return back()->withInput()->with('error', 'Subkategori tidak valid untuk kategori yang dipilih.');
            }
        }

        $oldFile = $document->file;
        $oldThumb = $document->thumbnail;
        $newFile = null;
        $newThumb = null;
        try {
            if ($request->file_source === 'upload' && $request->hasFile('file')) {
                $newFile = $request->file('file')->store('documents/files', 'public');
            }
            if ($request->hasFile('thumbnail')) {
                $newThumb = $request->file('thumbnail')->store('documents/thumbnails', 'public');
            }
            $data = $request->only(['category_uuid', 'title', 'excerpt', 'status']);
            $data['description'] = HtmlSanitizer::clean($request->description);
            if ($request->filled('published_at')) $data['published_at'] = $request->published_at;
            $data['slug'] = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
            $data['file'] = $request->file_source === 'url'
                ? null
                : ($newFile ?? $document->file);
            $data['file_url'] = $request->file_source === 'url'
                ? $request->file_url
                : null;
            if ($newThumb) $data['thumbnail'] = $newThumb;

            \Illuminate\Support\Facades\DB::beginTransaction();
            // simpan versi lama sebelum update (agar tidak hilang file lama)
            $nextVersi = ($document->versions()->max('versi') ?? 0) + 1;
            \App\Models\DocumentVersion::create([
                'document_uuid' => $document->uuid,
                'title' => $document->title,
                'file' => $oldFile,
                'file_url' => $document->file_url,
                'thumbnail' => $oldThumb,
                'keterangan' => 'Versi sebelum update: ' . now()->format('d/m/Y H:i'),
                'user_uuid' => auth()->user()->uuid ?? null,
                'versi' => $nextVersi,
            ]);
            $document->update($data);
            \Illuminate\Support\Facades\DB::commit();
            if ($oldFile && $oldFile !== $document->file && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
            if ($newThumb && $oldThumb && Storage::disk('public')->exists($oldThumb)) Storage::disk('public')->delete($oldThumb);
            return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if ($newFile && Storage::disk('public')->exists($newFile)) Storage::disk('public')->delete($newFile);
            if ($newThumb && Storage::disk('public')->exists($newThumb)) Storage::disk('public')->delete($newThumb);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    /**
     * Remove the specified document.
     */
    public function destroy(Document $document)
    {
        if (! $this->isAdmin() && $document->user_uuid !== auth()->user()->uuid) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        $file = $document->file;
        $thumb = $document->thumbnail;
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $document->delete();
            \Illuminate\Support\Facades\DB::commit();
            if ($file && Storage::disk('public')->exists($file)) Storage::disk('public')->delete($file);
            if ($thumb && Storage::disk('public')->exists($thumb)) Storage::disk('public')->delete($thumb);
            return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('documents.index')->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) return back()->with('error', 'Tidak ada data dipilih.');
        $docs = Document::whereIn('uuid', $ids)
            ->when(! $this->isAdmin($request->user()), fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->get();
        if ($docs->isEmpty()) return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            Document::whereIn('uuid', $docs->pluck('uuid')->all())->delete();
            \Illuminate\Support\Facades\DB::commit();
            foreach ($docs as $d) {
                if ($d->file && Storage::disk('public')->exists($d->file)) Storage::disk('public')->delete($d->file);
                if ($d->thumbnail && Storage::disk('public')->exists($d->thumbnail)) Storage::disk('public')->delete($d->thumbnail);
            }
            return back()->with('success', $docs->count() . ' dokumen berhasil dihapus.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
