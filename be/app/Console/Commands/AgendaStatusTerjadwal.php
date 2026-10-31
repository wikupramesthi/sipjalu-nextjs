<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use Illuminate\Console\Command;

class AgendaStatusTerjadwal extends Command
{
    protected $signature = 'agenda:status-terjadwal';

    protected $description = 'Arsipkan otomatis agenda published yang sudah lewat waktu akhirnya';

    public function handle(): int
    {
        $agendas = Agenda::where('status', 'published')
            ->whereNotNull('waktu_akhir')
            ->where('waktu_akhir', '<=', now())
            ->limit(200)
            ->get();

        $jumlah = 0;

        foreach ($agendas as $agenda) {
            $agenda->update(['status' => 'draft']);
            $agenda->logs()->create([
                'aksi' => 'diarsipkan',
                'catatan' => 'Otomatis: waktu akhir sudah lewat',
            ]);
            $jumlah++;
        }

        $this->info("{$jumlah} agenda diarsipkan otomatis karena sudah lewat waktu.");

        return self::SUCCESS;
    }
}
