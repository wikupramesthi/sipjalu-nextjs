<?php

namespace App\Http\Controllers;

use App\Services\SystemHealthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function __construct(protected SystemHealthService $health) {}

    /**
     * Halaman visual health check.
     */
    public function page()
    {
        return view('pages.health.index');
    }

    /**
     * Status kesehatan sistem untuk monitoring (boleh publik).
     * 200 = sehat, 503 = ada yang gagal.
     * Detail per-komponen (DB/storage/scheduler) disamarkan agar tidak
     * membocorkan info infra; hasil di-cache 30 detik anti-abuse.
     */
    public function index(): JsonResponse
    {
        $payload = Cache::remember('health:status', 30, function () {
            $sehat = collect($this->health->allChecks())->every(fn ($c) => $c['ok'] === true);

            return [
                'sehat' => $sehat,
                'waktu' => now()->format('d M Y H:i:s'),
            ];
        });

        return response()->json([
            'status' => $payload['sehat'] ? 'ok' : 'terganggu',
            'waktu' => $payload['waktu'],
        ], $payload['sehat'] ? 200 : 503);
    }

    /**
     * Detail pemeriksaan untuk pengguna yang berhak melihat halaman health check.
     */
    public function details(): JsonResponse
    {
        return response()->json([
            'pemeriksaan' => $this->health->allChecks(),
            'waktu' => now()->format('d M Y H:i:s'),
        ]);
    }
}
