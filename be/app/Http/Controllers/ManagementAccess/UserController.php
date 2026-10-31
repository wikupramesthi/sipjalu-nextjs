<?php

namespace App\Http\Controllers\ManagementAccess;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementAccess\StoreUserRequest;
use App\Http\Requests\ManagementAccess\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('roles:id,name')
            ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $role = $request->input('role');
                if ($role === '__none') {
                    $query->whereDoesntHave('roles');
                } else {
                    $query->whereHas('roles', function ($q) use ($role) {
                        $q->where('name', $role);
                    });
                }
            })
            ->latest()
            ->get();

        $roles = Role::orderBy('name')->get();

        return view('management-access.user.index', compact('users', 'roles'));
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
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->forceFill([
            'email_verified_at' => ! empty($validated['verified']) ? now() : null,
        ])->save();

        $user->assignRole($validated['role'] ?? []);

        return back()->with('success', 'User has been created successfully!');
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
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->syncRoles($validated['role'] ?? []);

        $user->forceFill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => ! empty($validated['verified']) ? now() : null,
        ])->save();

        return back()->with('success', 'User has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User has been deleted successfully!');
    }
}
