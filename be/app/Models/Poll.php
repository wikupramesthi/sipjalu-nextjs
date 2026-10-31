<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Poll extends Model
{
    protected $table = 'polls';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['question', 'options', 'status'];

    protected $casts = [
        'options' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function addVote(string $option, string $ip): void
    {
        DB::table('poll_votes')->insert([
            'uuid' => (string) Str::uuid(),
            'poll_id' => $this->id,
            'option' => $option,
            'ip_address' => $ip,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Ambil hasil voting untuk poll ini
     *
     * @return \Illuminate\Support\Collection
     */
    public function results()
    {
        return DB::table('poll_votes')
            ->select('option', DB::raw('COUNT(*) as total'))
            ->where('poll_id', $this->id)
            ->groupBy('option')
            ->pluck('total', 'option');
    }
    /**
     * Cek apakah IP sudah memberikan suara untuk poll ini
     *
     * @param string $ip
     * @return bool
     */ 
    public function hasVoted(string $ip): bool
    {
        return DB::table('poll_votes')
            ->where('poll_id', $this->id)
            ->where('ip_address', $ip)
            ->exists();
    }
}
