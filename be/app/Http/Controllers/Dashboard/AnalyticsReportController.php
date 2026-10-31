<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Album;
use App\Models\Category;
use App\Models\Document;
use App\Models\Faq;
use App\Models\Kontak;
use App\Models\Page;
use App\Models\User;
use App\Models\VisitorDailyStat;
use App\Models\VisitorLog;
use App\Models\WebsiteIdentity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsReportController extends Controller
{
    public function preview(Request $request)
    {
        return view('pages.dashboard.analytics-preview', $this->collectData($request));
    }

    public function download(Request $request)
    {
        $data = $this->collectData($request);

        $pdf = Pdf::loadView('pages.dashboard.analytics-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans');

        $filename = 'laporan-analitik-' . $data['startDate']->format('Ymd') . '-' . $data['endDate']->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    private function parseRange(Request $request): array
    {
        try {
            $startDate = $request->filled('start_date')
                ? Carbon::parse($request->start_date)->startOfDay()
                : now()->subDays(30)->startOfDay();
            $endDate = $request->filled('end_date')
                ? Carbon::parse($request->end_date)->endOfDay()
                : now()->endOfDay();
        } catch (\Throwable) {
            $startDate = now()->subDays(30)->startOfDay();
            $endDate = now()->endOfDay();
        }

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        if ($startDate->diffInDays($endDate) > 365) {
            $startDate = $endDate->copy()->subDays(365)->startOfDay();
        }

        return [$startDate, $endDate];
    }

    private function growth(int $current, int $previous): float
    {
        return $previous > 0 ? round(($current - $previous) / $previous * 100, 1) : 0;
    }

    private function collectData(Request $request): array
    {
        [$startDate, $endDate] = $this->parseRange($request);
        $daysDiff = (int) $startDate->diffInDays($endDate) + 1;

        // OPD hanya melihat angka miliknya sendiri; inbox pesan & OPD nonaktif khusus admin.
        $isAdminViewer = $request->user()->hasAnyRole(['super-admin', 'admin']);
        $ownerUuid = $isAdminViewer ? null : $request->user()->uuid;

        $identity = WebsiteIdentity::current();
        $logoPath = ($identity->logo && file_exists(public_path('storage/' . $identity->logo)))
            ? public_path('storage/' . $identity->logo)
            : null;

        $visitorStats = VisitorLog::getStatsForRange($startDate, $endDate);

        $prevStart = $startDate->copy()->subDays($daysDiff);
        $prevEnd = $startDate->copy()->subSecond();
        $visitorGrowth = $this->growth($visitorStats['total_visits'], VisitorLog::whereBetween('visited_at', [$prevStart, $prevEnd])->count());
        $uniqueGrowth = $this->growth($visitorStats['unique_visitors'], VisitorLog::whereBetween('visited_at', [$prevStart, $prevEnd])->distinct()->count('ip_address'));

        $totalAgendas = Agenda::whereBetween('created_at', [$startDate, $endDate])
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))->count();
        $publishedAgendas = Agenda::where('status', 'published')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))->count();
        $pendingAgendas = Agenda::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))->count();
        $totalMessages = $isAdminViewer ? Kontak::whereBetween('created_at', [$startDate, $endDate])->count() : 0;
        $unreadMessages = $isAdminViewer ? Kontak::where('status', 'open')
            ->whereBetween('created_at', [$startDate, $endDate])->count() : 0;

        $agendaTrenLabels = [];
        $agendaTrenData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $agendaTrenLabels[] = $bulan->translatedFormat('M Y');
            $agendaTrenData[] = Agenda::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
                ->count();
        }
        $trenMax = max(1, max($agendaTrenData));

        $topOpd = User::role('opd')
            ->withCount(['agendas as total_agenda', 'agendas as pending_agenda' => fn ($q) => $q->where('status', 'pending')])
            ->orderByDesc('total_agenda')
            ->limit(5)
            ->get(['uuid', 'name']);
        $topOpdMax = max(1, (int) $topOpd->max('total_agenda'));

        $topViewedAgendas = Agenda::where('status', 'published')
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
            ->orderByDesc('views')
            ->limit(5)
            ->get(['uuid', 'slug', 'title', 'views', 'created_at']);

        $agendaKategori = Category::withCount(['agendas' => fn ($q) => $ownerUuid ? $q->where('user_uuid', $ownerUuid) : $q])->orderByDesc('agendas_count')->limit(8)->get();

        $browserStats = VisitorLog::whereBetween('visited_at', [$startDate, $endDate])
            ->selectRaw('browser, count(*) as total')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'browser');

        $kotaStats = VisitorLog::whereBetween('visited_at', [$startDate, $endDate])
            ->whereNotNull('city')
            ->selectRaw('city, count(*) as total')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'city');

        $deviceStats = VisitorDailyStat::getDeviceBreakdownForRange($startDate, $endDate);
        $deviceTotal = max(1, array_sum($deviceStats['values']));

        $opdTidakAktif = $isAdminViewer ? User::role('opd')
            ->whereDoesntHave('agendas', fn ($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->withMax('agendas as last_agenda_at', 'created_at')
            ->orderBy('name')
            ->get(['uuid', 'name'])
            ->map(fn ($user) => [
                'name' => $user->name,
                'last_agenda' => $user->last_agenda_at ? Carbon::parse($user->last_agenda_at) : null,
            ]) : collect();

        return [
            'isAdminViewer' => $isAdminViewer,
            'identity' => $identity,
            'logoPath' => $logoPath,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'daysDiff' => $daysDiff,
            'dateRangeLabel' => $startDate->translatedFormat('d F Y') . ' – ' . $endDate->translatedFormat('d F Y'),
            'generatedAt' => now(),
            'generatedBy' => $request->user()->name ?? '-',
            'visitorStats' => $visitorStats,
            'visitorGrowth' => $visitorGrowth,
            'uniqueGrowth' => $uniqueGrowth,
            'visitorGrowth' => $this->growth($visitorStats['total_visits'], VisitorLog::whereBetween('visited_at', [$prevStart, $prevEnd])->count()),
            'uniqueGrowth' => $this->growth($visitorStats['unique_visitors'], VisitorLog::whereBetween('visited_at', [$prevStart, $prevEnd])->distinct()->count('ip_address')),
            'totalAgendas' => $totalAgendas,
            'publishedAgendas' => $publishedAgendas,
            'pendingAgendas' => $pendingAgendas,
            'totalMessages' => $totalMessages,
            'unreadMessages' => $unreadMessages,
            'totalFaq' => Faq::where('status', 'active')->count(),
            'totalPages' => Page::count(),
            'totalDocuments' => Document::count(),
            'totalOpdAktif' => User::role('opd')->count(),
            'totalGaleri' => Album::where('status', 'active')->count(),
            'agendaTrenLabels' => $agendaTrenLabels,
            'agendaTrenData' => $agendaTrenData,
            'trenMax' => $trenMax,
            'topOpd' => $topOpd,
            'topOpdMax' => $topOpdMax,
            'topViewedAgendas' => $topViewedAgendas,
            'agendaKategori' => $agendaKategori,
            'browserStats' => $browserStats,
            'kotaStats' => $kotaStats,
            'deviceStats' => $deviceStats,
            'deviceTotal' => $deviceTotal,
            'opdTidakAktif' => $opdTidakAktif,
        ];
    }
}
