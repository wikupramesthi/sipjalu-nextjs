<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Proxy cuaca Kota Bekasi (Open-Meteo, gratis tanpa kunci) untuk pita
 * cuaca halaman /cctv.
 *
 * GET /api/cuaca → [{name, temp, label, icon}], cache 10 menit.
 * Lewat proxy server-side, browser tidak bergantung pada koneksi langsung
 * ke api.open-meteo.com (sering diblokir jaringan/adblock).
 */
class CuacaController extends Controller
{
    private const API_URL = 'https://api.open-meteo.com/v1/forecast';
    private const CACHE_TTL = 600;
    private const STALE_CACHE_TTL = 3600;

    /** Titik query per wilayah (hanya untuk meminta data; yang tampil dari API). */
    private const AREAS = [
        ['Kota Bekasi', -6.2383, 106.9756],
        ['Bekasi Barat', -6.235, 106.955],
        ['Bekasi Timur', -6.25, 107.005],
        ['Bekasi Utara', -6.22, 107.0],
        ['Bekasi Selatan', -6.265, 106.99],
        ['Jatiasih', -6.295, 106.95],
        ['Jatisampurna', -6.305, 106.915],
        ['Pondokgede', -6.285, 106.93],
        ['Pondokmelati', -6.3, 106.945],
        ['Mustikajaya', -6.27, 107.03],
        ['Bantargebang', -6.335, 106.99],
        ['Rawalumbu', -6.255, 107.005],
        ['Medan Satria', -6.225, 107.02],
    ];

    private const WMO = [
        0 => ['Cerah', '☀️'],
        1 => ['Cerah berawan', '🌤️'],
        2 => ['Berawan sebagian', '⛅'],
        3 => ['Mendung', '☁️'],
        45 => ['Berkabut', '🌫️'],
        48 => ['Kabut beku', '🌫️'],
        51 => ['Gerimis ringan', '🌦️'],
        53 => ['Gerimis sedang', '🌦️'],
        55 => ['Gerimis lebat', '🌧️'],
        61 => ['Hujan ringan', '🌦️'],
        63 => ['Hujan sedang', '🌧️'],
        65 => ['Hujan lebat', '🌧️'],
        80 => ['Hujan singkat', '🌦️'],
        81 => ['Hujan singkat sedang', '🌧️'],
        82 => ['Hujan singkat lebat', '⛈️'],
        95 => ['Badai petir', '⛈️'],
        96 => ['Badai petir + hujan es', '⛈️'],
        99 => ['Badai petir berat', '⛈️'],
    ];

    public function index(): JsonResponse
    {
        try {
            $rows = Cache::remember('cuaca_bekasi', self::CACHE_TTL, function () {
                $lats = implode(',', array_column(self::AREAS, 1));
                $lons = implode(',', array_column(self::AREAS, 2));
                $res = Http::connectTimeout(2)->timeout(4)->acceptJson()->get(self::API_URL, [
                    'latitude' => $lats,
                    'longitude' => $lons,
                    'current' => 'temperature_2m,weather_code',
                    'timezone' => 'Asia/Jakarta',
                ]);
                if (!$res->successful()) {
                    throw new \RuntimeException('Upstream cuaca menjawab ' . $res->status());
                }
                $json = $res->json();
                $list = is_array($json) && array_is_list($json) ? $json : [$json];
                $rows = [];
                foreach (self::AREAS as $i => [$name]) {
                    $current = is_array($list[$i] ?? null) ? ($list[$i]['current'] ?? null) : null;
                    $code = is_array($current) && isset($current['weather_code']) && is_numeric($current['weather_code'])
                        ? (int) $current['weather_code'] : null;
                    $temp = is_array($current) && isset($current['temperature_2m']) && is_numeric($current['temperature_2m'])
                        ? (int) round((float) $current['temperature_2m']) : null;
                    $meta = $code !== null ? (self::WMO[$code] ?? null) : null;
                    $rows[] = ($meta && $temp !== null)
                        ? ['name' => $name, 'temp' => $temp, 'label' => $meta[0], 'icon' => $meta[1]]
                        : ['name' => $name, 'temp' => null, 'label' => 'Data cuaca sedang tidak tersedia', 'icon' => '🌡️'];
                }

                if (collect($rows)->contains(fn (array $row) => $row['temp'] !== null)) {
                    Cache::put('cuaca_bekasi_stale', $rows, self::STALE_CACHE_TTL);
                }

                return $rows;
            });

            return $this->success('Cuaca terkini berhasil diambil', $rows);
        } catch (\Exception $e) {
            $staleRows = Cache::get('cuaca_bekasi_stale');
            if (is_array($staleRows) && $staleRows !== []) {
                return $this->success('Menggunakan data cuaca terakhir yang tersedia', $staleRows);
            }

            Log::error('Cuaca fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil cuaca terkini');
        }
    }
}
