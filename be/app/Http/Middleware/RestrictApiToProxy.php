<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Jangan biarkan /api dapat dipanggil langsung dari internet.
 *
 * Jalur yang diizinkan:
 *  - proxy FE mengirim header X-API-Proxy yang sama dengan API_PROXY_SECRET, atau
 *  - peer TCP (REMOTE_ADDR, bukan X-Forwarded-For) berasal dari allowlist
 *    API_ALLOWED_PROXY_IPS yang diisi eksplisit (opsional), atau
 *  - peer TCP berasal dari jaringan privat/loopback (default, bisa dimatikan
 *    dengan API_TRUST_PRIVATE_PEERS=false), atau
 *  - route termasuk pengecualian untuk klien langsung (auth, v1, opd).
 *
 * Peer publik tanpa secret/allowlist tetap ditolak, supaya /api tidak bisa
 * ditembak langsung dari internet. Jaringan privat dipercaya agar proxy
 * FE/ingress di Docker/Kubernetes langsung berfungsi, bahkan bila secret
 * belum/tidak cocok. Set API_TRUST_PRIVATE_PEERS=false untuk mematikan ini.
 *
 * Menggunakan REMOTE_ADDR (bukan request->ip()) agar X-Forwarded-For yang
 * dipalsukan tidak bisa mengelabui pemeriksaan ini.
 */
class RestrictApiToProxy
{
    private const PRIVATE_RANGES = [
        '127.0.0.0/8',
        '::1/128',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        '169.254.0.0/16',
        'fc00::/7',
        'fe80::/10',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isExempt($request) || $this->isTrusted($request)) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Endpoint tidak ditemukan.',
            'data' => null,
        ], 404);
    }

    private function isExempt(Request $request): bool
    {
        foreach ((array) config('api_access.exempt_prefixes', []) as $prefix) {
            $prefix = trim((string) $prefix, '/');
            if ($prefix !== '' && $request->is($prefix, $prefix . '/*')) {
                return true;
            }
        }

        return false;
    }

    private function isTrusted(Request $request): bool
    {
        $peer = (string) $request->server('REMOTE_ADDR', '');
        if ($peer === '') {
            return false;
        }

        if ((bool) config('api_access.trust_private_peers', true)
            && IpUtils::checkIp($peer, self::PRIVATE_RANGES)) {
            return true;
        }

        $secret = trim((string) config('api_access.proxy_secret', ''));

        if ($secret !== '') {
            $header = (string) config('api_access.header', 'X-API-Proxy');
            $given = trim((string) $request->headers->get($header, ''));

            return $given !== '' && hash_equals($secret, $given);
        }

        foreach ((array) config('api_access.trusted_proxies', []) as $cidr) {
            $cidr = trim((string) $cidr);
            if ($cidr !== '' && IpUtils::checkIp($peer, $cidr)) {
                return true;
            }
        }

        return false;
    }
}
