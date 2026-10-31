<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Facades\SEO;


class Category extends Model
{
    use HasFactory, HasSEO, Searchable, HasUuid;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['uuid', 'name', 'slug','description','icon'];

    public function agendas()
    {
            return $this->hasMany(Agenda::class, 'category_uuid', 'uuid');

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

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'agendas_count' => $this->agendas()->count(),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }
}
