<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Models\Aduan;
use App\Models\AduanTindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AduanTindakLanjutController extends Controller
{
    
    use HandlesTransactions;
/**
     * Tambah tindak lanjut + opsional ubah status aduan induk.
     */
    public function store(Request $request, $aduanUuid)
    {
        // Hanya petugas yang boleh menambah tindak lanjut / mengubah status.
        abort_unless($request->user()->hasAnyRole(['super-admin', 'admin', 'uptd']), 403);

        $aduan = Aduan::where('uuid', $aduanUuid)->firstOrFail();

        $request->validate([
            'status' => 'nullable|in:menunggu,diverifikasi,diproses,selesai,ditolak',
            'catatan' => 'required|string|max:5000',
            'foto_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tanggal' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->only(['status', 'catatan', 'tanggal']);
            $data['uuid'] = (string) Str::uuid();
            $data['aduan_uuid'] = $aduan->uuid;
            $data['user_uuid'] = $request->user()->uuid;
            $data['tanggal'] = $request->tanggal ?? now();

            foreach (['foto_1', 'foto_2'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store('aduan-tindak-lanjut', 'public');
                }
            }

            AduanTindakLanjut::create($data);

            $pesan = 'Tindak lanjut berhasil ditambahkan.';

            if ($request->filled('status') && $request->status !== $aduan->status) {
                $aduan->update(['status' => $request->status]);
                $pesan .= ' Status aduan diubah menjadi "' . ucfirst($request->status) . '".';
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', $pesan);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Gagal tambah tindak lanjut: ' . $th->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan tindak lanjut. Silakan coba lagi.');
        }
    }

    /**
     * Hapus satu tindak lanjut beserta fotonya (status induk tidak dikembalikan).
     */
    public function destroy(Request $request, $uuid)
    {
        abort_unless($request->user()->hasAnyRole(['super-admin', 'admin', 'uptd']), 403);

        $item = AduanTindakLanjut::where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();

        try {
            foreach (['foto_1', 'foto_2'] as $field) {
                if ($item->{$field} && Storage::disk('public')->exists($item->{$field})) {
                    Storage::disk('public')->delete($item->{$field});
                }
            }

            $item->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Tindak lanjut berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Gagal hapus tindak lanjut: ' . $th->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus tindak lanjut. Silakan coba lagi.');
        }
    }
}
