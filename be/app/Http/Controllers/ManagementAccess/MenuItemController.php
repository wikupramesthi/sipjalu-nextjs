<?php

namespace App\Http\Controllers\ManagementAccess;

use App\Models\ManagementAccess\MenuItem;
use App\Models\ManagementAccess\MenuGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\ManagementAccess\StoreMenuItemRequest;
use App\Http\Requests\ManagementAccess\UpdateMenuItemRequest;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, MenuGroup $menu)
    {
        // If user is not authorized, abort
        // if (! Gate::allows('menu_item_index')) {
        //     abort(403);
        // }
        $menuItems = $menu->items()
            ->when(! blank($request->search), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('permission_name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);
        $permissions = Permission::orderBy('name')->get();
        $routes = Route::getRoutes();

        return view('management-access.menu-item.index', compact('menu', 'menuItems', 'permissions', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If user is not authorized, abort
        // if (! Gate::allows('menu_item_index')) {
        //     abort(403);
        // }
        return view('pages.management-access.menu.item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request, MenuGroup $menu)
    {
        $validated = $request->validated();
        MenuItem::create(array_merge(
            $validated,
            array(
                'menu_group_id' => $menu->id,
                'status' => ! blank($validated['status'] ?? null) ? true : false,
                'position' => $menu->items()->max('position') + 1
            )
        ));
        return back()->with('success', 'Menu Item has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem, MenuGroup $menu, $id)
    {
        // dd($menu->id, $menuItem, $id);
        $menuItemId = $menuItem->findOrFail($id);
        abort_if($menuItemId->menu_group_id !== $menu->id, 404);
        $validated = $request->validated();
        $menuItemId->update(array_merge(
            $validated,
            array(
                'menu_group_id' => $menu->id,
                'status' => ! blank($validated['status'] ?? null) ? true : false
            )
        ));

        return back()->with('success', 'Menu Item has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuGroup $menu, MenuItem $item)
    {
        abort_if($item->menu_group_id !== $menu->id, 404);
        $item->delete();

        return back()->with('success', 'Menu Item has been deleted successfully!');
    }
}
