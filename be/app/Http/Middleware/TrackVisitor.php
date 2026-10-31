<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Cache;

class TrackVisitor
{
    protected $excludedPaths = [
        '/api/', '/backend/', '/auth/', '/logout',
        '/css/', '/js/', '/img/', '/dist/', '/vendor/',
        '/storage/', '/favicon.ico', '/robots.txt', '/sitemap.xml',
    ];

    protected $excludedExtensions = [
        'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'pdf', 'zip', 'doc', 'docx',
        'xls', 'xlsx', 'ppt', 'pptx',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Defer tracking to after response - non-blocking
        if ($this->shouldTrack($request)) {
            $this->bufferVisit($request);
        }

        return $response;
    }

    protected function shouldTrack(Request $request): bool
    {
        // Fast path checks first
        if ($request->isMethod('POST') || $request->isMethod('PUT') || 
            $request->isMethod('PATCH') || $request->isMethod('DELETE')) {
            return false;
        }

        $path = $request->path();
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext && isset($this->excludedExtensions[strtolower($ext)])) {
            return false;
        }

        foreach ($this->excludedPaths as $excluded) {
            if (str_starts_with($path, trim($excluded, '/'))) {
                return false;
            }
        }

        return true;
    }

    protected function bufferVisit(Request $request): void
    {
        $key = 'visitor_buffer_' . date('Y-m-d H:i'); // 1-minute buckets
        $data = [
            'ip' => $request->ip(),
            'ua' => $request->header('User-Agent', ''),
            'url' => $request->fullUrl(),
            'ref' => $request->header('Referer'),
            'dt' => $this->detectDevice($request->header('User-Agent', '')),
            'br' => $this->detectBrowser($request->header('User-Agent', '')),
            'os' => $this->detectOS($request->header('User-Agent', '')),
            'time' => now()->format('H:i:s'),
        ];

        // Compatible with all cache drivers (database/file/redis)
        $count = Cache::get($key . '_count', 0);
        $visits = Cache::get($key, []);
        $visits[] = $data;
        Cache::put($key, $visits, 600); // 10 min TTL
        Cache::put($key . '_count', $count + 1, 600);

        // Flush every ~50 visits
        if ($count >= 50) {
            $this->flushBuffer($key);
        }
    }

    protected function flushBuffer(string $key): void
    {
        $visits = Cache::pull($key, []);
        Cache::forget($key . '_count');

        if (empty($visits)) return;

        $today = now()->startOfDay();
        $tomorrow = now()->endOfDay();
        
        // Get existing IPs for today in one query
        $ipToday = VisitorLog::whereBetween('visited_at', [$today, $tomorrow])
            ->pluck('ip_address')
            ->flip()
            ->toArray();

        $toInsert = [];
        foreach ($visits as $v) {
            $isUnique = !isset($ipToday[$v['ip']]);
            if ($isUnique) $ipToday[$v['ip']] = true;

            $toInsert[] = [
                'ip_address' => $v['ip'],
                'user_agent' => $v['ua'],
                'url' => $v['url'],
                'referrer' => $v['ref'],
                'device_type' => $v['dt'],
                'browser' => $v['br'],
                'os' => $v['os'],
                'is_unique' => $isUnique,
                'visited_at' => $v['time'],
            ];
        }

        if ($toInsert) {
            VisitorLog::insert($toInsert);
        }
    }

    protected function detectDevice(string $ua): string
    {
        if (stripos($ua, 'Mobile') || stripos($ua, 'Android') || stripos($ua, 'iPhone')) {
            return (stripos($ua, 'iPad') || stripos($ua, 'Tablet')) ? 'tablet' : 'mobile';
        }
        return 'desktop';
    }

    protected function detectBrowser(string $ua): string
    {
        if (preg_match('/Edg\/(\d+)/', $ua)) return 'Edge';
        if (preg_match('/Chrome\/(\d+)/', $ua)) return 'Chrome';
        if (preg_match('/Firefox\/(\d+)/', $ua)) return 'Firefox';
        if (preg_match('/Safari\/(\d+)/', $ua) && !preg_match('/Chrome/', $ua)) return 'Safari';
        if (preg_match('/MSIE|Trident/', $ua)) return 'IE';
        if (preg_match('/Opera|OPR/', $ua)) return 'Opera';
        return 'Other';
    }

    protected function detectOS(string $ua): string
    {
        if (preg_match('/Windows NT 10.0/', $ua)) return 'Windows 10/11';
        if (preg_match('/Windows NT 6.3/', $ua)) return 'Windows 8.1';
        if (preg_match('/Windows NT 6.2/', $ua)) return 'Windows 8';
        if (preg_match('/Windows NT 6.1/', $ua)) return 'Windows 7';
        if (preg_match('/Mac OS X/', $ua)) return 'macOS';
        if (preg_match('/Linux/', $ua)) return 'Linux';
        if (preg_match('/Android/', $ua)) return 'Android';
        if (preg_match('/iPhone|iPad/', $ua)) return 'iOS';
        return 'Other';
    }
}