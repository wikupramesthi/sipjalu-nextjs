<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * GET /api/banners — read-only untuk frontend.
     * Query: ?kategori=slider|banner|pengumuman|... &limit=1..10 &sort=latest|oldest
     * Untuk posisi banner/slider otomatis limit 5 (rapih slider FE) kecuali diminta lain.
     * sort=latest = terbaru dulu (dipakai slider pengumuman).
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kategori' => ['nullable', 'string', 'max:30'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
                'sort' => ['nullable', 'in:latest,oldest'],
            ]);

            $kategori = $request->input('kategori');
            $sort = $request->input('sort');
            $limit = $request->integer('limit', 0);
            $cacheKey = sprintf('index:k=%s:l=%d:s=%s', $kategori ?: 'all', $limit, $sort ?: 'oldest');

            $data = PublicApiCache::remember('banners', $cacheKey, function () use ($kategori, $sort, $limit) {
                // Hanya ambil banner foto yang aktif
                $query = Banner::where('tipe', 'foto')->where('status', 'active');

                $allowedKategori = ['slider', 'banner', 'pengumuman', 'infografis', 'galeri', 'popup', 'mitra', 'lainnya'];
                // alias: "banner" dianggap sama dengan "slider" (istilah FE)
                $effectiveKategori = $kategori === 'banner' ? 'slider' : $kategori;

                if ($kategori && in_array($kategori, $allowedKategori, true)) {
                    $query->where('posisi', $effectiveKategori);
                }

                $query->orderBy('created_at', $sort === 'latest' ? 'desc' : 'asc');

                // Mitra carousel menampilkan maksimal 7; banner lain tetap maksimal 5.
                if ($limit === 0 && in_array($kategori, ['slider', 'banner'], true)) {
                    $limit = 5;
                }
                if ($limit > 0) {
                    $query->limit(min($limit, $kategori === 'mitra' ? 7 : 5));
                }

                return BannerResource::collection($query->get())->resolve();
            }, PublicApiCache::TTL_DEFAULT);

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar banner berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
                'data' => []
            ], 422);
        } catch (\Exception $e) {
            Log::error('Banner fetch error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data banner',
                'data' => []
            ], 500);
        }
    }
}
