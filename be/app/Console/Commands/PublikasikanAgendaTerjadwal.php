<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use Illuminate\Console\Command;

class PublikasikanAgendaTerjadwal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:publikasikan-terjadwal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publikasikan otomatis agenda berstatus scheduled yang waktu terbitnya sudah tiba';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $agendas = Agenda::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->limit(200)
            ->get();

        $jumlah = 0;

        foreach ($agendas as $agenda) {
            $agenda->update(['status' => 'published']);
            $agenda->logs()->create([
                'user_uuid' => null,
                'aksi'      => 'dipublikasikan',
                'catatan'   => 'Otomatis sesuai jadwal terbit',
            ]);
            $jumlah++;
        }

        $this->info("{$jumlah} agenda dipublikasikan otomatis.");

        return self::SUCCESS;
    }
}
