<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\ImageOptimizable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Berita extends Model
{
    use HasSEO, HasUuid, ImageOptimizable, Searchable, SoftDeletes;

    protected $table = 'beritas';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'category_uuid',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'video',
        'scheduled_at',
        'views',
        'tagging',
        'status',
        'search_engine',
        'is_featured',
        'is_popular',
        'is_urgent',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Berita $berita): void {
            if (empty($berita->uuid)) {
                $berita->uuid = (string) Str::uuid();
            }

            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->title);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    public function images(): HasMany
    {
        return $this->hasMany(BeritaImage::class, 'berita_uuid', 'uuid')->orderBy('sort_order');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(BeritaAttachment::class, 'berita_uuid', 'uuid')->latest();
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            image: $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            author: $this->user?->name,
            robots: $this->search_engine === 'noindex' ? 'noindex, nofollow' : 'index, follow',
            canonical_url: route('beritas.show', $this->uuid),
            schema: SchemaCollection::make()->addArticle(),
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => strip_tags($this->content),
            'category' => $this->category?->name,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'is_popular' => $this->is_popular,
            'is_urgent' => $this->is_urgent,
            'views' => $this->views,
            'user_name' => $this->user?->name,
            'thumbnail' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === 'published'
            && ($this->scheduled_at === null || $this->scheduled_at <= now());
    }

    protected function getImageAttributes(): array
    {
        return ['featured_image' => 'public'];
    }
}
