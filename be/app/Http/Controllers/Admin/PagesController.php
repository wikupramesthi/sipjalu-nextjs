<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\Page;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class PagesController extends Controller
{
    
    use HandlesTransactions;
/**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $pages = Page::orderBy('created_at', 'desc')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->paginate(10)->withQueryString();
        return view('pages.halaman.index', compact('pages', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.halaman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'excerpt'         => 'nullable|string',
            'content'         => 'required|string',
            'featured_image'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published'    => 'required|in:0,1',
            'published_at'    => 'required|date',
            'has_sidebar'     => 'required|in:0,1',
        ]);

        $path = null;
        try {
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('pages', 'public');
            }
            \Illuminate\Support\Facades\DB::beginTransaction();
            Page::create([
                'uuid'           => Str::uuid(),
                'title'          => $request->title,
                'slug'           => Str::slug($request->title),
                'excerpt'        => $request->excerpt,
                'content'        => HtmlSanitizer::clean($request->content),
                'featured_image' => $path,
                'is_published'   => $request->is_published,
                'published_at'   => $request->published_at ?? now(),
                'user_uuid'      => auth()->user()->uuid ?? null,
                'has_sidebar'    => $request->has_sidebar,
            ]);
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('pages.index')->with('success', 'Page added successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateSidebar($uuid)
    {
        $page = Page::where('uuid', $uuid)->firstOrFail();
        $page->update([
            'has_sidebar' => !$page->has_sidebar
        ]);

        return redirect()->route('pages.index')->with('success', 'Sidebar status updated successfully.');
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
    public function edit(string $uuid)
    {
        $page = Page::where('uuid', $uuid)->firstOrFail();
        return view('pages.halaman.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'excerpt'        => 'required|string|max:500',
            'content'        => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published'   => 'required|in:0,1',
            'has_sidebar'    => 'required|in:0,1',
            'published_at'   => 'required|date',
        ]);

        $page = Page::where('uuid', $uuid)->firstOrFail();
        $oldPath = $page->featured_image;
        $newPath = null;
        $path = $oldPath;
        try {
            if ($request->hasFile('featured_image')) {
                $newPath = $request->file('featured_image')->store('pages', 'public');
                $path = $newPath;
            }
            \Illuminate\Support\Facades\DB::beginTransaction();
            $page->update([
                'title'          => $request->title,
                'slug'           => Str::slug($request->title),
                'excerpt'        => $request->excerpt,
                'content'        => HtmlSanitizer::clean($request->content),
                'featured_image' => $path,
                'is_published'   => $request->is_published,
                'has_sidebar'    => $request->has_sidebar,
                'published_at'   => $request->published_at,
            ]);
            \Illuminate\Support\Facades\DB::commit();
            if ($newPath && $oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        return $this->transactional(function () use ($uuid) {
            $page = Page::where('uuid', $uuid)->firstOrFail();
            if ($page->featured_image && Storage::disk('public')->exists($page->featured_image)) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $page->delete();
        }, 'Page deleted successfully.');
    }
}
