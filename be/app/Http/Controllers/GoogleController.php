<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // Stateful (pakai session + state) agar terlindungi CSRF.
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $email = strtolower(trim((string) $googleUser->getEmail()));
            $googleId = (string) $googleUser->getId();
            $emailVerified = (bool) ($googleUser->user['email_verified'] ?? false);

            // Tolak email yang tidak terverifikasi Google (anti take-over).
            if ($email === '' || $googleId === '' || ! $emailVerified) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Google tidak terverifikasi. Gunakan login email/password.']);
            }

            // 1. Cari berdasarkan google_id dulu (tautan yang sah).
            $user = User::where('google_id', $googleId)->first();

            // 2. Belum tertaut: cocokkan email terverifikasi, lalu tautkan.
            if (! $user) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    if ($user->google_id && $user->google_id !== $googleId) {
                        return redirect()->route('login')
                            ->withErrors(['email' => 'Email ini sudah tertaut ke akun Google lain. Hubungi administrator.']);
                    }
                    $user->forceFill(['google_id' => $googleId])->save();
                } else {
                    $user = User::create([
                        'name' => $googleUser->getName() ?: $email,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $googleUser->getAvatar(),
                        'password' => Str::random(40),
                        'email_verified_at' => now(),
                    ]);

                    $user->assignRole('user');
                }
            }

            // Tolak akun yang dinonaktifkan.
            if (($user->is_active ?? 'active') === 'inactive') {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun dinonaktifkan. Hubungi administrator.']);
            }

            Auth::login($user);
            $request->session()->regenerate();
            session()->put('last_activity', time());
            session()->put('login_at', time());

            return redirect()->route('dashboard.index');
        } catch (\Exception $e) {
            Log::warning('Google login gagal: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login Google gagal. Silakan coba lagi.');
        }
    }
}
