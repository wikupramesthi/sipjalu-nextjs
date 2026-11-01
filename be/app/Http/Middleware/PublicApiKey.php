<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kunci API publik untuk endpoint yang dibaca SPA (header X-Api-Key).
 *
 * Mekanisme:
 * - Nilai kunci diambil dari config `api.public_key` (env PUBLIC_API_KEY).
 * - Env kosong (lokal/dev): middleware meloloskan semua.
 * - Env terisi (produksi): permintaan tanpa X-Api-Key yang cocok ditolak 403.
 *
 * Sifatnya penghalang scraper: kunci ikut ter-embed di bundle FE (build-time),
 * jadi endpoint ini bukan autentikasi. Endpoint Sanctum (auth/*, v1/*, opd/*)
 * dan stream media CCTV sengaja di luar middleware ini karena sudah memakai
 * token/throttle masing-masing.
 */
class PublicApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = trim((string) config('api.public_key'));

        if ($key === '') {
            return $next($request);
        }

        $given = trim((string) $request->header('X-Api-Key', ''));

        if ($given === '' || ! hash_equals($key, $given)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses API ditolak. Sertakan kunci publik yang valid.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
