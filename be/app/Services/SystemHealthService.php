<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Single source of truth untuk pengecekan kesehatan sistem.
 * Dipakai DashboardController (widget) dan HealthController (API/halaman).
 * Meringkas duplikasi pengecekan scheduler & storage yang sebelumnya tersebar.
 */
class SystemHealthService
{
    public function heartbeatPath(): string
    {
        return storage_path('app/heartbeat.json');
    }

    public function getSchedulerLastRun(): ?Carbon
    {
        // Coba Storage::disk('local') dulu (lebih portable), fallback ke file path
        try {
            if (Storage::disk('local')->exists('heartbeat.json')) {
                $data = json_decode(Storage::disk('local')->get('heartbeat.json'), true);
                if (!empty($data['last_run'])) {
                    return Carbon::parse($data['last_run']);
                }
            }
        } catch (\Throwable) {
        }

        $path = $this->heartbeatPath();
        if (!is_file($path)) {
            return null;
        }
        $raw = @file_get_contents($path);
        $data = json_decode($raw, true);
        if (empty($data['last_run'])) {
            return null;
        }
        try {
            return Carbon::parse($data['last_run']);
        } catch (\Throwable) {
            return null;
        }
    }

    public function isSchedulerOk(?Carbon $lastRun = null): bool
    {
        $lastRun ??= $this->getSchedulerLastRun();
        return $lastRun && now()->diffInMinutes($lastRun) < 15;
    }

    public function isStorageOk(): bool
    {
        // Cek storage/app/public writable (dipakai Dashboard)
        // dan Storage::disk('public') tulis-baca (dipakai Health)
        if (!is_writable(storage_path('app/public'))) {
            // fallback cek via Storage disk
            try {
                $f = '.health-check-' . uniqid();
                Storage::disk('public')->put($f, 'ok');
                $ok = Storage::disk('public')->get($f) === 'ok';
                Storage::disk('public')->delete($f);
                return $ok;
            } catch (\Throwable) {
                return false;
            }
        }
        return true;
    }

    public function checkDatabase(): array
    {
        try {
            DB::select('select 1');
            return ['ok' => true];
        } catch (\Throwable) {
            return ['ok' => false, 'pesan' => 'Basis data tidak terjangkau.'];
        }
    }

    public function checkStorage(): array
    {
        try {
            $file = '.health-' . uniqid();
            Storage::disk('public')->put($file, 'ok');
            $readable = Storage::disk('public')->get($file) === 'ok';
            Storage::disk('public')->delete($file);
            return $readable
                ? ['ok' => true]
                : ['ok' => false, 'pesan' => 'Berkas tidak bisa dibaca kembali.'];
        } catch (\Throwable) {
            return ['ok' => false, 'pesan' => 'Penyimpanan tidak bisa ditulisi.'];
        }
    }

    public function checkScheduler(): array
    {
        try {
            if (!Storage::disk('local')->exists('heartbeat.json')) {
                // fallback file check
                if (!is_file($this->heartbeatPath())) {
                    return ['ok' => false, 'pesan' => 'Scheduler belum pernah jalan.', 'jalan_terakhir' => null];
                }
            }
            $data = null;
            if (Storage::disk('local')->exists('heartbeat.json')) {
                $data = json_decode(Storage::disk('local')->get('heartbeat.json'), true);
            } else {
                $data = json_decode(@file_get_contents($this->heartbeatPath()), true);
            }
            $last = isset($data['last_run']) ? Carbon::parse($data['last_run']) : null;
            if (!$last) {
                return ['ok' => false, 'pesan' => 'Data heartbeat rusak.', 'jalan_terakhir' => null];
            }
            $minutes = now()->diffInMinutes($last);
            return [
                'ok' => $minutes < 15,
                'jalan_terakhir' => $last->format('d M Y H:i:s'),
                'pesan' => $minutes < 15 ? null : "Scheduler terakhir jalan {$minutes} menit lalu.",
            ];
        } catch (\Throwable) {
            return ['ok' => false, 'pesan' => 'Gagal memeriksa scheduler.', 'jalan_terakhir' => null];
        }
    }

    /**
     * @return array<string, array>
     */
    public function allChecks(): array
    {
        return [
            'database' => $this->checkDatabase(),
            'penyimpanan' => $this->checkStorage(),
            'penjadwal' => $this->checkScheduler(),
        ];
    }
}
