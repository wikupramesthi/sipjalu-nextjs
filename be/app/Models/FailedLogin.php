<?php

namespace App\Models;

use App\Models\Concerns\ParsesUserAgent;
use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model
{
    use ParsesUserAgent;

    protected $fillable = [
        'email',
        'user_uuid',
        'ip_address',
        'user_agent',
        'reason',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
