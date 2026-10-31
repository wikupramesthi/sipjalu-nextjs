<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use Illuminate\Console\Command;

class TandaiAgendaPopuler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:populer-otomatis {--limit=5 : Jumlah agenda teratas yang ditandai populer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronkan flag populer ke N agenda published dengan views terbanyak';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $topUuids = Agenda::where('status', 'published')
            ->orderByDesc('views')
            ->limit($limit)
            ->pluck('uuid')
            ->all();

        Agenda::query()->where('is_popular_manual', false)->update(['is_popular' => false]);

        if (! empty($topUuids)) {
            Agenda::whereIn('uuid', $topUuids)->update(['is_popular' => true]);
        }

        $this->info(count($topUuids) . " agenda ditandai populer (top {$limit} views).");

        return self::SUCCESS;
    }
}
