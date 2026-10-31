<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use App\Models\User;
use App\Notifications\KontakBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function captcha()
    {
        $a = random_int(10, 50);
        $b = random_int(10, 50);

        $captchaId = Str::uuid()->toString();

        Cache::put(
            'contact_captcha_' . $captchaId,
            $a + $b,
            now()->addMinutes(3)
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'captcha_id' => $captchaId,
                'question' => "How much is the result of {$a} + {$b}?"
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email:rfc|max:255',
            'no_telp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'isi' => 'required|string|max:5000',
            'captcha_id' => 'required|string|size:36',
            'captcha_answer' => 'required|integer|between:20,100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Ambil jawaban CAPTCHA dari cache
        $cacheKey = 'contact_captcha_' . $request->captcha_id;
        $correctAnswer = Cache::get($cacheKey);

        if ($correctAnswer === null) {
            return response()->json([
                'success' => false,
                'message' => 'CAPTCHA sudah kedaluwarsa. Silakan muat ulang halaman.',
            ], 422);
        }

        // Cek jawaban (salah pun hanguskan: ruang jawaban cuma 2-18,
        // tanpa ini bisa di-brute-force dalam 2 menit via throttle).
        if ((int) $request->captcha_answer !== (int) $correctAnswer) {
            Cache::forget($cacheKey);

            return response()->json([
                'success' => false,
                'message' => 'Jawaban CAPTCHA salah. Silakan minta soal baru.',
            ], 422);
        }

        // CAPTCHA benar → hapus supaya tidak bisa dipakai ulang
        Cache::forget($cacheKey);

        // Simpan ke database (plain text, tanpa HTML)
        $contact = Kontak::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'isi' => strip_tags($request->isi),
        ]);

        // Beri tahu petugas backend
        try {
            $petugas = User::role(['super-admin', 'admin'])->get();

            foreach ($petugas as $orang) {
                $orang->notify(new KontakBaruNotification(
                    $contact->nama,
                    Str::limit($contact->isi, 80)
                ));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        // Kembalikan ID saja — jangan echo seluruh baris DB ke publik.
        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim. Kami akan segera menghubungi Anda.',
            'data' => ['uuid' => $contact->uuid],
        ], 201);
    }
}
