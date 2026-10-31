<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AduanTindakLanjutResource extends JsonResource
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
            'tanggal' => $this->tanggal?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'catatan' => $this->catatan,
            'foto_1' => $this->foto_1 ? asset('storage/' . $this->foto_1) : null,
            'foto_2' => $this->foto_2 ? asset('storage/' . $this->foto_2) : null,
            'petugas' => $this->whenLoaded('user', fn () => $this->user?->name, 'Petugas'),
        ];
    }
}
