<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteMenu;
use App\Http\Requests\StoreWebsiteMenuRequest;
use App\Http\Requests\UpdateWebsiteMenuRequest;
use Illuminate\Http\Request;

class WebsiteMenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $stats = [
            'total'    => WebsiteMenu::count(),
            'active'   => WebsiteMenu::where('status', true)->count(),
            'inactive' => WebsiteMenu::where('status', false)->count(),
        ];
        $menus = WebsiteMenu::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('position')->paginate(10)->withQueryString();
        return view('pages.website-menu.index', compact('menus', 'stats', 'search'));
    }

    public function create()
    {
        return view('pages.website-menu.create');
    }

    public function store(StoreWebsiteMenuRequest $request)
    {
        WebsiteMenu::create($request->validated());
        return redirect()->route('website-menu.index')->with('success', 'Menu website berhasil dibuat.');
    }

    public function edit(WebsiteMenu $websiteMenu)
    {
        return view('pages.website-menu.edit', compact('websiteMenu'));
    }

    public function update(UpdateWebsiteMenuRequest $request, WebsiteMenu $websiteMenu)
    {
        $websiteMenu->update($request->validated());
        return redirect()->route('website-menu.index')->with('success', 'Menu website berhasil diperbarui.');
    }

    public function destroy(WebsiteMenu $websiteMenu)
    {
        $websiteMenu->delete();
        return back()->with('success', 'Menu website berhasil dihapus.');
    }
}
