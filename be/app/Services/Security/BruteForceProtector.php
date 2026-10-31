<?php

namespace App\Services\Security;

use App\Models\LoginLockout;
use Illuminate\Support\Str;

class BruteForceProtector
{
    public function maxAttempts(): int
    {
        return (int) config('security.brute_force.max_attempts', 5);
    }

    public function decayMinutes(): int
    {
        return (int) config('security.brute_force.decay_minutes', 15);
    }

    public function lockoutMinutes(): int
    {
        return (int) config('security.brute_force.lockout_minutes', 30);
    }

    public function normalizeEmail(?string $email): ?string
    {
        if ($email === null || trim($email) === '') {
            return null;
        }

        return Str::lower(trim($email));
    }

    /**
     * Return the active lockout affecting the given IP or email, if any.
     */
    public function isLocked(?string $ip, ?string $email): ?LoginLockout
    {
        $email = $this->normalizeEmail($email);

        if (blank($ip) && blank($email)) {
            return null;
        }

        return LoginLockout::query()
            ->active()
            ->where(function ($query) use ($ip, $email) {
                if ($ip) {
                    $query->orWhere(fn ($q) => $q->where('type', 'ip')->where('value', $ip));
                }

                if ($email) {
                    $query->orWhere(fn ($q) => $q->where('type', 'email')->where('value', $email));
                }
            })
            ->orderByDesc('blocked_until')
            ->first();
    }

    /**
     * Register a failed attempt and lock the IP/email when the threshold is hit.
     *
     * Anti-DoS: kunci level email hanya untuk email yang benar-benar
     * terdaftar dan dengan ambang 3x lipat, agar penyerang tidak bisa
     * mengunci akun korban untuk semua orang dengan spam password salah.
     */
    public function recordFailure(?string $ip, ?string $email): ?LoginLockout
    {
        $lockout = null;
        $threshold = $this->maxAttempts();
        $normalizedEmail = $this->normalizeEmail($email);

        foreach ([['ip', $ip, $threshold], ['email', $normalizedEmail, $threshold * 3]] as [$type, $value, $limit]) {
            if (blank($value)) {
                continue;
            }

            if ($type === 'email' && ! \App\Models\User::where('email', $value)->exists()) {
                continue;
            }

            $row = LoginLockout::firstOrNew(['type' => $type, 'value' => $value]);

            if (
                $row->exists
                && $row->last_attempt_at
                && $row->last_attempt_at->lt(now()->subMinutes($this->decayMinutes()))
            ) {
                $row->attempts = 0;
                $row->blocked_until = null;
                $row->reason = null;
            }

            $row->attempts = (int) $row->attempts + 1;
            $row->last_attempt_at = now();

            if ($row->attempts >= $limit) {
                $row->blocked_until = now()->addMinutes($this->lockoutMinutes());
                $row->reason = 'Terdeteksi percobaan login gagal berulang';
                $lockout = $row;
            }

            $row->save();
        }

        return $lockout;
    }

    /**
     * Clear counters and lockouts after a successful authentication.
     */
    public function clear(?string $ip, ?string $email): void
    {
        $email = $this->normalizeEmail($email);

        if (blank($ip) && blank($email)) {
            return;
        }

        LoginLockout::query()
            ->where(function ($query) use ($ip, $email) {
                if ($ip) {
                    $query->orWhere(fn ($q) => $q->where('type', 'ip')->where('value', $ip));
                }

                if ($email) {
                    $query->orWhere(fn ($q) => $q->where('type', 'email')->where('value', $email));
                }
            })
            ->delete();
    }

    public function lockedMessage(LoginLockout $lockout): string
    {
        $minutes = (int) max(1, now()->diffInRealMinutes($lockout->blocked_until));

        return "Akses diblokir sementara karena terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$minutes} menit.";
    }
}
