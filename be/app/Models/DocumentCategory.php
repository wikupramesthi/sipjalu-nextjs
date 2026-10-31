<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Facades\SEO;
use Illuminate\Support\Str;


class DocumentCategory extends Model
{
    use HasFactory, HasSEO;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['uuid', 'parent_uuid', 'name', 'slug','description','status'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'category_uuid', 'uuid');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_uuid', 'uuid');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_uuid', 'uuid');
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->name,
            description: $this->slug,
            author: $this->name,
            published_time: $this->created_at,
            schema: SchemaCollection::make()->addArticle(),
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
    
}
