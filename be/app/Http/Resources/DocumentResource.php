<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'         => $this->uuid,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'excerpt'      => $this->excerpt,
            'published_at' => $this->published_at?->format('Y-m-d'),
            // Uploaded files use the same-origin storage path; external files retain their URL.
            'file'         => $this->file_url ?: ($this->file ? '/storage/' . $this->file : null),
            'thumbnail'    => $this->thumbnail ? '/storage/' . $this->thumbnail : null,
            'category'     => $this->category?->name,
            'category_slug' => $this->category?->slug,
            'subcategory'  => $this->subcategory?->name ?? ($this->category?->parent ? $this->category?->name : null),
            'subcategory_slug' => $this->subcategory?->slug ?? ($this->category?->parent ? $this->category?->slug : null),
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
