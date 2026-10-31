<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * JANGAN pakai '*' di produksi: X-Forwarded-For bisa dipalsukan untuk
     * mengakali throttle/brute-force. Daftarkan IP proxy tepercaya via
     * TRUSTED_PROXIES (pisahkan koma, dukung CIDR). Default hanya localhost.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    public function __construct()
    {
        $raw = trim((string) env('TRUSTED_PROXIES', ''));
        if ($raw === '' || strtolower($raw) === 'none') {
            $this->proxies = ['127.0.0.1', '::1', '10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16'];
        } elseif ($raw === '*') {
            $this->proxies = '*';
        } else {
            $this->proxies = array_values(array_filter(array_map('trim', explode(',', $raw))));
        }
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
