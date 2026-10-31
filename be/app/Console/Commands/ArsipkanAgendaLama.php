<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use Illuminate\Console\Command;

class ArsipkanAgendaLama extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:arsipkan-otomatis {--bulan=6 : Arsipkan agenda published yang selesai lebih dari N bulan lalu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Arsipkan otomatis agenda published yang sudah lama selesai agar daftar aktif tetap ramping';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bulan = max(1, (int) $this->option('bulan'));
        $batas = now()->copy()->subMonths($bulan);

        $agendas = Agenda::where('status', 'published')
            ->where(function ($q) use ($batas) {
                $q->where('waktu_akhir', '<=', $batas)
                    ->orWhere(function ($qq) use ($batas) {
                        $qq->whereNull('waktu_akhir')
                            ->where('waktu_mulai', '<=', $batas);
                    })
                    ->orWhere(function ($qq) use ($batas) {
                        $qq->whereNull('waktu_akhir')
                            ->whereNull('waktu_mulai')
                            ->where('scheduled_at', '<=', $batas);
                    });
            })
            ->limit(500)
            ->get();

        $jumlah = 0;

        foreach ($agendas as $agenda) {
            $agenda->delete();
            $agenda->logs()->create([
                'user_uuid' => null,
                'aksi'      => 'diarsipkan',
                'catatan'   => 'Otomatis (selesai > ' . $bulan . ' bulan)',
            ]);
            $jumlah++;
        }

        $this->info("Berhasil mengarsipkan {$jumlah} agenda lama.");

        return self::SUCCESS;
    }
}
