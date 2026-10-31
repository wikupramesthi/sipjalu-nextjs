<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Ringkas duplikasi boot() UUID di ~20 model.
 * Penggunaan: use HasUuid; + hapus boot() manual.
 * Otomatis set uuid string jika kosong.
 */
trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
