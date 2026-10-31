<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentSubcategoryController extends Controller
{
    use HandlesTransactions;

    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $subcategories = DocumentCategory::with('parent')
            ->withCount('documents')
            ->whereNotNull('parent_uuid')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('parent', fn ($parentQuery) => $parentQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('parent_uuid')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $parentCategories = DocumentCategory::whereNull('parent_uuid')
            ->orderBy('name')
            ->get();
        $activeParentCategories = DocumentCategory::whereNull('parent_uuid')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('pages.document-subcategories.index', compact('subcategories', 'parentCategories', 'activeParentCategories', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_uuid' => [
                'required',
                Rule::exists('document_categories', 'uuid')
                    ->whereNull('parent_uuid')
                    ->where('status', 'active'),
            ],
            'name' => ['required', 'string', 'max:255', 'unique:document_categories,name'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        return $this->transactional(function () use ($validated) {
            DocumentCategory::create([
                'uuid' => (string) Str::uuid(),
                'parent_uuid' => $validated['parent_uuid'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);
        }, 'Subkategori dokumen berhasil ditambahkan.');
    }

    public function update(Request $request, string $uuid)
    {
        $subcategory = DocumentCategory::whereNotNull('parent_uuid')->where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'parent_uuid' => ['required', Rule::exists('document_categories', 'uuid')->whereNull('parent_uuid')],
            'name' => ['required', 'string', 'max:255', Rule::unique('document_categories', 'name')->ignore($uuid, 'uuid')],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        return $this->transactional(function () use ($subcategory, $validated) {
            $subcategory->update([
                'parent_uuid' => $validated['parent_uuid'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);
        }, 'Subkategori dokumen berhasil diperbarui.');
    }

    public function destroy(string $uuid)
    {
        $subcategory = DocumentCategory::whereNotNull('parent_uuid')
            ->withCount('documents')
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($subcategory->documents_count > 0) {
            return back()->with('error', 'Subkategori tidak dapat dihapus karena masih memiliki dokumen.');
        }

        return $this->transactional(function () use ($subcategory) {
            $subcategory->delete();
        }, 'Subkategori dokumen berhasil dihapus.');
    }
}
