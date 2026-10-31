<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Category;
use App\Support\PublicApiCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BeritaLiveController extends Controller
{
    /**
     * GET /api/berita-live?limit=N — berita lokal dalam format yang dipakai SPA.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $this->limit($request);
            $featured = $request->boolean('featured');

            $items = PublicApiCache::remember(
                'berita',
                'live:limit=' . $limit . ':featured=' . ($featured ? '1' : '0'),
                function () use ($limit, $featured) {
                    $query = $this->publishedPosts(false);
                    if ($featured) {
                        $query->where('is_featured', true);
                    }

                    return $query->limit($limit)
                        ->get()
                        ->map(fn (Berita $berita): array => $this->toLiveItem($berita))
                        ->all();
                },
                PublicApiCache::TTL_DEFAULT
            );

            return $this->success('Berita terbaru berhasil diambil', $items);
        } catch (\Exception $e) {
            Log::error('Berita lokal fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil berita terbaru');
        }
    }

    /**
     * GET /api/post/all?limit=N — format kompatibel dengan API post Bekasikota.
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $limit = $this->limit($request);

            $posts = PublicApiCache::remember(
                'berita',
                'all:limit=' . $limit,
                fn () => $this->publishedPosts()
                    ->limit($limit)
                    ->get()
                    ->map(fn (Berita $berita): array => $this->toBekasiPost($berita))
                    ->all(),
                PublicApiCache::TTL_DEFAULT
            );

            return $this->success('Daftar berita berhasil diambil', ['post' => $posts]);
        } catch (\Exception $e) {
            Log::error('Daftar berita lokal fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil berita');
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $data = PublicApiCache::remember(
                'berita',
                'detail:' . $slug,
                function () use ($slug) {
                    $berita = $this->publishedPosts()
                        ->where('slug', $slug)
                        ->first();

                    return $berita ? ['post' => $this->toBekasiPost($berita)] : null;
                },
                PublicApiCache::TTL_SHORT
            );

            if ($data === null) {
                return $this->notFound('Berita tidak ditemukan');
            }

            return $this->success('Detail berita berhasil diambil', $data);
        } catch (\Exception $e) {
            Log::error('Detail berita lokal fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil detail berita');
        }
    }

    public function byCategory(Request $request, string $slug): JsonResponse
    {
        try {
            $category = Category::query()->where('slug', $slug)->first();

            if (! $category) {
                return $this->notFound('Kategori berita tidak ditemukan');
            }

            $limit = $this->limit($request);

            $data = PublicApiCache::remember(
                'berita',
                'category:' . $slug . ':limit=' . $limit,
                function () use ($category, $limit) {
                    $posts = $this->publishedPosts()
                        ->where('category_uuid', $category->uuid)
                        ->limit($limit)
                        ->get()
                        ->map(fn (Berita $berita): array => $this->toBekasiPost($berita))
                        ->all();

                    return [
                        'category' => [
                            'id_category' => $category->uuid,
                            'title_category' => $category->name,
                            'slug' => $category->slug,
                        ],
                        'post' => $posts,
                    ];
                },
                PublicApiCache::TTL_DEFAULT
            );

            return $this->success('Daftar berita kategori berhasil diambil', $data);
        } catch (\Exception $e) {
            Log::error('Berita kategori lokal fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil berita kategori');
        }
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required_without:keyword', 'nullable', 'string', 'max:100'],
            'keyword' => ['required_without:q', 'nullable', 'string', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1', 'max:10000'],
        ]);
        $keyword = trim((string) ($validated['q'] ?? $validated['keyword'] ?? ''));

        if ($keyword === '') {
            return $this->validationError('Kata kunci pencarian wajib diisi', [
                'q' => ['Kata kunci pencarian wajib diisi.'],
            ]);
        }

        try {
            $limit = min(max($request->integer('limit', 12), 1), 50);
            $page = (int) ($validated['page'] ?? 1);

            $data = PublicApiCache::remember(
                'berita',
                'search:' . sha1($keyword) . ':page=' . $page . ':limit=' . $limit,
                function () use ($keyword, $limit, $page) {
                    $paginator = $this->publishedPosts(false)
                        ->where(function (Builder $query) use ($keyword): void {
                            $query->where('title', 'like', '%' . $keyword . '%')
                                ->orWhere('excerpt', 'like', '%' . $keyword . '%')
                                ->orWhere('content', 'like', '%' . $keyword . '%')
                                ->orWhere('tagging', 'like', '%' . $keyword . '%')
                                ->orWhereHas('category', fn (Builder $categoryQuery) => $categoryQuery->where('name', 'like', '%' . $keyword . '%'));
                        })
                        ->orderBy('uuid')
                        ->simplePaginate($limit, ['*'], 'page', $page);

                    return [
                        'keyword' => $keyword,
                        'post' => $paginator->getCollection()
                            ->map(fn (Berita $berita): array => $this->toSearchPost($berita))
                            ->all(),
                        'pagination' => [
                            'current_page' => $paginator->currentPage(),
                            'per_page' => $paginator->perPage(),
                            'from' => $paginator->firstItem(),
                            'to' => $paginator->lastItem(),
                            'has_more' => $paginator->hasMorePages(),
                        ],
                    ];
                },
                PublicApiCache::TTL_SHORT
            );

            return $this->success('Hasil pencarian berita berhasil diambil', $data);
        } catch (\Exception $e) {
            Log::error('Pencarian berita lokal error: ' . $e->getMessage());

            return $this->error('Gagal mencari berita');
        }
    }

    private function limit(Request $request): int
    {
        return min(max($request->integer('limit', 10), 1), 50);
    }

    private function publishedPosts(bool $includeContent = true): Builder
    {
        $query = Berita::query()
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
            })
            ->orderByRaw('COALESCE(scheduled_at, created_at) DESC');

        if ($includeContent) {
            return $query->with(['category', 'images']);
        }

        return $query
            ->select([
                'uuid',
                'category_uuid',
                'title',
                'slug',
                'excerpt',
                'featured_image',
                'scheduled_at',
                'views',
                'tagging',
                'is_popular',
                'is_featured',
                'status',
                'created_at',
            ])
            ->with([
                'category:uuid,name',
                'images:uuid,berita_uuid,image_path,sort_order',
            ]);
    }

    private function toSearchPost(Berita $berita): array
    {
        $post = $this->toBekasiPost($berita);
        unset($post['content'], $post['status']);

        return $post;
    }

    private function toBekasiPost(Berita $berita): array
    {
        $publishedAt = $berita->scheduled_at ?? $berita->created_at;
        $imagePath = $berita->featured_image ?: $berita->images->first()?->image_path;

        return [
            'id_post' => $berita->uuid,
            'title' => $berita->title,
            'seotitle' => $berita->slug,
            'picture' => $this->imageUrl($imagePath),
            'date' => $publishedAt?->format('Y-m-d') ?? '',
            'time' => $publishedAt?->format('H:i:s') ?? '',
            'content' => $berita->content,
            'tag' => $berita->tagging ?? '',
            'title_category' => $berita->category?->name ?? 'Kegiatan',
            'id_category' => $berita->category_uuid,
            'hits' => (int) $berita->views,
            'is_popular' => (bool) $berita->is_popular,
            'is_featured' => (bool) $berita->is_featured,
            'status' => $berita->status,
        ];
    }

    private function toLiveItem(Berita $berita): array
    {
        $post = $this->toBekasiPost($berita);

        return [
            'title' => $post['title'],
            'slug' => $post['seotitle'],
            'date' => $post['date'],
            'time' => $post['time'],
            'datetime' => trim($post['date'] . ' ' . $post['time']),
            'image' => $post['picture'],
            'excerpt' => $berita->excerpt ?? '',
            'tags' => collect(explode(',', $post['tag']))->map(fn (string $tag): string => trim($tag))->filter()->values()->all(),
            'sourceHref' => '',
            'category' => $post['title_category'],
            'categoryId' => $post['id_category'],
            'hits' => $post['hits'],
            'is_popular' => $post['is_popular'],
            'is_featured' => $post['is_featured'],
            'status' => $post['status'],
        ];
    }

    private function imageUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $path) === 1) {
            return $path;
        }

        $path = preg_replace('#^storage/#', '', ltrim($path, '/')) ?? $path;

        return asset('storage/' . $path);
    }
}
