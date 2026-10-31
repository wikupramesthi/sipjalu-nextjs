<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteIdentityResource;
use App\Models\WebsiteIdentity;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WebsiteIdentityController extends Controller
{
    /**
     * GET /api/website-identity
     * Public read-only, untuk SEO, logo, favicon, footer (alamat/email/telp).
     */
    public function show(): JsonResponse
    {
        try {
            $data = PublicApiCache::remember('identity', 'show', function () {
                $identity = WebsiteIdentity::first();

                if (! $identity) {
                    // fallback default agar FE tidak 404 saat belum diisi admin
                    $identity = WebsiteIdentity::create([
                        'site_name' => config('app.name', 'Pemerintah Kota Bekasi'),
                        'site_title' => 'Pemerintah Kota Bekasi',
                        'tagline' => 'Pemerintah Kota Bekasi',
                    ]);
                }

                return (new WebsiteIdentityResource($identity))->resolve();
            }, PublicApiCache::TTL_LONG);

            return $this->success('Identitas website berhasil diambil', $data);
        } catch (\Exception $e) {
            Log::error('WebsiteIdentity fetch error: ' . $e->getMessage());
            return $this->error('Gagal mengambil identitas website', null, 500);
        }
    }

    /**
     * GET /api/website-identities (alias plural, untuk kompatibilitas)
     */
    public function index(): JsonResponse
    {
        return $this->show();
    }
}
