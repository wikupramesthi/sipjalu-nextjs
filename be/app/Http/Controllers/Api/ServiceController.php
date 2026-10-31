<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * GET /api/services?search=&category=&per_page=
     * Publik read-only: hanya layanan aktif yang ditampilkan.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'in:external,internal,other'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        try {
            $perPage = (int) ($validated['per_page'] ?? 12);
            $page = max(1, $request->integer('page', 1));
            $search = trim((string) ($validated['search'] ?? ''));
            $category = (string) ($validated['category'] ?? '');

            $payload = PublicApiCache::remember(
                'services',
                sprintf(
                    'index:page=%d:per=%d:q=%s:cat=%s',
                    $page,
                    $perPage,
                    sha1($search),
                    $category !== '' ? $category : 'all'
                ),
                function () use ($perPage, $page, $search, $category) {
                    $services = Service::query()
                        ->active()
                        ->search($search !== '' ? $search : null)
                        ->when(
                            $category !== '',
                            fn ($query) => $query->where('category', $category)
                        )
                        ->latest()
                        ->paginate($perPage, ['*'], 'page', $page);

                    return [
                        'message' => $services->isEmpty() ? 'Belum ada layanan' : 'Daftar layanan berhasil diambil',
                        'data' => ServiceResource::collection($services->items())->resolve(),
                        'meta' => [
                            'current_page' => $services->currentPage(),
                            'per_page' => $services->perPage(),
                            'total' => $services->total(),
                            'last_page' => $services->lastPage(),
                            'from' => $services->firstItem(),
                            'to' => $services->lastItem(),
                        ],
                    ];
                },
                PublicApiCache::TTL_DEFAULT
            );

            return response()->json([
                'status' => 'success',
                'message' => $payload['message'],
                'data' => $payload['data'],
                'meta' => $payload['meta'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Service index error: ' . $e->getMessage());

            return $this->error('Gagal mengambil layanan.');
        }
    }

    /**
     * GET /api/services/{uuid}
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            $data = PublicApiCache::remember(
                'services',
                'show:' . $uuid,
                function () use ($uuid) {
                    $service = Service::query()
                        ->active()
                        ->where('uuid', $uuid)
                        ->first();

                    return $service ? (new ServiceResource($service))->resolve() : null;
                },
                PublicApiCache::TTL_LONG
            );

            if ($data === null) {
                return $this->notFound('Layanan tidak ditemukan.');
            }

            return $this->success('Detail layanan berhasil diambil.', $data);
        } catch (\Throwable $e) {
            Log::error('Service show error: ' . $e->getMessage());

            return $this->error('Gagal mengambil detail layanan.');
        }
    }
}
