<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PenggunaExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $request;

    /**
     * Constructor untuk menerima filter.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Data yang akan diexport ke Excel.
     */
    public function view(): View
    {
        $query = User::query();

        // Hanya filter role user bila role-nya ada (hindari error 500).
        if (\Spatie\Permission\Models\Role::where('name', 'user')->exists()) {
            $query->role('user');
        } else {
            $query->whereRaw('1 = 0');
        }

        // Filter Tahun
        if ($this->request->filled('tahun')) {
            $query->whereYear(
                'created_at',
                $this->request->tahun
            );
        }

        // Filter Tanggal Mulai
        if ($this->request->filled('start_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $this->request->start_date
            );
        }

        // Filter Tanggal Akhir
        if ($this->request->filled('end_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $this->request->end_date
            );
        }

        $pengguna = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.dashboard.exports', [
            'pengguna' => $pengguna,

            'filters' => [
                'tahun'      => $this->request->tahun,
                'start_date' => $this->request->start_date,
                'end_date'   => $this->request->end_date,
            ],
        ]);
    }

    /**
     * Nama sheet Excel.
     */
    public function title(): string
    {
        return 'Data Pengguna';
    }
}