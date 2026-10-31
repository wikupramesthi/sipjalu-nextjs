<?php

namespace App\Http\Controllers\ManagementAccess;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\ManagementAccess\StoreRoleRequest;
use App\Http\Requests\ManagementAccess\UpdateRoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::query()
            ->when(! blank($request->search), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('guard_name', 'like', '%' . $request->search . '%');
            })
            ->with('permissions', function ($query) {
                return $query->select('id', 'name');
            })
            ->orderBy('name')
            ->paginate(10);

        $users = User::query()
            ->when(! blank($request->search), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->with('roles', function ($query) {
                return $query->select('name');
            })
            ->latest()
            ->paginate(10);
        // $permissions = Permission::orderBy('name')->get();

        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::select('name', 'id')->groupBy('name')->get();

        $permissions = array();

        foreach ($role_permission as $per) {

            $key = substr($per->name, 0, strpos($per->name, "."));

            if (str_starts_with($per->name, $key)) {

                $permissions[$key][] = $per;
            }

        }

        // dd($permission);

        return view('management-access.role.index', compact('roles', 'users', 'permissions'));
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
    public function store(StoreRoleRequest $request)
    {
        Role::create($request->validated())
                ?->givePermissionTo(! blank($request->permissions) ? $request->permissions : array());

        return back()->with('success', 'Role has been created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated())
            && $role->syncPermissions(! blank($request->permissions) ? $request->permissions : array());

        return back()->with('success', 'Role has been updated successfully!');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('success', 'Role has been deleted successfully!');

    }
}
