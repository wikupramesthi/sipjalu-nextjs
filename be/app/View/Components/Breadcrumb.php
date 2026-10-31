<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private string $title,
        private string $page,
        private string $route,
        private ?string $active = null,
    ) {
        $this->title = $title;
        $this->page = $page;
        $this->route = $route;
        // Fallback ke title agar satu atribut yang lupa diisi tidak meledak 500.
        $this->active = $active ?? $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.web.breadcrumb', [
            'title' => $this->title,
            'page' => $this->page,
            'route' => $this->route,
            'active' => $this->active,
        ]);
    }
}
