<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'is_published' => (bool) $this->is_published,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'has_sidebar' => (bool) $this->has_sidebar,
        ];
    }
}
