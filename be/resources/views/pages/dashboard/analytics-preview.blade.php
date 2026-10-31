@extends('layouts.app')
@section('title', 'Preview Laporan Analitik')

@section('breadcrumb')
<x-breadcrumb title="Laporan Analitik" page="Dashboard" active="Preview Laporan" route="{{ route('dashboard.index') }}" />
@endsection

@section('content')
@push('after-style')
<style>
    .report-paper { background: #fff; max-width: 920px; margin: 0 auto; box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.12); border-radius: 6px; overflow: hidden; }
    .report-kop { border-bottom: 4px double #1e293b; }
    .report-kop h4 { letter-spacing: 1px; }
    .report-sec-title { border-left: 4px solid #0a4d8e; padding-left: 10px; font-weight: 700; }
    .report-kpi { border: 1px solid #e2e8f0; border-radius: 10px; }
    .report-bar-track { height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
    .report-bar-fill { height: 100%; border-radius: 99px; }
    .report-daily { display: flex; align-items: flex-end; gap: 3px; height: 130px; }
    .report-daily .bar { flex: 1; min-width: 4px; background: linear-gradient(180deg, #60a5fa, #0a4d8e); border-radius: 3px 3px 0 0; }
    @media print {
        .report-toolbar, .page-title, .breadcrumb { display: none !important; }
        .report-paper { box-shadow: none; max-width: 100%; }
    }
</style>
@endpush

<div class="container-fluid pb-5">

    {{-- Toolbar aksi (tidak ikut tercetak) --}}
    <div class="report-toolbar d-flex flex-wrap align-items-center gap-2 mb-3">
        <a href="{{ route('dashboard.index', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <span class="badge bg-info text-dark ms-1"><i class="bi bi-calendar3 me-1"></i>{{ $dateRangeLabel }} ({{ $daysDiff }} hari)</span>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('dashboard.analytics.pdf', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    {{-- Kertas laporan --}}
    <div class="report-paper">
        <div class="p-4 p-md-5">

            {{-- KOP --}}
            <div class="report-kop d-flex align-items-center gap-3 pb-3 mb-4">
                @if($identity->logoUrl())
                    <img src="{{ $identity->logoUrl() }}" alt="Logo" style="height:72px; width:auto; object-fit:contain;">
                @endif
                <div class="text-center flex-grow-1">
                    <h4 class="fw-bold mb-0">PEMERINTAH KOTA BEKASI</h4>
                    <h5 class="fw-semibold mb-1">{{ $identity->site_name ?? 'Portal Resmi' }}</h5>
                    <div class="small text-muted">{{ $identity->address ?? '' }}</div>
                    <div class="small text-muted">{{ $identity->phone ?? '' }}{{ $identity->phone && $identity->email ? ' • ' : '' }}{{ $identity->email ?? '' }}</div>
                </div>
            </div>

            {{-- Judul laporan --}}
            <div class="text-center mb-4">
                <h5 class="fw-bold mb-1 text-decoration-underline">LAPORAN ANALITIK WEBSITE</h5>
                <div class="small text-muted">Periode: {{ $dateRangeLabel }} ({{ $daysDiff }} hari)</div>
                <div class="small text-muted">Dibuat pada {{ $generatedAt->translatedFormat('d F Y H:i') }} WIB oleh {{ $generatedBy }}</div>
            </div>

            {{-- Ringkasan eksekutif --}}
            <h6 class="report-sec-title mb-3">I. RINGKASAN EKSEKUTIF</h6>
            <div class="row g-3 mb-4">
                <div class="col-6 @if($isAdminViewer) col-lg-3 @else col-lg-4 @endif">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Total Kunjungan</div>
                        <div class="fw-bold fs-4">{{ number_format($visitorStats['total_visits']) }}</div>
                        <span class="badge {{ $visitorGrowth >= 0 ? 'bg-success' : 'bg-danger' }}">{{ $visitorGrowth >= 0 ? '+' : '' }}{{ $visitorGrowth }}%</span>
                    </div>
                </div>
                <div class="col-6 @if($isAdminViewer) col-lg-3 @else col-lg-4 @endif">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Pengunjung Unik</div>
                        <div class="fw-bold fs-4">{{ number_format($visitorStats['unique_visitors']) }}</div>
                        <span class="badge {{ $uniqueGrowth >= 0 ? 'bg-success' : 'bg-danger' }}">{{ $uniqueGrowth >= 0 ? '+' : '' }}{{ $uniqueGrowth }}%</span>
                    </div>
                </div>
                <div class="col-6 @if($isAdminViewer) col-lg-3 @else col-lg-4 @endif">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Total Agenda</div>
                        <div class="fw-bold fs-4">{{ number_format($totalAgendas) }}</div>
                        <span class="badge bg-info text-dark">{{ $publishedAgendas }} tayang • {{ $pendingAgendas }} pending</span>
                    </div>
                </div>
                @if($isAdminViewer)
                <div class="col-6 col-lg-3">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Pesan Masuk</div>
                        <div class="fw-bold fs-4">{{ number_format($totalMessages) }}</div>
                        <span class="badge bg-warning text-dark">{{ $unreadMessages }} belum dibaca</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Tren kunjungan --}}
            <h6 class="report-sec-title mb-1">II. TREN KUNJUNGAN HARIAN</h6>
            <p class="small text-muted mb-2">30 hari terakhir pada periode laporan.</p>
            @php
                $dLabels = array_slice($visitorStats['daily']['labels'] ?? [], -30);
                $dTotals = array_slice($visitorStats['daily']['total'] ?? [], -30);
                $dMax = max(1, max($dTotals ?: [0]));
            @endphp
            <div class="border rounded p-3 mb-1">
                <div class="report-daily mb-2">
                    @foreach($dTotals as $v)
                        <div class="bar" style="height: {{ max(3, round($v / $dMax * 100)) }}%;" title="{{ number_format($v) }}"></div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>{{ $dLabels[0] ?? '' }}</span>
                    <span>Puncak: {{ number_format($dMax) }} kunjungan/hari</span>
                    <span>{{ end($dLabels) ?: '' }}</span>
                </div>
            </div>
            <div class="mb-4"></div>

            {{-- Tren agenda + perangkat --}}
            <h6 class="report-sec-title mb-3">III. TREN AGENDA & PERANGKAT PENGUNJUNG</h6>
            <div class="row g-3 mb-4">
                <div class="col-lg-7">
                    <div class="border rounded p-3 h-100">
                        <div class="small fw-semibold mb-2">Agenda masuk 6 bulan terakhir</div>
                        @foreach($agendaTrenLabels as $i => $bln)
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="small text-muted" style="width:64px;">{{ $bln }}</div>
                                <div class="report-bar-track flex-grow-1">
                                    <div class="report-bar-fill" style="width: {{ round(($agendaTrenData[$i] ?? 0) / $trenMax * 100) }}%; background: #139a8d;"></div>
                                </div>
                                <div class="small fw-bold" style="width:36px; text-align:right;">{{ $agendaTrenData[$i] ?? 0 }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border rounded p-3 h-100">
                        <div class="small fw-semibold mb-2">Komposisi perangkat</div>
                        @foreach($deviceStats['labels'] as $i => $dev)
                            @php $pct = round(($deviceStats['values'][$i] ?? 0) / $deviceTotal * 100, 1); @endphp
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="small text-muted" style="width:64px;">{{ $dev }}</div>
                                <div class="report-bar-track flex-grow-1">
                                    <div class="report-bar-fill" style="width: {{ $pct }}%; background: #0a4d8e;"></div>
                                </div>
                                <div class="small fw-bold" style="width:52px; text-align:right;">{{ $pct }}%</div>
                            </div>
                        @endforeach
                        <div class="small fw-semibold mt-3 mb-1">Browser teratas</div>
                        @forelse($browserStats as $name => $total)
                            <div class="d-flex justify-content-between small border-bottom py-1">
                                <span>{{ $name ?: 'Lainnya' }}</span><strong>{{ number_format($total) }}</strong>
                            </div>
                        @empty
                            <div class="small text-muted">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Top OPD + top dilihat --}}
            <h6 class="report-sec-title mb-3">IV. KINERJA OPD & KONTEN POPULER</h6>
            <div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="small fw-semibold mb-2">Top 5 OPD pengirim agenda</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <thead class="table-light"><tr><th style="width:32px;">No</th><th>OPD</th><th class="text-end">Agenda</th><th class="text-end">Pending</th></tr></thead>
                        <tbody>
                            @forelse($topOpd as $i => $opd)
                                <tr><td>{{ $i + 1 }}</td><td>{{ $opd->name }}</td><td class="text-end">{{ $opd->total_agenda }}</td><td class="text-end">{{ $opd->pending_agenda }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <div class="small fw-semibold mb-2">5 Agenda paling banyak dilihat</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <thead class="table-light"><tr><th style="width:32px;">No</th><th>Judul</th><th class="text-end">Dilihat</th></tr></thead>
                        <tbody>
                            @forelse($topViewedAgendas as $i => $a)
                                <tr><td>{{ $i + 1 }}</td><td>{{ \Illuminate\Support\Str::limit($a->title, 50) }}</td><td class="text-end">{{ number_format($a->views) }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kategori + kota + perhatian --}}
            <h6 class="report-sec-title mb-3">V. KATEGORI, WILAYAH & PERLU PERHATIAN</h6>
            <div class="row g-3 mb-4">
                <div class="@if($isAdminViewer) col-lg-4 @else col-lg-6 @endif">
                    <div class="small fw-semibold mb-2">Agenda per kategori</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            @forelse($agendaKategori as $kat)
                                <tr><td>{{ $kat->name }}</td><td class="text-end" style="width:56px;">{{ $kat->agendas_count }}</td></tr>
                            @empty
                                <tr><td class="text-center text-muted">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="@if($isAdminViewer) col-lg-4 @else col-lg-6 @endif">
                    <div class="small fw-semibold mb-2">Kota asal pengunjung</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            @forelse($kotaStats as $kota => $total)
                                <tr><td>{{ $kota }}</td><td class="text-end" style="width:56px;">{{ number_format($total) }}</td></tr>
                            @empty
                                <tr><td class="text-center text-muted">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="small fw-semibold mt-3 mb-2">Inventaris konten</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            <tr><td>Halaman</td><td class="text-end" style="width:56px;">{{ $totalPages }}</td></tr>
                            <tr><td>Dokumen</td><td class="text-end" style="width:56px;">{{ $totalDocuments }}</td></tr>
                            <tr><td>FAQ aktif</td><td class="text-end" style="width:56px;">{{ $totalFaq }}</td></tr>
                            <tr><td>Galeri aktif</td><td class="text-end" style="width:56px;">{{ $totalGaleri }}</td></tr>
                            <tr><td>OPD aktif</td><td class="text-end" style="width:56px;">{{ $totalOpdAktif }}</td></tr>
                        </tbody>
                    </table>
                </div>
                @if($isAdminViewer)
                <div class="col-lg-4">
                    <div class="small fw-semibold mb-2">OPD 7 hari tidak aktif ({{ $opdTidakAktif->count() }})</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            @forelse($opdTidakAktif->take(10) as $opd)
                                <tr>
                                    <td>{{ $opd['name'] }}</td>
                                    <td class="text-end small text-muted" style="width:110px;">{{ $opd['last_agenda'] ? $opd['last_agenda']->diffForHumans() : 'Belum pernah' }}</td>
                                </tr>
                            @empty
                                <tr><td class="text-center text-muted">Semua OPD aktif. 🎉</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- Tanda tangan --}}
            <div class="row mt-5">
                <div class="col-6 small text-muted">
                    Dibuat oleh: <strong>{{ $generatedBy }}</strong><br>
                    Pada: {{ $generatedAt->translatedFormat('d F Y, H:i') }} WIB<br>
                    <span style="font-size:0.75rem;">Laporan dibuat otomatis oleh sistem.</span>
                </div>
                <div class="col-6 text-center small">
                    Bekasi, {{ $generatedAt->translatedFormat('d F Y') }}<br>
                    Mengetahui,<br><br><br><br>
                    ( .............................................. )
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
