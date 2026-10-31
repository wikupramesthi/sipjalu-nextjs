<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentCategoryResource;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     *
     * Mendukung `?category={slug}` untuk filter satu kategori dan
     * `?limit=N` (1-50). Hanya dokumen published (status aktif dan
     * tanggal terbit tiba) yang ditampilkan ke publik.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->integer('limit', 0);
            $query = $this->documentsQuery()->orderByDesc('published_at');
            if ($request->filled('category')) {
                $slug = $request->string('category')->toString();
                $query->whereHas('category', fn (Builder $query) => $query->where('slug', $slug));
            }
            if ($limit > 0) {
                $query->limit(min($limit, 50));
            }
            $documents = $query->get();

            if ($documents->isEmpty()) {
                return $this->success('Belum ada dokumen', []);
            }

            return $this->success('Daftar dokumen berhasil diambil', DocumentResource::collection($documents));
        } catch (\Exception $e) {
            Log::error('Document fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil dokumen');
        }
    }

    /**
     * Display a listing of the document categories.
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = DocumentCategory::with(['parent', 'children'])->where('status', 'active')->orderBy('name')->get();

            return $this->success('Daftar kategori dokumen berhasil diambil', DocumentCategoryResource::collection($categories));
        } catch (\Exception $e) {
            Log::error('Document category fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil kategori dokumen');
        }
    }

    /**
     * Get subcategories for a specific category.
     */
    public function getSubcategories(Request $request): JsonResponse
    {
        try {
            $categoryUuid = $request->input('category_uuid');
            $subcategories = DocumentCategory::where('parent_uuid', $categoryUuid)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            return $this->success('Daftar subkategori dokumen berhasil diambil', DocumentCategoryResource::collection($subcategories));
        } catch (\Exception $e) {
            Log::error('Document subcategories fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil subkategori dokumen');
        }
    }

    /**
     * Display a listing of the documents for the given category slug.
     *
     * Mendukung `?limit=N` (1-50) seperti index().
     */
    public function byCategory(Request $request, string $slug): JsonResponse
    {
        try {
            $category = DocumentCategory::where('slug', $slug)->where('status', 'active')->first();

            if (! $category) {
                return $this->error('Kategori dengan slug "' . $slug . '" tidak ditemukan', [], 404);
            }

            $categoryUuids = array_merge([$category->uuid], $category->children()->pluck('uuid')->all());

            $limit = $request->integer('limit', 0);
            $query = $this->documentsQuery()
                ->whereIn('category_uuid', $categoryUuids)
                ->orderByDesc('published_at');
            if ($limit > 0) {
                $query->limit(min($limit, 50));
            }
            $documents = $query->get();

            if ($documents->isEmpty()) {
                return $this->success('Belum ada dokumen di kategori "' . $slug . '"', []);
            }

            return $this->success(
                'Daftar dokumen kategori "' . $slug . '" berhasil diambil',
                DocumentResource::collection($documents)
            );
        } catch (\Exception $e) {
            Log::error('Document by category fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil dokumen kategori ' . $slug);
        }
    }

    private function documentsQuery(): Builder
    {
        return Document::query()->with(['category.parent', 'subcategory'])->published();
    }
}
