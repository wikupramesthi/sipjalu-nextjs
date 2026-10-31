<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy — diselaraskan dengan skrip yang dipakai:
        // tailwind/jsdelivr/unpkg (aset), googletagmanager (Analytics FE),
        // widget GPR Kominfo (github.io). 'unsafe-eval' DIBUANG (temuan pentest
        // umum); 'unsafe-inline' dipertahankan untuk skrip inline Blade/GA.
        // form-action diperluas untuk dev: localhost/127.0.0.1 beda port tetap self.
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://unpkg.com https://news.google.com https://www.googletagmanager.com https://rahadiana.github.io",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://unpkg.com data:",
            "img-src 'self' data: https:",
            "connect-src 'self' https://cdn.jsdelivr.net https://news.google.com https://www.google-analytics.com https://region1.google-analytics.com https://api.open-meteo.com",
            "frame-ancestors 'none'",
            "form-action 'self' http://127.0.0.1:* http://localhost:* https://127.0.0.1:* https://localhost:*",
            "base-uri 'self'",
            "object-src 'none'",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // HSTS - only in production with HTTPS
        if (config('app.env') === 'production' && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}