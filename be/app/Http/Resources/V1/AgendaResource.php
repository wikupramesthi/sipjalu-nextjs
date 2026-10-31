<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Representasi agenda untuk web bekasikota.go.id.
 *
 * Beda dengan AgendaResource publik (FE internal): semua URL gambar
 * dibuat absolut terhadap BE sendiri (asset()/url()), plus ada
 * `api.detail` agar bekasikota bisa fetch detail by slug tanpa
 * hardcode base URL. Web bekasikota me-render HTML sendiri.
 */
class AgendaResource extends JsonResource
{
    /**
     * Tanggal acara: waktu_mulai dulu, jatuh ke scheduled_at, lalu created_at.
     */
    private function eventDate(): ?\Carbon\CarbonInterface
    {
        return $this->waktu_mulai ?? $this->scheduled_at ?? $this->created_at;
    }

    private function absoluteImage(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = ltrim($path, '/');

        // Sudah absolut (http/https) → kembalikan apa adanya.
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // Path storage relatif → absolut terhadap BE.
        if (str_starts_with($path, 'storage/')) {
            return url($path);
        }

        return url('storage/' . $path);
    }

    public function toArray(Request $request): array
    {
        $event = $this->eventDate();

        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'tagging' => $this->tagging,

            // Field kalender (dipakai widget bekasikota).
            'tanggal' => $event?->format('Y-m-d'),
            'jam_mulai' => $this->waktu_mulai?->format('H:i'),
            'jam_selesai' => $this->waktu_akhir?->format('H:i'),
            'scheduled_at' => $this->scheduled_at?->format('Y-m-d H:i:s'),
            'waktu_mulai' => $this->waktu_mulai?->format('Y-m-d H:i:s'),
            'waktu_akhir' => $this->waktu_akhir?->format('Y-m-d H:i:s'),

            'lokasi' => $this->lokasi,
            'jenis_agenda' => $this->jenis_agenda,
            'category' => $this->category?->name,
            'category_slug' => $this->category?->slug,
            'author' => $this->user?->name,
            'views' => (int) ($this->views ?? 0),
            'is_featured' => (bool) $this->is_featured,
            'is_popular' => (bool) $this->is_popular,

            'gambar' => $this->absoluteImage($this->featured_image),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn ($image) => [
                    'uuid' => $image->uuid,
                    'gambar' => $this->absoluteImage($image->image_path),
                    'caption' => $image->caption,
                    'sort_order' => $image->sort_order,
                ])->values()->all();
            }, []),

            'url' => $this->url,
            'link' => $this->link,
            'video' => $this->video,

            // URL BE sendiri (bukan deep-link portal) — bekasikota fetch lagi untuk detail.
            'api' => [
                'detail' => url('/api/v1/agenda/' . $this->slug),
            ],

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
