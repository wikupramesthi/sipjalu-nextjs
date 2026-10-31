<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\Aduan;
use App\Models\AduanTindakLanjut;
use App\Models\AuditLog;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\User;
use App\Notifications\AduanBaruNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AduanController extends Controller
{
    
    use HandlesTransactions;
/**
     * Petugas = boleh melihat & mengelola semua aduan.
     * Selain itu (warga/role user) hanya boleh mengelola aduan milik sendiri.
     */
    protected function isPetugas(Request $request): bool
    {
        return $request->user()->hasAnyRole(['super-admin', 'admin', 'uptd']);
    }

    /**
     * Ambil satu aduan dengan pembatasan kepemilikan untuk warga.
     */
    protected function findAduan(Request $request, string $uuid, string $scope = 'aktif'): Aduan
    {
        $query = match ($scope) {
            'sampah' => Aduan::onlyTrashed(),
            'semua' => Aduan::withTrashed(),
            default => Aduan::query(),
        };

        if (!$this->isPetugas($request)) {
            $query->where('user_uuid', $request->user()->uuid);
        }

        return $query->with(['user', 'kecamatan', 'kelurahan', 'tindakLanjuts.user'])
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    /**
     * Warga hanya boleh mengubah/membatalkan aduan yang masih menunggu.
     */
    protected function pastikanBisaUbah(Request $request, Aduan $item): ?RedirectResponse
    {
        if ($this->isPetugas($request)) {
            return null;
        }

        if ($item->status !== 'menunggu') {
            return redirect()
                ->back()
                ->with('error', 'Pengaduan yang sudah "' . ucfirst($item->status) . '" tidak dapat diubah/dibatalkan. Hubungi petugas untuk bantuan.');
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tampil = in_array($request->get('tampil'), ['sampah', 'arsip'], true) ? $request->get('tampil') : 'aktif';

        $query = $this->filteredQuery($request, $tampil);

        $items = $query
            ->orderByDesc($tampil === 'sampah' ? 'deleted_at' : ($tampil === 'arsip' ? 'archived_at' : 'tanggal_pengaduan'))
            ->paginate(10)
            ->withQueryString();

        $kecamatans = Kecamatan::orderBy('nama')->get();

        $trashQuery = Aduan::onlyTrashed();
        if (!$this->isPetugas($request)) {
            $trashQuery->where('user_uuid', $request->user()->uuid);
        }
        $trashCount = $trashQuery->count();

        $arsipQuery = Aduan::arsip();
        if (!$this->isPetugas($request)) {
            $arsipQuery->where('user_uuid', $request->user()->uuid);
        }
        $arsipCount = $arsipQuery->count();

        // Statistik mengikuti filter & tab yang sedang aktif (tanpa paginasi).
        $statsQuery = $this->filteredQuery($request, $tampil);
        $stats = ['total' => (clone $statsQuery)->count()];
        foreach (['menunggu', 'diverifikasi', 'diproses', 'selesai', 'ditolak'] as $st) {
            $stats[$st] = (clone $statsQuery)->where('status', $st)->count();
        }

        return view('pages.aduan.index', [
            'title' => 'Aduan',
            'items' => $items,
            'kecamatans' => $kecamatans,
            'tampil' => $tampil,
            'trashCount' => $trashCount,
            'arsipCount' => $arsipCount,
            'stats' => $stats,
        ]);
    }

    public function gis(Request $request)
    {
        $query = $this->filteredQuery($request, 'aktif')
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori', $request->input('kategori')))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('kecamatan_id'), fn($q) => $q->where('kecamatan_id', $request->input('kecamatan_id')))
            ->latest('tanggal_pengaduan')
            ->limit(500);

        $items = $query->get();
        $aduanGeo = $items->map(fn($a) => [
            'uuid' => $a->uuid,
            'nomor_aduan' => $a->nomor_aduan,
            'judul' => $a->judul,
            'kategori' => $a->kategori,
            'status' => $a->status,
            'lokasi' => $a->lokasi,
            'kecamatan' => $a->kecamatan->nama ?? null,
            'kelurahan' => $a->kelurahan->nama ?? null,
            'latitude' => (float) $a->latitude,
            'longitude' => (float) $a->longitude,
            'tanggal_pengaduan' => $a->tanggal_pengaduan?->format('d/m/Y'),
        ]);
        $kecamatans = \App\Models\Kecamatan::orderBy('nama')->get();
        $aduanCount = $items->count();
        $perStatus = $items->groupBy('status')->map->count();
        $periodeLabel = $request->filled('kategori') || $request->filled('status') ? 'Filter aktif' : 'Semua aduan berkoordinat';

        return view('pages.gis.index', compact('aduanGeo', 'kecamatans', 'aduanCount', 'perStatus', 'periodeLabel'));
    }

    public function gisExport(Request $request)
    {
        $items = $this->filteredQuery($request, 'aktif')
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori', $request->input('kategori')))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('kecamatan_id'), fn($q) => $q->where('kecamatan_id', $request->input('kecamatan_id')))
            ->latest('tanggal_pengaduan')->limit(500)->get();

        $features = $items->map(fn($a) => [
            'type' => 'Feature',
            'geometry' => ['type' => 'Point', 'coordinates' => [(float)$a->longitude, (float)$a->latitude]],
            'properties' => [
                'uuid' => $a->uuid,
                'nomor' => $a->nomor_aduan,
                'judul' => $a->judul,
                'kategori' => $a->kategori,
                'status' => $a->status,
                'lokasi' => $a->lokasi,
                'tanggal' => $a->tanggal_pengaduan?->toDateString(),
            ],
        ]);

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
            'meta' => ['count' => $features->count(), 'exported_at' => now()->toISOString()],
        ], 200, ['Content-Disposition' => 'attachment; filename="gis-aduan-' . now()->format('Ymd') . '.geojson"']);
    }

    /**
     * Query aduan dengan filter yang sama seperti halaman index
     * (dipakai index & unduhan laporan).
     */
    protected function filteredQuery(Request $request, string $tampil = 'aktif')
    {
        $query = $tampil === 'sampah'
            ? Aduan::onlyTrashed()->with(['user', 'kecamatan', 'kelurahan'])->withCount('tindakLanjuts')
            : Aduan::with(['user', 'kecamatan', 'kelurahan'])->withCount('tindakLanjuts');

        if ($tampil === 'arsip') {
            $query->arsip();
        } elseif ($tampil === 'aktif') {
            $query->belumArsip();
        }
        // sampah: tampilkan semua yang terhapus (termasuk yang terarsip)

        // Warga hanya melihat aduan milik sendiri
        if (!$this->isPetugas($request)) {
            $query->where('user_uuid', $request->user()->uuid);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_aduan', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%')
                    ->orWhere('isi_aduan', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', 'like', '%' . $request->kategori . '%');
        }

        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pengaduan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pengaduan', '<=', $request->tanggal_selesai);
        }

        return $query;
    }

    /**
     * Unduh laporan PDF (mengikuti filter yang sedang aktif).
     */
    public function exportPdf(Request $request)
    {
        $tampil = in_array($request->get('tampil'), ['sampah', 'arsip'], true) ? $request->get('tampil') : 'aktif';

        $items = $this->filteredQuery($request, $tampil)
            ->orderByDesc($tampil === 'sampah' ? 'deleted_at' : ($tampil === 'arsip' ? 'archived_at' : 'tanggal_pengaduan'))
            ->limit(1000)
            ->get();

        $total = $items->count();
        $perStatus = $items->groupBy('status')->map->count();
        $selesai = $perStatus->get('selesai', 0);
        $persenSelesai = $total > 0 ? round($selesai / $total * 100, 1) : 0;

        $perKategori = Aduan::KATEGORI;
        $rekapKategori = [];
        foreach ($perKategori as $kat) {
            $milik = $items->where('kategori', $kat);
            $rekapKategori[] = [
                'nama' => $kat,
                'total' => $milik->count(),
                'selesai' => $milik->where('status', 'selesai')->count(),
            ];
        }
        $maxKategori = max(array_merge([1], array_column($rekapKategori, 'total')));

        $rekapKecamatan = $items->groupBy(fn ($a) => $a->kecamatan->nama ?? 'Luar Wilayah / Tanpa Kecamatan')
            ->map(fn ($g) => ['nama' => $g->first()->kecamatan->nama ?? 'Luar Wilayah / Tanpa Kecamatan', 'total' => $g->count()])
            ->sortByDesc('total')
            ->values();
        $maxKecamatan = max([1, $rekapKecamatan->max('total') ?? 0]);

        $statusList = ['menunggu', 'diverifikasi', 'diproses', 'selesai', 'ditolak'];
        $maxStatus = max([1, ...array_map(fn ($s) => $perStatus->get($s, 0), $statusList)]);

        if ($request->filled('tanggal_mulai') || $request->filled('tanggal_selesai')) {
            $periode = ($request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai)->format('d M Y') : 'Awal')
                . ' – '
                . ($request->tanggal_selesai ? Carbon::parse($request->tanggal_selesai)->format('d M Y') : 'Sekarang');
        } else {
            $periode = 'Keseluruhan';
        }

        $pdf = Pdf::loadView('pages.aduan.laporan', [
            'items' => $items,
            'total' => $total,
            'perStatus' => $perStatus,
            'selesai' => $selesai,
            'persenSelesai' => $persenSelesai,
            'rekapKategori' => $rekapKategori,
            'maxKategori' => $maxKategori,
            'rekapKecamatan' => $rekapKecamatan,
            'maxKecamatan' => $maxKecamatan,
            'statusList' => $statusList,
            'maxStatus' => $maxStatus,
            'periode' => $periode,
            'tampil' => $tampil,
            'dicetakOleh' => $request->user()->name,
            'waktuCetak' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengaduan-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.aduan.create', [
            'title' => 'Tambah Aduan',
            'kecamatans' => Kecamatan::orderBy('nama')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid);

        $kelurahans = $item->kecamatan_id
            ? Kelurahan::where('kecamatan_id', $item->kecamatan_id)->orderBy('nama')->get()
            : collect();

        return view('pages.aduan.edit', [
            'title' => 'Edit Aduan ' . $item->nomor_aduan,
            'item' => $item,
            'kecamatans' => Kecamatan::orderBy('nama')->get(),
            'kelurahans' => $kelurahans,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid, 'semua');

        // Jejak audit: perubahan aduan ini + tindak lanjutnya (sudah direkam otomatis).
        $tlUuids = $item->tindakLanjuts()->pluck('uuid');
        $jejak = AuditLog::query()
            ->where(function ($q) use ($item, $tlUuids) {
                $q->where(function ($q2) use ($item) {
                    $q2->where('auditable_type', Aduan::class)
                        ->where('auditable_id', $item->uuid);
                })->orWhere(function ($q2) use ($tlUuids) {
                    $q2->where('auditable_type', AduanTindakLanjut::class)
                        ->whereIn('auditable_id', $tlUuids);
                });
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('pages.aduan.show', [
            'title' => 'Detail Aduan ' . $item->nomor_aduan,
            'item' => $item,
            'jejak' => $jejak,
        ]);
    }

    /**
     * Ajax: daftar kelurahan per kecamatan (untuk form cascading).
     */
    public function getKelurahan($kecamatan_id)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama')
            ->pluck('nama', 'id');

        return response()->json($kelurahans);
    }

    /**
     * Ajax: cek aduan sejenis (peringatan duplikat) saat mengisi form.
     */
    public function cekDuplikat(Request $request)
    {
        $request->validate([
            'kategori' => 'nullable|string|max:100',
            // NOTE: kandidat duplikat di bawah dibatasi milik sendiri untuk non-petugas (anti-intip).
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|integer',
            'tanggal_kejadian' => 'nullable|date',
            'kecuali_uuid' => 'nullable|string',
        ]);

        $mirip = Aduan::cariDuplikat(
            $request->kategori,
            $request->latitude,
            $request->longitude,
            $request->kecamatan_id,
            $request->tanggal_kejadian,
            $request->kecuali_uuid
        );

        // Non-petugas hanya boleh melihat kandidat miliknya sendiri.
        if (! $request->user()->hasAnyRole(['super-admin', 'admin', 'uptd'])) {
            $mirip = $mirip->where('user_uuid', $request->user()->uuid)->values();
        }

        return response()->json([
            'data' => $mirip->map(fn (Aduan $a) => [
                'nomor_aduan' => $a->nomor_aduan,
                'judul' => $a->judul,
                'status' => $a->status,
                'tanggal' => $a->tanggal_pengaduan?->format('d M Y'),
                'url' => route('aduans.show', $a->uuid),
            ])->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hanya super-admin & admin yang boleh mengatur status/prioritas/sifat.
        $bolehAturPenanganan = $request->user()->hasAnyRole(['super-admin', 'admin']);

        $rules = [
            'kategori' => 'required|string|in:' . implode(',', Aduan::KATEGORI),
            'judul' => 'required|string|max:255',
            'isi_aduan' => 'required|string|max:10000',
            'lokasi' => 'nullable|string|max:255',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'foto_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tanggal_kejadian' => 'nullable|date',
            'tanggal_pengaduan' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'user_uuid' => 'nullable|exists:users,uuid',
            'is_anonim' => 'nullable|boolean',
        ];

        if ($bolehAturPenanganan) {
            $rules['status'] = 'required|in:menunggu,diverifikasi,diproses,selesai,ditolak';
            $rules['prioritas'] = 'required|in:rendah,sedang,tinggi,darurat';
            $rules['sifat'] = 'required|in:biasa,penting,segera,rahasia';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $data = $request->only([
                'kategori',
                'judul',
                'isi_aduan',
                'lokasi',
                'kecamatan_id',
                'kelurahan_id',
                'tanggal_kejadian',
                'tanggal_pengaduan',
                'latitude',
                'longitude',
                'user_uuid',
            ]);

            if ($bolehAturPenanganan) {
                $data += $request->only(['status', 'prioritas', 'sifat']);
            } else {
                $data += ['status' => 'menunggu', 'prioritas' => 'sedang', 'sifat' => 'biasa'];
            }

            $data['is_anonim'] = $request->boolean('is_anonim');

            $data['uuid'] = (string) Str::uuid();
            $data['nomor_aduan'] = Aduan::generateNomorAduan();
            $data['tanggal_pengaduan'] = $request->tanggal_pengaduan ?? now();

            // Warga selalu tercatat sebagai pelapor dirinya sendiri
            if (!$this->isPetugas($request)) {
                $data['user_uuid'] = $request->user()->uuid;
            } elseif (empty($data['user_uuid']) && auth()->check()) {
                $data['user_uuid'] = auth()->user()->uuid;
            }

            foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('aduans', 'public');
                }
            }

            Aduan::create($data);

            DB::commit();

            // Beri tahu petugas lain (bukan pembuatnya)
            try {
                $petugas = User::role(['super-admin', 'admin', 'uptd'])
                    ->where('uuid', '!=', $request->user()->uuid)
                    ->get();

                foreach ($petugas as $orang) {
                    $orang->notify(new AduanBaruNotification(
                        $data['nomor_aduan'],
                        $data['judul'],
                        $request->user()->name,
                        $data['uuid']
                    ));
                }
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()
                ->route('aduans.index')
                ->with('success', 'Aduan berhasil ditambahkan dengan nomor ' . $data['nomor_aduan'] . '.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid);

        if ($blocked = $this->pastikanBisaUbah($request, $item)) {
            return $blocked;
        }

        $bolehAturPenanganan = $request->user()->hasAnyRole(['super-admin', 'admin']);

        $rules = [
            'kategori' => 'required|string|in:' . implode(',', Aduan::KATEGORI),
            'judul' => 'required|string|max:255',
            'isi_aduan' => 'required|string|max:10000',
            'lokasi' => 'nullable|string|max:255',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'foto_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto_1' => 'nullable|boolean',
            'hapus_foto_2' => 'nullable|boolean',
            'hapus_foto_3' => 'nullable|boolean',
            'tanggal_kejadian' => 'nullable|date',
            'tanggal_pengaduan' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_anonim' => 'nullable|boolean',
        ];

        if ($bolehAturPenanganan) {
            $rules['status'] = 'required|in:menunggu,diverifikasi,diproses,selesai,ditolak';
            $rules['prioritas'] = 'required|in:rendah,sedang,tinggi,darurat';
            $rules['sifat'] = 'required|in:biasa,penting,segera,rahasia';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $data = $request->only([
                'kategori',
                'judul',
                'isi_aduan',
                'lokasi',
                'kecamatan_id',
                'kelurahan_id',
                'tanggal_kejadian',
                'tanggal_pengaduan',
                'latitude',
                'longitude',
            ]);

            $data['is_anonim'] = $request->boolean('is_anonim');

            if ($bolehAturPenanganan) {
                $data += $request->only(['status', 'prioritas', 'sifat']);
            }

            foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
                $hapusFlag = 'hapus_' . $field;

                // Hapus foto via checkbox
                if ($request->boolean($hapusFlag) && $item->{$field}) {
                    if (Storage::disk('public')->exists($item->{$field})) {
                        Storage::disk('public')->delete($item->{$field});
                    }
                    $data[$field] = null;
                }

                // Ganti dengan upload baru
                if ($request->hasFile($field)) {
                    if ($item->{$field} && Storage::disk('public')->exists($item->{$field})) {
                        Storage::disk('public')->delete($item->{$field});
                    }
                    $data[$field] = $request->file($field)->store('aduans', 'public');
                }
            }

            $item->update($data);

            DB::commit();

            return redirect()
                ->route('aduans.index')
                ->with('success', 'Aduan ' . $item->nomor_aduan . ' berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Arsipkan aduan secara manual (tidak tampil di daftar aktif).
     */
    public function arsip(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid, 'semua');

        if ($item->trashed()) {
            return redirect()->back()->with('error', 'Data yang ada di sampah tidak bisa diarsipkan.');
        }

        $item->update(['archived_at' => now()]);

        return redirect()
            ->route('aduans.index')
            ->with('success', 'Aduan ' . $item->nomor_aduan . ' diarsipkan.');
    }

    /**
     * Kembalikan aduan dari arsip ke daftar aktif.
     */
    public function batalArsip(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid, 'semua');

        $item->update(['archived_at' => null]);

        return redirect()
            ->route('aduans.index')
            ->with('success', 'Aduan ' . $item->nomor_aduan . ' dikembalikan ke daftar aktif.');
    }

    /**
     * Beri/ubah penilaian oleh pemilik aduan (hanya bila sudah selesai).
     */
    public function rate(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid, 'semua');

        if ($item->user_uuid !== $request->user()->uuid) {
            abort(403, 'Hanya pemilik aduan yang boleh memberi penilaian.');
        }

        if ($item->status !== 'selesai') {
            return redirect()
                ->back()
                ->with('error', 'Penilaian hanya bisa diberikan setelah aduan selesai ditangani.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        $item->update([
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
            'rated_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Terima kasih atas penilaian Anda!');
    }

    /**
     * Soft delete: pindahkan ke tempat sampah (foto tetap disimpan).
     */
    public function destroy(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid);

        if ($blocked = $this->pastikanBisaUbah($request, $item)) {
            return $blocked;
        }

        $item->delete();

        return redirect()
            ->back()
            ->with('success', 'Aduan ' . $item->nomor_aduan . ' dipindahkan ke tempat sampah.');
    }

    /**
     * Pindahkan aduan terpilih ke tempat sampah (soft delete).
     * Warga hanya bisa memindahkan aduan milik sendiri yang masih menunggu.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada aduan yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);
        $isPetugas = $this->isPetugas($request);

        $query = Aduan::query();
        if (! $isPetugas) {
            $query->where('user_uuid', $request->user()->uuid);
        }
        $items = $query->whereIn('uuid', $ids)->get();

        $trashed = 0;
        $skipped = 0;
        foreach ($items as $item) {
            if (! $isPetugas && $item->status !== 'menunggu') {
                $skipped++;
                continue;
            }
            $item->delete();
            $trashed++;
        }
        $skipped += count($ids) - $items->count();

        if ($trashed === 0) {
            return back()->with('error', 'Tidak ada aduan yang dapat dipindahkan ke sampah.');
        }

        return back()->with('success', $trashed . ' aduan dipindahkan ke tempat sampah.'
            . ($skipped > 0 ? " {$skipped} dilewati (bukan milik Anda / status terkunci)." : ''));
    }

    /**
     * Kembalikan aduan terpilih dari tempat sampah.
     */
    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada aduan yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $query = Aduan::onlyTrashed();
        if (! $this->isPetugas($request)) {
            $query->where('user_uuid', $request->user()->uuid);
        }

        $restored = $query->whereIn('uuid', $ids)->restore();

        if (! $restored) {
            return back()->with('error', 'Tidak ada aduan yang dapat dikembalikan.');
        }

        return back()->with('success', $restored . ' aduan berhasil dikembalikan.');
    }

    /**
     * Kembalikan aduan dari tempat sampah.
     */
    public function restore(Request $request, $uuid)
    {
        $item = $this->findAduan($request, $uuid, 'sampah');

        $item->restore();

        return redirect()
            ->route('aduans.index')
            ->with('success', 'Aduan ' . $item->nomor_aduan . ' berhasil dikembalikan.');
    }

    /**
     * Hapus permanen beserta file fotonya.
     */
    public function forceDestroy(Request $request, $uuid)
    {
        if (!$this->isPetugas($request)) {
            abort(403, 'Hanya petugas yang dapat menghapus permanen.');
        }

        $item = $this->findAduan($request, $uuid, 'sampah');

        DB::beginTransaction();

        try {
            foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
                if ($item->{$field} && Storage::disk('public')->exists($item->{$field})) {
                    Storage::disk('public')->delete($item->{$field});
                }
            }

            $item->forceDelete();

            DB::commit();

            return redirect()
                ->route('aduans.index', ['tampil' => 'sampah'])
                ->with('success', 'Aduan dihapus permanen.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}
