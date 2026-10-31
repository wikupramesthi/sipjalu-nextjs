<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PorprovBeritaController extends Controller
{
    private const SOURCE_BASE = 'https://porprovbekasikota2026.id';
    private const CACHE_TTL = 600;
    private const STALE_CACHE_TTL = 86400;
    private const SOURCE_LIST_CACHE_KEY = 'porprov_berita_source_v2';
    private const SOURCE_LIST_STALE_CACHE_KEY = 'porprov_berita_source_stale_v2';

    /**
     * GET /api/porprov-berita — daftar berita (proxy + cache 10 menit).
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
            ]);
            $limit = min(max($request->integer('limit', 5), 1), 10);

            $items = collect($this->sourceNews())
                ->take($limit)
                ->map(function ($d) {
                    if (!is_array($d)) {
                        return null;
                    }
                    $slug = (string) ($d['slug'] ?? '');
                    $img = (string) ($d['featuredImage']['url'] ?? '');
                    if ($img !== '' && !str_starts_with($img, 'http')) {
                        $img = self::SOURCE_BASE . $img;
                    }
                    $date = '';
                    if (!empty($d['publishedAt'])) {
                        try {
                            $date = (new \DateTime($d['publishedAt']))->format('d-m-Y');
                        } catch (\Exception) {
                            $date = (string) $d['publishedAt'];
                        }
                    }
                    return [
                        'id' => $d['id'] ?? 0,
                        'title' => (string) ($d['title'] ?? ''),
                        'slug' => $slug,
                        'href' => self::SOURCE_BASE . '/berita/' . $slug,
                        'image' => $img,
                        'date' => $date,
                        'category' => (string) ($d['tipeBerita'] ?? 'News'),
                    ];
                })
                ->filter()
                ->values()
                ->all();

            return response()->json([
                'status' => 'success',
                'message' => 'Berita Porprov berhasil diambil',
                'data' => $items,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
                'data' => [],
            ], 422);
        } catch (\Exception $e) {
            Log::error('Porprov berita proxy error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil berita Porprov dari portal resmi.',
                'data' => [],
            ], 502);
        }
    }

    /**
     * GET /api/porprov-berita/{idOrSlug} — detail berita (proxy server-side).
     * Mendukung id numerik atau slug string. Jika slug, cari ID dari list cache dulu.
     */
    public function show(Request $request, string $idOrSlug): JsonResponse
    {
        try {
            $cacheKey = 'porprov_berita_detail_' . $idOrSlug;
            $item = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($idOrSlug) {
                // Jika numeric ID, fetch langsung
                if (ctype_digit($idOrSlug)) {
                    return $this->fetchDetailById((int) $idOrSlug);
                }

                $target = null;
                foreach ($this->sourceNews() as $doc) {
                    if (($doc['slug'] ?? '') === $idOrSlug) {
                        $target = $doc;
                        break;
                    }
                }

                if (!$target || !isset($target['id'])) {
                    return null;
                }

                return $this->fetchDetailById((int) $target['id']);
            });

            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Berita tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Detail berita Porprov berhasil diambil',
                'data' => $item,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Porprov berita detail proxy error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail berita Porprov.',
                'data' => null,
            ], 502);
        }
    }

    /**
     * Fetch detail by numeric ID from external API.
     */
    private function fetchDetailById(int $id): ?array
    {
        $res = Http::connectTimeout(2)->timeout(5)->acceptJson()->get(self::SOURCE_BASE . '/api/berita/' . $id);
        if (!$res->successful()) {
            return null;
        }
        $d = $res->json();
        if (!isset($d['id'])) {
            return null;
        }

        $img = (string) ($d['featuredImage']['url'] ?? '');
        if ($img !== '' && !str_starts_with($img, 'http')) {
            $img = self::SOURCE_BASE . $img;
        }

        $isoDate = '';
        $date = '';
        if (!empty($d['publishedAt'])) {
            try {
                $dt = new \DateTime($d['publishedAt']);
                $isoDate = $dt->format('Y-m-d');
                $date = $dt->format('d F Y');
            } catch (\Exception) {
                $isoDate = (string) $d['publishedAt'];
                $date = (string) $d['publishedAt'];
            }
        }

        $contentHtml = $this->lexicalToHtml($d['content'] ?? null);

        $cabangOlahraga = [];
        if (isset($d['cabangOlahraga']) && is_array($d['cabangOlahraga'])) {
            foreach ($d['cabangOlahraga'] as $c) {
                $cabangOlahraga[] = [
                    'id' => $c['id'] ?? 0,
                    'title' => (string) ($c['title'] ?? ''),
                    'slug' => (string) ($c['slug'] ?? ''),
                ];
            }

        }

        return [
            'id' => $d['id'],
            'title' => (string) ($d['title'] ?? ''),
            'slug' => (string) ($d['slug'] ?? ''),
            'href' => self::SOURCE_BASE . '/berita/' . ($d['slug'] ?? ''),
            'image' => $img,
            'imageAlt' => (string) ($d['featuredImage']['alt'] ?? $d['title'] ?? ''),
            'date' => $date,
            'isoDate' => $isoDate,
            'category' => (string) ($d['tipeBerita'] ?? 'News'),
            'content' => $contentHtml,
            'cabangOlahraga' => $cabangOlahraga,
        ];
    }

    /**
     * Semua daftar memakai satu cache sumber agar limit berbeda tidak
     * memicu permintaan upstream yang bersamaan. Cache stale menjaga halaman
     * tetap berisi berita saat portal sumber sedang lambat/tidak tersedia.
     *
     * @return array<int, array<string, mixed>>
     */
    private function sourceNews(): array
    {
        $cached = Cache::get(self::SOURCE_LIST_CACHE_KEY);
        if (is_array($cached)) {
            return $cached;
        }

        try {
            return Cache::lock(self::SOURCE_LIST_CACHE_KEY . ':refresh', 10)->block(6, function (): array {
                $cached = Cache::get(self::SOURCE_LIST_CACHE_KEY);
                if (is_array($cached)) {
                    return $cached;
                }

                $res = Http::connectTimeout(2)->timeout(5)->acceptJson()->get(
                    self::SOURCE_BASE . '/api/berita',
                    ['limit' => 20]
                );
                if (!$res->successful()) {
                    throw new \RuntimeException('Upstream berita Porprov menjawab ' . $res->status());
                }

                $docs = $res->json('docs', []);
                if (!is_array($docs)) {
                    throw new \RuntimeException('Respons daftar berita Porprov tidak valid');
                }

                Cache::put(self::SOURCE_LIST_CACHE_KEY, $docs, self::CACHE_TTL);
                Cache::put(self::SOURCE_LIST_STALE_CACHE_KEY, $docs, self::STALE_CACHE_TTL);

                return $docs;
            });
        } catch (\Exception $e) {
            $stale = Cache::get(self::SOURCE_LIST_STALE_CACHE_KEY);
            if (is_array($stale) && $stale !== []) {
                Log::warning('Menggunakan cache berita Porprov terakhir setelah upstream gagal.', [
                    'error' => $e->getMessage(),
                ]);
                Cache::put(self::SOURCE_LIST_CACHE_KEY, $stale, 30);

                return $stale;
            }

            throw $e;
        }
    }

    /**
     * Konversi Lexical JSON → HTML (support paragraph, heading, list, blockquote, format).
     */
    private function lexicalToHtml($lexical): string
    {
        if (!$lexical || !isset($lexical['root']['children']) || !is_array($lexical['root']['children'])) {
            return '';
        }
        $htmlParts = [];
        foreach ($lexical['root']['children'] as $child) {
            $type = $child['type'] ?? '';
            switch ($type) {
                case 'paragraph':
                    $html = $this->renderRichText($child['children'] ?? []);
                    if (!trim(strip_tags($html))) {
                        // Skip empty paragraphs entirely
                        break;
                    }
                    $htmlParts[] = '<p>' . $html . '</p>';
                    break;
                case 'heading':
                    $tag = 'h' . min(max((int)($child['tag'] ?? 2), 2), 4);
                    $html = $this->renderRichText($child['children'] ?? []);
                    $htmlParts[] = "<{$tag}>" . $html . "</{$tag}>";
                    break;
                case 'bulleted-list':
                case 'numbered-list':
                    $tag = $type === 'numbered-list' ? 'ol' : 'ul';
                    $items = [];
                    foreach (($child['children'] ?? []) as $listItem) {
                        if (($listItem['type'] ?? '') === 'list-item') {
                            $itemHtml = $this->renderRichText($listItem['children'] ?? []);
                            $items[] = '<li>' . $itemHtml . '</li>';
                        }
                    }
                    if ($items) {
                        $htmlParts[] = '<' . $tag . '>' . implode('', $items) . '</' . $tag . '>';
                    }
                    break;
                case 'quote':
                    $html = $this->renderRichText($child['children'] ?? []);
                    $htmlParts[] = '<blockquote>' . $html . '</blockquote>';
                    break;
                case 'code':
                    $html = $this->renderRichText($child['children'] ?? []);
                    $htmlParts[] = '<pre><code>' . htmlspecialchars($html, ENT_QUOTES, 'UTF-8') . '</code></pre>';
                    break;
                default:
                    // Coba render sebagai paragraph fallback
                    if (!empty($child['children'])) {
                        $html = $this->renderRichText($child['children']);
                        if (trim(strip_tags($html))) {
                            $htmlParts[] = '<p>' . $html . '</p>';
                        }
                    }
                    break;
            }
        }
        return implode("\n", $htmlParts);
    }

    /**
     * Render array of Lexical text nodes dengan format (bold, italic, link, dll).
     */
    private function renderRichText($nodes): string
    {
        $html = '';
        if (!is_array($nodes)) return $html;
        foreach ($nodes as $node) {
            if (!is_array($node)) continue;

            // Text node dengan format
            if (isset($node['text']) && is_string($node['text'])) {
                $text = htmlspecialchars($node['text'], ENT_QUOTES, 'UTF-8');
                $format = $node['format'] ?? 0;
                $type = $node['type'] ?? 'text';

                // Link: hanya http(s) yang dirender (tolak javascript:/data: dari CMS).
                if ($type === 'link' && isset($node['url'])) {
                    $rawUrl = trim((string) $node['url']);
                    if (preg_match('#^https?://#i', $rawUrl)) {
                        $url = htmlspecialchars($rawUrl, ENT_QUOTES, 'UTF-8');
                        $text = "<a href=\"{$url}\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"text-brand-700 underline hover:text-brand-800\">{$text}</a>";
                    }
                } else {
                    // Inline formats (bitmask): 1=bold, 2=italic, 4=underline, 8=strikethrough, 16=code
                    if ($format & 1) $text = '<strong>' . $text . '</strong>';
                    if ($format & 2) $text = '<em>' . $text . '</em>';
                    if ($format & 4) $text = '<u>' . $text . '</u>';
                    if ($format & 8) $text = '<del>' . $text . '</del>';
                    if ($format & 16) $text = '<code>' . $text . '</code>';
                }

                // Atribut style mentah dari CMS tidak diteruskan (vektor CSS injection).
                $html .= $text;
            }

            // Nested children (untuk link, dll)
            if (isset($node['children']) && is_array($node['children'])) {
                $html .= $this->renderRichText($node['children']);
            }
        }
        return $html;
    }
}