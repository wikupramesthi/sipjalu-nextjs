<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Hash;


use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, SoftDeletes;

    public const DEFAULT_PASSWORD = 'password';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'email',
        'no_hp',
        'alamat',
        'kecamatan_id',
        'kelurahan_id',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'sumber_informasi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    public function getAuthIdentifierName()
    {
        return 'uuid';
    }

    public function isUsingDefaultPassword(): bool
    {
        return Hash::check(self::DEFAULT_PASSWORD, $this->password);
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            'membership_start_date' => 'date',
            'membership_end_date' => 'date',
            'total_quota' => 'integer',
            'remaining_quota' => 'integer',
        ];
    }

    /**
     * Auto-generate UUID when creating user.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relationship to Kecamatan.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * Relationship to Kelurahan.
     */
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * Agenda yang diinput user ini (untuk statistik OPD).
     */
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'user_uuid', 'uuid');
    }
}
