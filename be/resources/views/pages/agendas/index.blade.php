@extends('layouts.app')
@section('title', 'Kelola Agenda')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Kelola Agenda" page="Agenda" active="Daftar Agenda" route="{{ route('agendas.index') }}" />
@endsection

<style>
    .agenda-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllAgenda { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarAgenda { border: 1px solid rgba(244, 63, 94, .25); }
    .agenda-table thead th { font-size: .7rem; text-transform: uppercase; letter-spacing: .6px; color: #64748b; font-weight: 700; background: #f8fafc; border-bottom: 2px solid #e9eef5; padding: .7rem .9rem; white-space: nowrap; }
    .agenda-table tbody td { padding: .85rem .9rem; vertical-align: middle; }
    .agenda-main { display: flex; gap: .8rem; align-items: flex-start; min-width: 260px; }
    .agenda-thumb { width: 72px; height: 52px; object-fit: cover; border-radius: 12px; box-shadow: 0 0 0 1px rgba(0,0,0,.07); flex-shrink: 0; }
    .agenda-thumb-placeholder { display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 52px; border-radius: 12px; background: #f1f5f9; color: #94a3b8; font-size: 1.1rem; flex-shrink: 0; }
    .agenda-title { font-size: .92rem; line-height: 1.4; color: #1e293b; }
    .agenda-excerpt { color: #64748b; font-size: .78rem; line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .agenda-meta-row { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 10px; margin-top: 5px; }
    .agenda-meta-inline { color: #64748b; font-size: .74rem; display: inline-flex; align-items: center; gap: 4px; }
    .agenda-meta-inline i { font-size: .8rem; }
    .agenda-meta { display: block; color: #64748b; font-size: .76rem; margin-top: 3px; }
    .agenda-meta i { margin-right: 4px; }
    .agenda-actions { display: inline-flex; gap: 4px; background: #f1f5f9; padding: 4px; border-radius: 10px; }
    .agenda-actions .btn { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; padding: 0; }
    .agenda-actions .btn i { font-size: .85rem; }
    .agenda-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
    .cal-dow { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #64748b; text-align: center; padding: 4px 0; }
    .cal-day { min-height: 88px; border: 1px solid #e9eef5; border-radius: 10px; padding: 6px; cursor: pointer; background: #fff; transition: border-color .15s ease, box-shadow .15s ease; }
    .cal-day:hover { border-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,.12); }
    .cal-day.other-month { opacity: .35; }
    .cal-day.today { border: 2px solid #0d6efd; }
    .cal-day.selected { border-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,.18); }
    .cal-num { font-size: .8rem; font-weight: 700; color: #334155; }
    .cal-dots { display: flex; flex-wrap: wrap; align-items: center; gap: 3px; margin-top: 4px; }
    .cal-dot { display: inline-block; width: 9px; height: 9px; border-radius: 50%; }
    .day-event { border: 1px solid #e9eef5; border-radius: 10px; padding: 8px 10px; background: #fff; font-size: .8rem; }
    .day-event:hover { border-color: #0d6efd; }
    @media (max-width: 767.98px) {
        .agenda-table { font-size: .82rem; }
        .agenda-table thead th, .agenda-table tbody td { padding: .55rem .6rem; }
        .agenda-main { min-width: 0; gap: .6rem; }
        .agenda-thumb, .agenda-thumb-placeholder { width: 56px; height: 42px; border-radius: 8px; font-size: .9rem; }
        .agenda-title { font-size: .85rem; }
        .agenda-excerpt { display: none; }
        .agenda-actions { flex-wrap: wrap; max-width: 108px; }
        .agenda-actions .btn { width: 28px; height: 28px; }
        .agenda-meta-row { gap: 2px 8px; }
        .card-body form .flex-grow-1 { flex-basis: 100%; }
        .cal-day { min-height: 56px; padding: 4px; border-radius: 8px; }
        .cal-num { font-size: .72rem; }
        .cal-dot { width: 7px; height: 7px; }
        #bulkBarAgenda .btn span, #bulkBarAgenda .btn { font-size: .75rem; }
    }
</style>

<section class="section">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Kartu statistik: klik untuk memfilter --}}
    @php
        $activeStat = ($trash ?? false) ? 'trash' : ((($flag ?? '') !== '') ? $flag : ((($status ?? '') !== '') ? $status : 'total'));
    @endphp
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><a href="{{ route('agendas.index') }}" class="text-decoration-none"><div class="card shadow-sm h-100 {{ $activeStat === 'total' ? 'border border-primary' : '' }}"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-news"></i></div><div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Agenda</div></div></div></div></div></a></div>
        <div class="col-6 col-md-4 col-xl"><a href="{{ route('agendas.index', ['status' => 'published']) }}" class="text-decoration-none"><div class="card shadow-sm h-100 {{ $activeStat === 'published' ? 'border border-primary' : '' }}"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['published']) }}</div><div class="ml-stat-label">Published</div></div></div></div></div></a></div>
        <div class="col-6 col-md-4 col-xl"><a href="{{ route('agendas.index', ['status' => 'pending']) }}" class="text-decoration-none"><div class="card shadow-sm h-100 {{ $activeStat === 'pending' ? 'border border-primary' : '' }}"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-hourglass-split"></i></div><div><div class="ml-stat-value">{{ number_format($stats['pending']) }}</div><div class="ml-stat-label">Pending Approval</div></div></div></div></div></a></div>
        <div class="col-6 col-md-4 col-xl">
            @if ($isApprover)
            <a href="{{ route('agendas.index', ['trash' => 1]) }}" class="text-decoration-none">
            @else
            <span class="text-decoration-none" style="cursor: not-allowed;" title="Hanya admin yang dapat mengakses arsip">
            @endif
            <div class="card shadow-sm h-100 {{ $activeStat === 'trash' ? 'border border-primary' : '' }}">
                <div class="card-body py-2 px-3">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bi bi-archive"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['trash']) }}</div>
                            <div class="ml-stat-label">Arsip</div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($isApprover)
            </a>
            @else
            </span>
            @endif
        </div>
        <div class="col-6 col-md-4 col-xl"><a href="{{ route('agendas.index', ['flag' => 'featured']) }}" class="text-decoration-none"><div class="card shadow-sm h-100 {{ $activeStat === 'featured' ? 'border border-primary' : '' }}"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bxs-star"></i></div><div><div class="ml-stat-value">{{ number_format($stats['featured']) }}</div><div class="ml-stat-label">Featured</div></div></div></div></div></a></div>
        <div class="col-6 col-md-4 col-xl"><a href="{{ route('agendas.index', ['flag' => 'popular']) }}" class="text-decoration-none"><div class="card shadow-sm h-100 {{ $activeStat === 'popular' ? 'border border-primary' : '' }}"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-danger"><i class="bx bxs-flame"></i></div><div><div class="ml-stat-value">{{ number_format($stats['popular']) }}</div><div class="ml-stat-label">Populer</div></div></div></div></div></a></div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <form method="GET" action="{{ route('agendas.index') }}">
                @if(($view ?? 'daftar') === 'kalender')
                    <input type="hidden" name="view" value="kalender">
                @endif
                <div class="d-flex flex-wrap align-items-end gap-2">
                    <div class="flex-grow-1" style="min-width:200px;">
                        <label class="form-label small mb-0" for="filterSearch">Cari</label>
                        <input type="text" name="search" id="filterSearch" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Judul atau slug...">
                    </div>
                    <div>
                        <label class="form-label small mb-0" for="filterStatus">Status</label>
                        <select name="status" id="filterStatus" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="">Semua</option>
                            <option value="published" @selected(($status ?? '')==='published')>Published</option>
                            <option value="pending" @selected(($status ?? '')==='pending')>Pending Approval</option>
                            <option value="draft" @selected(($status ?? '')==='draft')>Draft</option>
                            <option value="scheduled" @selected(($status ?? '')==='scheduled')>Scheduled</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-0" for="filterJenis">Jenis</label>
                        <select name="jenis" id="filterJenis" class="form-select form-select-sm" style="min-width:110px;">
                            <option value="">Semua</option>
                            <option value="offline" @selected(($jenis ?? '')==='offline')>Offline</option>
                            <option value="online" @selected(($jenis ?? '')==='online')>Online</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-0" for="filterSort">Urutkan</label>
                        <select name="sort" id="filterSort" class="form-select form-select-sm" style="min-width:140px;">
                            <option value="terbaru" @selected(($sort ?? 'terbaru')==='terbaru')>Terbaru</option>
                            <option value="terdekat" @selected(($sort ?? '')==='terdekat')>Waktu terdekat</option>
                            <option value="populer" @selected(($sort ?? '')==='populer')>Paling dilihat</option>
                            <option value="judul" @selected(($sort ?? '')==='judul')>Judul A-Z</option>
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary" title="Terapkan filter"><i class="bi bi-funnel"></i> Filter</button>
                        <a href="{{ route('agendas.index', ($view ?? 'daftar') === 'kalender' ? ['view' => 'kalender'] : []) }}" class="btn btn-sm btn-secondary" title="Reset filter">Reset</a>
                        <a href="{{ route('agendas.export', request()->query()) }}" class="btn btn-sm btn-success" title="Ekspor CSV hasil filter"><i class="bi bi-download"></i></a>
                        <a href="{{ route('agendas.import') }}" class="btn btn-sm btn-dark" title="Import agenda dari Excel/CSV"><i class="bi bi-upload"></i></a>
                        <a href="{{ route('agendas.logAudit') }}" class="btn btn-sm btn-info" title="Log Audit Approval"><i class="bi bi-clock-history"></i></a>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#agendaAdvFilter" title="Filter lanjutan (tanggal & waktu)"><i class="bi bi-sliders"></i></button>
                    </div>
                    <div class="d-flex gap-1 ms-auto">
                        @can('agendas.store')
                            <a href="{{ route('agendas.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
                        @endcan
                        @if ($isApprover)
                            @if ($trash)
                                <a href="{{ route('agendas.index') }}" class="btn btn-sm btn-danger"><i class="bi bi-arrow-left"></i> Kembali</a>
                            @else
                                <a href="{{ route('agendas.index', ['trash' => 1]) }}" class="btn btn-sm btn-danger"><i class="bi bi-archive"></i> Arsip</a>
                            @endif
                        @else
                                <span class="btn btn-sm btn-danger" style="cursor: not-allowed;" title="Hanya admin yang dapat mengakses arsip"><i class="bi bi-archive"></i> Arsip</span>
                        @endif
                    </div>
                </div>
                <div class="collapse mt-2 @if(($start_date ?? '') !== '' || ($end_date ?? '') !== '' || ($waktu ?? 'semua') !== 'semua') show @endif" id="agendaAdvFilter">
                    <div class="d-flex flex-wrap align-items-end gap-2 border-top pt-2">
                        <div>
                            <label class="form-label small mb-0">Dari tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $start_date ?? '' }}">
                        </div>
                        <div>
                            <label class="form-label small mb-0">Sampai tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $end_date ?? '' }}">
                        </div>
                        <div>
                            <label class="form-label small mb-0">Waktu kegiatan</label>
                            <select name="waktu" class="form-select form-select-sm" style="min-width:150px;">
                                <option value="semua" @selected(($waktu ?? 'semua')==='semua')>Semua waktu</option>
                                <option value="hari_ini" @selected(($waktu ?? '')==='hari_ini')>Hari ini</option>
                                <option value="minggu_ini" @selected(($waktu ?? '')==='minggu_ini')>Minggu ini</option>
                                <option value="bulan_ini" @selected(($waktu ?? '')==='bulan_ini')>Bulan ini</option>
                                <option value="terlewat" @selected(($waktu ?? '')==='terlewat')>Sudah lewat</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label small mb-0">Bulan laporan</label>
                            <input type="month" name="bulan" class="form-control form-control-sm" value="{{ request('bulan', now()->format('Y-m')) }}">
                        </div>
                        <div class="d-flex gap-1">
                            <button type="submit" formaction="{{ route('agendas.laporan') }}" formmethod="get" formtarget="_blank" class="btn btn-sm btn-danger" title="Unduh laporan PDF bulan terpilih"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
                            <button type="submit" formaction="{{ route('agendas.laporanExcel') }}" formmethod="get" class="btn btn-sm btn-success" title="Unduh laporan Excel bulan terpilih"><i class="bi bi-file-earmark-spreadsheet"></i> Excel</button>
                            <button type="submit" formaction="{{ route('agendas.laporanPptx') }}" formmethod="get" class="btn btn-sm btn-warning" title="Unduh laporan PowerPoint bulan terpilih"><i class="bi bi-file-earmark-ppt"></i> PowerPoint</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">{{ ($trash ?? false) ? 'Arsip Agenda' : 'Daftar Agenda' }}</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($stats['total']) }} agenda</span>
            </div>
            <div class="btn-group btn-group-sm" role="group" aria-label="Tampilan agenda">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'daftar']) }}" class="btn {{ ($view ?? 'daftar') !== 'kalender' ? 'btn-primary' : 'btn-outline-primary bg-white' }}"><i class="bi bi-list-ul me-1"></i>Daftar</a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'kalender']) }}" class="btn {{ ($view ?? 'daftar') === 'kalender' ? 'btn-primary' : 'btn-outline-primary bg-white' }}"><i class="bi bi-calendar3 me-1"></i>Kalender</a>
            </div>
        </div>

        @if(($view ?? 'daftar') !== 'kalender')

        @can('agendas.destroy')
        <div id="bulkBarAgenda" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 bg-danger-subtle">
            <span class="small fw-semibold text-danger"><i class="bi bi-check-square me-1"></i><span id="bulkCountAgenda">0</span> agenda dipilih</span>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAgendaSelection()">Batal</button>
                @if (! $trash)
                    @can('agendas.approve')
                        <button type="button" class="btn btn-sm btn-primary" onclick="bulkApproveAgenda()"><i class="bi bi-check-lg"></i> Setujui terpilih</button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="bulkRejectAgenda()"><i class="bi bi-arrow-counterclockwise"></i> Ke Draft terpilih</button>
                    @endcan
                    <button type="button" class="btn btn-sm btn-dark" onclick="bulkSetStatusAgenda()"><i class="bi bi-flag"></i> Ubah Status</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkDeleteAgenda()"><i class="bi bi-trash"></i> Hapus terpilih</button>
                @endif
                @if ($trash)
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkRestoreAgenda()"><i class="bi bi-arrow-counterclockwise"></i> Pulih terpilih</button>
                @endif
            </div>
        </div>
        <form id="bulkDeleteAgendaForm" action="{{ route('agendas.bulkDestroy') }}" method="POST" class="d-none">
            @csrf @method('DELETE')
            <div id="bulkIdsAgenda"></div>
        </form>
        <form id="bulkRestoreAgendaForm" action="{{ route('agendas.bulkRestore') }}" method="POST" class="d-none">
            @csrf @method('PUT')
            <div id="bulkRestoreIdsAgenda"></div>
        </form>
        <form id="bulkApproveAgendaForm" action="{{ route('agendas.bulkApprove') }}" method="POST" class="d-none">
            @csrf
            <div id="bulkApproveIdsAgenda"></div>
        </form>
        <form id="bulkStatusAgendaForm" action="{{ route('agendas.bulkStatus') }}" method="POST" class="d-none">
            @csrf
            <div id="bulkStatusIdsAgenda"></div>
        </form>
        @endcan

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 agenda-table" style="font-size:0.9rem;">
                    <thead>
                        <tr>
                            @can('agendas.destroy')
                                <th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllAgenda" class="form-check-input" title="Pilih semua"></th>
                            @endcan
                            <th>Agenda</th>
                            <th class="hide-sm" style="min-width:210px;">Pelaksanaan</th>
                            <th>Status</th>
                            <th class="text-end" style="width:130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendas as $item)
                            @php
                                $jenis = $item->jenis_agenda ?? 'offline';
                            @endphp
                            <tr>
                                @can('agendas.destroy')
                                    <td class="text-center"><input type="checkbox" class="form-check-input agenda-check" value="{{ $item->uuid }}"></td>
                                @endcan
                                <td>
                                    <div class="agenda-main">
                                        @if($item->thumbnail_url)
                                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="agenda-thumb" loading="lazy">
                                        @else
                                            <span class="agenda-thumb-placeholder"><i class="bi bi-image"></i></span>
                                        @endif
                                        <div>
                                            <span class="fw-semibold d-block agenda-title">
                                                {{ Str::limit($item->title, 60) }}
                                                @if($item->is_featured)
                                                    <i class="bi bi-star-fill text-warning ms-1" title="Featured"></i>
                                                @endif
                                                @if($item->is_popular)
                                                    <i class="bi bi-fire text-danger ms-1" title="Populer"></i>
                                                @endif
                                                @if($item->is_urgent)
                                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Urgent"></i>
                                                @endif
                                            </span>
                                            @if($item->excerpt)
                                                <small class="agenda-excerpt">{{ Str::limit($item->excerpt, 90) }}</small>
                                            @endif
                                            <div class="agenda-meta-row">
                                                <span class="agenda-meta-inline"><i class="bi bi-folder2-open text-primary"></i>{{ $item->category->name ?? '-' }}</span>
                                                <span class="agenda-meta-inline"><i class="bi bi-person text-primary"></i>{{ $item->user->name ?? '-' }}</span>
                                                <span class="agenda-meta-inline"><i class="bi bi-eye text-primary"></i>{{ number_format($item->views) }}</span>
                                                <span class="agenda-meta-inline"><i class="bi bi-calendar3 text-primary"></i>{{ optional($item->scheduled_at)->format('d M y') ?? '-' }}</span>
                                                @if($item->waktu_mulai)
                                                    <span class="agenda-meta-inline d-md-none"><i class="bi bi-clock text-primary"></i>{{ optional($item->waktu_mulai)->format('d M y, H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="hide-sm">
                                    <div class="d-flex flex-wrap align-items-center gap-1">
                                        <span class="badge rounded-pill {{ $jenis === 'online' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }}"><i class="bi {{ $jenis === 'online' ? 'bi-globe' : 'bi-geo-alt' }} me-1"></i>{{ $jenis === 'online' ? 'Online' : 'Offline' }}</span>
                                        @if($item->waktu_mulai)
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ optional($item->waktu_mulai)->format('d M y, H:i') }}
                                                @if($item->waktu_akhir)
                                                    - {{ optional($item->waktu_akhir)->format('H:i') }}
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                    @if($item->lokasi)
                                        <small class="agenda-meta"><i class="bi bi-pin-map"></i>{{ Str::limit($item->lokasi, 34) }}</small>
                                    @endif
                                    @if($item->url)
                                        <small class="agenda-meta"><i class="bi bi-link-45deg"></i><a href="{{ $item->url }}" target="_blank" rel="noopener">Buka tautan</a></small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status==='published')
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
                                    @elseif($item->status==='pending')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle" title="Menunggu persetujuan admin"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                    @elseif($item->status==='draft')
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
                                    @elseif($item->status==='scheduled')
                                        <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle">Scheduled</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">{{ ucfirst($item->status) }}</span>
                                    @endif
                                    @if($item->is_selesai)
                                        <span class="badge rounded-pill bg-secondary text-white mt-1" title="Waktu kegiatan sudah lewat">Selesai</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="agenda-actions">
                                        <a href="{{ route('agendas.show', $item->uuid) }}" class="btn btn-sm btn-info" title="Lihat detail"><i class="bi bi-eye"></i></a>
                                        @if(! $trash)
                                            @can('agendas.approve')
                                                @if($item->status==='pending')
                                                    <form action="{{ route('agendas.approve', $item->uuid) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-primary" title="Setujui & publikasikan"><i class="bi bi-check-lg"></i></button></form>
                                                    <button type="button" onclick="rejectAgenda('{{ $item->uuid }}')" class="btn btn-sm btn-warning text-dark" title="Tolak, kembalikan ke draft"><i class="bi bi-x-lg"></i></button>
                                                    <button type="button" onclick="mintaRevisiAgenda('{{ $item->uuid }}')" class="btn btn-sm btn-info text-white" title="Minta revisi (tetap pending)"><i class="bi bi-pencil-square"></i></button>
                                                @endif
                                            @endcan
                                            @can('agendas.update')
                                                <a href="{{ route('agendas.edit', $item->uuid) }}" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>
                                            @endcan
                                            @if($isApprover)
                                                <button type="button" onclick="duplicateAgenda('{{ $item->uuid }}')" class="btn btn-sm btn-secondary" title="Duplikat agenda"><i class="bi bi-copy"></i></button>
                                            @endif
                                        @endif
                                        @can('agendas.destroy')
                                            @if($item->deleted_at)
                                                @if($isApprover)
                                                <a onclick="restoreAgenda('{{ $item->uuid }}')" class="btn btn-sm btn-success" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></a>
                                                <a onclick="if(confirm('Hapus permanen? Tidak bisa dipulihkan.')) document.getElementById('forceForm_{{ $item->uuid }}').submit()" class="btn btn-sm btn-danger" title="Hapus Permanen"><i class="bi bi-x-octagon"></i></a>
                                                <form id="restoreForm_{{ $item->uuid }}" action="{{ route('agendas.restore', $item->uuid) }}" method="POST" class="d-none">@method('PUT')@csrf</form>
                                                <form id="forceForm_{{ $item->uuid }}" action="{{ route('agendas.forceDestroy', $item->uuid) }}" method="POST" class="d-none">@method('DELETE')@csrf</form>
                                                @endif
                                            @else
                                                <a onclick="showSweetAlert('{{ $item->uuid }}')" class="btn btn-sm btn-danger" title="Arsipkan"><i class="bi bi-trash"></i></a>
                                                <form id="deleteForm_{{ $item->uuid }}" action="{{ route('agendas.destroy', $item->uuid) }}" method="POST" class="d-none">@method('DELETE')@csrf<input type="hidden" name="alasan_arsip" id="alasanArsip_{{ $item->uuid }}" value="lainnya"></form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada agenda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">Menampilkan {{ $agendas->firstItem() }}-{{ $agendas->lastItem() }} dari {{ $agendas->total() }}</small>
                <div>{{ $agendas->links('pagination::minimal') }}</div>
            </div>
        </div>
    </div>
    @endif

    @if(($view ?? 'daftar') === 'kalender')
    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="calPrev" title="Bulan sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <h6 class="mb-0" id="calTitle">Kalender</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="calNext" title="Bulan berikutnya"><i class="bi bi-chevron-right"></i></button>
                <button type="button" class="btn btn-sm btn-primary" id="calToday">Hari ini</button>
            </div>
            <div class="d-flex align-items-center gap-2 small text-muted flex-wrap">
                <span><span class="cal-dot" style="background:#198754;"></span>Published</span>
                <span><span class="cal-dot" style="background:#ffc107;"></span>Pending</span>
                <span><span class="cal-dot" style="background:#6c757d;"></span>Draft</span>
                <span><span class="cal-dot" style="background:#0dcaf0;"></span>Scheduled</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="agenda-cal-grid" id="agendaCalGrid"></div>
                </div>
                <div class="col-lg-4">
                    <div class="border rounded p-3 h-100 bg-light-subtle">
                        <h6 class="mb-0" id="dayPanelTitle">Pilih tanggal</h6>
                        <small class="text-muted d-block mb-2" id="dayPanelSub">Klik tanggal pada kalender untuk melihat agendanya.</small>
                        <div id="dayPanelList" class="d-flex flex-column gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

