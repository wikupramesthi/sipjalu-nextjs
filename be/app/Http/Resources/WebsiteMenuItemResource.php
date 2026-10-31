<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteMenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $link = null;
        try {
            // Link internal selalu relatif agar tidak terikat host/domain saat
            // API diakses lewat proxy dengan Host berbeda.
            $link = $this->route
                ? route($this->route, $this->route_params ?? [], false)
                : $this->url;
        } catch (\Throwable $e) {
            $link = $this->url;
        }
        $link = $this->toRelativeInternalLink($link);

        $children = $this->whenLoaded('allChildren') ?? $this->whenLoaded('children');
        // ensure children are filtered by status already via eager load

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'route' => $this->route,
            'route_params' => $this->route_params,
            'icon' => $this->icon,
            'target_blank' => (bool) $this->target_blank,
            'position' => $this->position,
            'link' => $link,
            'children' => WebsiteMenuItemResource::collection($children ?? collect()),
        ];
    }

    /**
     * Buang skema+host untuk tautan yang menunjuk host request sendiri.
     * Domain apa pun tetap memakai path relatif yang sama.
     */
    private function toRelativeInternalLink(?string $link): ?string
    {
        if (! is_string($link) || $link === '') {
            return $link;
        }

        $parts = parse_url($link);
        if (! is_array($parts) || empty($parts['host'])) {
            return $link;
        }

        $requestHost = request()?->getHost();
        if (! $requestHost || strcasecmp($parts['host'], $requestHost) !== 0) {
            return $link;
        }

        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return $path . $query . $fragment;
    }
}
