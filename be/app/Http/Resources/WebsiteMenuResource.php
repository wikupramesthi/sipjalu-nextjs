<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteMenuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'location' => $this->location,
            'position' => $this->position,
            'items' => WebsiteMenuItemResource::collection($this->whenLoaded('activeItems', $this->activeItems) ?? $this->whenLoaded('items', $this->items) ?? collect()),
        ];
    }
}
