<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Tindak lanjut aduan versi publik: tanggal, status, catatan.
 * Tanpa nama petugas dan tanpa foto internal.
 */
class PublicAduanTindakLanjutResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tanggal' => $this->tanggal?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'catatan' => $this->catatan,
        ];
    }
}
