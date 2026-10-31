<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentCategoryController extends Controller
{
    use HandlesTransactions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $allForStats = DocumentCategory::whereNull('parent_uuid')->withCount('documents')->get();
        $DocumentCategoryCounts = $allForStats;
        $stats = [
            'total'    => $allForStats->count(),
            'active'   => $allForStats->where('status', 'active')->count(),
            'inactive' => $allForStats->where('status', 'inactive')->count(),
        ];

        $categories = DocumentCategory::whereNull('parent_uuid')
            ->withCount(['documents', 'children'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('parent', fn ($parentQuery) => $parentQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('pages.document-categories.index', compact('categories', 'DocumentCategoryCounts', 'stats', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        return $this->transactional(function () use ($request) {
            DocumentCategory::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'status' => $request->status,
            ]);
        }, 'Kategori dokumen berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $item = DocumentCategory::whereNull('parent_uuid')->where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('document_categories', 'name')->ignore($uuid, 'uuid')],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        return $this->transactional(function () use ($item, $request) {
            $item->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'status' => $request->status,
            ]);
        }, 'Kategori dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $item = DocumentCategory::whereNull('parent_uuid')
            ->withCount(['documents', 'children'])
            ->where('uuid', $uuid)
            ->firstOrFail();
        if ($item->children_count > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki subkategori.');
        }

        return $this->transactional(function () use ($uuid) {
            DocumentCategory::where('uuid', $uuid)->delete();
        }, 'Kategori dokumen berhasil dihapus.');
    }

    /**
     * Remove the selected resources from storage.
     * Kategori yang masih memiliki dokumen atau subkategori dilewati.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada kategori yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $categories = DocumentCategory::whereNull('parent_uuid')
            ->withCount(['documents', 'children'])
            ->whereIn('uuid', $ids)
            ->get();

        if ($categories->isEmpty()) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        }

        $deletable = $categories->where('documents_count', 0)->where('children_count', 0);
        $skipped = $categories->count() - $deletable->count();

        if ($deletable->isEmpty()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki dokumen atau subkategori.');
        }

        return $this->transactional(function () use ($deletable) {
            DocumentCategory::whereIn('uuid', $deletable->pluck('uuid')->all())->delete();
        }, $deletable->count() . ' kategori berhasil dihapus.'
            . ($skipped > 0 ? " {$skipped} kategori dilewati karena masih memiliki dokumen atau subkategori." : ''));
    }
}
