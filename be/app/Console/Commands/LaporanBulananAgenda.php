<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use App\Models\User;
use App\Notifications\AgendaMonthlyReportNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LaporanBulananAgenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agenda:laporan-bulanan {periode? : Periode laporan format YYYY-MM (default: bulan lalu)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan bulanan agenda via email ke super-admin/admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $periode = $this->argument('periode') ?: now()->subMonth()->format('Y-m');

        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $periode)) {
            $this->error('Format periode tidak valid. Gunakan YYYY-MM, contoh: 2026-09');

            return self::FAILURE;
        }

        [$tahun, $bulan] = explode('-', $periode);
        $start = Carbon::create((int) $tahun, (int) $bulan, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $periodLabel = $start->translatedFormat('F Y');

        $dibuat = Agenda::whereBetween('created_at', [$start, $end])->get(['status']);
        $created = $dibuat->count();
        $pending = $dibuat->where('status', 'pending')->count();
        $draft = $dibuat->where('status', 'draft')->count();
        $scheduled = $dibuat->where('status', 'scheduled')->count();
        $published = $dibuat->where('status', 'published')->count();
        $publishedTotal = Agenda::where('status', 'published')->count();

        $topOpdQuery = User::role('opd')
            ->withCount(['agendas as total_agenda' => fn ($q) => $q->whereBetween('created_at', [$start, $end])])
            ->orderByDesc('total_agenda')
            ->limit(5)
            ->get(['uuid', 'name'])
            ->filter(fn ($u) => $u->total_agenda > 0);

        $topOpd = $topOpdQuery
            ->map(fn ($u) => $u->name . ' (' . $u->total_agenda . ')')
            ->implode(', ');

        $stats = [
            'created'        => $created,
            'pending'        => $pending,
            'draft'          => $draft,
            'scheduled'      => $scheduled,
            'published'      => $published,
            'publishedTotal' => $publishedTotal,
            'topOpd'         => $topOpd,
        ];

        $penerima = User::role('super-admin')->get();

        if ($penerima->isEmpty()) {
            $penerima = User::role('admin')->get();
        }

        if ($penerima->isEmpty()) {
            $this->warn('Tidak ada penerima dengan role super-admin/admin. Laporan tidak dikirim.');

            return self::SUCCESS;
        }

        foreach ($penerima as $user) {
            $user->notify(new AgendaMonthlyReportNotification($periodLabel, $stats));
        }

        $this->info("Laporan {$periodLabel} dikirim ke {$penerima->count()} penerima (agenda dibuat: {$created}).");

        return self::SUCCESS;
    }
}
