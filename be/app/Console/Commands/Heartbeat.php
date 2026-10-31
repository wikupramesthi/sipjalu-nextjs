<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Heartbeat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:heartbeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tandai waktu jalan terakhir scheduler (dipantau endpoint /health)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Storage::disk('local')->put('heartbeat.json', json_encode([
            'last_run' => now()->toIso8601String(),
        ]));

        $this->info('Heartbeat tercatat: ' . now()->format('d M Y H:i:s'));

        return self::SUCCESS;
    }
}
