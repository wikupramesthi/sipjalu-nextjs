<?php

namespace App\Http\Requests\Auth;

use App\Services\Security\BruteForceProtector;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'captcha'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'captcha.required' => __('login.captcha_required'),
            'captcha.captcha' => __('login.captcha_invalid'),
        ];
    }

    /**
     * Redirect login yang gagal SELALU kembali ke halaman login.
     *
     * StartSession menyimpan URL GET terakhir sebagai "previous URL", dan
     * browser me-load gambar captcha (GET /captcha-file/...) SETELAH halaman
     * login. Tanpa override ini, redirect()->back() saat validasi gagal akan
     * nyasar ke URL gambar captcha sehingga user tidak pernah melihat
     * notifikasi error (halaman terlihat "tidak mau" login).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo(route('login'));
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $protector = app(BruteForceProtector::class);
        $lockout = $protector->isLocked($this->ip(), $this->input('email'));

        if ($lockout) {
            throw ValidationException::withMessages([
                'email' => $protector->lockedMessage($lockout),
            ]);
        }

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // Listener RecordFailedLogin sudah menambah counter DB via event Failed.
            // Cek ulang: jika percobaan ini memicu lockout, langsung tampilkan pesan blokir.
            $freshLockout = $protector->isLocked($this->ip(), $this->input('email'));

            if ($freshLockout) {
                throw ValidationException::withMessages([
                    'email' => $protector->lockedMessage($freshLockout),
                ]);
            }

            $remaining = max(0, $protector->maxAttempts() - RateLimiter::attempts($this->throttleKey()));

            throw ValidationException::withMessages([
                'email' => $remaining > 0
                    ? trans('auth.failed') . " Sisa kesempatan: {$remaining} kali sebelum akun/IP diblokir sementara."
                    : trans('auth.failed'),
            ]);
        }

        // Tolak akun yang dinonaktifkan (pesan generik anti-enumerasi).
        if ((Auth::user()->is_active ?? 'active') === 'inactive') {
            Auth::logout();
            $this->session()->invalidate();
            $this->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $maxAttempts = app(BruteForceProtector::class)->maxAttempts();

        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = (int) ceil($seconds / 60);

        // Pesan ramah tetap di halaman login (bukan halaman 429):
        // "Silakan coba lagi / silakan login kembali dalam ..."
        $waitText = $seconds >= 60
            ? "{$minutes} menit"
            : "{$seconds} detik";

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Silakan login kembali dalam waktu {$waitText}.",
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower(trim((string) $this->input('email'))));
    }
}
