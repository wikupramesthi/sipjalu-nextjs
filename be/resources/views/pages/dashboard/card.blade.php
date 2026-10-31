@extends('layouts.app')

@section('content')

<style>
    .berita-dashboard-card-trigger {
        display: block;
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        color: inherit;
        font: inherit;
        text-align: left;
    }
</style>

<div class="container-fluid px-md-4">

    {{-- Data injection for external JS --}}
    <script>
        window.dashboardData = {
            visitorStats: @json($visitorStats),
            agendas: {
                total: {{ $totalAgendas }},
                published: {{ $publishedAgendas }}
            },
            agendaTrenLabels: @json($agendaTrenLabels ?? []),
            agendaTrenData: @json($agendaTrenData ?? []),
            agendaPendingData: @json($agendaPendingData ?? []),
            topOpdLabels: @json(($topOpd ?? collect())->pluck('name')),
            topOpdData: @json(($topOpd ?? collect())->pluck('total_agenda')),
            agendaKategoriLabels: @json($agendaKategoriLabels ?? []),
            agendaKategoriData: @json($agendaKategoriData ?? []),
            trendKategoriLabels: @json($trendKategoriLabels ?? []),
            trendKategoriData: @json($trendKategoriData ?? []),
            trendKategoriNama: @json($trendKategoriNama ?? []),
            komposisiKontenLabels: @json($komposisiKontenLabels ?? []),
            komposisiKontenData: @json($komposisiKontenData ?? []),
            browserLabels: @json($browserStats ? $browserStats->keys()->values() : []),
            browserData: @json($browserStats ? $browserStats->values()->values() : []),
            kotaLabels: @json($kotaStats ? $kotaStats->keys()->values() : []),
            kotaData: @json($kotaStats ? $kotaStats->values()->values() : []),
            routes: {
                deviceStats: "{{ route('dashboard.device-stats') }}"
            }
        };
    </script>

    {{-- Hero Section --}}
    <div class="dash-hero mb-3">
        <div class="hero-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="hero-date">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ now()->translatedFormat('l, j F Y') }}
                </span>
                @php
                    $hourNow = (int) now()->format('H');
                    $greeting = $hourNow < 11 ? 'Selamat Pagi' : ($hourNow < 15 ? 'Selamat Siang' : ($hourNow < 19 ? 'Selamat Sore' : 'Selamat Malam'));
                @endphp
                <h3 class="fw-bold mb-1">{{ $greeting }}, {{ auth()->user()->name }}</h3>
                <p class="mb-0 small text-white">Ringkasan statistik pengunjung website Pemerintah Kota Bekasi.</p>
                @if (($pendingCount ?? 0) > 0)
                    <a href="{{ route('agendas.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning text-dark mt-2" id="pendingBadge">
                        <i class="bi bi-hourglass-split me-1"></i><span id="pendingCountNum">{{ $pendingCount }}</span> agenda menunggu persetujuan
                    </a>
                @endif
                @if ($isAdminViewer ?? false)
                    <a href="{{ route('beritas.index', ['status' => 'pending']) }}"
                        class="btn btn-sm btn-info text-dark mt-2 {{ ($pendingBeritaCount ?? 0) > 0 ? '' : 'd-none' }}"
                        id="pendingBeritaBadge">
                        <i class="bi bi-newspaper me-1"></i><span id="pendingBeritaCountNum">{{ $pendingBeritaCount ?? 0 }}</span> berita menunggu persetujuan
                    </a>
                @endif
            </div>
            <div class="d-flex gap-2 hero-actions">
                <a href="{{ route('dashboard.analytics.preview', request()->only(['start_date', 'end_date'])) }}" class="btn btn-success"><i class="bi bi-file-earmark-bar-graph me-1"></i> Download Analytics</a>
                <a href="{{ route('agendas.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-1"></i> Tulis Agenda</a>
            </div>
        </div>
    </div>

    {{-- Date Range Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <form method="GET" action="{{ route('dashboard.index') }}" class="d-flex flex-wrap align-items-end gap-2">
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small fw-semibold mb-0 me-1">Dari</label>
                    <input type="date" name="start_date" class="form-control form-control-sm dash-date-input" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="d-flex align-items-center gap-1">
                    <label class="form-label small fw-semibold mb-0 me-1">Sampai</label>
                    <input type="date" name="end_date" class="form-control form-control-sm dash-date-input" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-filter me-1"></i> Filter</button>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-danger btn-sm"><i class="bi bi-x-circle me-1"></i> Reset</a>
                </div>
                <div class="small text-muted ms-auto d-none d-md-block"><strong>Range:</strong> {{ $dateRangeLabel }}</div>
            </form>
        </div>
    </div>

<!-- Key Stats Row with Trends -->
    @php($isDashboardAdmin = auth()->user()->hasAnyRole(['super-admin', 'admin']))
    <div class="row g-4 mb-4">
        {{-- Total Visitors --}}
        <div class="col-6 col-lg-3">
            @if ($isDashboardAdmin)
                <a href="{{ route('health.page') }}" class="text-decoration-none text-reset">
            @else
                <div>
            @endif
                <div class="card border-danger shadow-sm h-100 stat-card {{ $isDashboardAdmin ? 'hover-lift' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-soft-red rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-people-fill text-danger'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block" title="{{ $dateRangeLabel ?? '' }}">Total Pengunjung ({{ $daysDiff ?? 30 }} hari)</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ number_format($visitorStats['total_visits']) }}</div>
                            @include('partials.stat-trend', ['growth' => $visitorGrowth, 'daysDiff' => $daysDiff ?? 30])
                        </div>
                    </div>
                </div>
            @if ($isDashboardAdmin)</a>@else</div>@endif
        </div>

        {{-- Unique Visitors --}}
        <div class="col-6 col-lg-3">
            @if ($isDashboardAdmin)
                <a href="{{ route('health.page') }}" class="text-decoration-none text-reset">
            @else
                <div>
            @endif
                <div class="card border-success shadow-sm h-100 stat-card {{ $isDashboardAdmin ? 'hover-lift' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-success rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-person-check-fill text-success'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block" title="{{ $dateRangeLabel ?? '' }}">Pengunjung Unik ({{ $daysDiff ?? 30 }} hari)</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ number_format($visitorStats['unique_visitors']) }}</div>
                            @include('partials.stat-trend', ['growth' => $uniqueGrowth, 'daysDiff' => $daysDiff ?? 30])
                        </div>
                    </div>
                </div>
            @if ($isDashboardAdmin)</a>@else</div>@endif
        </div>

        {{-- Total Agendas --}}
        <div class="col-6 col-lg-3">
            <button type="button" class="berita-dashboard-card-trigger"
                data-bs-toggle="modal" data-bs-target="#agendaStatusModal" aria-label="Lihat jumlah agenda berdasarkan status">
                <div class="card border-info shadow-sm h-100 stat-card hover-lift">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-info rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-file-text-fill text-info'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block" title="{{ $dateRangeLabel ?? '' }}">Total Agenda ({{ $daysDiff ?? 30 }} hari)</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ $totalAgendas }}</div>
                            @include('partials.stat-trend', ['growth' => $agendaGrowth, 'daysDiff' => $daysDiff ?? 30])
                        </div>
                    </div>
                </div>
            </button>
        </div>

        {{-- OPD Aktif --}}
        <div class="col-6 col-lg-3">
            @if ($isDashboardAdmin)
                <a href="{{ route('user.index') }}" class="text-decoration-none text-reset">
            @else
                <div>
            @endif
                <div class="card border-primary shadow-sm h-100 stat-card {{ $isDashboardAdmin ? 'hover-lift' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-primary rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-building-fill text-primary'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block">Total OPD Aktif</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ $totalOpdAktif }}</div>
                        </div>
                    </div>
                </div>
            @if ($isDashboardAdmin)</a>@else</div>@endif
        </div>

        {{-- Pages --}}
        <div class="col-6 col-lg-3">
            @if ($isDashboardAdmin)
                <a href="{{ route('pages.index') }}" class="text-decoration-none text-reset">
            @else
                <div>
            @endif
                <div class="card border-dark shadow-sm h-100 stat-card {{ $isDashboardAdmin ? 'hover-lift' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-dark rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-file-earmark-fill text-dark'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block">Total Halaman</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ $totalPages }}</div>
                        </div>
                    </div>
                </div>
            @if ($isDashboardAdmin)</a>@else</div>@endif
        </div>

        {{-- Documents --}}
        <div class="col-6 col-lg-3">
            @if ($isDashboardAdmin)
                <a href="{{ route('documents.index') }}" class="text-decoration-none text-reset">
            @else
                <div>
            @endif
                <div class="card border-info shadow-sm h-100 stat-card {{ $isDashboardAdmin ? 'hover-lift' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-cyan rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-file-earmark-text-fill text-info'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block">Total Dokumen</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ $totalDocuments }}</div>
                        </div>
                    </div>
                </div>
            @if ($isDashboardAdmin)</a>@else</div>@endif
        </div>

        {{-- Berita --}}
        <div class="col-6 col-lg-3">
            <button type="button" class="berita-dashboard-card-trigger"
                data-bs-toggle="modal" data-bs-target="#beritaStatusModal" aria-label="Lihat jumlah berita berdasarkan status">
                <div class="card border-primary shadow-sm h-100 stat-card hover-lift">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-primary rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-newspaper text-primary'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block">Total Berita</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ number_format($totalBerita) }}</div>
                        </div>
                    </div>
                </div>
            </button>
        </div>

        {{-- Galeri --}}
        <div class="col-6 col-lg-3">
            @if ($isDashboardAdmin)
                <a href="{{ route('banner.index') }}" class="text-decoration-none text-reset">
            @else
                <div>
            @endif
                <div class="card border-secondary shadow-sm h-100 stat-card {{ $isDashboardAdmin ? 'hover-lift' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3 p-4 p-md-5">
                        <div class="stats-icon bg-light-secondary rounded-3 p-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <i class='bi bi-images text-secondary'></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stat-label small text-truncate d-block">Total Galeri</div>
                            <div class="stat-value fw-bold text-truncate" style="font-size: 1.25rem; line-height: 1.2;">{{ $totalGaleri }}</div>
                        </div>
                    </div>
                </div>
            @if ($isDashboardAdmin)</a>@else</div>@endif
        </div>

    </div>

    <style>
        .berita-status-modal .modal-content { border: 0; border-radius: 1rem; overflow: hidden; }
        .berita-status-modal .modal-header { padding: 1.25rem 1.5rem; background: #f8faff; }
        .berita-status-modal .modal-body { padding: 1.5rem; }
        .berita-status-modal .modal-dialog { max-width: 700px; }
        .berita-status-heading-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: .8rem;
            background: #e7f1ff;
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .berita-status-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-height: 112px;
            padding: 1.1rem;
            border: 1px solid #e6eaf0;
            border-left-width: 4px;
            border-radius: .8rem;
            background: #fff;
            color: inherit;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .berita-status-card:hover { transform: translateY(-2px); box-shadow: 0 .45rem 1rem rgba(15, 23, 42, .09); color: inherit; }
        .berita-status-card-success { border-left-color: #198754; }
        .berita-status-card-warning { border-left-color: #ffc107; }
        .berita-status-card-secondary { border-left-color: #6c757d; }
        .berita-status-card-info { border-left-color: #0dcaf0; }
        .berita-status-card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            border-radius: .75rem;
            font-size: 1.2rem;
        }
        .berita-status-card-label { color: #64748b; font-size: .85rem; line-height: 1.35; }
        .berita-status-card-count { color: #1e293b; font-size: 1.65rem; font-weight: 700; line-height: 1.15; }
        .berita-status-card-unit { color: #94a3b8; font-size: .75rem; }
        .berita-status-card-arrow { margin-left: auto; color: #94a3b8; }
        @media (max-width: 575.98px) {
            .berita-status-modal .modal-header { padding: 1rem; }
            .berita-status-modal .modal-body { padding: 1rem; }
            .berita-status-card { min-height: 96px; gap: .75rem; padding: .85rem; }
            .berita-status-card-icon { width: 40px; height: 40px; flex-basis: 40px; }
            .berita-status-card-count { font-size: 1.4rem; }
        }
    </style>
    <div class="modal fade berita-status-modal" id="beritaStatusModal" tabindex="-1" aria-labelledby="beritaStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="berita-status-heading-icon"><i class="bi bi-newspaper"></i></span>
                        <div>
                            <h5 class="modal-title fw-bold mb-1" id="beritaStatusModalLabel">Status Berita</h5>
                            <small class="text-muted">Ringkasan berita berdasarkan status saat ini</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach ([
                            'published' => ['label' => 'Disetujui / Tayang', 'icon' => 'bi-check-circle-fill', 'color' => 'success', 'background' => 'bg-success-subtle'],
                            'pending' => ['label' => 'Menunggu Persetujuan', 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'background' => 'bg-warning-subtle'],
                            'draft' => ['label' => 'Draft', 'icon' => 'bi-file-earmark-text-fill', 'color' => 'secondary', 'background' => 'bg-secondary-subtle'],
                            'scheduled' => ['label' => 'Terjadwal', 'icon' => 'bi-calendar-event-fill', 'color' => 'info', 'background' => 'bg-info-subtle'],
                        ] as $status => $item)
                            <div class="col-12 col-sm-6">
                                <a href="{{ route('beritas.index', ['status' => $status]) }}"
                                    class="berita-status-card berita-status-card-{{ $item['color'] }} text-decoration-none"
                                    aria-label="Lihat {{ $item['label'] }}: {{ $beritaStatusCounts[$status] ?? 0 }} berita">
                                    <span class="berita-status-card-icon {{ $item['background'] }} text-{{ $item['color'] }}">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>
                                    <span class="min-w-0 flex-grow-1">
                                        <span class="berita-status-card-label d-block">{{ $item['label'] }}</span>
                                        <span class="berita-status-card-count d-block">{{ number_format($beritaStatusCounts[$status] ?? 0) }}</span>
                                        <span class="berita-status-card-unit">berita</span>
                                    </span>
                                    <i class="bi bi-chevron-right berita-status-card-arrow"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade berita-status-modal" id="agendaStatusModal" tabindex="-1" aria-labelledby="agendaStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="berita-status-heading-icon"><i class="bi bi-calendar-event"></i></span>
                        <div>
                            <h5 class="modal-title fw-bold mb-1" id="agendaStatusModalLabel">Status Agenda</h5>
                            <small class="text-muted">Ringkasan agenda dalam {{ $daysDiff ?? 30 }} hari terakhir</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach ([
                            'published' => ['label' => 'Disetujui / Tayang', 'icon' => 'bi-check-circle-fill', 'color' => 'success', 'background' => 'bg-success-subtle'],
                            'pending' => ['label' => 'Menunggu Persetujuan', 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'background' => 'bg-warning-subtle'],
                            'draft' => ['label' => 'Draft', 'icon' => 'bi-file-earmark-text-fill', 'color' => 'secondary', 'background' => 'bg-secondary-subtle'],
                            'scheduled' => ['label' => 'Terjadwal', 'icon' => 'bi-calendar-event-fill', 'color' => 'info', 'background' => 'bg-info-subtle'],
                        ] as $status => $item)
                            <div class="col-12 col-sm-6">
                                <a href="{{ route('agendas.index', [
                                    'status' => $status,
                                    'start_date' => $startDate->format('Y-m-d'),
                                    'end_date' => $endDate->format('Y-m-d'),
                                ]) }}"
                                    class="berita-status-card berita-status-card-{{ $item['color'] }} text-decoration-none"
                                    aria-label="Lihat {{ $item['label'] }}: {{ $agendaStatusCounts[$status] ?? 0 }} agenda">
                                    <span class="berita-status-card-icon {{ $item['background'] }} text-{{ $item['color'] }}">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>
                                    <span class="min-w-0 flex-grow-1">
                                        <span class="berita-status-card-label d-block">{{ $item['label'] }}</span>
                                        <span class="berita-status-card-count d-block">{{ number_format($agendaStatusCounts[$status] ?? 0) }}</span>
                                        <span class="berita-status-card-unit">agenda</span>
                                    </span>
                                    <i class="bi bi-chevron-right berita-status-card-arrow"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section - Tabbed --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <ul class="nav nav-tabs nav-fill dash-tabs mb-0" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-visitor">Pengunjung</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-content">Konten</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-device">Perangkat</button></li>
            </ul>
        </div>
        <div class="card-body p-3">
            <div class="tab-content" style="min-height: 300px;">
                {{-- Visitor Tab --}}
                <div class="tab-pane fade show active" id="tab-visitor">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0">Statistik Pengunjung</h6>
                            <small class="text-muted">{{ $dateRangeLabel ?? '' }} &middot; {{ $daysDiff ?? 30 }} hari</small>
                        </div>
                        <div class="d-flex gap-2" role="group">
                            <button type="button" class="btn btn-sm chart-toggle chart-toggle-red active" data-chart-type="daily" onclick="switchVisitorChart('daily', this)"><i class="bi bi-calendar-day me-1"></i> Harian</button>
                            <button type="button" class="btn btn-sm chart-toggle chart-toggle-green" data-chart-type="monthly" onclick="switchVisitorChart('monthly', this)"><i class="bi bi-calendar-month me-1"></i> Bulanan</button>
                        </div>
                    </div>
                    <div style="height:200px"><canvas id="visitorChart"></canvas></div>
                    <div class="d-flex justify-content-between align-items-center gap-3 border-top mt-3 pt-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="active-visitors-dot" aria-hidden="true"></span>
                            <div>
                                <div class="fw-semibold small">Pengunjung Aktif Sekarang</div>
                                <small class="text-muted">Unik dalam 5 menit terakhir</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fs-4 fw-bold lh-1" id="activeVisitorCount" aria-live="polite">-</div>
                            <small class="text-muted" id="activeVisitorUpdated">Memuat...</small>
                        </div>
                    </div>
                </div>


                {{-- Content Tab --}}
                <div class="tab-pane fade" id="tab-content">
                    <div class="row g-3 mb-3">
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-bar-chart me-1"></i>Status Agenda</h6>
                                </div>
                                <div class="card-body p-3 pb-2 d-flex align-items-center justify-content-center">
                                    <div class="d-flex justify-content-center mb-3" style="height: 180px;">
                                        <canvas id="agendaStatusChart" width="180" height="180"></canvas>
                                    </div>
                                    <div class="row g-2 text-center w-100">
                                        <div class="col-4"><div class="fw-bold text-success">{{ $publishedAgendas }}</div><div class="small text-muted">Dipublikasikan</div></div>
                                        <div class="col-4"><div class="fw-bold text-warning">{{ $totalAgendas - $publishedAgendas }}</div><div class="small text-muted">Draft</div></div>
                                        <div class="col-4"><div class="fw-bold text-primary">{{ $totalAgendas }}</div><div class="small text-muted">Total</div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-bar-chart-steps me-1"></i>Top 5 OPD Pengirim Agenda</h6>
                                </div>
                                <div class="card-body p-3 pb-2" style="height: 260px;">
                                    <canvas id="topOpdChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-tags me-1"></i>Agenda per Kategori</h6>
                                </div>
                                <div class="card-body p-3 pb-2" style="height: 230px;">
                                    <canvas id="agendaKategoriChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-pie-chart me-1"></i>Komposisi Konten</h6>
                                </div>
                                <div class="card-body p-3 pb-2 d-flex justify-content-center" style="height: 230px;">
                                    <canvas id="komposisiKontenChart" width="230" height="230"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i>Tren Agenda per Kategori (6 Bulan)</h6>
                                </div>
                                <div class="card-body p-3 pb-2" style="height: 260px;">
                                    <canvas id="trendKategoriChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Device Tab --}}
                <div class="tab-pane fade" id="tab-device">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-device-desktop me-1"></i>Perangkat</h6>
                                </div>
                                <div class="card-body p-3 pb-2" style="height: 280px;">
                                    <div style="position: relative; height: 100%;"><canvas id="deviceChart"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-browser-chrome me-1"></i>Browser Pengunjung</h6>
                                </div>
                                <div class="card-body p-3 pb-2" style="height: 280px;">
                                    <div style="position: relative; height: 100%; display: flex; justify-content: center; align-items: center;"><canvas id="browserChart"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-geo-alt me-1"></i>Kota Asal Pengunjung</h6>
                                </div>
                                <div class="card-body p-3 pb-2" style="height: 280px;">
                                    <div style="position: relative; height: 100%;"><canvas id="kotaChart"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Agenda OPD: tren + top 5 + antrean approval --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i>Tren Agenda OPD (6 Bulan Terakhir)</h6>
                    <a href="{{ route('agendas.index') }}" class="small text-decoration-none fw-medium text-primary">Kelola <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-3 pb-2" style="height: 230px;">
                    <canvas id="agendaTrenChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                    <h6 class="mb-0 small fw-semibold"><i class="bi bi-trophy me-1"></i>Top 5 OPD</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($topOpd ?? [] as $i => $opd)
                        <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2">
                            <span class="fw-bold text-muted" style="width: 18px;">{{ $i + 1 }}</span>
                            <div class="avatar avatar-sm flex-shrink-0">
                                <img src="{{ $opd->avatar ? (Str::startsWith($opd->avatar, 'http') ? $opd->avatar : asset('storage/' . $opd->avatar)) : asset('dist/assets/images/avatar.jpg') }}" alt="{{ $opd->name }}" class="rounded-circle" style="width: 34px; height: 34px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">{{ $opd->name }}</div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ ($topOpd->max('total_agenda') ?? 1) > 0 ? round($opd->total_agenda / max($topOpd->max('total_agenda'), 1) * 100) : 0 }}%;" aria-valuenow="{{ $opd->total_agenda }}" aria-valuemin="0" aria-valuemax="{{ $topOpd->max('total_agenda') ?? 1 }}"></div>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold small">{{ $opd->total_agenda }}</div>
                                @if ($opd->pending_agenda > 0)
                                    <span class="badge bg-warning text-dark" style="font-size: 0.62rem;">{{ $opd->pending_agenda }} pending</span>
                                @else
                                    <small class="text-muted" style="font-size: 0.65rem;">agenda</small>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                            <small>Belum ada OPD pengirim agenda.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if (($pendingAgendas ?? collect())->isNotEmpty() || ($pendingBeritas ?? collect())->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3 border-start border-warning border-3">
            <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 small fw-semibold"><i class="bi bi-hourglass-split me-1 text-warning"></i>Antrean Persetujuan ({{ $pendingAgendas->count() + $pendingBeritas->count() }} menunggu)</h6>
                <div class="d-flex gap-3">
                    @if ($pendingAgendas->isNotEmpty())
                        <a href="{{ route('agendas.index', ['status' => 'pending']) }}" class="small text-decoration-none fw-medium text-primary">Review agenda <i class="bi bi-arrow-right ms-1"></i></a>
                    @endif
                    @if ($pendingBeritas->isNotEmpty())
                        <a href="{{ route('beritas.index', ['status' => 'pending']) }}" class="small text-decoration-none fw-medium text-primary">Review berita <i class="bi bi-arrow-right ms-1"></i></a>
                    @endif
                </div>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($pendingAgendas as $agenda)
                    <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2">
                        <i class="bi bi-file-earmark-text text-warning"></i>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate">{{ $agenda->title }}</div>
                            <small class="text-muted">oleh {{ $agenda->user->name ?? '-' }} &middot; {{ $agenda->created_at->diffForHumans() }}</small>
                        </div>
                        <a href="{{ route('agendas.edit', $agenda->uuid) }}" class="btn btn-sm btn-outline-primary">Review</a>
                    </div>
                @endforeach
                @foreach ($pendingBeritas as $berita)
                    <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2">
                        <i class="bi bi-newspaper text-info"></i>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate">{{ $berita->title }}</div>
                            <small class="text-muted">Berita oleh {{ $berita->user->name ?? '-' }} &middot; {{ $berita->created_at->diffForHumans() }}</small>
                        </div>
                        <a href="{{ route('beritas.edit', $berita->uuid) }}" class="btn btn-sm btn-outline-primary">Review</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (($agendaMingguIni ?? collect())->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3 border-start border-info border-3">
            <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 small fw-semibold"><i class="bi bi-calendar-event me-1 text-info"></i>Agenda 7 Hari ke Depan ({{ $agendaMingguIni->count() }})</h6>
                <a href="{{ route('agendas.index', ['waktu' => 'minggu_ini']) }}" class="small text-decoration-none fw-medium text-primary">Lihat semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($agendaMingguIni as $agenda)
                    <a href="{{ route('agendas.show', $agenda->uuid) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 px-3 py-2">
                        <span class="badge {{ ($agenda->jenis_agenda ?? 'offline') === 'online' ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary' }}"><i class="bi {{ ($agenda->jenis_agenda ?? 'offline') === 'online' ? 'bi-globe' : 'bi-geo-alt' }}"></i></span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate">{{ $agenda->title }}</div>
                            <small class="text-muted">{{ optional($agenda->waktu_mulai)->translatedFormat('D, d M H:i') ?? '-' }} &middot; {{ $agenda->category->name ?? '-' }}</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if (($agendaAkanTerbit ?? collect())->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3 border-start border-warning border-3">
            <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 small fw-semibold"><i class="bi bi-clock-history me-1 text-warning"></i>Akan Terbit Otomatis ({{ $agendaAkanTerbit->count() }})</h6>
                <a href="{{ route('agendas.index', ['status' => 'scheduled']) }}" class="small text-decoration-none fw-medium text-primary">Lihat semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($agendaAkanTerbit as $agenda)
                    <a href="{{ route('agendas.show', $agenda->uuid) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 px-3 py-2">
                        <span class="badge bg-warning-subtle text-warning"><i class="bi bi-alarm"></i></span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate">{{ $agenda->title }}</div>
                            <small class="text-muted">{{ optional($agenda->scheduled_at)->translatedFormat('D, d M H:i') ?? '-' }} &middot; {{ $agenda->category->name ?? '-' }}</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if (($pendingTerlama ?? collect())->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3 border-start border-danger border-3">
            <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 small fw-semibold"><i class="bi bi-hourglass-split me-1 text-danger"></i>Pending Terlama ({{ $pendingTerlama->count() }})</h6>
                <a href="{{ route('agendas.index', ['status' => 'pending']) }}" class="small text-decoration-none fw-medium text-primary">Lihat semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($pendingTerlama as $agenda)
                    <a href="{{ route('agendas.show', $agenda->uuid) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 px-3 py-2">
                        <span class="badge bg-danger-subtle text-danger"><i class="bi bi-hourglass"></i></span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate">{{ $agenda->title }}</div>
                            <small class="text-muted">{{ $agenda->user?->name ?? '-' }} &middot; {{ $agenda->created_at->diffForHumans() }}</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if (($responseTimeOpd ?? collect())->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3 border-start border-success border-3">
            <div class="card-header bg-transparent py-2 px-3 border-bottom">
                <h6 class="mb-0 small fw-semibold"><i class="bi bi-speedometer2 me-1 text-success"></i>Response Time OPD (rata-rata approval)</h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($responseTimeOpd as $item)
                    <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold small text-truncate">{{ $item['name'] }}</div>
                        </div>
                        <span class="badge bg-success-subtle text-success">{{ $item['hours'] }} jam</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Activity --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
                    <h5 class="card-title mb-0 small fw-semibold"><i class="bi bi-clock-history me-1"></i>Aktivitas Terbaru</h5>
                    <a href="{{ route('agendas.index') }}" class="small text-decoration-none fw-medium text-primary">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-3 pb-2">
                    <div class="timeline">
                        @foreach ($recentActivities as $activity)
                        <div class="timeline-item {{ $activity['type'] === 'message' ? 'timeline-success' : '' }}">
                            <div class="timeline-icon bg-light-{{ $activity['color'] }} rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                <i class="bi {{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 min-w-0 mb-1">
                                    <span class="badge rounded-pill {{ $activity['badge_class'] }} flex-shrink-0">{{ $activity['label'] }}</span>
                                    <div class="fw-medium small text-truncate">{{ $activity['title'] }}</div>
                                </div>
                                <div class="small text-muted timeline-desc text-truncate">{{ $activity['description'] }}</div>
                            </div>
                            <div class="small text-muted ms-2 d-none d-md-block text-end">{{ $activity['time']->translatedFormat('d M Y, H:i') }}<br><span class="text-muted">{{ $activity['time']->diffForHumans() }}</span></div>
                            <a href="{{ $activity['url'] }}" class="btn btn-outline-{{ $activity['color'] }} btn-sm ms-2 d-inline-flex align-items-center text-nowrap" style="padding: 0.25rem 0.6rem; gap: 4px;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="flex-shrink-0" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8a13 13 0 0 1-1.66 2.043C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>Lihat</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="@hasanyrole('super-admin|admin') col-lg-3 @else col-lg-6 @endhasanyrole">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                    <h5 class="card-title mb-0 small fw-semibold"><i class="bi bi-eye me-1"></i>Paling Banyak Dilihat</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($topViewedContent ?? [] as $tema)
                        <a href="{{ $tema['url'] }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 px-3 py-2">
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <div class="fw-semibold small text-truncate">{{ $tema['title'] }}</div>
                                    <span class="badge rounded-pill {{ $tema['type'] === 'Agenda' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }} flex-shrink-0">{{ $tema['type'] }}</span>
                                </div>
                                <small class="text-muted">{{ $tema['created_at']->translatedFormat('d M Y, H:i') }}</small>
                            </div>
                            <span class="badge bg-light-primary text-primary"><i class="bi bi-eye me-1"></i>{{ number_format($tema['views']) }}</span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4"><small>Belum ada berita atau agenda.</small></div>
                    @endforelse
                </div>
            </div>
        </div>

        @hasanyrole('super-admin|admin')
        <div class="col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                    <h5 class="card-title mb-0 small fw-semibold"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>OPD 7 Hari Tidak Aktif</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($opdTidakAktif ?? [] as $opd)
                        <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <img src="{{ $opd['avatar'] ? (Str::startsWith($opd['avatar'], 'http') ? $opd['avatar'] : asset('storage/' . $opd['avatar'])) : asset('dist/assets/images/avatar.jpg') }}" alt="{{ $opd['name'] }}" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">{{ $opd['name'] }}</div>
                                <small class="text-muted">{{ $opd['last_agenda'] ? 'Terakhir isi: ' . $opd['last_agenda']->diffForHumans() : 'Belum pernah isi agenda' }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4"><small>Semua OPD aktif.</small></div>
                    @endforelse
                </div>
            </div>
        </div>
        @endhasanyrole
    </div>

</div>

@push('after-style')
<style>
    .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; padding: 1rem !important; }
    .stat-card.hover-lift:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important; }
    .stat-trend { display: flex; align-items: center; gap: 0.25rem; margin-top: 0.25rem; font-size: 0.7rem; }
    .stat-trend i { font-size: 0.65rem; }
    .bg-light-primary { background-color: rgba(94, 114, 228, 0.1) !important; }
    .bg-light-success { background-color: rgba(45, 206, 137, 0.1) !important; }
    .bg-light-info { background-color: rgba(13, 202, 240, 0.1) !important; }
    .bg-light-warning { background-color: rgba(251, 99, 64, 0.1) !important; }
    .bg-light-danger { background-color: rgba(245, 54, 92, 0.1) !important; }
    .bg-light-secondary { background-color: rgba(108, 117, 125, 0.1) !important; }
    .bg-light-dark { background-color: rgba(33, 37, 41, 0.1) !important; }
    .bg-light-soft-red { background-color: rgba(245, 54, 92, 0.1) !important; }
    .bg-light-cyan { background-color: rgba(13, 202, 240, 0.1) !important; }
    .timeline-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .timeline-item:last-child { border-bottom: none; }
    .timeline-icon { flex-shrink: 0; }
    .chart-toggle { border-radius: 10px; padding: 6px 14px; font-weight: 600; font-size: 0.82rem; border: 1px solid transparent; display: inline-flex; align-items: center; transition: all .15s ease; }
    .chart-toggle-red { background-color: #fee2e2; color: #b91c1c; border-color: #fecaca; }
    .chart-toggle-red:hover { background-color: #fecaca; color: #991b1b; }
    .chart-toggle-red.active { background-color: #f87171; border-color: #f87171; color: #fff; box-shadow: 0 2px 6px rgba(248, 113, 113, .35); }
    .chart-toggle-green { background-color: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .chart-toggle-green:hover { background-color: #bbf7d0; color: #166534; }
    .chart-toggle-green.active { background-color: #22c55e; border-color: #22c55e; color: #fff; box-shadow: 0 2px 6px rgba(34, 197, 94, .35); }
    .active-visitors-dot { width: 10px; height: 10px; border-radius: 50%; background: #2dce89; box-shadow: 0 0 0 4px rgba(45, 206, 137, .14); flex-shrink: 0; }
</style>
@endpush

@push('after-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>if (typeof window.Chart === 'undefined') { document.write('<script src="{{ asset('dist/assets/extensions/chart.js/chart.umd.js') }}"><\/script>'); }</script>
<script src="{{ asset('js/dashboard/charts.js') }}"></script>
<script>
    async function refreshActiveVisitors() {
        const countElement = document.getElementById('activeVisitorCount');
        const updatedElement = document.getElementById('activeVisitorUpdated');
        if (!countElement || !updatedElement) return;

        try {
            const response = await fetch('{{ route('dashboard.active-visitors') }}', {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) throw new Error('Gagal memuat jumlah pengunjung aktif.');

            const data = await response.json();
            countElement.textContent = Number(data.count || 0).toLocaleString('id-ID');
            updatedElement.textContent = `Diperbarui ${new Date(data.updated_at).toLocaleTimeString('id-ID')}`;
        } catch (error) {
            countElement.textContent = '-';
            updatedElement.textContent = 'Data belum tersedia';
        }
    }

    refreshActiveVisitors();
    const activeVisitorsInterval = setInterval(() => {
        if (!document.hidden) refreshActiveVisitors();
    }, 60000);
    window.addEventListener('beforeunload', () => clearInterval(activeVisitorsInterval));

    setInterval(function () {
        fetch('{{ route('agendas.pendingCount') }}', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var el = document.getElementById('pendingCountNum');
                if (el) { el.textContent = data.count; }
            }).catch(function () {});
        @if ($isAdminViewer ?? false)
            fetch('{{ route('dashboard.beritas.pendingCount') }}', { headers: { 'Accept': 'application/json' } })
                .then(function (r) {
                    if (!r.ok) throw new Error('Gagal memuat jumlah berita pending.');
                    return r.json();
                })
                .then(function (data) {
                    var badge = document.getElementById('pendingBeritaBadge');
                    var count = document.getElementById('pendingBeritaCountNum');
                    if (badge && count) {
                        count.textContent = data.count;
                        badge.classList.toggle('d-none', data.count < 1);
                    }
                })
                .catch(function (error) { console.error(error); });
        @endif
    }, 60000);
</script>
@endpush

@endsection
