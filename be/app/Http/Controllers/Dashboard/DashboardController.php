<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Reference;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\User;
use App\Models\VisitorLog;
use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Kontak;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Document;
use App\Models\Aduan;
use App\Models\Album;
use App\Services\SystemHealthService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(protected SystemHealthService $health) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showDefaultPasswordModal = (bool) $request->session()->pull('show_default_password_modal', false)
            && $request->user()->isUsingDefaultPassword();

        // Warga (role user, bukan petugas) mendapat dashboard pengaduan milik sendiri.
        if (
            $request->user()->hasRole('user') &&
            !$request->user()->hasAnyRole(['super-admin', 'admin', 'uptd'])
        ) {
            return $this->memberDashboard($showDefaultPasswordModal);
        }

        // Parse date range (tahan input tanggal rusak + rentang terbalik)
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

        // Tukar kalau rentang terbalik agar filter tetap jalan
        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        // Batasi maksimal 366 hari agar query tetap ringan
        if ($startDate->diffInDays($endDate) > 365) {
            $startDate = $endDate->copy()->subDays(365)->startOfDay();
        }

        $dateRangeLabel = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        $daysDiff = (int) $startDate->diffInDays($endDate) + 1;

        // Visitor statistics for date range - cache 5 menit per range
        $cacheKeyBase = 'dashboard_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd');
        $visitorStats = Cache::remember($cacheKeyBase . '_visitor', 300, fn () => VisitorLog::getStatsForRange($startDate, $endDate));

        // OPD hanya melihat angka miliknya sendiri; admin melihat semua.
        // (Cache dipisah per pemilik agar tidak bocor antar user.)
        $isAdminViewer = $request->user()->hasAnyRole(['super-admin', 'admin']);
        $ownerUuid = $isAdminViewer ? null : $request->user()->uuid;

        // Content statistics for date range - cache 1 menit per range
        $cacheKeyStats = 'dashboard_stats_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '_' . ($ownerUuid ?? 'all');
        $contentStats = Cache::remember($cacheKeyStats, 60, fn () => [
            ...$this->getAgendaStatusStats($ownerUuid, $startDate, $endDate),
            'totalMessages' => Kontak::whereBetween('created_at', [$startDate, $endDate])->count(),
            'unreadMessages' => Kontak::where('status', 'open')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'totalFaq' => Faq::where('status', 'active')->count(),
            ...$this->getBeritaStatusStats($ownerUuid),
            'totalPages' => Page::count(),
            'totalDocuments' => Document::count(),
            'totalOpdAktif' => User::role('opd')->count(),
            'totalGaleri' => Album::where('status', 'active')->count(),
        ]);

        extract($contentStats);


        // Tren agenda masuk per bulan (6 bulan terakhir) untuk grafik OPD
        $agendaTrenLabels = [];
        $agendaTrenData = [];
        $agendaPendingData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $agendaTrenLabels[] = $bulan->translatedFormat('M Y');
            $agendaTrenData[] = Agenda::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
                ->count();
            $agendaPendingData[] = Agenda::where('status', 'pending')
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
                ->count();
        }

        $topOpd = User::role('opd')
            ->withCount(['agendas as total_agenda', 'agendas as pending_agenda' => fn ($q) => $q->where('status', 'pending')])
            ->orderByDesc('total_agenda')
            ->limit(5)
            ->get(['uuid', 'name', 'avatar']);

        $responseTimeOpd = \App\Models\AgendaLog::where('aksi', 'disetujui')
            ->whereHas('agenda', function ($q) use ($ownerUuid) {
                $q->where('status', 'published');
                if ($ownerUuid) {
                    $q->where('user_uuid', $ownerUuid);
                }
            })
            ->with(['agenda'])
            ->get()
            ->filter(fn ($log) => $log->agenda && $log->agenda->created_at)
            ->groupBy(fn ($log) => $log->agenda->user_uuid)
            ->map(function ($logs) {
                $times = $logs->map(fn ($log) => $log->agenda->created_at->diffInHours($log->created_at))->filter(fn ($h) => $h > 0);
                return $times->isNotEmpty() ? round($times->avg(), 1) : 0;
            })
            ->sort()
            ->take(5)
            ->map(function ($hours, $userUuid) {
                $user = \App\Models\User::where('uuid', $userUuid)->first(['name']);
                return ['name' => $user?->name ?? '-', 'hours' => $hours];
            })
            ->values();

        // Konten paling banyak dilihat, lintas agenda dan berita
        $topViewedAgendas = Agenda::where('status', 'published')
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
            ->orderByDesc('views')
            ->limit(5)
            ->get(['uuid', 'slug', 'title', 'views', 'created_at'])
            ->map(fn ($agenda) => [
                'type' => 'Agenda',
                'title' => $agenda->title,
                'created_at' => $agenda->created_at,
                'views' => $agenda->views,
                'url' => route('agendas.show', $agenda->slug),
            ]);

        $topViewedBerita = Berita::where('status', 'published')
            ->where(fn ($query) => $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now()))
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
            ->orderByDesc('views')
            ->limit(5)
            ->get(['uuid', 'title', 'views', 'created_at'])
            ->map(fn ($berita) => [
                'type' => 'Berita',
                'title' => $berita->title,
                'created_at' => $berita->created_at,
                'views' => $berita->views,
                'url' => route('beritas.show', $berita->uuid),
            ]);

        $topViewedContent = $topViewedAgendas
            ->concat($topViewedBerita)
            ->sortByDesc('views')
            ->take(5)
            ->values();

        // Agenda 7 hari ke depan (widget dashboard)
        $agendaMingguIni = Agenda::with('category')
            ->where('status', 'published')
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
            ->whereBetween('waktu_mulai', [now()->startOfDay(), now()->copy()->addDays(7)->endOfDay()])
            ->orderBy('waktu_mulai')
            ->limit(6)
            ->get(['uuid', 'title', 'jenis_agenda', 'lokasi', 'waktu_mulai', 'waktu_akhir', 'category_uuid']);

        // Agenda terjadwal yang akan terbit
        $agendaAkanTerbit = Agenda::with('category')
            ->where('status', 'scheduled')
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
            ->where('scheduled_at', '<=', now()->copy()->addDays(7))
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get(['uuid', 'title', 'jenis_agenda', 'lokasi', 'scheduled_at', 'category_uuid']);

        // Agenda pending terlama (belum direview)
        $pendingTerlama = Agenda::with('user')
            ->where('status', 'pending')
            ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
            ->orderBy('created_at')
            ->limit(5)
            ->get(['uuid', 'title', 'user_uuid', 'created_at']);

        $agendaKategori = Category::withCount('agendas')->orderByDesc('agendas_count')->get();
        $agendaKategoriLabels = $agendaKategori->pluck('name');
        $agendaKategoriData = $agendaKategori->pluck('agendas_count');

        $trendKategoriLabels = [];
        $trendKategoriData = [];
        $kategoriList = Category::orderBy('name')->get(['uuid', 'name']);
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $trendKategoriLabels[] = $bulan->translatedFormat('M');
            $dataBulan = [];
            foreach ($kategoriList as $kat) {
                $dataBulan[] = Agenda::where('category_uuid', $kat->uuid)
                    ->whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))
                    ->count();
            }
            $trendKategoriData[] = $dataBulan;
        }
        $trendKategoriNama = $kategoriList->pluck('name')->all();

        // Komposisi konten (untuk chart donut)
        $komposisiKontenLabels = ['Halaman', 'Dokumen', 'FAQ', 'Agenda'];
        $komposisiKontenData = [Page::count(), Document::count(), Faq::count(), Agenda::count()];

        // Browser & kota asal pengunjung (untuk chart)
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

        // OPD tidak isi agenda dalam 7 hari terakhir (khusus admin; 1 query via withMax, tanpa N+1)
        $opdTidakAktif = $isAdminViewer ? User::role('opd')
            ->whereDoesntHave('agendas', fn ($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->withMax('agendas as last_agenda_at', 'created_at')
            ->orderBy('name')
            ->get(['uuid', 'name', 'avatar'])
            ->map(fn ($user) => [
                'name' => $user->name,
                'avatar' => $user->avatar,
                'last_agenda' => $user->last_agenda_at ? Carbon::parse($user->last_agenda_at) : null,
            ]) : collect();
        $pendingAgendas = Agenda::with('user')
            ->where('status', 'pending')
            ->when(! $isAdminViewer, fn ($q) => $q->where('user_uuid', $request->user()->uuid))
            ->latest('created_at')
            ->limit(5)
            ->get(['uuid', 'title', 'user_uuid', 'created_at']);
        $pendingBeritas = $isAdminViewer
            ? Berita::with('user')
                ->where('status', 'pending')
                ->latest('created_at')
                ->limit(5)
                ->get(['uuid', 'title', 'user_uuid', 'created_at'])
            : collect();

        // Antrean approval agenda (OPD: milik sendiri, admin: semua)
        $pendingCountQuery = Agenda::where('status', 'pending');
        if (! $request->user()->hasAnyRole(['super-admin', 'admin'])) {
            $pendingCountQuery->where('user_uuid', $request->user()->uuid);
        }
        $pendingCount = $pendingCountQuery->count();
        $pendingBeritaCount = $isAdminViewer
            ? Berita::where('status', 'pending')->count()
            : 0;
        $prevStart = $startDate->copy()->subDays($daysDiff);
        $prevEnd = $startDate->copy()->subSecond();

        $visitorGrowth = $this->growth($visitorStats['total_visits'], VisitorLog::whereBetween('visited_at', [$prevStart, $prevEnd])->count());
        $uniqueGrowth = $this->growth($visitorStats['unique_visitors'], VisitorLog::whereBetween('visited_at', [$prevStart, $prevEnd])->distinct()->count('ip_address'));
        $agendaGrowth = $this->growth($totalAgendas, Agenda::whereBetween('created_at', [$prevStart, $prevEnd])->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))->count());
        $publishedGrowth = $this->growth($publishedAgendas, Agenda::where('status', 'published')->whereBetween('created_at', [$prevStart, $prevEnd])->when($ownerUuid, fn ($q) => $q->where('user_uuid', $ownerUuid))->count());
        $messageGrowth = $this->growth($totalMessages, Kontak::whereBetween('created_at', [$prevStart, $prevEnd])->count());

        // Ringkasan kesehatan sistem untuk widget dashboard (single source via SystemHealthService)
        $schedulerTerakhir = $this->health->getSchedulerLastRun();
        $schedulerOk = $this->health->isSchedulerOk($schedulerTerakhir);
        $storageOk = $this->health->isStorageOk();

        // Recent activities for date range (non-admin: hanya milik sendiri)
        $recentActivities = $this->getRecentActivities($startDate, $endDate, 5, $isAdminViewer ? null : $request->user()->uuid);

        return view('pages.dashboard.index', compact(
            'visitorStats',
            'recentActivities',
            'totalAgendas',
            'publishedAgendas',
            'totalMessages',
            'unreadMessages',
            'totalFaq',
            'totalBerita',
            'beritaStatusCounts',
            'totalPages',
            'totalDocuments',
            'totalOpdAktif',
            'totalGaleri',
            'agendaTrenLabels',
            'agendaTrenData',
            'agendaPendingData',
            'topOpd',
            'responseTimeOpd',
            'pendingAgendas',
            'pendingBeritas',
            'agendaMingguIni',
            'agendaAkanTerbit',
            'pendingTerlama',
            'showDefaultPasswordModal',
            'opdTidakAktif',
            'topViewedContent',
            'agendaKategoriLabels',
            'agendaKategoriData',
            'trendKategoriLabels',
            'trendKategoriData',
            'trendKategoriNama',
            'komposisiKontenLabels',
            'komposisiKontenData',
            'browserStats',
            'kotaStats',
            'pendingCount',
            'pendingBeritaCount',
            'isAdminViewer',
            'schedulerTerakhir',
            'schedulerOk',
            'storageOk',
            'dateRangeLabel',
            'startDate',
            'endDate',
            'daysDiff',
            'visitorGrowth',
            'uniqueGrowth',
            'agendaGrowth',
            'publishedGrowth',
            'agendaStatusCounts',
            'messageGrowth'
        ));
    }

    public function pendingBeritaCount(Request $request): JsonResponse
    {
        abort_unless($request->user()->hasAnyRole(['super-admin', 'admin']), 403);

        return response()->json([
            'count' => Berita::where('status', 'pending')->count(),
        ]);
    }

    private function getBeritaStatusStats(?string $ownerUuid): array
    {
        $statusCounts = array_fill_keys(['published', 'pending', 'draft', 'scheduled'], 0);
        $counts = Berita::query()
            ->when($ownerUuid, fn ($query) => $query->where('user_uuid', $ownerUuid))
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        foreach ($counts as $status => $count) {
            if (array_key_exists($status, $statusCounts)) {
                $statusCounts[$status] = (int) $count;
            }
        }

        return [
            'totalBerita' => array_sum($statusCounts),
            'beritaStatusCounts' => $statusCounts,
        ];
    }

    private function getAgendaStatusStats(?string $ownerUuid, Carbon $startDate, Carbon $endDate): array
    {
        $statusCounts = array_fill_keys(['published', 'pending', 'draft', 'scheduled'], 0);
        $counts = Agenda::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($ownerUuid, fn ($query) => $query->where('user_uuid', $ownerUuid))
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        foreach ($counts as $status => $count) {
            if (array_key_exists($status, $statusCounts)) {
                $statusCounts[$status] = (int) $count;
            }
        }

        return [
            'totalAgendas' => array_sum($statusCounts),
            'publishedAgendas' => $statusCounts['published'],
            'agendaStatusCounts' => $statusCounts,
        ];
    }

    protected function growth(int $current, int $previous): float
    {
        return $previous > 0 ? round(($current - $previous) / $previous * 100, 1) : 0;
    }

    /**
     * Dashboard warga: ringkasan & riwayat pengaduan milik sendiri.
     */
    protected function memberDashboard(bool $showDefaultPasswordModal = false)
    {
        $user = Auth::user();

        $base = Aduan::where('user_uuid', $user->uuid);

        $statTotal = (clone $base)->count();
        $statMenunggu = (clone $base)->where('status', 'menunggu')->count();
        $statDiproses = (clone $base)->whereIn('status', ['diverifikasi', 'diproses'])->count();
        $statSelesai = (clone $base)->where('status', 'selesai')->count();
        $belumDinilai = (clone $base)->where('status', 'selesai')->whereNull('rating')->count();

        $recentAduan = (clone $base)
            ->with(['kecamatan', 'kelurahan'])
            ->orderByDesc('tanggal_pengaduan')
            ->limit(6)
            ->get();

        return view('pages.dashboard.index', compact(
            'statTotal',
            'statMenunggu',
            'statDiproses',
            'statSelesai',
            'belumDinilai',
            'recentAduan',
            'showDefaultPasswordModal',
        ));
    }

    protected function getRecentActivities(Carbon $startDate, Carbon $endDate, int $limit = 5, ?string $onlyUserUuid = null): array
    {
        $activities = [];

        // Recent agendas
        $recentAgendas = Agenda::whereBetween('created_at', [$startDate, $endDate])
            ->when($onlyUserUuid, fn ($q) => $q->where('user_uuid', $onlyUserUuid))
            ->latest('created_at')
            ->limit(5)
            ->get(['uuid', 'slug', 'title', 'status', 'created_at']);

        foreach ($recentAgendas as $agenda) {
            $activities[] = [
                'type' => 'agenda',
                'label' => 'Agenda',
                'badge_class' => 'bg-primary-subtle text-primary',
                'icon' => 'bi-file-earmark-text',
                'color' => 'primary',
                'title' => 'Agenda ' . ($agenda->status === 'published' ? 'dipublikasikan' : 'dibuat'),
                'description' => $agenda->title,
                'time' => $agenda->created_at,
                'date' => $agenda->created_at->translatedFormat('d M Y'),
                'url' => route('agendas.index', ['search' => $agenda->title]),
            ];
        }

        $recentBerita = Berita::whereBetween('created_at', [$startDate, $endDate])
            ->when($onlyUserUuid, fn ($q) => $q->where('user_uuid', $onlyUserUuid))
            ->latest('created_at')
            ->limit(5)
            ->get(['uuid', 'title', 'status', 'created_at']);

        foreach ($recentBerita as $berita) {
            $activities[] = [
                'type' => 'berita',
                'label' => 'Berita',
                'badge_class' => 'bg-success-subtle text-success',
                'icon' => 'bi-newspaper',
                'color' => 'success',
                'title' => 'Berita ' . ($berita->status === 'published' ? 'dipublikasikan' : 'dibuat'),
                'description' => $berita->title,
                'time' => $berita->created_at,
                'date' => $berita->created_at->translatedFormat('d M Y'),
                'url' => route('beritas.index', ['search' => $berita->title]),
            ];
        }

        // Recent messages (inbox kontak hanya untuk admin; OPD tidak punya akses)
        $recentMessages = $onlyUserUuid
            ? collect()
            : Kontak::whereBetween('created_at', [$startDate, $endDate])
                ->latest('created_at')
                ->limit(3)
                ->get(['uuid', 'nama', 'isi', 'created_at']);

        foreach ($recentMessages as $message) {
            $activities[] = [
                'type' => 'message',
                'label' => 'Pesan',
                'badge_class' => 'bg-secondary-subtle text-secondary',
                'icon' => 'bi-envelope',
                'color' => 'success',
                'title' => 'Pesan baru dari ' . $message->nama,
                'description' => Str::limit($message->isi, 50),
                'time' => $message->created_at,
                'date' => $message->created_at->translatedFormat('d M Y'),
                'url' => route('layanan.kontak', ['search' => $message->nama]),
            ];
        }

        // Sort by time and limit
        usort($activities, fn($a, $b) => $b['time'] <=> $a['time']);

        return array_slice($activities, 0, $limit);
    }

    public function deviceStats(Request $request)
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

        $deviceStats = VisitorLog::getDeviceStatsForRange($startDate, $endDate);

        return response()->json($deviceStats);
    }

    public function activeVisitors(): JsonResponse
    {
        $payload = Cache::remember('dashboard:active-visitors', 30, function () {
            $activeIps = collect(range(0, 4))
                ->flatMap(function (int $minutesAgo) {
                    $bucket = now()->subMinutes($minutesAgo)->format('Y-m-d H:i');

                    return Cache::get('visitor_buffer_' . $bucket, []);
                })
                ->pluck('ip')
                ->filter()
                ->unique();

            return [
                'count' => $activeIps->count(),
                'window_minutes' => 5,
                'updated_at' => now()->toIso8601String(),
            ];
        });

        return response()->json($payload);
    }

    public function submitSumber(Request $request)
    {
        $request->validate([
            'no_hp' => [
                'required',
                'regex:/^[0-9]{8,15}$/',
                'unique:users,no_hp,' . Auth::user()->uuid . ',uuid',
            ],
            'sumber_informasi' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.regex'    => 'Format nomor WhatsApp tidak valid.',
            'no_hp.unique'   => 'Nomor WhatsApp sudah terdaftar.',

            'sumber_informasi.required' => 'Sumber informasi wajib dipilih.',
            'sumber_informasi.string'   => 'Sumber informasi tidak valid.',
            'sumber_informasi.max'      => 'Sumber informasi terlalu panjang.',
        ]);

        $user = Auth::user();

        $no_hp = trim($request->no_hp);

        $user->update([
            'no_hp'             => $no_hp,
            'sumber_informasi'  => $request->sumber_informasi,
        ]);

        Auth::setUser($user->fresh());

        return redirect()
            ->route('dashboard.index')
            ->with(
                'success',
                'Data Anda telah berhasil disimpan. Terima kasih atas informasi yang telah diberikan.'
            );
    }
}
