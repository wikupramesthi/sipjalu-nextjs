<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\VisitorLog;
use App\Models\VisitorDailyStat;
use Carbon\Carbon;

class AggregateVisitorStats extends Command
{
    protected $signature = 'visitor:aggregate {--date= : Aggregate for specific date (Y-m-d), default: yesterday}';
    protected $description = 'Aggregate visitor logs into daily summary table for fast dashboard queries';

    public function handle(): int
    {
        $date = $this->option('date') 
            ? Carbon::parse($this->option('date')) 
            : Carbon::yesterday();

        $dateStr = $date->format('Y-m-d');
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $this->info("Aggregating visitor stats for {$dateStr}...");

        // Check if already aggregated
        $existing = VisitorDailyStat::where('date', $dateStr)->first();
        if ($existing && !$this->confirm("Data for {$dateStr} already exists. Overwrite?")) {
            $this->info('Skipped.');
            return Command::SUCCESS;
        }

        // Aggregate from raw logs
        $stats = VisitorLog::whereBetween('visited_at', [$startOfDay, $endOfDay])
            ->selectRaw('
                COUNT(*) as total_visits,
                SUM(CASE WHEN is_unique THEN 1 ELSE 0 END) as unique_visitors,
                SUM(CASE WHEN device_type = "desktop" THEN 1 ELSE 0 END) as desktop_visits,
                SUM(CASE WHEN device_type = "mobile" THEN 1 ELSE 0 END) as mobile_visits,
                SUM(CASE WHEN device_type = "tablet" THEN 1 ELSE 0 END) as tablet_visits
            ')
            ->first();

        // Top pages
        $topPages = VisitorLog::whereBetween('visited_at', [$startOfDay, $endOfDay])
            ->selectRaw('url, COUNT(*) as count')
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['url' => $item->url, 'count' => $item->count])
            ->toArray();

        // Top referrers
        $topReferrers = VisitorLog::whereBetween('visited_at', [$startOfDay, $endOfDay])
            ->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as count')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['referrer' => $item->referrer, 'count' => $item->count])
            ->toArray();

        // Top countries
        $topCountries = VisitorLog::whereBetween('visited_at', [$startOfDay, $endOfDay])
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['country' => $item->country, 'count' => $item->count])
            ->toArray();

        // Browsers
        $browsers = VisitorLog::whereBetween('visited_at', [$startOfDay, $endOfDay])
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get()
            ->map(fn($item) => ['browser' => $item->browser, 'count' => $item->count])
            ->toArray();

        // OS
        $os = VisitorLog::whereBetween('visited_at', [$startOfDay, $endOfDay])
            ->whereNotNull('os')
            ->selectRaw('os, COUNT(*) as count')
            ->groupBy('os')
            ->orderByDesc('count')
            ->get()
            ->map(fn($item) => ['os' => $item->os, 'count' => $item->count])
            ->toArray();

        // Upsert into daily stats
        VisitorDailyStat::updateOrCreate(
            ['date' => $dateStr],
            [
                'total_visits' => $stats->total_visits ?? 0,
                'unique_visitors' => $stats->unique_visitors ?? 0,
                'desktop_visits' => $stats->desktop_visits ?? 0,
                'mobile_visits' => $stats->mobile_visits ?? 0,
                'tablet_visits' => $stats->tablet_visits ?? 0,
                'top_pages' => $topPages,
                'top_referrers' => $topReferrers,
                'top_countries' => $topCountries,
                'browsers' => $browsers,
                'os' => $os,
            ]
        );

        $this->info("Aggregated: {$stats->total_visits} visits, {$stats->unique_visitors} unique visitors");

        return Command::SUCCESS;
    }
}