<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Faq;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $faqs = Faq::where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('auth.login', compact('faqs'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $request->session()->put('last_activity', time());
        $request->session()->put('login_at', time());
        $request->session()->put(
            'show_default_password_modal',
            $request->user()->isUsingDefaultPassword(),
        );

        // Default: URL absolut dashboard (sudah single /be/ berkat
        // forceRootUrl dinamis di AppServiceProvider).
        $default = route('dashboard.index');

        // Ambil intended URL (kalau user awalnya kepentok auth saat buka
        // halaman dalam). Normalisasi agar TIDAK PERNAH jadi /be/be/... :
        // Hasil tinker: to('/be/x') = {root}/be/x (DOUBLE), sedangkan
        // to('/x') = {root}/x (single) dan to('http://h/be/x') diteruskan
        // apa adanya. Jadi:
        // - absolute URL -> cukup kolapskan /be/be jadi /be;
        // - relative path -> kolapskan lalu BUANG satu prefix /be di depan
        //   supaya to() menempelkan tepat satu /be.
        $intended = $request->session()->pull('url.intended', $default);
        if (! is_string($intended) || $intended === '') {
            $intended = $default;
        } elseif (preg_match('#^https?://#i', $intended)) {
            $intended = preg_replace('#(/be)(/be)+(?=/|$)#', '$1', $intended);
        } else {
            if (! str_starts_with($intended, '/')) {
                $intended = '/' . $intended;
            }
            $intended = preg_replace('#(/be)(/be)+(?=/|$|\?)#', '$1', $intended);
            $intended = preg_replace('#^/be(?=/|$|\?)#', '', $intended);
            if ($intended === '' || $intended === '/') {
                $intended = $default;
            }
        }

        // Demi keamanan: hanya izinkan redirect internal (relative path
        // atau absolute URL dengan host yang sama). Selain itu paksa ke
        // dashboard agar tidak jadi open-redirect.
        if (is_string($intended) && preg_match('#^https?://#i', $intended)) {
            try {
                $intendedHost = parse_url($intended, PHP_URL_HOST);
                $currentHost = $request->getHost();
                if ($intendedHost && $currentHost && strcasecmp($intendedHost, $currentHost) !== 0) {
                    $intended = $default;
                }
            } catch (\Throwable $e) {
                $intended = $default;
            }
        }

        // to() meneruskan URL absolut apa adanya (tanpa nempel root lagi),
        // jadi single /be/ terjamin setelah normalisasi di atas.
        return redirect()
            ->to($intended)
            ->with('success', 'Welcome to the admin page!');
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user(); // ambil dulu sebelum logout

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && in_array($user->role, ['admin', 'super-admin'])) {
            return redirect('auth/login');
        }

        return redirect('auth/login');
    }
}
