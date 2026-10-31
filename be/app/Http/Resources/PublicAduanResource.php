<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Representasi aduan untuk endpoint PUBLIK (index & track).
 *
 * Prinsip: tampilkan hanya yang perlu untuk transparansi (nomor, judul,
 * status, tindak lanjut), sembunyikan data pribadi: nama pelapor, koordinat
 * presisi, foto, dan seluruh relasi user/petugas.
 */
class PublicAduanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'nomor_aduan' => $this->nomor_aduan,
            'kategori' => $this->kategori,
            'judul' => $this->judul,
            'isi_aduan' => $this->isi_aduan,
            'lokasi' => $this->lokasi,
            'kecamatan' => $this->whenLoaded('kecamatan', fn () => $this->kecamatan?->nama),
            'kelurahan' => $this->whenLoaded('kelurahan', fn () => $this->kelurahan?->nama),
            'tanggal_pengaduan' => $this->tanggal_pengaduan?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'tindak_lanjut' => PublicAduanTindakLanjutResource::collection($this->whenLoaded('tindakLanjuts')),
        ];
    }
}
