<?php

namespace App\Listeners;

use App\Models\FailedLogin;
use App\Services\Security\BruteForceProtector;
use Illuminate\Auth\Events\Failed;

class RecordFailedLogin
{
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? $event->user?->email;
        $ip = request()->ip();

        FailedLogin::create([
            'email' => $email,
            'user_uuid' => $event->user?->getAuthIdentifier(),
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'reason' => 'credentials',
            'attempted_at' => now(),
        ]);

        try {
            app(BruteForceProtector::class)->recordFailure($ip, $email);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
