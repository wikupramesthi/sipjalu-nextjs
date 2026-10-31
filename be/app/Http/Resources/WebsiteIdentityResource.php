<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteIdentityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $toUrl = function (?string $path): ?string {
            if (! $path) {
                return null;
            }
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            // kembalikan path relatif agar works di dev (vite proxy /storage -> backend)
            // dan di prod (nginx proxy /storage), tanpa hardcode APP_URL/domain.
            // tidak cek exists lagi agar favicon/logo dari DB tetap tampil sesuai upload admin
            // (FE punya onError fallback ke assets/logo.png bila 404).
            return '/storage/' . ltrim($path, '/');
        };

        return [
            'uuid' => $this->uuid,
            'site_name' => $this->site_name,
            'site_title' => $this->site_title,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'youtube_url' => $this->youtube_url,
            'tiktok_url' => $this->tiktok_url,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'og_image' => $this->og_image,
            'google_analytics_id' => $this->google_analytics_id,
            'google_site_verification' => $this->google_site_verification,
            'hero_badge' => $this->hero_badge,
            'hero_title' => $this->hero_title,
            'hero_title_highlight' => $this->hero_title_highlight,
            'hero_lead' => $this->hero_lead,
            'hero_cta_label' => $this->hero_cta_label,
            'hero_cta_url' => $this->hero_cta_url,
            'hero_countdown_label' => $this->hero_countdown_label,
            'hero_countdown_target' => $this->hero_countdown_target?->toISOString(),
            // raw path (storage)
            'logo' => $this->logo,
            'favicon' => $this->favicon,
            'hero_background' => $this->hero_background,
            // resolved absolute url untuk frontend langsung pakai <img src> / favicon
            'logo_url' => $toUrl($this->logo),
            'favicon_url' => $toUrl($this->favicon),
            'og_image_url' => $toUrl($this->og_image),
            'hero_background_url' => $toUrl($this->hero_background),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
