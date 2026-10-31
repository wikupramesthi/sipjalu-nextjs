<?php

namespace App\Http\Controllers\ManagementAccess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementAccess\StoreMenuGroupRequest;
use App\Http\Requests\ManagementAccess\UpdateMenuGroupRequest;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use App\Models\ManagementAccess\MenuGroup;

class MenuGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuGroups = MenuGroup::orderBy('name')->get();

        $permissions = Permission::orderBy('name')->get();

        return view(
            'management-access.menu-group.index',
            compact(
                'menuGroups',
                'permissions'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuGroupRequest $request)
    {
        $validated = $request->validated();
        MenuGroup::create(array_merge(
            $validated,
            array(
                'status' => ! blank($validated['status'] ?? null) ? true : false,
                'position' => MenuGroup::max('position') + 1
            ),
        ));
        Cache::flush();
        return back()->with('success', 'Menu has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuGroup $menuGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuGroup $menuGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuGroupRequest $request, MenuGroup $menuGroup, $id)
    {
        $data = $request->validated();
        $data['status'] = ! blank($data['status'] ?? null) ? true : false;
        $findId = $menuGroup->find($id);
        $findId->update($data);
        Cache::flush();

        return back()->with('success', 'Menu has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $decryptID = decrypt($id);
        $menu = MenuGroup::findOrFail($decryptID);
        $menu->delete();
        Cache::flush();

        return back()->with('Sukses', 'Data berhasil dihapus');
    }
}
