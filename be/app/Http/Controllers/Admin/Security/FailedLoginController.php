<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\FailedLogin;
use App\Services\Security\AnomalyDetector;
use Illuminate\Http\Request;

class FailedLoginController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->value();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $attempts = FailedLogin::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->when($startDate, fn ($query) => $query->whereDate('attempted_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('attempted_at', '<=', $endDate))
            ->latest('attempted_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => FailedLogin::count(),
            'last24' => FailedLogin::where('attempted_at', '>=', now()->subDay())->count(),
            'unique_ip' => FailedLogin::whereNotNull('ip_address')->distinct()->count('ip_address'),
            'unique_email' => FailedLogin::whereNotNull('email')->distinct()->count('email'),
        ];

        $detector = app(AnomalyDetector::class);
        $suspiciousIps = $detector->suspiciousIps();
        $stats['suspicious_ip'] = count($suspiciousIps);

        return view('pages.security.failed-login.index', compact(
            'attempts',
            'stats',
            'search',
            'startDate',
            'endDate',
            'detector',
            'suspiciousIps',
        ));
    }

    public function destroy(FailedLogin $failedLogin)
    {
        $failedLogin->delete();

        return back()->with('success', 'Catatan login gagal berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }
        $count = FailedLogin::whereIn('id', array_map('intval', $ids))->delete();
        return back()->with('success', $count . ' catatan login gagal berhasil dihapus.');
    }

    public function clear()
    {
        FailedLogin::query()->delete();

        return back()->with('success', 'Semua catatan login gagal berhasil dibersihkan.');
    }
}
