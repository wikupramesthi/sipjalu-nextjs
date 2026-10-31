<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;

/**
 * Format response JSON standar untuk seluruh API:
 *
 *   Sukses  : { "status": "success", "message": "...", "data": ... }
 *   List    : + { "meta": { current_page, per_page, total, last_page, from, to } }
 *   Gagal   : { "status": "error", "message": "...", "data": null }
 *   Validasi: { "status": "error", "message": "...", "errors": { field: [...] } } (422)
 */
trait ApiResponse
{
    protected function success(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function created(string $message, mixed $data = null): JsonResponse
    {
        return $this->success($message, $data, 201);
    }

    protected function error(string $message, mixed $data = null, int $status = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function notFound(string $message = 'Data tidak ditemukan'): JsonResponse
    {
        return $this->error($message, null, 404);
    }

    protected function unauthorized(string $message = 'Tidak terautentikasi.'): JsonResponse
    {
        return $this->error($message, null, 401);
    }

    protected function forbidden(string $message = 'Anda tidak memiliki akses.'): JsonResponse
    {
        return $this->error($message, null, 403);
    }

    protected function validationError(string $message, array $errors = []): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    protected function paginated(string $message, $paginator, ?callable $transform = null): JsonResponse
    {
        $data = $transform ? $transform($paginator->items()) : $paginator->items();

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }
}
