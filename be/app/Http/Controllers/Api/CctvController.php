<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Proxy CCTV Kota Bekasi (eofficev2) untuk halaman /cctv.
 *
 * - GET /api/cctv              → daftar kamera (proxy list.json, cache 10 menit)
 * - GET /api/cctv/stream/{f}   → playlist HLS, segmen ditulis ulang ke /api/cctv/seg/…
 * - GET /api/cctv/seg/{f}      → byte segmen .ts (video/MP2T)
 *
 * Lewat proxy server-side, browser tidak kena blokir CORS upstream.
 * Host upstream tetap (konstanta) — nama berkas divalidasi ketat sehingga
 * tidak bisa dipakai SSRF/probing path (hanya basename [A-Za-z0-9_-] + ext).
 */
class CctvController extends Controller
{
    private const LIST_URL = 'https://eofficev2.bekasikota.go.id/backupcctv/m3/list.json';
    private const STREAM_BASE = 'https://eofficev2.bekasikota.go.id/backupcctv/m3/';
    private const LIST_TTL = 600;
    private const LIST_STALE_TTL = 86400;
    // Playlist HLS bergulir tiap ~3 detik. Cache lama (60 dtk) membuat
    // hls.js meminta segmen yang sudah dihapus upstream (404) sehingga
    // siaran tampak mati. 5 detik cukup untuk meredam request beruntun.
    private const PLAYLIST_TTL = 5;

    /**
     * GET /api/cctv — daftar kamera: [{name, title, stream, pic}].
     */
    public function index(): JsonResponse
    {
        try {
            // v2: bentuk URL stream diubah menjadi relatif; key baru agar
            // cache lama (absolut, bisa menunjuk localhost) tidak dipakai.
            $items = Cache::remember('cctv_list_v2', self::LIST_TTL, function () {
                // Keep the total wait below the frontend request timeout.
                $res = Http::connectTimeout(2)->timeout(4)->acceptJson()->get(self::LIST_URL);
                if (!$res->successful()) {
                    throw new \RuntimeException('Upstream CCTV menjawab ' . $res->status());
                }
                $list = $res->json();
                if (!is_array($list)) {
                    return [];
                }
                $items = collect($list)
                    ->map(function ($c) {
                        if (!is_array($c)) {
                            return null;
                        }
                        $path = $this->cleanPlaylistName($c['path'] ?? '');
                        $name = trim((string) ($c['name'] ?? ''));
                        if ($path === '' || $name === '') {
                            return null;
                        }
                        $pic = trim((string) ($c['pic'] ?? ''));
                        if ($pic !== '' && !preg_match('#^https?://#i', $pic)) {
                            $pic = '';
                        }
                        return [
                            'name' => $name,
                            'title' => str_replace('_', ' ', preg_replace('/\.m3u8$/i', '', $name)),
                            // Relatif: jangan pakai url() karena APP_URL sering
                            // salah/berbeda (mis. http://localhost) sehingga
                            // browser menembak host yang tidak bisa dijangkau.
                            'stream' => '/api/cctv/stream/' . $path,
                            'pic' => $pic,
                        ];
                    })
                    ->filter()
                    ->values()
                    ->all();

                if ($items !== []) {
                    Cache::put('cctv_list_stale_v2', $items, self::LIST_STALE_TTL);
                }

                return $items;
            });

            return $this->success('Daftar CCTV berhasil diambil', $items);
        } catch (\Exception $e) {
            $staleItems = Cache::get('cctv_list_stale_v2');
            if (is_array($staleItems) && $staleItems !== []) {
                return $this->success('Daftar CCTV menggunakan cache terakhir', $staleItems);
            }

            Log::error('CCTV list fetch error: ' . $e->getMessage());

            return $this->error('Gagal mengambil daftar CCTV');
        }
    }

    /**
     * GET /api/cctv/stream/{file} — playlist HLS dengan segmen ditulis ulang
     * ke proxy segmen milik sendiri (bebas CORS, hemat satu hop DNS).
     * Query ?fresh=1 melewati cache 5 detik untuk mengambil klip terbaru
     * (dipakai pemutar saat mengulang otomatis).
     */
    public function stream(Request $request, string $file): Response
    {
        $clean = $this->cleanPlaylistName($file);
        if ($clean === '') {
            abort(404);
        }

        try {
            $cacheKey = 'cctv_playlist_' . $clean;
            if ($request->boolean('fresh')) {
                Cache::forget($cacheKey);
            }
            $playlist = Cache::remember($cacheKey, self::PLAYLIST_TTL, function () use ($clean) {
                $res = Http::connectTimeout(2)->timeout(6)->get(self::STREAM_BASE . $clean);
                if (!$res->successful()) {
                    throw new \RuntimeException('Upstream playlist menjawab ' . $res->status());
                }
                $body = trim($res->body());
                if (!str_starts_with($body, '#EXTM3U')) {
                    throw new \RuntimeException('Playlist CCTV tidak valid');
                }
                return $this->rewritePlaylist($body);
            });

            return response($playlist, 200, [
                'Content-Type' => 'application/vnd.apple.mpegurl',
                'Cache-Control' => 'public, max-age=30',
            ]);
        } catch (\Exception $e) {
            Log::error('CCTV stream fetch error: ' . $e->getMessage());
            abort(502);
        }
    }

    /**
     * GET /api/cctv/seg/{file} — byte segmen .ts (dialirkan langsung).
     */
    public function segment(string $file): Response
    {
        $clean = $this->cleanSegmentName($file);
        if ($clean === '') {
            abort(404);
        }

        try {
            $res = Http::connectTimeout(2)->timeout(8)->get(self::STREAM_BASE . $clean);
            if (!$res->successful()) {
                abort(502);
            }

            return response($res->body(), 200, [
                'Content-Type' => 'video/MP2T',
                'Cache-Control' => 'public, max-age=300',
            ]);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('CCTV segment fetch error: ' . $e->getMessage());
            abort(502);
        }
    }

    /**
     * Tulis ulang baris segmen relatif menjadi URL proxy segmen absolut.
     * Baris komentar (#...) dan URL absolut dibiarkan apa adanya.
     */
    private function rewritePlaylist(string $body): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $body) ?: [];
        foreach ($lines as $i => $line) {
            $uri = trim($line);
            if ($uri === '' || str_starts_with($uri, '#') || preg_match('#^https?://#i', $uri)) {
                continue;
            }
            $seg = $this->cleanSegmentName(basename($uri));
            // URL relatif agar mengikuti origin halaman/proxy FE. URL absolut
            // dari url() memakai APP_URL dan bisa mengarah ke localhost
            // (gagal di produksi, juga mixed-content di HTTPS).
            $lines[$i] = $seg === '' ? $uri : '/api/cctv/seg/' . $seg;
        }
        return implode("\n", $lines);
    }

    /** Basename ketat untuk playlist: huruf/angka/_/- + .m3u8. */
    private function cleanPlaylistName(mixed $value): string
    {
        $base = basename(trim((string) $value));
        return preg_match('/^[A-Za-z0-9_\-]+\.m3u8$/', $base) ? $base : '';
    }

    /** Basename ketat untuk segmen: huruf/angka/_/- + .ts. */
    private function cleanSegmentName(mixed $value): string
    {
        $base = basename(trim((string) $value));
        return preg_match('/^[A-Za-z0-9_\-]+\.ts$/', $base) ? $base : '';
    }
}
