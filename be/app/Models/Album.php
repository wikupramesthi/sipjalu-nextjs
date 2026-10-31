<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $table = 'albums';
    protected $fillable = [
        'nama',
        'deskripsi',
        'cover',
        'status',
    ];

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    public function fotos()
    {
        return $this->belongsToMany(Banner::class, 'album_foto', 'album_uuid', 'banner_uuid');
    }

    public function coverFoto()
    {
        if ($this->cover) {
            $foto = Banner::where('uuid', $this->cover)->first();
            if ($foto) {
                return $foto;
            }
        }

        return $this->fotos()->first();
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