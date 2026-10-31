<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaLog extends Model
{
    use HasUuid;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'agenda_uuid',
        'user_uuid',
        'aksi',
        'catatan',
        'approved_by',
        'rejected_by',
    ];

    public const AKSI_LABEL = [
        'dibuat'            => 'Dibuat',
        'diperbarui'        => 'Diperbarui',
        'disetujui'         => 'Disetujui',
        'ditolak'           => 'Ditolak ke draft',
        'diduplikat'        => 'Diduplikat',
        'diarsipkan'        => 'Diarsipkan',
        'dipulihkan'        => 'Dipulihkan',
        'disetujui_massal'  => 'Disetujui (massal)',
        'pengingat_terkirim' => 'Pengingat H-1 terkirim',
        'dipublikasikan'  => 'Dipublikasikan (otomatis)',
        'ditolak_massal'  => 'Dikembalikan ke draft (massal)',
        'revisi_diminta'  => 'Revisi diminta',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class, 'agenda_uuid', 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function getLabelAttribute(): string
    {
        return self::AKSI_LABEL[$this->aksi] ?? $this->aksi;
    }
}
