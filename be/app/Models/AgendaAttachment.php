<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaAttachment extends Model
{
    use HasUuid;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'agenda_uuid',
        'file_path',
        'nama_asli',
        'ukuran',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class, 'agenda_uuid', 'uuid');
    }

    public function url(): string
    {
        return route('agendas.attachments.download', [$this->agenda_uuid, $this->uuid]);
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
