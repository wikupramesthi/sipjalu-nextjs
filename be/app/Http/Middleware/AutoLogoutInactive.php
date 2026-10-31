<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutInactive
{
    /**
     * Logout user jika idle melebihi SESSION_INACTIVE_TIMEOUT (default 30 menit).
     * Server-side layer: proteksi walau JS dimatikan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('keep-alive')) {
            return $next($request);
        }

        if (Auth::check()) {
            $timeoutMinutes = (int) config('session.inactive_timeout', 60);
            $absoluteMinutes = (int) config('session.absolute_lifetime', 720);
            $lastActivity = $request->session()->get('last_activity');

            // Batas umur absolut: keep-alive tidak bisa memperpanjang selamanya.
            // Sesi lama tanpa penanda (dibuat sebelum fitur ini) diberi penanda
            // sekarang agar tidak langsung ter-logout.
            $loginAt = $request->session()->get('login_at');
            if ($loginAt === null) {
                $request->session()->put('login_at', time());
            } elseif (time() - (int) $loginAt > $absoluteMinutes * 60) {
                return $this->forceLogout($request, 'Sesi telah mencapai batas waktu maksimal. Silakan login kembali.');
            }

            if ($lastActivity !== null) {
                $inactiveSeconds = time() - $lastActivity;
                if ($inactiveSeconds > $timeoutMinutes * 60) {
                    return $this->forceLogout($request, 'Sesi berakhir karena tidak ada aktivitas selama ' . $timeoutMinutes . ' menit. Silakan login kembali.');
                }
            }

            // update timestamp tiap request terautentikasi
            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }

    private function forceLogout(Request $request, string $message): Response
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => $message], 401);
        }

        return redirect()
            ->route('login')
            ->withErrors(['session_expired' => $message]);
    }
}
