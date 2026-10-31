<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Analitik Website</title>
<style>
    @page { margin: 56px 38px 64px 38px; }
    * { font-family: 'DejaVu Sans', sans-serif; }
    body { font-size: 9.5px; color: #1e293b; line-height: 1.5; }

    /* ===== KOP ===== */
    .kop-table { width: 100%; border-collapse: collapse; }
    .kop-title { text-align: center; }
    .kop-title h2 { margin: 0; font-size: 15px; letter-spacing: 1.5px; color: #0a4d8e; }
    .kop-title h3 { margin: 3px 0 2px 0; font-size: 11.5px; color: #1e293b; }
    .kop-title p { margin: 0; font-size: 8.5px; color: #64748b; }
    .kop-rule-navy { border-top: 3px solid #0a4d8e; margin: 10px 0 0 0; }
    .kop-rule-teal { border-top: 1px solid #139a8d; margin: 2px 0 16px 0; }

    /* ===== Judul + meta ===== */
    .doc-title { text-align: center; margin-bottom: 10px; }
    .doc-title h4 { margin: 0 0 3px 0; font-size: 13px; letter-spacing: 1px; color: #0a4d8e; }
    .meta-box { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    .meta-box td { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 5px 9px; font-size: 9px; }
    .meta-box .lbl { color: #64748b; width: 130px; }

    /* ===== Seksi ===== */
    .sec { font-size: 10.5px; font-weight: bold; color: #ffffff; background: #0a4d8e; padding: 5px 10px; margin: 16px 0 8px 0; letter-spacing: 0.4px; }
    .sec-sub { font-size: 10px; font-weight: bold; color: #0a4d8e; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; margin: 10px 0 6px 0; }
    .note { font-size: 8.5px; color: #64748b; margin: 2px 0 8px 0; }

    /* ===== Tabel ===== */
    table.grid { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.grid thead { display: table-header-group; }
    table.grid th { background: #0a4d8e; color: #ffffff; padding: 5px 7px; font-size: 9px; text-align: left; }
    table.grid td { border: 1px solid #cbd5e1; padding: 4px 7px; }
    table.grid tr { page-break-inside: avoid; }
    .zebra { background: #f8fafc; }
    .num { text-align: right; white-space: nowrap; }
    .up { color: #15803d; font-weight: bold; }
    .down { color: #b91c1c; font-weight: bold; }

    /* ===== KPI ===== */
    .kpi-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    .kpi-table td { width: 25%; border: 1px solid #cbd5e1; text-align: center; padding: 9px 4px; vertical-align: top; }
    .kpi-lbl { font-size: 8.5px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-val { font-size: 17px; font-weight: bold; color: #0a4d8e; margin: 2px 0; }
    .kpi-sub { font-size: 8.5px; color: #475569; }

    /* ===== Bar ===== */
    .bar-track { background: #e2e8f0; height: 10px; }
    .bar-fill-blue { background: #0a4d8e; height: 10px; }
    .bar-fill-teal { background: #139a8d; height: 10px; }

    /* ===== Sorotan ===== */
    .hl-box { border: 1px solid #cbd5e1; border-left: 4px solid #139a8d; background: #f8fafc; padding: 7px 10px; margin-bottom: 4px; }
    .hl-box p { margin: 2px 0; font-size: 9.5px; }

    /* ===== Footer + ttd ===== */
    .footer { position: fixed; bottom: -46px; left: 0; right: 0; text-align: center; font-size: 8px; color: #64748b; border-top: 1px solid #cbd5e1; padding-top: 4px; }
    .pagenum:before { content: counter(page); }
    .pagecount:before { content: counter(pages); }
    .sig-table { width: 100%; margin-top: 24px; border-collapse: collapse; }
    .sig-table td { font-size: 10px; vertical-align: top; }
</style>
</head>
<body>

<div class="footer">
    Laporan Analitik Website &bull; {{ $dateRangeLabel }} &nbsp;|&nbsp; Halaman <span class="pagenum"></span> dari <span class="pagecount"></span> &nbsp;|&nbsp; Dibuat otomatis oleh sistem pada {{ $generatedAt->translatedFormat('d F Y H:i') }} WIB
</div>

{{-- ================= KOP ================= --}}
<table class="kop-table">
    <tr>
        @if($logoPath)
            <td style="width:84px; vertical-align:middle;"><img src="{{ $logoPath }}" style="height:62px;"></td>
        @endif
        <td class="kop-title">
            <h2>PEMERINTAH KOTA BEKASI</h2>
            <h3>{{ mb_strtoupper($identity->site_name ?? 'PORTAL RESMI') }}</h3>
            <p>{{ $identity->address ?? '' }}</p>
            <p>{{ trim(($identity->phone ?? '') . ($identity->phone && $identity->email ? ' &nbsp;|&nbsp; ' : '') . ($identity->email ?? '')) }}</p>
        </td>
        @if($logoPath)<td style="width:84px;"></td>@endif
    </tr>
</table>
<div class="kop-rule-navy"></div>
<div class="kop-rule-teal"></div>

{{-- ================= JUDUL ================= --}}
<div class="doc-title">
    <h4>LAPORAN ANALITIK WEBSITE</h4>
</div>
<table class="meta-box">
    <tr>
        <td class="lbl">Periode Laporan</td>
        <td><strong>{{ $dateRangeLabel }}</strong> ({{ $daysDiff }} hari)</td>
        <td class="lbl">Tanggal Dibuat</td>
        <td>{{ $generatedAt->translatedFormat('d F Y, H:i') }} WIB</td>
    </tr>
    <tr>
        <td class="lbl">Dibuat Oleh</td>
        <td>{{ $generatedBy }}</td>
        <td class="lbl">Sumber Data</td>
        <td>Sistem Pencatatan Website</td>
    </tr>
</table>

{{-- ================= SOROTAN ================= --}}
@php
    $peakIdx = 0;
    $dTotalsAll = $visitorStats['daily']['total'] ?? [];
    foreach ($dTotalsAll as $i => $v) { if ($v > ($dTotalsAll[$peakIdx] ?? 0)) $peakIdx = $i; }
    $dLabelsAll = $visitorStats['daily']['labels'] ?? [];
@endphp
<div class="sec">SOROTAN UTAMA</div>
<div class="hl-box">
    <p>&bull; Total <strong>{{ number_format($visitorStats['total_visits']) }} kunjungan</strong> dari <strong>{{ number_format($visitorStats['unique_visitors']) }} pengunjung unik</strong>{{ $visitorGrowth >= 0 ? ', naik' : ', turun' }} <strong>{{ abs($visitorGrowth) }}%</strong> dibanding periode sebelumnya.</p>
    <p>&bull; Puncak kunjungan <strong>{{ number_format($dTotalsAll[$peakIdx] ?? 0) }}</strong> pada <strong>{{ $dLabelsAll[$peakIdx] ?? '-' }}</strong>.</p>
    <p>&bull; <strong>{{ number_format($totalAgendas) }} agenda</strong> diterbitkan ({{ $publishedAgendas }} tayang, {{ $pendingAgendas }} menunggu persetujuan)@if($isAdminViewer) dengan <strong>{{ number_format($totalMessages) }} pesan masuk</strong> ({{ $unreadMessages }} belum dibaca)@endif.</p>
    @if($isAdminViewer)
    @if($opdTidakAktif->count() > 0)
    <p>&bull; Perhatian: <strong>{{ $opdTidakAktif->count() }} OPD</strong> tidak mengisi agenda dalam 7 hari terakhir.</p>
    @else
    <p>&bull; Seluruh OPD aktif mengisi agenda dalam 7 hari terakhir.</p>
    @endif
    @endif
</div>

{{-- ================= I. KPI ================= --}}
<div class="sec">I. RINGKASAN EKSEKUTIF</div>
<table class="kpi-table">
    <tr>
        <td>
            <div class="kpi-lbl">Total Kunjungan</div>
            <div class="kpi-val">{{ number_format($visitorStats['total_visits']) }}</div>
            <div class="{{ $visitorGrowth >= 0 ? 'up' : 'down' }}">{{ $visitorGrowth >= 0 ? '▲ +' : '▼ ' }}{{ $visitorGrowth }}%</div>
        </td>
        <td>
            <div class="kpi-lbl">Pengunjung Unik</div>
            <div class="kpi-val">{{ number_format($visitorStats['unique_visitors']) }}</div>
            <div class="{{ $uniqueGrowth >= 0 ? 'up' : 'down' }}">{{ $uniqueGrowth >= 0 ? '▲ +' : '▼ ' }}{{ $uniqueGrowth }}%</div>
        </td>
        <td>
            <div class="kpi-lbl">Total Agenda</div>
            <div class="kpi-val">{{ number_format($totalAgendas) }}</div>
            <div class="kpi-sub">{{ $publishedAgendas }} tayang &bull; {{ $pendingAgendas }} pending</div>
        </td>
        @if($isAdminViewer)
        <td>
            <div class="kpi-lbl">Pesan Masuk</div>
            <div class="kpi-val">{{ number_format($totalMessages) }}</div>
            <div class="kpi-sub">{{ $unreadMessages }} belum dibaca</div>
        </td>
        @endif
    </tr>
</table>

{{-- ================= II. TREN ================= --}}
<div class="sec">II. TREN KUNJUNGAN HARIAN</div>
<p class="note">30 hari terakhir pada periode laporan. Puncak: {{ number_format(max(1, max($dTotalsAll ?: [0]))) }} kunjungan/hari.</p>
@php
    $dLabels = array_slice($dLabelsAll, -30);
    $dTotals = array_slice($dTotalsAll, -30);
    $dMax = max(1, max($dTotals ?: [0]));
@endphp
<table class="grid">
    <thead><tr><th style="width:70px;">Tanggal</th><th>Grafik Kunjungan</th><th class="num" style="width:80px;">Jumlah</th></tr></thead>
    <tbody>
    @foreach($dTotals as $i => $v)
        <tr class="{{ $loop->even ? 'zebra' : '' }}">
            <td>{{ $dLabels[$i] ?? '' }}</td>
            <td><div class="bar-track"><div class="bar-fill-blue" style="width: {{ max(2, round($v / $dMax * 100)) }}%;"></div></div></td>
            <td class="num">{{ number_format($v) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- ================= III. AGENDA + PERANGKAT ================= --}}
<div class="sec">III. TREN AGENDA & PERANGKAT PENGUNJUNG</div>
<div class="sec-sub">Agenda masuk 6 bulan terakhir</div>
<table class="grid">
    <thead><tr><th style="width:90px;">Bulan</th><th>Grafik</th><th class="num" style="width:60px;">Jumlah</th></tr></thead>
    <tbody>
    @foreach($agendaTrenLabels as $i => $bln)
        <tr class="{{ $loop->even ? 'zebra' : '' }}">
            <td>{{ $bln }}</td>
            <td><div class="bar-track"><div class="bar-fill-teal" style="width: {{ round(($agendaTrenData[$i] ?? 0) / $trenMax * 100) }}%;"></div></div></td>
            <td class="num">{{ $agendaTrenData[$i] ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="sec-sub">Komposisi perangkat pengunjung</div>
<table class="grid">
    <thead><tr><th>Perangkat</th><th class="num" style="width:80px;">Jumlah</th><th class="num" style="width:60px;">Persen</th></tr></thead>
    <tbody>
    @foreach($deviceStats['labels'] as $i => $dev)
        <tr class="{{ $loop->even ? 'zebra' : '' }}">
            <td>{{ $dev }}</td>
            <td class="num">{{ number_format($deviceStats['values'][$i] ?? 0) }}</td>
            <td class="num">{{ round(($deviceStats['values'][$i] ?? 0) / $deviceTotal * 100, 1) }}%</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="sec-sub">Browser teratas</div>
<table class="grid">
    <thead><tr><th>Browser</th><th class="num" style="width:90px;">Kunjungan</th></tr></thead>
    <tbody>
    @forelse($browserStats as $name => $total)
        <tr class="{{ $loop->even ? 'zebra' : '' }}"><td>{{ $name ?: 'Lainnya' }}</td><td class="num">{{ number_format($total) }}</td></tr>
    @empty
        <tr><td colspan="2">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

{{-- ================= IV. OPD + POPULER ================= --}}
<div class="sec">IV. KINERJA OPD & KONTEN POPULER</div>
<div class="sec-sub">Top 5 OPD pengirim agenda</div>
<table class="grid">
    <thead><tr><th style="width:30px;">No</th><th>Nama OPD</th><th class="num" style="width:60px;">Agenda</th><th class="num" style="width:60px;">Pending</th></tr></thead>
    <tbody>
    @forelse($topOpd as $i => $opd)
        <tr class="{{ $loop->even ? 'zebra' : '' }}"><td>{{ $i + 1 }}</td><td>{{ $opd->name }}</td><td class="num">{{ $opd->total_agenda }}</td><td class="num">{{ $opd->pending_agenda }}</td></tr>
    @empty
        <tr><td colspan="4">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="sec-sub">5 agenda paling banyak dilihat</div>
<table class="grid">
    <thead><tr><th style="width:30px;">No</th><th>Judul Agenda</th><th class="num" style="width:70px;">Dilihat</th></tr></thead>
    <tbody>
    @forelse($topViewedAgendas as $i => $a)
        <tr class="{{ $loop->even ? 'zebra' : '' }}"><td>{{ $i + 1 }}</td><td>{{ $a->title }}</td><td class="num">{{ number_format($a->views) }}</td></tr>
    @empty
        <tr><td colspan="3">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

{{-- ================= V. LAINNYA ================= --}}
<div class="sec">V. KATEGORI, WILAYAH & INVENTARIS</div>
<div class="sec-sub">Agenda per kategori</div>
<table class="grid">
    <thead><tr><th>Kategori</th><th class="num" style="width:70px;">Jumlah</th></tr></thead>
    <tbody>
    @forelse($agendaKategori as $kat)
        <tr class="{{ $loop->even ? 'zebra' : '' }}"><td>{{ $kat->name }}</td><td class="num">{{ $kat->agendas_count }}</td></tr>
    @empty
        <tr><td colspan="2">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="sec-sub">Kota asal pengunjung</div>
<table class="grid">
    <thead><tr><th>Kota</th><th class="num" style="width:80px;">Kunjungan</th></tr></thead>
    <tbody>
    @forelse($kotaStats as $kota => $total)
        <tr class="{{ $loop->even ? 'zebra' : '' }}"><td>{{ $kota }}</td><td class="num">{{ number_format($total) }}</td></tr>
    @empty
        <tr><td colspan="2">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="sec-sub">Inventaris konten website</div>
<table class="grid">
    <thead><tr><th>Jenis Konten</th><th class="num" style="width:80px;">Jumlah</th></tr></thead>
    <tbody>
        <tr><td>Halaman</td><td class="num">{{ $totalPages }}</td></tr>
        <tr class="zebra"><td>Dokumen</td><td class="num">{{ $totalDocuments }}</td></tr>
        <tr><td>FAQ aktif</td><td class="num">{{ $totalFaq }}</td></tr>
        <tr class="zebra"><td>Galeri aktif</td><td class="num">{{ $totalGaleri }}</td></tr>
        <tr><td>OPD aktif</td><td class="num">{{ $totalOpdAktif }}</td></tr>
    </tbody>
</table>

@if($isAdminViewer)
<div class="sec-sub">OPD 7 hari tidak aktif ({{ $opdTidakAktif->count() }})</div>
<table class="grid">
    <thead><tr><th>Nama OPD</th><th style="width:150px;">Terakhir Mengisi</th></tr></thead>
    <tbody>
    @forelse($opdTidakAktif->take(15) as $opd)
        <tr class="{{ $loop->even ? 'zebra' : '' }}"><td>{{ $opd['name'] }}</td><td>{{ $opd['last_agenda'] ? $opd['last_agenda']->translatedFormat('d F Y') : 'Belum pernah' }}</td></tr>
    @empty
        <tr><td colspan="2">Semua OPD aktif.</td></tr>
    @endforelse
    </tbody>
</table>
@endif

{{-- ================= TTD ================= --}}
<table class="sig-table">
    <tr>
        <td style="width:50%;">
            Dibuat oleh: <strong>{{ $generatedBy }}</strong><br>
            Pada: {{ $generatedAt->translatedFormat('d F Y, H:i') }} WIB<br>
            Laporan dibuat otomatis oleh sistem.
        </td>
        <td style="width:50%; text-align:center;">
            Bekasi, {{ $generatedAt->translatedFormat('d F Y') }}<br>
            Mengetahui,<br><br><br><br><br>
            ( .............................................. )
        </td>
    </tr>
</table>

</body>
</html>
