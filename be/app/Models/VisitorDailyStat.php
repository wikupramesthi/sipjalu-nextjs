<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VisitorDailyStat extends Model
{
    protected $table = 'visitor_daily_stats';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'date';

    protected $fillable = [
        'date',
        'total_visits',
        'unique_visitors',
        'desktop_visits',
        'mobile_visits',
        'tablet_visits',
        'top_pages',
        'top_referrers',
        'top_countries',
        'browsers',
        'os',
    ];

    protected $casts = [
        'date' => 'date',
        'top_pages' => 'array',
        'top_referrers' => 'array',
        'top_countries' => 'array',
        'browsers' => 'array',
        'os' => 'array',
    ];

    /**
     * Get stats for a date range
     */
    public static function getRange(string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
    }

    /**
     * Get last N days stats
     */
    public static function getLastDays(int $days): \Illuminate\Database\Eloquent\Collection
    {
        $startDate = now()->subDays($days - 1)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');
        
        return self::getRange($startDate, $endDate);
    }

    /**
     * Get monthly aggregated stats
     */
    public static function getMonthly(int $months = 12): array
    {
        $startDate = now()->subMonths($months)->startOfMonth()->format('Y-m-d');
        
        $stats = self::where('date', '>=', $startDate)
            ->orderBy('date')
            ->get(['date', 'total_visits', 'unique_visitors']);

        $monthly = $stats->groupBy(function ($item) {
            return $item->date->format('Y-m');
        });

        $labels = [];
        $total = [];
        $unique = [];

        foreach ($monthly as $month => $items) {
            $labels[] = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
            $total[] = $items->sum('total_visits');
            $unique[] = $items->sum('unique_visitors');
        }

        return [
            'labels' => $labels,
            'total' => $total,
            'unique' => $unique,
        ];
    }

    /**
     * Get device breakdown for last N days
     */
    public static function getDeviceBreakdown(int $days = 30): array
    {
        $startDate = now()->subDays($days - 1)->format('Y-m-d');
        
        $stats = self::where('date', '>=', $startDate)
            ->selectRaw('SUM(desktop_visits) as desktop, SUM(mobile_visits) as mobile, SUM(tablet_visits) as tablet')
            ->first();

        return [
            'labels' => ['Desktop', 'Mobile', 'Tablet'],
            'values' => [
                $stats->desktop ?? 0,
                $stats->mobile ?? 0,
                $stats->tablet ?? 0,
            ],
        ];
    }

    /**
     * Get top pages for last N days
     */
    public static function getTopPages(int $days = 30, int $limit = 10): array
    {
        $startDate = now()->subDays($days - 1)->format('Y-m-d');
        
        $stats = self::where('date', '>=', $startDate)
            ->whereNotNull('top_pages')
            ->get('top_pages');

        $pageCounts = [];
        
        foreach ($stats as $stat) {
            if ($stat->top_pages) {
                foreach ($stat->top_pages as $page) {
                    $url = $page['url'] ?? '';
                    $count = $page['count'] ?? 0;
                    if ($url) {
                        $pageCounts[$url] = ($pageCounts[$url] ?? 0) + $count;
                    }
                }
            }
        }

        arsort($pageCounts);
        
        $labels = [];
        $values = [];
        
        foreach (array_slice($pageCounts, 0, $limit, true) as $url => $count) {
            $labels[] = strlen($url) > 50 ? substr($url, 0, 47) . '...' : $url;
            $values[] = $count;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Get device breakdown for a specific date range
     */
    public static function getDeviceBreakdownForRange(Carbon $startDate, Carbon $endDate): array
    {
        $stats = self::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->selectRaw('SUM(desktop_visits) as desktop, SUM(mobile_visits) as mobile, SUM(tablet_visits) as tablet')
            ->first();

        return [
            'labels' => ['Desktop', 'Mobile', 'Tablet'],
            'values' => [
                $stats->desktop ?? 0,
                $stats->mobile ?? 0,
                $stats->tablet ?? 0,
            ],
        ];
    }
}