<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLockout extends Model
{
    protected $fillable = [
        'type',
        'value',
        'attempts',
        'reason',
        'blocked_until',
        'last_attempt_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'blocked_until' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('blocked_until', '>', now());
    }

    public function getIsBlockedAttribute(): bool
    {
        return $this->blocked_until && $this->blocked_until->isFuture();
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'email' ? 'Email' : 'IP Address';
    }
}
