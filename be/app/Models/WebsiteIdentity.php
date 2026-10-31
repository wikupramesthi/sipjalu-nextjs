<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WebsiteIdentity extends Model
{
    use HasFactory;

    protected $table = 'website_identities';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'site_name',
        'site_title',
        'tagline',
        'description',
        'logo',
        'favicon',
        'email',
        'phone',
        'address',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'tiktok_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'google_analytics_id',
        'google_site_verification',
        'hero_badge',
        'hero_title',
        'hero_title_highlight',
        'hero_lead',
        'hero_cta_label',
        'hero_cta_url',
        'hero_countdown_label',
        'hero_countdown_target',
        'hero_background',
    ];

    protected $casts = [
        'hero_countdown_target' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function logoUrl(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function faviconUrl(): ?string
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    public function ogImageUrl(): ?string
    {
        return $this->og_image ? asset('storage/' . $this->og_image) : null;
    }

    public function heroBackgroundUrl(): ?string
    {
        return $this->hero_background ? asset('storage/' . $this->hero_background) : null;
    }

    public static function current(): self
    {
        return static::first() ?? static::create([
            'site_name' => config('app.name', 'Pemerintah Kota Bekasi'),
            'site_title' => 'Pemerintah Kota Bekasi',
        ]);
    }
}
