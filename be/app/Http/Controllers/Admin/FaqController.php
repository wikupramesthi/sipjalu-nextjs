<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\Faq;
use App\Services\HtmlSanitizer;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    use HandlesTransactions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $items = Faq::orderBy('urutan', 'ASC')
            ->when($search, function ($query) use ($search) {
                $query->where('pertanyaan', 'like', "%{$search}%");
            })
            ->paginate(10)->withQueryString();
        $stats = [
            'total'    => Faq::count(),
            'active'   => Faq::where('status', 'active')->count(),
            'inactive' => Faq::where('status', 'inactive')->count(),
        ];
        return view('pages.faq.index', [
            'title' => 'FAQ & Answer',
            'items' => $items,
            'stats' => $stats,
            'search' => $search,
        ]);
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
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string|max:20000',
            'kategori'   => 'nullable|in:tentang-agenda,jadwal,lokasi,publikasi,lainnya',
            'status' => 'required|in:active,inactive',
            'urutan' => 'required|integer|min:1|unique:faqs,urutan',
        ]);

        return $this->transactional(function () use ($request) {
            Faq::create([
                'uuid' => (string) Str::uuid(),
                'pertanyaan'  => $request->pertanyaan,
                'jawaban'     => HtmlSanitizer::clean($request->jawaban),
                'kategori'    => $request->kategori,
                'status'      => $request->status,
                'urutan'      => $request->urutan,
            ]);
        }, 'Faq dan Answer berhasil ditambahkan.');
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
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string|max:20000',
            'kategori'   => 'nullable|in:tentang-agenda,jadwal,lokasi,publikasi,lainnya',
            'urutan'     => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        return $this->transactional(function () use ($uuid, $request) {
            $item = Faq::where('uuid', $uuid)->firstOrFail();
            $item->update([
                'pertanyaan' => $request->pertanyaan,
                'jawaban'    => HtmlSanitizer::clean($request->jawaban),
                'kategori'   => $request->kategori,
                'urutan'     => $request->urutan,
                'status'     => $request->status,
            ]);
        }, 'Faq dan Answer berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        return $this->transactional(function () use ($uuid) {
            $item = Faq::where('uuid', $uuid)->firstOrFail();
            $item->delete();
        }, 'Faq dan Answer berhasil dihapus.');
    }

    /**
     * Remove the selected resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada FAQ yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        return $this->transactional(function () use ($ids) {
            Faq::whereIn('uuid', $ids)->delete();
        }, count($ids) . ' FAQ berhasil dihapus.');
    }

    public function kontak(Request $request)
    {
        $search = $request->input('search');
        $kontaks = Kontak::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
        ->latest()->paginate(10)->withQueryString();
        return view('pages.kontak.index', compact('kontaks', 'search'));
    }

    public function forceDelete($uuid)
    {
        try {
            $item = Kontak::where('uuid', $uuid)->firstOrFail();

            $item->delete();

            return redirect()
                ->back()
                ->with('success', 'Message permanently deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pesan masuk.');
        }
    }
}
