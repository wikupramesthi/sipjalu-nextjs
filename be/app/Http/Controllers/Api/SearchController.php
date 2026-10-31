<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /** Tipe yang boleh dicari publik. Aduan dikecualikan (PII + nomor lacak). */
    private const PUBLIC_TYPES = ['agenda', 'page', 'faq', 'document', 'category'];

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'types' => 'nullable|array|max:5',
            'types.*' => 'string|in:agenda,page,faq,document,category',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $query = trim((string) ($validated['q'] ?? ''));
        $types = $validated['types'] ?? self::PUBLIC_TYPES;
        // Lapisan ganda: paksa allowlist walau validasi lolos.
        $types = array_values(array_intersect($types, self::PUBLIC_TYPES));
        if ($types === []) {
            $types = self::PUBLIC_TYPES;
        }
        $limit = min((int) ($validated['limit'] ?? 10), 20);

        if (mb_strlen($query) < 2) {
            return response()->json([
                'query' => $query,
                'results' => [],
                'total' => 0,
            ]);
        }

        $results = [];
        $total = 0;

        $modelMap = [
            'agenda' => [
                'model' => Agenda::class,
                'label' => 'Agenda',
                'route' => 'agendas.show',
                'icon' => 'bi-newspaper',
            ],
            'page' => [
                'model' => Page::class,
                'label' => 'Halaman',
                'route' => 'pages.show',
                'icon' => 'bi-file-richtext',
            ],
            'faq' => [
                'model' => Faq::class,
                'label' => 'FAQ',
                'route' => 'faqs.show',
                'icon' => 'bi-question-circle',
            ],
            'document' => [
                'model' => Document::class,
                'label' => 'Dokumen',
                'route' => 'documents.show',
                'icon' => 'bi-file-earmark-text',
            ],
            'category' => [
                'model' => Category::class,
                'label' => 'Kategori',
                'route' => 'categories.show',
                'icon' => 'bi-tags',
            ],
        ];

        foreach ($types as $type) {
            if (!isset($modelMap[$type])) {
                continue;
            }

            $config = $modelMap[$type];
            $modelClass = $config['model'];

            try {
                // Filter klien diabaikan sengaja: where() arbitrer membuka
                // probing atribut model via Meilisearch. Hanya q + limit.
                $search = $modelClass::search($query);

                $items = $search->limit($limit)->get();

                $formatted = $items->map(function ($item) use ($config, $type) {
                    $url = $this->generateUrl($type, $item, $config['route']);
                    
                    return [
                        'type' => $type,
                        'id' => $item->getKey(),
                        'title' => $this->getTitle($item),
                        'subtitle' => $this->getSubtitle($item, $type),
                        'url' => $url,
                        'icon' => $config['icon'],
                        'thumbnail' => $this->getThumbnail($item),
                        'metadata' => $this->getMetadata($item, $type),
                    ];
                })->values();

                $results[$type] = [
                    'label' => $config['label'],
                    'icon' => $config['icon'],
                    'count' => $items->count(),
                    'items' => $formatted,
                ];

                $total += $items->count();
            } catch (\Throwable $e) {
                // Silently skip failed searches (e.g., Meilisearch not running)
                $results[$type] = [
                    'label' => $config['label'],
                    'icon' => $config['icon'],
                    'count' => 0,
                    'items' => [],
                    'error' => 'Search unavailable',
                ];
            }
        }

        return response()->json([
            'query' => $query,
            'total' => $total,
            'results' => $results,
            'took' => 0, // Could add timing if needed
        ]);
    }

    public function suggest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'limit' => 'nullable|integer|min:1|max:10',
        ]);

        $query = trim((string) ($validated['q'] ?? ''));
        $limit = min((int) ($validated['limit'] ?? 5), 10);

        if (mb_strlen($query) < 1) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = [];
        $types = ['agenda', 'page', 'faq', 'document', 'category'];

        foreach ($types as $type) {
            try {
                $modelClass = match ($type) {
                    'agenda' => Agenda::class,
                    'page' => Page::class,
                    'faq' => Faq::class,
                    'document' => Document::class,
                    'category' => Category::class,
                    default => null,
                };

                if (!$modelClass) continue;

                $items = $modelClass::search($query)
                    ->limit($limit)
                    ->get(['id', 'title', 'slug', 'name', 'judul', 'pertanyaan']);

                foreach ($items as $item) {
                    $title = $this->getTitle($item);
                    $suggestions[] = [
                        'text' => $title,
                        'type' => $type,
                        'id' => $item->getKey(),
                        'url' => $this->generateUrl($type, $item, $this->getRoute($type)),
                    ];
                }
            } catch (\Throwable) {
                // Skip
            }
        }

        // Deduplicate by text
        $suggestions = collect($suggestions)
            ->unique('text')
            ->take($limit)
            ->values()
            ->all();

        return response()->json(['suggestions' => $suggestions]);
    }

    private function getTitle($item): string
    {
        return $item->title ?? $item->judul ?? $item->name ?? $item->pertanyaan ?? 'Tanpa Judul';
    }

    private function getSubtitle($item, string $type): string
    {
        return match ($type) {
            'agenda' => $item->category?->name ?? ucfirst($item->status ?? 'draft'),
            'page' => $item->is_published ? 'Dipublikasikan' : 'Draft',
            'faq' => ucfirst($item->status ?? 'active'),
            'document' => $item->category?->name ?? ucfirst($item->status ?? '-'),
            'category' => $item->agendas_count . ' agenda',
            default => '',
        };
    }

    private function getThumbnail($item): ?string
    {
        return $item->thumbnail_url 
            ?? ($item->featured_image ? asset('storage/' . $item->featured_image) : null)
            ?? ($item->gambar ? asset('storage/' . $item->gambar) : null)
            ?? ($item->thumbnail ? asset('storage/' . $item->thumbnail) : null)
            ?? null;
    }

    private function getMetadata($item, string $type): array
    {
        return match ($type) {
            'agenda' => [
                'views' => $item->views ?? 0,
                'is_featured' => $item->is_featured ?? false,
                'published_at' => $item->created_at?->toISOString(),
            ],
            'document' => [
                'file_size' => $item->file_size ?? null,
                'downloads' => $item->downloads ?? 0,
            ],
            default => [],
        };
    }

    private function getRoute(string $type): string
    {
        return match ($type) {
            'agenda' => 'agendas.show',
            'page' => 'pages.show',
            'faq' => 'faqs.show',
            'document' => 'documents.show',
            'category' => 'categories.show',
            default => '#',
        };
    }

    private function generateUrl(string $type, $item, string $route): string
    {
        $key = $item->slug ?? $item->uuid ?? $item->nomor_aduan ?? $item->getKey();
        
        try {
            return route($route, $key);
        } catch (\Throwable) {
            return '#';
        }
    }
}