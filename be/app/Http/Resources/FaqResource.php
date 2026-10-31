<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'id' => $this->uuid,
            'question' => $this->pertanyaan,
            'answer' => $this->jawaban,
            'kategori' => $this->kategori instanceof \BackedEnum ? $this->kategori->value : $this->kategori,
            'status' => $this->status,
            'urutan' => $this->urutan,
        ];
    }
}
