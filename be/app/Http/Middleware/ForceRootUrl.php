<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Terapkan host/scheme dari request aktif (bukan dari host APP_URL).
 *
 * Path subdirektori (mis. /be) tetap diambil dari APP_URL agar route()
 * dan cookie/captcha tetap berada di prefix yang benar. Host harus
 * per-request supaya satu deployment bisa melayani domain apa pun dan
 * tetap benar di test/Octane.
 */
class ForceRootUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $configured = (string) config('app.url');

        if ($configured !== '') {
            $rootPath = parse_url($configured, PHP_URL_PATH) ?: '';
            $rootPath = '/' . trim($rootPath, '/');
            $rootPath = $rootPath === '/' ? '' : $rootPath;

            $scheme = $request->getScheme();
            if (str_starts_with($configured, 'https://')) {
                $scheme = 'https';
            }

            $host = $request->getHttpHost();
            if ($host !== '') {
                URL::forceRootUrl($scheme . '://' . $host . $rootPath);
            }

            if ($scheme === 'https') {
                URL::forceScheme('https');
            }
        }

        return $next($request);
    }
}
