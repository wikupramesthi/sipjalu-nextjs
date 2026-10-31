<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use App\Traits\HasUuid;
use App\Traits\ImageOptimizable;

class Agenda extends Model
{
    use HasFactory, HasSEO, SoftDeletes, Searchable, ImageOptimizable, HasUuid;

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
        'scheduled_at',
        'jenis_agenda',
        'lokasi',
        'waktu_mulai',
        'waktu_akhir',
        'url',
        'parent_uuid',
        'recurrence_rule',
        'recurrence_end',
        'tagging',
        'status',
        'video',
        'search_engine',
        'featured_image',
        'views',
        'is_featured',
        'is_popular',
        'is_popular_manual',
        'is_urgent',
        'alasan_arsip',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'waktu_mulai'  => 'datetime',
        'waktu_akhir'  => 'datetime',
        'recurrence_end' => 'date',
        'is_featured'  => 'boolean',
        'is_popular'   => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Agenda::class, 'parent_uuid', 'uuid');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Agenda::class, 'parent_uuid', 'uuid')->orderBy('waktu_mulai');
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
        return $this->hasMany(AgendaImage::class, 'agenda_uuid', 'uuid')->orderBy('sort_order');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AgendaAttachment::class, 'agenda_uuid', 'uuid')->latest();
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AgendaLog::class, 'agenda_uuid', 'uuid')->latest();
    }

    /**
     * Agenda dianggap selesai bila waktu akhir (atau waktu mulai bila akhir kosong) sudah lewat.
     */
    public function getIsSelesaiAttribute(): bool
    {
        $akhir = $this->waktu_akhir ?? $this->waktu_mulai;

        return $akhir !== null && $akhir->isPast();
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->where('is_popular', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->where('scheduled_at', '<=', now())
                ->orWhereNull('scheduled_at');
        });
    }

    public function incrementViews(): bool
    {
        return $this->increment('views');
    }

    /**
     * URL of the featured image, falling back to the first slider photo.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return $this->images->first()?->url();
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            image: $this->featured_image,
            author: $this->user?->name,
            robots: 'index, follow',
            canonical_url: route('agendas.show', $this->slug),
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
            'jenis_agenda' => $this->jenis_agenda,
            'lokasi' => $this->lokasi,
            'is_featured' => $this->is_featured,
            'is_popular' => $this->is_popular,
            'views' => $this->views,
            'user_name' => $this->user?->name,
            'thumbnail' => $this->thumbnail_url,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === 'published';
    }

    protected function getImageAttributes(): array
    {
        return [
            'featured_image' => 'public',
        ];
    }
}
