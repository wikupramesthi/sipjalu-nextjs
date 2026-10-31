<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AduanResource extends JsonResource
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
            'nomor_aduan' => $this->nomor_aduan,
            'pelapor' => $this->is_anonim
                ? 'Anonim'
                : ($this->relationLoaded('user') && $this->user
                    ? $this->user->name
                    : 'Masyarakat'),
            'is_anonim' => (bool) $this->is_anonim,
            'is_arsip' => $this->archived_at !== null,
            'rating' => $this->rating,
            'ulasan' => $this->ulasan,
            'kategori' => $this->kategori,
            'judul' => $this->judul,
            'isi_aduan' => $this->isi_aduan,
            'lokasi' => $this->lokasi,
            'kecamatan' => $this->whenLoaded('kecamatan', fn () => $this->kecamatan?->nama),
            'kelurahan' => $this->whenLoaded('kelurahan', fn () => $this->kelurahan?->nama),
            'foto_1' => $this->foto_1 ? asset('storage/' . $this->foto_1) : null,
            'foto_2' => $this->foto_2 ? asset('storage/' . $this->foto_2) : null,
            'foto_3' => $this->foto_3 ? asset('storage/' . $this->foto_3) : null,
            'tanggal_kejadian' => $this->tanggal_kejadian?->format('Y-m-d'),
            'tanggal_pengaduan' => $this->tanggal_pengaduan?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'prioritas' => $this->prioritas,
            'sifat' => $this->sifat,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'tindak_lanjut' => AduanTindakLanjutResource::collection($this->whenLoaded('tindakLanjuts')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
