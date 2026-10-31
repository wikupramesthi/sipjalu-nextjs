<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Terbitkan token Sanctum untuk web bekasikota.go.id.
 *
 *   php artisan integrasi:token-bekasikota admin@example.go.id --nama=bekasikota-web
 *
 * Token dicetak sekali (plain text) — simpan di env web bekasikota
 * sebagai `AGENDA_API_TOKEN`, lalu kirim sebagai header:
 * `Authorization: Bearer <token>`.
 */
class BuatTokenBekasikota extends Command
{
    protected $signature = 'integrasi:token-bekasikota
        {email : Email user pemilik token}
        {--nama=bekasikota-web : Nama device/token}';

    protected $description = 'Terbitkan token Sanctum untuk integrasi web bekasikota.go.id';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $nama = substr((string) $this->option('nama'), 0, 50) ?: 'bekasikota-web';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User dengan email {$email} tidak ditemukan.");

            return self::FAILURE;
        }

        $token = $user->createToken($nama, ['agenda:read'])->plainTextToken;

        $this->info('Token berhasil dibuat.');
        $this->line('User : ' . $user->email);
        $this->line('Nama : ' . $nama);
        $this->line('Token: ' . $token);
        $this->warn('Simpan token ini — tidak bisa dilihat lagi setelah ini.');

        return self::SUCCESS;
    }
}
