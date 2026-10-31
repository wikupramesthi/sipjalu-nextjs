<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;

class WallboardController extends Controller
{
    /**
     * Layar TV pusat pantau pengaduan (read-only, refresh otomatis).
     * Akses: sudah login, cookie wallboard HttpOnly, atau ?token= sekali pakai
     * untuk klaim (langsung ditukar cookie + redirect bersih agar token tidak
     * menetap di access-log/history/Referer).
     */
    public function index(Request $request)
    {
        $token = config('wallboard.token');

        if ($request->user()) {
            $boleh = true;
        } elseif ($token && hash_equals((string) $token, (string) $request->cookie('wallboard_token'))) {
            $boleh = true;
        } elseif ($token && $request->filled('token') && hash_equals((string) $token, (string) $request->query('token'))) {
            // Tukar token query menjadi cookie HttpOnly lalu buang dari URL.
            return redirect()
                ->route('wallboard', [], 303)
                ->withCookie(cookie(
                    'wallboard_token',
                    $token,
                    60 * 24 * 30, // 30 hari untuk TV display
                    null, null,
                    (bool) config('session.secure', false),
                    true, // httpOnly
                    false,
                    config('session.same_site', 'lax')
                ));
        } else {
            $boleh = false;
        }

        if (!$boleh) {
            abort(403, 'Akses wallboard membutuhkan login atau token yang valid.');
        }

        $total = Aduan::belumArsip()->count();
        $selesai = Aduan::belumArsip()->where('status', 'selesai')->count();
        $menunggu = Aduan::belumArsip()->where('status', 'menunggu')->count();
        $diproses = Aduan::belumArsip()->whereIn('status', ['diverifikasi', 'diproses'])->count();
        $persen = $total > 0 ? round($selesai / $total * 100, 1) : 0;

        $perKategori = [];
        foreach (Aduan::KATEGORI as $kat) {
            $perKategori[] = [
                'nama' => $kat,
                'total' => Aduan::belumArsip()->where('kategori', $kat)->count(),
            ];
        }
        $maks = max([1, ...array_column($perKategori, 'total')]);

        $terbaru = Aduan::belumArsip()
            ->orderByDesc('tanggal_pengaduan')
            ->limit(8)
            ->get(['uuid', 'nomor_aduan', 'judul', 'kategori', 'status', 'tanggal_pengaduan']);

        return response()
            ->view('pages.wallboard.index', compact(
                'total',
                'selesai',
                'menunggu',
                'diproses',
                'persen',
                'perKategori',
                'maks',
                'terbaru'
            ))
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
