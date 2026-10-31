<?php

namespace App\Models;

use App\Enums\FaqKategori;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Faq extends Model
{
    use HasFactory, Searchable, HasUuid;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['uuid', 'pertanyaan', 'jawaban', 'kategori', 'urutan','status'];

    protected $casts = [
        'kategori' => FaqKategori::class,
    ];


    public function toSearchableArray(): array
    {
        return [
            'id' => $this->uuid,
            'pertanyaan' => $this->pertanyaan,
            'jawaban' => strip_tags($this->jawaban),
            'kategori' => $this->kategori,
            'status' => $this->status,
            'urutan' => $this->urutan,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === 'active';
    }
}
