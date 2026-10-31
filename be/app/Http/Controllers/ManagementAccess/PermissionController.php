<?php

namespace App\Http\Controllers\ManagementAccess;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementAccess\StorePermissionRequest;
use App\Http\Requests\ManagementAccess\UpdatePermissionRequest;
use App\Http\Requests\ManagementAccess\UpdateRouteRequest;
use Spatie\Permission\Models\Permission;



class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $permissions = Permission::query()
            ->when(! blank($request->search), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('guard_name', 'like', '%' . $request->search . '%');
            })
            ->with('roles', function ($query) {
                return $query->select('id', 'name');
            })
            ->orderBy('name')->get();

        $roles = Role::orderBy('name')->get();

        if ($roles->isEmpty()) {
            abort(403, 'No roles found.');
        }

        return view('management-access.permission.index', compact('permissions', 'roles'));
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
    public function store(StorePermissionRequest $request)
    {
        Permission::create($request->validated())
                ?->assignRole(! blank($request->roles) ? $request->roles : array());

        return back()->with('success', 'Permission has been created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated())
            && $permission->syncRoles(! blank($request->roles) ? $request->roles : array());

        return back()->with('success', 'Permission has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('success', 'Permission has been deleted successfully!');

    }
}
