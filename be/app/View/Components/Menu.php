<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ManagementAccess\MenuGroup;
use Illuminate\Support\Facades\Cache;

class Menu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View|Closure|string
    {
        // Cache 5 menit per user role biar tidak query tiap render (N+1 teratasi)
        $userId = auth()->id() ?? 'guest';
        $roles = auth()->check() ? implode('_', auth()->user()->getRoleNames()->toArray()) : 'guest';
        $cacheKey = "sidebar_menus_{$userId}_{$roles}";
        $menus = Cache::remember($cacheKey, 300, function () {
            return MenuGroup::query()
                ->with(['items' => fn ($q) => $q->where('status', true)->orderBy('position')])
                ->where('status', true)
                ->orderBy('position')
                ->get();
        });
        return view('components.web.sidebar', compact('menus'));
    }
}
