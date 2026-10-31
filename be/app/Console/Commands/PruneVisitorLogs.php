<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VisitorLog;
use Carbon\Carbon;

class PruneVisitorLogs extends Command
{
    protected $signature = 'visitor:prune {--days=90 : Keep logs for N days, default: 90}';
    protected $description = 'Delete old raw visitor logs, keeping only aggregated daily stats';

    public function handle(): int
    {
        $keepDays = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($keepDays)->startOfDay();

        $this->info("Pruning visitor logs older than {$keepDays} days (before {$cutoffDate->format('Y-m-d')})...");

        $deleted = VisitorLog::where('visited_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$deleted} old visitor log records.");
        $this->info("Daily aggregated stats in visitor_daily_stats table are preserved.");

        return Command::SUCCESS;
    }
}