<?php

namespace App\Http\Controllers\ManagementAccess;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManagementAccess\Route;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route as FacadesRoute;
use App\Http\Requests\ManagementAccess\StoreRouteRequest;
use App\Http\Requests\ManagementAccess\UpdateRouteRequest;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::orderBy('route')->get();

        $facadesRoutes = FacadesRoute::getRoutes();

        $permissions = Permission::orderBy('name')->get();

        return view(
            'management-access.route.index',
            compact('routes', 'permissions', 'facadesRoutes')
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
    public function store(StoreRouteRequest $request)
    {
        $validated = $request->validated();
        Route::create(array_merge(
            $validated,
            array('status' => ! blank($validated['status'] ?? null) ? true : false)
        ));

        return back()->with('success', 'Route has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRouteRequest $request, Route $route)
    {
        $validated = $request->validated();
        $route->update(array_merge(
            $validated,
            array('status' => ! blank($validated['status'] ?? null) ? true : false)
        ));

        return back()->with('success', 'Route has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return back()->with('success', 'Route has been deleted successfully!');
    }
}
