<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fotoUrl = fn(?string $path) => $path ? '/storage/' . ltrim($path, '/') : null;

        // cover: pakai relasi coverFoto bila ada, fallback foto pertama
        $cover = null;
        try {
            $cover = $this->coverFoto();
        } catch (\Throwable $e) {
            $cover = null;
        }

        $fotos = [];
        if ($this->relationLoaded('fotos')) {
            $fotos = $this->fotos->map(fn($f) => [
                'uuid' => $f->uuid,
                'nama' => $f->nama,
                'gambar' => $fotoUrl($f->gambar),
                'deskripsi' => $f->deskripsi ?? null,
            ])->values()->all();
        }

        return [
            'uuid' => $this->uuid,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status,
            'foto_count' => $this->whenCounted('fotos', $this->fotos_count ?? null, fn() => $this->fotos()->count()),
            'cover_uuid' => $cover?->uuid,
            'cover_url' => $cover && $cover->gambar ? $fotoUrl($cover->gambar) : null,
            'cover_nama' => $cover?->nama,
            'fotos' => $fotos,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
