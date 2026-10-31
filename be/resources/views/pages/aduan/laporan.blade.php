<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Masyarakat</title>
    <style>
        @page {
            margin: 22px 28px 42px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.45;
        }

        /* Kop */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 90px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .kop-text .instansi {
            font-size: 15px;
            font-weight: bold;
            color: #1B3A5F;
            letter-spacing: 0.5px;
        }

        .kop-text .sub {
            font-size: 10px;
            color: #4b5563;
        }

        .kop-rule {
            border: none;
            border-top: 3px solid #1B3A5F;
            margin: 6px 0 1px 0;
        }

        .kop-rule2 {
            border: none;
            border-top: 1px solid #C9A227;
            margin: 0 0 12px 0;
        }

        /* Judul */
        .judul {
            text-align: center;
            margin-bottom: 4px;
        }

        .judul h1 {
            font-size: 16px;
            color: #1B3A5F;
            margin: 0 0 2px 0;
            letter-spacing: 1px;
        }

        .judul .periode {
            font-size: 11px;
            font-weight: bold;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 0;
            font-size: 9px;
            color: #4b5563;
        }

        .meta td {
            padding: 1px 4px;
        }

        /* Ringkasan */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1B3A5F;
            border-left: 4px solid #C9A227;
            padding-left: 8px;
            margin: 14px 0 8px 0;
        }

        .stat-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stat-table td {
            width: 25%;
            padding: 4px;
        }

        .stat-box {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
        }

        .stat-box.hero {
            background: #1B3A5F;
            color: #ffffff;
            border-color: #1B3A5F;
        }

        .stat-label {
            font-size: 9px;
            color: #6b7280;
        }

        .hero .stat-label {
            color: #d1d5db;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #1B3A5F;
        }

        .hero .stat-value {
            color: #ffffff;
        }

        /* Grafik batang */
        .chart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .chart-table > tbody > tr > td {
            width: 50%;
            vertical-align: top;
            padding: 0 8px 0 0;
        }

        .bar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bar-table td {
            padding: 2px 4px;
            vertical-align: middle;
        }

        .bar-label {
            width: 32%;
            font-size: 9px;
        }

        .bar-track {
            width: 56%;
        }

        .bar-fill {
            height: 12px;
            border-radius: 3px;
            background: #1B3A5F;
        }

        .bar-fill.green {
            background: #198754;
        }

        .bar-fill.gold {
            background: #C9A227;
        }

        .bar-val {
            width: 12%;
            text-align: right;
            font-weight: bold;
            font-size: 9px;
        }

        /* Tabel */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .data-table th {
            background: #1B3A5F;
            color: #ffffff;
            padding: 5px 4px;
            text-align: left;
        }

        .data-table td {
            padding: 4px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) td {
            background: #f3f4f6;
        }

        .st {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
            color: #ffffff;
        }

        .st-menunggu { background: #6c757d; }
        .st-diverifikasi { background: #0d99c7; }
        .st-diproses { background: #b8860b; }
        .st-selesai { background: #198754; }
        .st-ditolak { background: #dc3545; }

        /* Tanda tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        .ttd-table td {
            width: 50%;
            vertical-align: top;
        }

        .ttd-box {
            text-align: center;
            font-size: 10px;
        }

        /* Footer halaman */
        .footer {
            position: fixed;
            bottom: -28px;
            left: 0;
            right: 0;
            font-size: 8px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding-top: 4px;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>
<body>

<div class="footer">
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td>Laporan Pengaduan Masyarakat — Pemerintah Kota Bekasi</td>
            <td style="text-align:right;">Halaman <span class="pagenum"></span></td>
        </tr>
    </table>
</div>

{{-- Kop --}}
<table class="kop-table">
    <tr>
        <td class="kop-logo">
            <img src="{{ public_path('img/logo.png') }}" style="height:68px;" alt="Logo">
        </td>
        <td class="kop-text">
            <div class="instansi">PEMERINTAH KOTA BEKASI</div>
            <div class="instansi">KOTA BEKASI</div>
            <div class="sub">Jl. Jend. Ahmad Yani No. 1, Kota Bekasi 17141 &nbsp;|&nbsp; Telp. (021) 8896176</div>
        </td>
        <td class="kop-logo"></td>
    </tr>
</table>
<hr class="kop-rule">
<hr class="kop-rule2">

{{-- Judul --}}
<div class="judul">
    <h1>LAPORAN PENGADUAN MASYARAKAT</h1>
    <div class="periode">Periode: {{ $periode }}</div>
</div>

<table class="meta">
    <tr>
        <td style="width:25%;">Cakupan data</td>
        <td style="width:35%;">: {{ $tampil === 'sampah' ? 'Tempat Sampah (terhapus)' : 'Data Aktif' }}</td>
        <td style="width:20%;">Dicetak oleh</td>
        <td>: {{ $dicetakOleh }}</td>
    </tr>
    <tr>
        <td>Jumlah data</td>
        <td>: {{ number_format($total) }} aduan</td>
        <td>Waktu cetak</td>
        <td>: {{ $waktuCetak }} WIB</td>
    </tr>
</table>

{{-- Ringkasan --}}
<div class="section-title">RINGKASAN EKSEKUTIF</div>
<table class="stat-table">
    <tr>
        <td>
            <div class="stat-box hero">
                <div class="stat-label">Tingkat Penyelesaian</div>
                <div class="stat-value">{{ $persenSelesai }}%</div>
            </div>
        </td>
        <td>
            <div class="stat-box">
                <div class="stat-label">Total Aduan</div>
                <div class="stat-value">{{ number_format($total) }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box">
                <div class="stat-label">Selesai Ditangani</div>
                <div class="stat-value">{{ number_format($selesai) }}</div>
            </div>
        </td>
        <td>
            <div class="stat-box">
                <div class="stat-label">Dalam Penanganan</div>
                <div class="stat-value">{{ number_format($perStatus->get('diverifikasi', 0) + $perStatus->get('diproses', 0)) }}</div>
            </div>
        </td>
    </tr>
</table>

{{-- Grafik --}}
<div class="section-title">SEBARAN ADUAN</div>
<table class="chart-table">
    <tr>
        <td>
            <strong>Berdasarkan Status</strong>
            <table class="bar-table">
                @foreach ($statusList as $st)
                @php $n = $perStatus->get($st, 0); @endphp
                <tr>
                    <td class="bar-label">{{ ucfirst($st) }}</td>
                    <td class="bar-track">
                        <div class="bar-fill {{ $st === 'selesai' ? 'green' : '' }}" style="width: {{ $n > 0 ? max(3, round($n / $maxStatus * 100)) : 0 }}%"></div>
                    </td>
                    <td class="bar-val">{{ number_format($n) }}</td>
                </tr>
                @endforeach
            </table>
        </td>
        <td>
            <strong>Berdasarkan Kategori</strong>
            <table class="bar-table">
                @foreach ($rekapKategori as $rk)
                <tr>
                    <td class="bar-label">{{ $rk['nama'] }}</td>
                    <td class="bar-track">
                        <div class="bar-fill gold" style="width: {{ $rk['total'] > 0 ? max(3, round($rk['total'] / $maxKategori * 100)) : 0 }}%"></div>
                    </td>
                    <td class="bar-val">{{ number_format($rk['total']) }}</td>
                </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>

{{-- Rekap kategori --}}
<div class="section-title">REKAP PER KATEGORI</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:45%;">Kategori</th>
            <th style="width:15%; text-align:right;">Total</th>
            <th style="width:15%; text-align:right;">Selesai</th>
            <th style="width:20%; text-align:right;">% Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rekapKategori as $i => $rk)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $rk['nama'] }}</td>
            <td style="text-align:right;">{{ number_format($rk['total']) }}</td>
            <td style="text-align:right;">{{ number_format($rk['selesai']) }}</td>
            <td style="text-align:right;">{{ $rk['total'] > 0 ? round($rk['selesai'] / $rk['total'] * 100, 1) : 0 }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Rekap kecamatan --}}
@if ($rekapKecamatan->count() > 0)
<div class="section-title">REKAP PER KECAMATAN</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:60%;">Kecamatan</th>
            <th style="width:35%; text-align:right;">Total Aduan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rekapKecamatan as $i => $rk)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $rk['nama'] }}</td>
            <td style="text-align:right;">{{ number_format($rk['total']) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Detail --}}
