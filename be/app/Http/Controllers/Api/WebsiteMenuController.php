<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteMenuResource;
use App\Models\WebsiteMenu;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebsiteMenuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $location = $request->filled('location') ? (string) $request->query('location') : '';

            $data = PublicApiCache::remember(
                'menus',
                'index:location=' . ($location !== '' ? $location : 'all'),
                function () use ($location) {
                    $query = WebsiteMenu::where('status', true)->orderBy('position');

                    if ($location !== '') {
                        $query->where('location', $location);
                    }

                    $menus = $query->with(['activeItems.allChildren' => function ($q) {
                        $q->where('status', true)->orderBy('position');
                    }])->get();

                    return WebsiteMenuResource::collection($menus)->resolve();
                },
                PublicApiCache::TTL_LONG
            );

            return $this->success('Daftar menu berhasil diambil', $data);
        } catch (\Exception $e) {
            Log::error('WebsiteMenu fetch error: ' . $e->getMessage());
            return $this->error('Gagal mengambil menu');
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $data = PublicApiCache::remember(
                'menus',
                'show:' . $slug,
                function () use ($slug) {
                    $menu = WebsiteMenu::where('status', true)
                        ->where(function ($q) use ($slug) {
                            $q->where('slug', $slug)->orWhere('id', $slug);
                        })
                        ->with(['activeItems.allChildren' => function ($q) {
                            $q->where('status', true)->orderBy('position');
                        }])
                        ->first();

                    return $menu ? (new WebsiteMenuResource($menu))->resolve() : null;
                },
                PublicApiCache::TTL_LONG
            );

            if ($data === null) {
                return $this->error('Menu tidak ditemukan', null, 404);
            }

            return $this->success('Detail menu berhasil diambil', $data);
        } catch (\Exception $e) {
            Log::error('WebsiteMenu detail error: ' . $e->getMessage());
            return $this->error('Gagal mengambil detail menu');
        }
    }
}
