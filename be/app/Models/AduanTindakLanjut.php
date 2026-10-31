<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AduanTindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'aduan_tindak_lanjuts';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'aduan_uuid',
        'user_uuid',
        'status',
        'catatan',
        'foto_1',
        'foto_2',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tindakLanjut) {
            if (!$tindakLanjut->uuid) {
                $tindakLanjut->uuid = (string) Str::uuid();
            }

            if (!$tindakLanjut->tanggal) {
                $tindakLanjut->tanggal = now();
            }
        });
    }

    public function aduan()
    {
        return $this->belongsTo(Aduan::class, 'aduan_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
