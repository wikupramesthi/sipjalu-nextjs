<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'          => $this->uuid,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'excerpt'       => $this->excerpt,
            'content'       => $this->content,
            'tagging'       => $this->tagging,
            // 'featured_image'    => $this->featured_image
            //     ? url('storage/' . $this->featured_image)
            //     : url('images/default.png'),
            'featured_image' => $this->featured_image
                ? '/storage/' . $this->featured_image
                : '/images/default.png',
            'images'        => $this->whenLoaded('images', function () {
                return $this->images->map(fn($image) => [
                    'uuid'       => $image->uuid,
                    'image_path' => url('storage/' . $image->image_path),
                    'caption'    => $image->caption,
                    'sort_order' => $image->sort_order,
                ]);
            }, []),
            'is_featured'   => (bool) $this->is_featured,
            'is_popular'    => (bool) $this->is_popular,
            'scheduled_at'  => $this->scheduled_at,
            'jenis_agenda'  => $this->jenis_agenda,
            'lokasi'        => $this->lokasi,
            'waktu_mulai'   => $this->waktu_mulai,
            'waktu_akhir'   => $this->waktu_akhir,
            'url'           => $this->url,
            'views'         => $this->views,
            'link'          => $this->link,
            'video'         => $this->video,
            'author'        => $this->user?->name,
            'category'      => $this->category?->name,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
