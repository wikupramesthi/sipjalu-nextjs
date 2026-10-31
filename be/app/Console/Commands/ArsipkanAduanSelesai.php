<?php

namespace App\Console\Commands;

use App\Models\Aduan;
use Illuminate\Console\Command;

class ArsipkanAduanSelesai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aduan:arsipkan {--tahun=1 : Arsipkan aduan selesai yang lebih tua dari N tahun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Arsipkan aduan berstatus selesai yang sudah tua agar tidak membebani daftar aktif';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tahun = max(1, (int) $this->option('tahun'));
        $batas = now()->subYears($tahun);

        $jumlah = Aduan::where('status', 'selesai')
            ->belumArsip()
            ->where(function ($q) use ($batas) {
                $q->where('tanggal_pengaduan', '<=', $batas)
                    ->orWhere(function ($q2) use ($batas) {
                        $q2->whereNull('tanggal_pengaduan')
                            ->where('created_at', '<=', $batas);
                    });
            })
            ->update(['archived_at' => now()]);

        $this->info("Berhasil mengarsipkan {$jumlah} aduan selesai yang lebih tua dari {$tahun} tahun.");

        return self::SUCCESS;
    }
}
