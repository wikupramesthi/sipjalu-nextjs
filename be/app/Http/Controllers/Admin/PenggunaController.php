<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenggunaExport;
use Spatie\Permission\Models\Role;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseQuery = $this->memberQuery()
            ->when($request->filled('tahun'), function ($query) use ($request) {
                $query->whereYear('created_at', $request->tahun);
            })
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            });

        // Statistik dari query yang SUDAH difilter (tanpa paginasi).
        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'verified'   => (clone $baseQuery)->whereNotNull('email_verified_at')->count(),
            'unverified' => (clone $baseQuery)->whereNull('email_verified_at')->count(),
        ];

        $users = $baseQuery->latest()->paginate(20)->withQueryString();

        return view(
            'pages.dashboard.pengguna',
            compact('users', 'stats')
        );
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
        //
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
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        return Excel::download(new PenggunaExport($request), 'pengguna-bekasikota.xlsx');
    }

    /**
     * Query dasar data member (role user). Aman bila role belum ada:
     * kembalikan hasil kosong alih-alih error 500.
     */
    private function memberQuery()
    {
        $query = User::query();

        if (! Role::where('name', 'user')->exists()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->role('user');
    }
}
