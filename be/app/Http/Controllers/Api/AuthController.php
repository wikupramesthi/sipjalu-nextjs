<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Security\BruteForceProtector;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Autentikasi token (Sanctum) untuk Frontend/Mobile.
 *
 * Catatan: login web memakai captcha berbasis session, sehingga tidak bisa
 * dipakai client stateless. Endpoint ini menggantikannya dengan kombinasi
 * rate-limit (throttle) + kunci brute-force (BruteForceProtector) yang sama.
 */
class AuthController extends Controller
{
    public function login(LoginRequest $request, BruteForceProtector $protector): JsonResponse
    {
        $email = $protector->normalizeEmail($request->input('email'));

        if ($lockout = $protector->isLocked($request->ip(), $email)) {
            return $this->error($protector->lockedMessage($lockout), null, 423);
        }

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            // Samakan audit & counter brute-force dengan login web.
            event(new Failed('api', $user, ['email' => $email]));

            if ($freshLockout = $protector->isLocked($request->ip(), $email)) {
                return $this->error($protector->lockedMessage($freshLockout), null, 423);
            }

            return $this->error('Email atau password salah.', null, 401);
        }

        $user->loadMissing('roles');
        $deviceName = substr((string) $request->input('device_name', 'frontend'), 0, 50);
        $token = $user->createToken($deviceName, ['opd:agenda'])->plainTextToken;

        Log::info('API login sukses', ['uuid' => $user->uuid, 'ip' => $request->ip()]);

        return $this->success('Login berhasil.', [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $request->user()->loadMissing('roles');

        return $this->success('Profil berhasil diambil.', [
            'user' => new UserResource($request->user()),
        ]);
    }

    /**
     * GET /api/user — profil ringkas pemilik token (bentuk sama seperti
     * bawaan Sanctum; dipakai klien mobile).
     * Selalu lewat UserResource agar field sensitif (google_id, dsb) tidak bocor.
     */
    public function user(Request $request): JsonResponse
    {
        return $this->success('Profil berhasil diambil.', [
            'user' => new UserResource($request->user()->loadMissing('roles')),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logout berhasil. Token saat ini dicabut.');
    }

    /** Cabut SEMUA token milik user (dipakai saat ganti password / perangkat hilang). */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->success('Logout berhasil. Semua token dicabut.');
    }
}
