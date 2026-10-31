<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ServiceManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private ServiceManager $services) {}

    public function index(Request $request): View
    {
        $baseQuery = Service::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'));

        // Statistik dari query yang SUDAH difilter (tanpa paginasi).
        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'active'   => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
            'external' => (clone $baseQuery)->where('category', 'external')->count(),
            'internal' => (clone $baseQuery)->where('category', 'internal')->count(),
        ];

        $services = $baseQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.services.index', compact('services', 'stats'));
    }

    public function create(): View
    {
        return view('pages.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'in:external,internal,other'],
            'url'         => ['nullable', 'url', 'max:255'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        try {
            $validated['is_active'] = $request->boolean('is_active');
            $this->services->create($validated, $request->file('image'));
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan layanan: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', 'Gagal menyimpan layanan. Silakan coba lagi.');
        }

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('pages.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'category'    => ['required', 'in:external,internal,other'],
            'url'         => ['nullable', 'url', 'max:255'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        try {
            $validated['is_active'] = $request->boolean('is_active');
            $this->services->update(
                $service,
                $validated,
                $request->file('image'),
                $request->boolean('remove_image')
            );
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui layanan: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', 'Gagal memperbarui layanan. Silakan coba lagi.');
        }

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        try {
            $this->services->delete($service);
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus layanan: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Gagal menghapus layanan. Silakan coba lagi.');
        }

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada layanan yang dipilih.');
        }

        try {
            $deleted = $this->services->bulkDelete($ids);
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus layanan terpilih: ' . $e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Gagal menghapus layanan terpilih. Silakan coba lagi.');
        }

        if ($deleted === 0) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        }

        return back()->with('success', $deleted . ' layanan berhasil dihapus.');
    }
}
