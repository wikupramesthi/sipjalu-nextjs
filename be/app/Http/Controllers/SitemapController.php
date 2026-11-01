<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\WebsiteIdentity;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $base = $this->baseUrl();

        // Static routes - prioritized for SEO
        $static = [
            ['loc' => '/', 'freq' => 'daily', 'prio' => '1.0'],
            ['loc' => '/agenda', 'freq' => 'daily', 'prio' => '0.9'],
            ['loc' => '/layanan', 'freq' => 'weekly', 'prio' => '0.8'],
            ['loc' => '/visi-misi', 'freq' => 'monthly', 'prio' => '0.8'],
            ['loc' => '/dokumen', 'freq' => 'weekly', 'prio' => '0.7'],
            ['loc' => '/event', 'freq' => 'weekly', 'prio' => '0.7'],
            ['loc' => '/pengumuman', 'freq' => 'weekly', 'prio' => '0.7'],
            ['loc' => '/informasi-pejabat', 'freq' => 'monthly', 'prio' => '0.6'],
            ['loc' => '/sejarah', 'freq' => 'yearly', 'prio' => '0.6'],
            ['loc' => '/faq', 'freq' => 'monthly', 'prio' => '0.6'],
            ['loc' => '/kontak', 'freq' => 'yearly', 'prio' => '0.6'],
            ['loc' => '/informasi-berkala', 'freq' => 'monthly', 'prio' => '0.6'],
            ['loc' => '/informasi-setiap-saat', 'freq' => 'monthly', 'prio' => '0.6'],
            ['loc' => '/informasi-serta-merta', 'freq' => 'monthly', 'prio' => '0.6'],
            ['loc' => '/informasi-yang-dikecualikan', 'freq' => 'yearly', 'prio' => '0.5'],
            ['loc' => '/teknis-peil-banjir', 'freq' => 'yearly', 'prio' => '0.5'],
            ['loc' => '/pemanfaatan-ruang-jalan', 'freq' => 'yearly', 'prio' => '0.5'],
        ];

        $urls = [];
        $today = now()->toDateString();

        foreach ($static as $s) {
            $urls[] = $this->urlEntry($base . $s['loc'], $today, $s['freq'], $s['prio']);
        }

        // Dynamic: Agenda - published & indexable
        try {
            $agendas = Agenda::where('status', 'published')
                ->where('search_engine', 'index')
                ->orderByDesc('updated_at')
                ->limit(500)
                ->get(['slug', 'updated_at']);
            foreach ($agendas as $a) {
                $lastmod = $a->updated_at ? $a->updated_at->toDateString() : $today;
                $urls[] = $this->urlEntry($base . '/agenda/' . rawurlencode($a->slug), $lastmod, 'weekly', '0.6');
            }
        } catch (\Throwable $e) {
            // silent
        }

        // Optional: identity updated_at as hint
        try {
            $identity = WebsiteIdentity::first();
            if ($identity && $identity->updated_at) {
                // already covered via static today, but could use for homepage
            }
        } catch (\Throwable $e) {
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
            . implode("\n", $urls) . "\n"
            . '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    private function baseUrl(): string
    {
        // Prefer APP_URL, fallback ke request origin, then bekasikota domain
        $appUrl = config('app.url') ?: URL::to('/');
        $base = rtrim($appUrl, '/');
        // pastikan https untuk SEO jika APP_URL masih http localhost di prod
        if (app()->environment('production') && str_starts_with($base, 'http://')) {
            $base = 'https://' . substr($base, 7);
        }
        // fallback domain Bekasikota jika masih localhost
        if (str_contains($base, 'localhost') || str_contains($base, '127.0.0.1')) {
            $base = 'https://bekasikota.go.id';
        }
        return $base;
    }

    private function urlEntry(string $loc, string $lastmod, string $freq, string $prio): string
    {
        $esc = htmlspecialchars($loc, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        return "  <url>\n    <loc>{$esc}</loc>\n    <lastmod>{$lastmod}</lastmod>\n    <changefreq>{$freq}</changefreq>\n    <priority>{$prio}</priority>\n  </url>";
    }
}
