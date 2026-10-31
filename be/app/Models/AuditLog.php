<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_uuid',
        'user_name',
        'user_email',
        'event',
        'auditable_type',
        'auditable_id',
        'auditable_label',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function getModelNameAttribute(): string
    {
        return $this->auditable_type ? class_basename($this->auditable_type) : '-';
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created' => 'Ditambahkan',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'restored' => 'Dipulihkan',
            default => ucfirst((string) $this->event),
        };
    }

    public function getEventBadgeAttribute(): string
    {
        return match ($this->event) {
            'created' => 'bg-success-subtle text-success',
            'updated' => 'bg-warning-subtle text-warning',
            'deleted' => 'bg-danger-subtle text-danger',
            'restored' => 'bg-info-subtle text-info',
            default => 'bg-secondary-subtle text-secondary',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function changes(): array
    {
        $old = $this->old_values ?? [];
        $new = $this->new_values ?? [];
        $keys = array_unique(array_merge(array_keys($old), array_keys($new)));

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = [
                'old' => $old[$key] ?? null,
                'new' => $new[$key] ?? null,
            ];
        }

        return $result;
    }
}
