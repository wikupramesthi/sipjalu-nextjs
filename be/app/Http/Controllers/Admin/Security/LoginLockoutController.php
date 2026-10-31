<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\LoginLockout;
use Illuminate\Http\Request;

class LoginLockoutController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->value();
        $type = $request->input('type');
        $status = $request->input('status');

        $lockouts = LoginLockout::query()
            ->when($search, fn ($query) => $query->where('value', 'like', "%{$search}%"))
            ->when($type, fn ($query) => $query->where('type', $type))
            ->when($status === 'blocked', fn ($query) => $query->active())
            ->when($status === 'tracked', fn ($query) => $query->whereNull('blocked_until'))
            ->orderByDesc('blocked_until')
            ->orderByDesc('last_attempt_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'blocked' => LoginLockout::active()->count(),
            'tracked' => LoginLockout::count(),
            'attempts_today' => LoginLockout::whereDate('last_attempt_at', today())->count(),
        ];

        return view('pages.security.login-lockout.index', compact(
            'lockouts',
            'stats',
            'search',
            'type',
            'status',
        ));
    }

    public function destroy(LoginLockout $loginLockout)
    {
        $loginLockout->delete();

        return back()->with('success', 'Blokir berhasil dibuka.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }
        $count = LoginLockout::whereIn('id', array_map('intval', $ids))->delete();
        return back()->with('success', $count . ' blokir berhasil dibuka.');
    }
}