<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteMenu;
use App\Models\WebsiteMenuItem;
use App\Http\Requests\StoreWebsiteMenuItemRequest;
use App\Http\Requests\UpdateWebsiteMenuItemRequest;
use Illuminate\Http\Request;

class WebsiteMenuItemController extends Controller
{
    public function index(WebsiteMenu $websiteMenu, Request $request)
    {
        $search = $request->input('search');
        $items = $websiteMenu->items()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->with('allChildren')
            ->paginate(5)->withQueryString();
        return view('pages.website-menu.items.index', compact('websiteMenu', 'items', 'search'));
    }

    public function create(WebsiteMenu $websiteMenu)
    {
        $menus = WebsiteMenu::where('status', true)->get();
        $parentItems = $websiteMenu->items()->whereNull('parent_id')->get();
        return view('pages.website-menu.items.create', compact('websiteMenu', 'menus', 'parentItems'));
    }

    public function store(StoreWebsiteMenuItemRequest $request, WebsiteMenu $websiteMenu)
    {
        $data = $request->validated();
        $data['website_menu_id'] = $websiteMenu->id;
        WebsiteMenuItem::create($data);
        return redirect()->route('website-menu.items.index', $websiteMenu)->with('success', 'Menu item berhasil dibuat.');
    }

    public function edit(WebsiteMenu $websiteMenu, WebsiteMenuItem $item)
    {
        $menus = WebsiteMenu::where('status', true)->get();
        $parentItems = $websiteMenu->items()->whereNull('parent_id')->where('id', '!=', $item->id)->get();
        return view('pages.website-menu.items.edit', compact('websiteMenu', 'item', 'menus', 'parentItems'));
    }

    public function update(UpdateWebsiteMenuItemRequest $request, WebsiteMenu $websiteMenu, WebsiteMenuItem $item)
    {
        $item->update($request->validated());
        return redirect()->route('website-menu.items.index', $websiteMenu)->with('success', 'Menu item berhasil diperbarui.');
    }

    public function destroy(WebsiteMenu $websiteMenu, WebsiteMenuItem $item)
    {
        $item->delete();
        return back()->with('success', 'Menu item berhasil dihapus.');
    }
}
