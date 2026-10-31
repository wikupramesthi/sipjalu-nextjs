<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $payload = PublicApiCache::remember(
                'pages',
                'index',
                function () {
                    $pages = Page::where('is_published', true)->orderBy('created_at', 'DESC')->get();

                    return [
                        'message' => $pages->isEmpty()
                            ? 'Belum ada halaman yang tersedia'
                            : 'Data halaman berhasil diambil',
                        'data' => PageResource::collection($pages)->resolve(),
                    ];
                },
                PublicApiCache::TTL_LONG
            );

            return response()->json([
                'status' => 'success',
                'message' => $payload['message'],
                'data' => $payload['data']
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Page fetch error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data halaman',
                'data' => []
            ], 500);
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $data = PublicApiCache::remember(
                'pages',
                'show:' . $slug,
                function () use ($slug) {
                    $page = Page::where('slug', $slug)
                        ->where('is_published', true)
                        ->first();

                    return $page ? (new PageResource($page))->resolve() : null;
                },
                PublicApiCache::TTL_LONG
            );

            if ($data === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Halaman tidak ditemukan',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Detail halaman berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Halaman tidak ditemukan',
                'data' => null
            ], 404);
        } catch (\Throwable $e) {

            Log::error('Page show error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil detail halaman',
                'data' => null
            ], 500);
        }
    }
}