<script>
    function showSweetAlert(getId) {
        Swal.fire({title:'Hapus agenda?', text:'Akan dipindahkan ke arsip.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', input:'select', inputOptions:{terlaksana:'Terlaksana', dibatalkan:'Dibatalkan', lainnya:'Lainnya'}, inputPlaceholder:'Alasan arsip', inputValue:'lainnya'}).then(r=>{ if(r.isConfirmed){ document.getElementById('alasanArsip_'+getId).value = r.value || 'lainnya'; document.getElementById('deleteForm_'+getId).submit(); } });
    }

    function restoreAgenda(getId) {
        Swal.fire({title:'Pulihkan agenda?', text:'Akan dikembalikan dari arsip.', icon:'question', showCancelButton:true, confirmButtonText:'Ya, Pulihkan!'}).then(r=>{ if(r.isConfirmed) document.getElementById('restoreForm_'+getId).submit(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAllAgenda');
        const bulkBar = document.getElementById('bulkBarAgenda');
        const bulkCount = document.getElementById('bulkCountAgenda');
        if (!checkAll || !bulkBar) return;

        function updateBulkBar() {
            const checks = document.querySelectorAll('.agenda-check');
            const selected = document.querySelectorAll('.agenda-check:checked');
            const n = selected.length;
            bulkCount.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
            checks.forEach(c => c.closest('tr').classList.toggle('row-selected', c.checked));
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.agenda-check').forEach(c => { c.checked = this.checked; });
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('agenda-check')) updateBulkBar();
        });

        window.clearAgendaSelection = function () {
            document.querySelectorAll('.agenda-check:checked').forEach(c => { c.checked = false; });
            updateBulkBar();
        };

        window.bulkDeleteAgenda = function () {
            const ids = Array.from(document.querySelectorAll('.agenda-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Hapus ' + ids.length + ' agenda?', text:'Data terpilih akan dipindahkan ke arsip.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkDeleteAgendaForm');
                    const box = document.getElementById('bulkIdsAgenda');
                    box.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        window.bulkRestoreAgenda = function () {
            const ids = Array.from(document.querySelectorAll('.agenda-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Pulihkan ' + ids.length + ' agenda?', text:'Data terpilih akan dikembalikan dari arsip.', icon:'question', showCancelButton:true, confirmButtonText:'Ya, Pulihkan!'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkRestoreAgendaForm');
                    const box = document.getElementById('bulkRestoreIdsAgenda');
                    box.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        window.bulkRejectAgenda = function () {
            const ids = Array.from(document.querySelectorAll('.agenda-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Kembalikan ' + ids.length + ' agenda ke draft?', icon:'question', showCancelButton:true, confirmButtonText:'Ya, ke Draft!'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkApproveAgendaForm');
                    const box = document.getElementById('bulkApproveIdsAgenda');
                    box.innerHTML = '';
                    let aksiInput = document.createElement('input');
                    aksiInput.type = 'hidden'; aksiInput.name = 'aksi'; aksiInput.value = 'draft';
                    box.appendChild(aksiInput);
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        window.bulkApproveAgenda = function () {
            const ids = Array.from(document.querySelectorAll('.agenda-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Setujui ' + ids.length + ' agenda?', text:'Agenda pending terpilih akan dipublikasikan.', icon:'question', showCancelButton:true, confirmButtonText:'Ya, Setujui!'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkApproveAgendaForm');
                    const box = document.getElementById('bulkApproveIdsAgenda');
                    box.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        window.bulkSetStatusAgenda = function () {
            const ids = Array.from(document.querySelectorAll('.agenda-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({
                title: 'Ubah Status ' + ids.length + ' Agenda',
                icon: 'question',
                html:
                    '<div class="text-start">' +
                    '<label class="form-label small mb-1">Status Tujuan</label>' +
                    '<select id="bulkStatusSelect" class="form-select form-select-sm">' +
                    '<option value="draft">Draft</option>' +
                    '<option value="scheduled">Scheduled</option>' +
                    '<option value="pending">Pending</option>' +
                    '</select>' +
                    '<div id="bulkScheduledWrap" class="mt-2 d-none">' +
                    '<label class="form-label small mb-1">Tanggal Terbit</label>' +
                    '<input type="datetime-local" id="bulkScheduledAt" class="form-control form-control-sm">' +
                    '</div>' +
                    '</div>',
                showCancelButton: true,
                confirmButtonText: 'Ubah Status',
                didOpen: function () {
                    const sel = document.getElementById('bulkStatusSelect');
                    const wrap = document.getElementById('bulkScheduledWrap');
                    sel.addEventListener('change', function () {
                        wrap.classList.toggle('d-none', sel.value !== 'scheduled');
                    });
                },
                preConfirm: function () {
                    const status = document.getElementById('bulkStatusSelect').value;
                    const scheduledAt = document.getElementById('bulkScheduledAt').value;
                    if (status === 'scheduled' && !scheduledAt) {
                        Swal.showValidationMessage('Tanggal terbit wajib diisi untuk status scheduled!');
                        return false;
                    }
                    return { status: status, scheduledAt: scheduledAt };
                }
            }).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkStatusAgendaForm');
                    const box = document.getElementById('bulkStatusIdsAgenda');
                    box.innerHTML = '';
                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden'; statusInput.name = 'status'; statusInput.value = r.value.status;
                    box.appendChild(statusInput);
                    if (r.value.scheduledAt) {
                        const schInput = document.createElement('input');
                        schInput.type = 'hidden'; schInput.name = 'scheduled_at'; schInput.value = r.value.scheduledAt;
                        box.appendChild(schInput);
                    }
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        updateBulkBar();
    });

    function duplicateAgenda(getId) {
        Swal.fire({title:'Duplikat agenda?', text:'Salinan akan dibuka di halaman edit agar jadwal bisa disesuaikan.', icon:'question', showCancelButton:true, confirmButtonText:'Ya, Duplikat!'}).then(r => {
            if (r.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url('backend/agendas') }}/' + getId + '/duplicate';
                const token = document.createElement('input');
                token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function rejectAgenda(getId) {
        Swal.fire({
            title: 'Tolak agenda?',
            text: 'Tulis alasan penolakan agar OPD tahu yang harus diperbaiki.',
            input: 'textarea',
            inputPlaceholder: 'Contoh: lokasi belum jelas, mohon dilengkapi...',
            inputAttributes: {maxlength: 500, rows: 3},
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak!',
            confirmButtonColor: '#f59e0b',
            inputValidator: function (value) {
                if (!value || !value.trim()) return 'Alasan penolakan wajib diisi!';
                return null;
            }
        }).then(r => {
            if (r.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url('backend/agendas') }}/' + getId + '/reject';
                const token = document.createElement('input');
                token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
                const note = document.createElement('input');
                note.type = 'hidden'; note.name = 'catatan'; note.value = r.value.trim();
                form.appendChild(token);
                form.appendChild(note);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function mintaRevisiAgenda(getId) {
        Swal.fire({
            title: 'Minta revisi?',
            text: 'Catatan revisi akan dikirim ke penulis. Agenda tetap pending.',
            input: 'textarea',
            inputPlaceholder: 'Contoh: lengkapi lokasi, tambahkan URL Zoom...',
            inputAttributes: {maxlength: 500, rows: 3},
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            inputValidator: function (value) {
                if (!value || !value.trim()) return 'Catatan revisi wajib diisi!';
                return null;
            }
        }).then(r => {
            if (r.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url('backend/agendas') }}/' + getId + '/minta-revisi';
                const token = document.createElement('input');
                token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
                const note = document.createElement('input');
                note.type = 'hidden'; note.name = 'catatan'; note.value = r.value.trim();
                form.appendChild(token);
                form.appendChild(note);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<script>
    // Kalender agenda (vanilla JS, Senin sebagai awal pekan)
    (function () {
        const grid = document.getElementById('agendaCalGrid');
        if (!grid) return;

        const CAL_URL = '{{ route('agendas.calendarData') }}';
        const SHOW_BASE = '{{ url('backend/agendas') }}';
        const STATUS_COLOR = {published: '#198754', pending: '#ffc107', draft: '#6c757d', scheduled: '#0dcaf0'};
        const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        let cur = new Date();
        cur.setDate(1);
        const cache = {};

        function pad(n) { return (n < 10 ? '0' : '') + n; }

        function esc(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
                return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c];
            });
        }

        function filterParams() {
            const p = {};
            ['search', 'status', 'jenis'].forEach(function (n) {
                const el = document.querySelector('[name="' + n + '"]');
                if (el && el.value) p[n] = el.value;
            });
            return p;
        }

        function loadMonth() {
            const y = cur.getFullYear(), m = cur.getMonth();
            document.getElementById('calTitle').textContent = MONTHS[m] + ' ' + y;
            const k = y + '-' + pad(m + 1);
            if (cache[k]) { render(cache[k], y, m); return; }

            const first = k + '-01';
            const last = k + '-' + pad(new Date(y, m + 1, 0).getDate());
            const params = new URLSearchParams(Object.assign({start: first, end: last}, filterParams()));
            grid.innerHTML = '<div class="text-muted small p-3">Memuat kalender...</div>';

            fetch(CAL_URL + '?' + params.toString(), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(function (r) { return r.json(); })
                .then(function (data) { cache[k] = data.events || []; render(cache[k], y, m); })
                .catch(function () { grid.innerHTML = '<div class="text-danger small p-3">Gagal memuat kalender.</div>'; });
        }

        function dayCell(dt, other, byDate) {
            const ds = dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate());
            const evs = byDate[ds] || [];
            const isToday = dt.toDateString() === new Date().toDateString();
            let dots = '';
            evs.slice(0, 3).forEach(function (e) {
                dots += '<span class="cal-dot" style="background:' + (STATUS_COLOR[e.status] || '#6c757d') + ';" title="' + esc(e.title) + '"></span>';
            });
            if (evs.length > 3) dots += '<small class="text-muted">+' + (evs.length - 3) + '</small>';
            return '<div class="cal-day' + (other ? ' other-month' : '') + (isToday ? ' today' : '') + '" data-date="' + ds + '">'
                + '<span class="cal-num">' + dt.getDate() + '</span>'
                + '<span class="cal-dots">' + dots + '</span></div>';
        }

        function render(events, y, m) {
            const byDate = {};
            events.forEach(function (e) { (byDate[e.date] = byDate[e.date] || []).push(e); });

            let html = '';
            ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'].forEach(function (n) { html += '<div class="cal-dow">' + n + '</div>'; });

            let lead = (new Date(y, m, 1).getDay() + 6) % 7;
            const prevLen = new Date(y, m, 0).getDate();
            for (let i = lead - 1; i >= 0; i--) html += dayCell(new Date(y, m - 1, prevLen - i), true, byDate);

            const days = new Date(y, m + 1, 0).getDate();
            for (let d = 1; d <= days; d++) html += dayCell(new Date(y, m, d), false, byDate);

            const tail = (7 - ((lead + days) % 7)) % 7;
            for (let d = 1; d <= tail; d++) html += dayCell(new Date(y, m + 1, d), true, byDate);

            grid.innerHTML = html;
            grid.querySelectorAll('.cal-day[data-date]').forEach(function (el) {
                el.addEventListener('click', function () {
                    grid.querySelectorAll('.cal-day.selected').forEach(function (s) { s.classList.remove('selected'); });
                    el.classList.add('selected');
                    showDay(el.getAttribute('data-date'), byDate);
                });
            });

            const t = new Date();
            showDay(t.getFullYear() + '-' + pad(t.getMonth() + 1) + '-' + pad(t.getDate()), byDate);
        }

        function showDay(ds, byDate) {
            const evs = byDate[ds] || [];
            const dt = new Date(ds + 'T00:00:00');
            const label = isNaN(dt.getTime()) ? ds : DAYS[(dt.getDay() + 6) % 7] + ', ' + dt.getDate() + ' ' + MONTHS[dt.getMonth()] + ' ' + dt.getFullYear();
            document.getElementById('dayPanelTitle').textContent = label;
            document.getElementById('dayPanelSub').textContent = evs.length ? evs.length + ' agenda pada tanggal ini.' : 'Tidak ada agenda pada tanggal ini.';

            const box = document.getElementById('dayPanelList');
            box.innerHTML = '';
            evs.forEach(function (e) {
                const time = (e.start_time || '') + (e.end_time ? ' - ' + e.end_time : '');
                const item = document.createElement('a');
                item.href = SHOW_BASE + '/' + e.id;
                item.className = 'day-event text-decoration-none text-dark d-block';
                item.innerHTML = '<span class="d-flex align-items-center gap-1 mb-1">'
                    + '<span class="cal-dot" style="background:' + (STATUS_COLOR[e.status] || '#6c757d') + ';"></span>'
                    + '<strong>' + esc(e.status) + '</strong>'
                    + '<span class="badge ' + (e.jenis === 'online' ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary') + ' ms-auto">' + esc(e.jenis) + '</span></span>'
                    + '<span class="fw-semibold d-block">' + esc(e.title) + '</span>'
                    + '<small class="text-muted">' + esc(time) + (e.lokasi ? ' &middot; ' + esc(e.lokasi) : '') + '</small>';
                box.appendChild(item);
            });
        }

        document.getElementById('calPrev').addEventListener('click', function () { cur.setMonth(cur.getMonth() - 1); loadMonth(); });
        document.getElementById('calNext').addEventListener('click', function () { cur.setMonth(cur.getMonth() + 1); loadMonth(); });
        document.getElementById('calToday').addEventListener('click', function () { cur = new Date(); cur.setDate(1); loadMonth(); });

        loadMonth();
    })();
</script>
@endsection
