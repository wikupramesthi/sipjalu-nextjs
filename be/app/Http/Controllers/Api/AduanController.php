<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AduanResource;
use App\Http\Resources\PublicAduanResource;
use App\Models\Aduan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AduanController extends Controller
{
    /**
     * Daftar aduan (publik: tanpa sifat rahasia & tanpa arsip).
     * Query: ?search= &status= &kategori= &page= &per_page=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|in:menunggu,diverifikasi,diproses,selesai,ditolak',
                'kategori' => 'nullable|string|max:100',
                'per_page' => 'nullable|integer|min:1|max:50',
            ]);

            // Publik: jangan eager-load relasi user/petugas (bocor nama & PII).
            $query = Aduan::with(['kecamatan', 'kelurahan', 'tindakLanjuts'])
                ->belumArsip()
                ->where('sifat', '!=', 'rahasia')
                ->orderByDesc('tanggal_pengaduan');

            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_aduan', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('kategori')) {
                $query->where('kategori', 'like', '%' . $request->input('kategori') . '%');
            }

            $aduan = $query->paginate($request->input('per_page', 10));

            return response()->json([
                'status' => 'success',
                'message' => $aduan->isEmpty() ? 'Belum ada aduan' : 'Data aduan berhasil diambil',
                'data' => PublicAduanResource::collection($aduan->items()),
                'meta' => [
                    'current_page' => $aduan->currentPage(),
                    'per_page' => $aduan->perPage(),
                    'total' => $aduan->total(),
                    'last_page' => $aduan->lastPage(),
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors(),
                'data' => [],
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Aduan index error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data aduan',
                'data' => [],
            ], 500);
        }
    }

    /**
     * Kirim aduan baru (wajib login, user_uuid diisi otomatis).
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'kategori' => 'required|string|in:' . implode(',', Aduan::KATEGORI),
                'judul' => 'required|string|max:255',
                'isi_aduan' => 'required|string|max:5000',
                'lokasi' => 'nullable|string|max:255',
                'kecamatan_id' => 'nullable|exists:kecamatans,id',
                'kelurahan_id' => 'nullable|exists:kelurahans,id',
                'foto_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'foto_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'foto_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'tanggal_kejadian' => 'nullable|date|before_or_equal:today',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_anonim' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            $data = $validated;
            $data['uuid'] = (string) Str::uuid();
            $data['nomor_aduan'] = Aduan::generateNomorAduan();
            $data['tanggal_pengaduan'] = now();
            $data['status'] = 'menunggu';
            $data['prioritas'] = 'sedang';
            $data['sifat'] = 'biasa';

            if ($request->user()) {
                $data['user_uuid'] = $request->user()->uuid;
            }

            foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('aduans', 'public');
                }
            }

            $aduan = Aduan::create($data);

            DB::commit();

            $aduan->loadMissing(['user', 'kecamatan', 'kelurahan', 'tindakLanjuts.user']);

            return response()->json([
                'status' => 'success',
                'message' => 'Aduan berhasil dikirim. Simpan nomor aduan Anda: ' . $aduan->nomor_aduan,
                'data' => new AduanResource($aduan),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Aduan store error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim aduan',
            ], 500);
        }
    }

    /**
     * Lacak aduan berdasarkan nomor_aduan (format ADU-YYYYMMDD-NNNN).
     * Respons publik: tanpa nama pelapor, koordinat, foto, dan nama petugas.
     */
    public function track(string $nomor_aduan): JsonResponse
    {
        // Tolak format di luar pola nomor resmi (anti-enumerasi/probing).
        if (! preg_match('/^ADU-\d{8}-\d{4}$/', $nomor_aduan)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor aduan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $aduan = Aduan::with(['kecamatan', 'kelurahan', 'tindakLanjuts'])
            ->belumArsip()
            ->where('nomor_aduan', $nomor_aduan)
            ->where('sifat', '!=', 'rahasia')
            ->first();

        if (!$aduan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor aduan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail aduan ditemukan',
            'data' => new PublicAduanResource($aduan),
        ]);
    }
}
