<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    /**
     * GET /api/albums?search=&per_page=&page=
     * Public read-only, hanya album active + cover + count (ringan untuk grid).
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'search' => ['nullable', 'string', 'max:150'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:24'],
            ]);

            $q = Album::query()->where('status', 'active')
                ->withCount('fotos')
                ->when($request->filled('search'), fn($qq) => $qq->where('nama', 'like', '%' . $request->input('search') . '%'))
                ->orderBy('created_at', 'desc');

            $perPage = (int) $request->input('per_page', 9);
            $data = $q->paginate($perPage);

            return $this->paginated(
                $data->isEmpty() ? 'Belum ada album' : 'Daftar album berhasil diambil',
                $data,
                fn($items) => AlbumResource::collection($items)
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
                'data' => [],
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Album index error: ' . $e->getMessage());
            return $this->error('Gagal mengambil album');
        }
    }

    /**
     * GET /api/albums/{uuid}
     * Detail + semua foto (untuk halaman /album/{uuid}).
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            // tabel banner singular -> qualified select pakai banner.*
            $album = Album::where('uuid', $uuid)->where('status', 'active')
                ->withCount('fotos')
                ->with(['fotos' => fn($q) => $q->select('banner.uuid', 'banner.nama', 'banner.gambar', 'banner.deskripsi')->orderBy('banner.created_at', 'desc')])
                ->first();

            if (! $album) {
                return $this->error('Album tidak ditemukan', null, 404);
            }

            return $this->success('Detail album berhasil diambil', new AlbumResource($album));
        } catch (\Throwable $e) {
            Log::error('Album show error: ' . $e->getMessage());
            return $this->error('Gagal mengambil detail album');
        }
    }
}
