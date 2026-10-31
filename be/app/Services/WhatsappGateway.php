<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Pengirim WhatsApp via gateway eksternal yang dikonfigurasi lewat .env:
 * WA_ENABLED, WA_GATEWAY_URL, WA_GATEWAY_TOKEN.
 *
 * Format request JSON: { "target": "628...", "message": "..." }.
 * Sesuaikan WA_GATEWAY_URL dengan format yang dipakai gateway instansi.
 * Bila nonaktif / gagal, pesan hanya dicatat di log dan tidak menggagalkan alur.
 */
class WhatsappGateway
{
    public static function enabled(): bool
    {
        return (bool) config('services.whatsapp.enabled', false)
            && (string) config('services.whatsapp.url', '') !== '';
    }

    public static function normalize(string $number): string
    {
        $digits = preg_replace('/\D+/', '', $number) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits;
    }

    public static function send(?string $to, string $message): bool
    {
        $target = $to !== null ? self::normalize($to) : '';

        if ($target === '' || $message === '') {
            return false;
        }

        if (! self::enabled()) {
            Log::info('[WA-OFF] ke ' . $target . ': ' . $message);

            return false;
        }

        try {
            $response = Http::timeout(15)->withHeaders([
                'Authorization' => 'Bearer ' . (string) config('services.whatsapp.token', ''),
            ])->post((string) config('services.whatsapp.url'), [
                'target'  => $target,
                'message' => $message,
            ]);

            if (! $response->successful()) {
                Log::warning('[WA-GAGAL] ke ' . $target . ' HTTP ' . $response->status() . ': ' . $response->body());
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('[WA-ERROR] ke ' . $target . ': ' . $e->getMessage());

            return false;
        }
    }
}
