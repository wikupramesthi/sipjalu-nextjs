<?php

namespace App\Models;

use App\Models\Concerns\ParsesUserAgent;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use ParsesUserAgent;

    protected $fillable = [
        'user_uuid',
        'name',
        'email',
        'ip_address',
        'user_agent',
        'guard',
        'logged_in_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
