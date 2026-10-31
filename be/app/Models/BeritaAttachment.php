<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAttachment extends Model
{
    use HasUuid;

    protected $table = 'berita_attachments';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['berita_uuid', 'file_path', 'nama_asli', 'ukuran'];

    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class, 'berita_uuid', 'uuid');
    }

    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getUkuranLabelAttribute(): string
    {
        $bytes = (int) $this->ukuran;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }

        return $bytes . ' B';
    }
}
