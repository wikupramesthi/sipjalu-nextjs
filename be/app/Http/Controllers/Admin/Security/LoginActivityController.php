<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Services\Security\AnomalyDetector;
use Illuminate\Http\Request;

class LoginActivityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->value();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $activities = LoginActivity::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->when($startDate, fn ($query) => $query->whereDate('logged_in_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('logged_in_at', '<=', $endDate))
            ->latest('logged_in_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => LoginActivity::count(),
            'today' => LoginActivity::whereDate('logged_in_at', today())->count(),
            'week' => LoginActivity::where('logged_in_at', '>=', now()->subDays(7))->count(),
            'unique' => LoginActivity::whereNotNull('user_uuid')->distinct()->count('user_uuid'),
        ];

        $detector = app(AnomalyDetector::class);
        $suspiciousUsers = $detector->suspiciousUserUuids();
        $suspiciousIps = $detector->suspiciousIps();
        $stats['suspicious'] = $detector->countSuspiciousLogins($suspiciousUsers, $suspiciousIps);

        return view('pages.security.login-activity.index', compact(
            'activities',
            'stats',
            'search',
            'startDate',
            'endDate',
            'detector',
            'suspiciousUsers',
            'suspiciousIps',
        ));
    }

    public function destroy(LoginActivity $loginActivity)
    {
        $loginActivity->delete();

        return back()->with('success', 'Riwayat login berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }
        $count = LoginActivity::whereIn('id', array_map('intval', $ids))->delete();
        return back()->with('success', $count . ' riwayat login berhasil dihapus.');
    }

    public function clear()
    {
        LoginActivity::query()->delete();

        return back()->with('success', 'Semua riwayat login berhasil dibersihkan.');
    }
}
