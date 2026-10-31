<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use App\Traits\ImageOptimizable;

class Document extends Model
{
    use HasUuids, HasSEO, SoftDeletes, Searchable, ImageOptimizable;

    protected $table = 'documents';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'category_uuid',
        'subcategory_uuid',
        'title',
        'slug',
        'excerpt',
        'description',
        'published_at',
        'file',
        'file_url',
        'thumbnail',
        'status',
    ];

    protected $casts = [
        'published_at' => 'date',
        'year' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->uuid)) {
                $document->uuid = (string) Str::uuid();
            }

            if (empty($document->slug)) {
                $document->slug = Str::slug($document->title);
            }
        });

        static::updating(function ($document) {
            if ($document->isDirty('title')) {
                $document->slug = Str::slug($document->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(
            DocumentCategory::class,
            'category_uuid',
            'uuid'
        );
    }

    public function subcategory()
    {
        return $this->belongsTo(
            DocumentCategory::class,
            'subcategory_uuid',
            'uuid'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class, 'document_uuid', 'uuid')->orderByDesc('versi');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhereDate('published_at', '<=', now());
            });
    }

    /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->excerpt ?? $this->description,
            image: $this->thumbnail
                ? asset('storage/' . $this->thumbnail)
                : null,
            robots: 'index, follow',
            canonical_url: route('documents.show', $this->slug),
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'description' => strip_tags($this->description),
            'category' => $this->category?->name,
            'status' => $this->status,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'file' => $this->file_url
                ? $this->file_url
                : ($this->file ? asset('storage/' . $this->file) : null),
            'published_at' => $this->published_at?->timestamp,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === 'active' 
            && ($this->published_at === null || $this->published_at <= now());
    }

    protected function getImageAttributes(): array
    {
        return [
            'thumbnail' => 'public',
            'file' => 'public',
        ];
    }
}