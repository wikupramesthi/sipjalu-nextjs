<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales for this app (limit to login page requirement).
     */
    protected array $supported = ['id', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        // Default ke Bahasa Indonesia jika belum ada preferensi di session
        // (abaikan Accept-Language browser agar halaman login selalu ID di kunjungan pertama)
        if (! $locale || ! in_array($locale, $this->supported, true)) {
            $locale = config('app.locale', 'id');
        }

        if (! in_array($locale, $this->supported, true)) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
