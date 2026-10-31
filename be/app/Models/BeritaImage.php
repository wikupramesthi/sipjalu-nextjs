<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaImage extends Model
{
    use HasUuid;

    protected $table = 'berita_images';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['berita_uuid', 'image_path', 'sort_order'];

    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class, 'berita_uuid', 'uuid');
    }

    public function url(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
