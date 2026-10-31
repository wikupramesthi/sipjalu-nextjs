<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'uuid'      => $this->uuid,
            'nama'      => $this->nama,
            'deskripsi' => $this->deskripsi,
            'link'      => $this->link,
            'gambar'    => $this->gambar
                ? '/storage/' . $this->gambar
                : '/images/default.png',
            'posisi'    => $this->posisi,
            'tipe'      => $this->tipe,
            'status'    => $this->status,
        ];
    }
}