<div class="section-title">DAFTAR ADUAN ({{ number_format($total) }})</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:4%;">No</th>
            <th style="width:13%;">Nomor</th>
            <th style="width:9%;">Tgl Lapor</th>
            <th style="width:26%;">Judul</th>
            <th style="width:12%;">Kategori</th>
            <th style="width:12%;">Kecamatan</th>
            <th style="width:10%;">Status</th>
            <th style="width:8%;">Prioritas</th>
            <th style="width:6%; text-align:center;">TL</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $i => $a)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $a->nomor_aduan }}</td>
            <td>{{ $a->tanggal_pengaduan?->format('d/m/Y H:i') ?? '-' }}</td>
            <td>{{ $a->judul }}</td>
            <td>{{ $a->kategori }}</td>
            <td>{{ $a->kecamatan->nama ?? '-' }}</td>
            <td><span class="st st-{{ $a->status }}">{{ ucfirst($a->status) }}</span></td>
            <td>{{ ucfirst($a->prioritas) }}</td>
            <td style="text-align:center;">{{ $a->tindak_lanjuts_count }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center;">Tidak ada data pada periode/filter ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Tanda tangan --}}
<table class="ttd-table">
    <tr>
        <td></td>
        <td>
            <div class="ttd-box">
                Bekasi, {{ now()->translatedFormat('d F Y') }}<br>
                Kepala Pemerintah Kota Bekasi<br><br><br><br><br>
                <strong><u>( ........................................ )</u></strong><br>
                NIP. ........................................
            </div>
        </td>
    </tr>
</table>

</body>
</html>
