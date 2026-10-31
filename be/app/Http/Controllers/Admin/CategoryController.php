<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HandlesTransactions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::withCount('agendas')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10)->withQueryString();
        $categoryCounts = $categories;
        return view('pages.categories.index', compact('categories', 'categoryCounts', 'search'));
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:2000',
            'icon' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\-\s]+$/i'],
        ]);

        return $this->transactional(function () use ($request) {
            Category::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'icon' => $request->icon,
            ]);
        }, 'Category added successfully.');
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
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $uuid . ',uuid',
            'description' => 'nullable|string|max:2000',
            'icon' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\-\s]+$/i'],
        ]);

        return $this->transactional(function () use ($uuid, $request) {
            $item = Category::where('uuid', $uuid)->firstOrFail();
            $item->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'icon' => $request->icon,
            ]);
        }, 'Category updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        return $this->transactional(function () use ($uuid) {
            $item = Category::where('uuid', $uuid)->firstOrFail();
            $item->delete();
        }, 'Category deleted successfully.');
    }
}
