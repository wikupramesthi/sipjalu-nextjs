<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'is_unique',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_unique' => 'boolean',
    ];

    public $timestamps = false;

    public static function logVisit(array $data): void
    {
        $today = now()->startOfDay();
        $tomorrow = now()->endOfDay();

        $exists = self::where('ip_address', $data['ip_address'])
            ->whereBetween('visited_at', [$today, $tomorrow])
            ->exists();

        $data['is_unique'] = !$exists;
        $data['visited_at'] = now();

        self::create($data);
    }

    /**
     * Get stats using optimized daily summary table
     */
    public static function getStats(int $days = 30): array
    {
        // Use daily stats table for fast queries
        $dailyStats = \App\Models\VisitorDailyStat::getLastDays($days);
        
        $totalVisits = $dailyStats->sum('total_visits');
        $uniqueVisitors = $dailyStats->sum('unique_visitors');

        $labels = [];
        $totalData = [];
        $uniqueData = [];

        foreach ($dailyStats as $stat) {
            $labels[] = $stat->date->format('d M');
            $totalData[] = $stat->total_visits;
            $uniqueData[] = $stat->unique_visitors;
        }

        // Fill missing days with zeros
        $filledLabels = [];
        $filledTotal = [];
        $filledUnique = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($days - 1 - $i)->format('Y-m-d');
            $filledLabels[] = now()->subDays($days - 1 - $i)->format('d M');
            
            $stat = $dailyStats->firstWhere('date', $date);
            $filledTotal[] = $stat?->total_visits ?? 0;
            $filledUnique[] = $stat?->unique_visitors ?? 0;
        }

        // Monthly stats from daily stats table
        $monthlyStats = \App\Models\VisitorDailyStat::getMonthly(12);

        return [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'daily' => [
                'labels' => $filledLabels,
                'total' => $filledTotal,
                'unique' => $filledUnique,
            ],
            'monthly' => $monthlyStats,
        ];
    }

    /**
     * Get device breakdown from daily stats
     */
    public static function getDeviceStats(int $days = 30): array
    {
        return \App\Models\VisitorDailyStat::getDeviceBreakdown($days);
    }

    /**
     * Get top pages from daily stats
     */
    public static function getTopPages(int $days = 30, int $limit = 10): array
    {
        return \App\Models\VisitorDailyStat::getTopPages($days, $limit);
    }

    public static function getRecentActivity(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::latest('visited_at')
            ->limit($limit)
            ->get(['ip_address', 'url', 'device_type', 'browser', 'os', 'visited_at']);
    }

    /**
     * Get stats for a specific date range using daily stats table
     */
    public static function getStatsForRange(Carbon $startDate, Carbon $endDate): array
    {
        $dailyStats = \App\Models\VisitorDailyStat::getRange(
            $startDate->format('Y-m-d'), 
            $endDate->format('Y-m-d')
        );
        
        $totalVisits = $dailyStats->sum('total_visits');
        $uniqueVisitors = $dailyStats->sum('unique_visitors');

        $labels = [];
        $totalData = [];
        $uniqueData = [];

        foreach ($dailyStats as $stat) {
            $labels[] = $stat->date->format('d M');
            $totalData[] = $stat->total_visits;
            $uniqueData[] = $stat->unique_visitors;
        }

        // Fill missing days with zeros
        $filledLabels = [];
        $filledTotal = [];
        $filledUnique = [];
        
        $days = $startDate->diffInDays($endDate) + 1;
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $filledLabels[] = $startDate->copy()->addDays($i)->format('d M');
            
            $stat = $dailyStats->firstWhere('date', $date);
            $filledTotal[] = $stat?->total_visits ?? 0;
            $filledUnique[] = $stat?->unique_visitors ?? 0;
        }

        // Monthly stats from daily stats table
        $monthlyStats = \App\Models\VisitorDailyStat::getMonthly(12);

        return [
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'daily' => [
                'labels' => $filledLabels,
                'total' => $filledTotal,
                'unique' => $filledUnique,
            ],
            'monthly' => $monthlyStats,
        ];
    }

    /**
     * Get device breakdown for a specific date range
     */
    public static function getDeviceStatsForRange(Carbon $startDate, Carbon $endDate): array
    {
        return \App\Models\VisitorDailyStat::getDeviceBreakdownForRange($startDate, $endDate);
    }
}