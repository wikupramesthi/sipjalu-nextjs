<?php

namespace App\Services\Security;

use App\Models\FailedLogin;
use App\Models\LoginActivity;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class AnomalyDetector
{
    public function windowHours(): int
    {
        return (int) config('security.anomaly.window_hours', 24);
    }

    public function distinctIpThreshold(): int
    {
        return (int) config('security.anomaly.distinct_ip_threshold', 3);
    }

    public function failedThreshold(): int
    {
        return (int) config('security.anomaly.failed_threshold', 5);
    }

    /**
     * @return array{0:int,1:int}
     */
    public function offHoursRange(): array
    {
        $range = config('security.anomaly.off_hours', [0, 5]);

        return [(int) ($range[0] ?? 0), (int) ($range[1] ?? 5)];
    }

    /**
     * IP addresses with an abnormal number of failed logins.
     */
    public function suspiciousIps(): array
    {
        return FailedLogin::query()
            ->where('attempted_at', '>=', now()->subHours($this->windowHours()))
            ->whereNotNull('ip_address')
            ->select('ip_address', DB::raw('COUNT(*) as total'))
            ->groupBy('ip_address')
            ->having('total', '>=', $this->failedThreshold())
            ->orderByDesc('total')
            ->pluck('ip_address')
            ->all();
    }

    /**
     * Users that logged in from an abnormal number of distinct IP addresses.
     */
    public function suspiciousUserUuids(): array
    {
        return LoginActivity::query()
            ->where('logged_in_at', '>=', now()->subHours($this->windowHours()))
            ->whereNotNull('user_uuid')
            ->select('user_uuid', DB::raw('COUNT(DISTINCT ip_address) as ips'))
            ->groupBy('user_uuid')
            ->having('ips', '>=', $this->distinctIpThreshold())
            ->orderByDesc('ips')
            ->pluck('user_uuid')
            ->all();
    }

    public function isOffHours(?CarbonInterface $time): bool
    {
        if (! $time) {
            return false;
        }

        [$start, $end] = $this->offHoursRange();
        $hour = (int) $time->format('G');

        if ($start <= $end) {
            return $hour >= $start && $hour < $end;
        }

        return $hour >= $start || $hour < $end;
    }

    /**
     * Human-readable anomaly reasons for a login activity row.
     */
    public function loginFlags(LoginActivity $activity, array $suspiciousUsers, array $suspiciousIps): array
    {
        $flags = [];

        if ($activity->user_uuid && in_array($activity->user_uuid, $suspiciousUsers, true)) {
            $flags[] = 'Login dari banyak IP';
        }

        if ($activity->ip_address && in_array($activity->ip_address, $suspiciousIps, true)) {
            $flags[] = 'IP berisiko';
        }

        if ($this->isOffHours($activity->logged_in_at)) {
            $flags[] = 'Jam tidak wajar';
        }

        return $flags;
    }

    /**
     * Human-readable anomaly reasons for a failed login row.
     */
    public function failedFlags(FailedLogin $attempt, array $suspiciousIps): array
    {
        $flags = [];

        if ($attempt->ip_address && in_array($attempt->ip_address, $suspiciousIps, true)) {
            $flags[] = 'IP berisiko';
        }

        if (blank($attempt->user_uuid)) {
            $flags[] = 'Akun tidak dikenal';
        }

        return $flags;
    }

    /**
     * Count login activities within the window that are flagged as anomalous.
     */
    public function countSuspiciousLogins(array $suspiciousUsers, array $suspiciousIps): int
    {
        return LoginActivity::query()
            ->where('logged_in_at', '>=', now()->subHours($this->windowHours()))
            ->where(function ($query) use ($suspiciousUsers, $suspiciousIps) {
                if (! empty($suspiciousUsers)) {
                    $query->orWhereIn('user_uuid', $suspiciousUsers);
                }

                if (! empty($suspiciousIps)) {
                    $query->orWhereIn('ip_address', $suspiciousIps);
                }

                $query->orWhere(function ($offHours) {
                    $this->applyOffHours($offHours, 'logged_in_at');
                });
            })
            ->count();
    }

    protected function applyOffHours($query, string $column): void
    {
        [$start, $end] = $this->offHoursRange();
        $from = sprintf('%02d:00:00', $start);
        $to = sprintf('%02d:00:00', $end);

        if ($start <= $end) {
            $query->whereTime($column, '>=', $from)->whereTime($column, '<', $to);

            return;
        }

        $query->where(function ($q) use ($column, $from, $to) {
            $q->whereTime($column, '>=', $from)->orWhereTime($column, '<', $to);
        });
    }
}
