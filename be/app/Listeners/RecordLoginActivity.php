<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use App\Services\Security\BruteForceProtector;
use Illuminate\Auth\Events\Login;

class RecordLoginActivity
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $ip = request()->ip();

        LoginActivity::create([
            'user_uuid' => $user?->getAuthIdentifier(),
            'name' => $user?->name,
            'email' => $user?->email,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'guard' => $event->guard,
            'logged_in_at' => now(),
        ]);

        try {
            app(BruteForceProtector::class)->clear($ip, $user?->email);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Deteksi login janggal (tanpa notifikasi — notifikasi hanya untuk alur agenda).
     * Dibiarkan sebagai hook bila di masa depan dibutuhkan lagi.
     */
    protected function peringatkanBilaAneh($user, string $ip): void
    {
        return;
    }
}
