<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Agenda {{ $periode->translatedFormat('F Y') }}</title>
    <style>
        @page { margin: 22px 28px 42px 28px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1f2937; line-height: 1.45; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-logo { width: 70px; text-align: center; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-text .instansi { font-size: 14px; font-weight: bold; color: #1B3A5F; }
        .kop-text .sub { font-size: 10px; color: #4b5563; }
        .kop-rule { border: none; border-top: 3px solid #1B3A5F; margin: 6px 0 1px 0; }
        .kop-rule2 { border: none; border-top: 1px solid #C9A227; margin: 0 0 12px 0; }
        h2.title { text-align: center; font-size: 13px; margin: 0 0 2px 0; color: #1B3A5F; }
        p.periode { text-align: center; margin: 0 0 12px 0; color: #4b5563; }
        table.rekap { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.rekap th, table.rekap td { border: 1px solid #d1d5db; padding: 4px 6px; text-align: left; }
        table.rekap th { background: #1B3A5F; color: #fff; }
        table.daftar { width: 100%; border-collapse: collapse; }
        table.daftar th, table.daftar td { border: 1px solid #d1d5db; padding: 4px 6px; vertical-align: top; }
        table.daftar th { background: #eef2f7; }
        .section { font-size: 11px; font-weight: bold; color: #1B3A5F; margin: 12px 0 4px 0; }
        .footer { margin-top: 18px; width: 100%; }
        .footer td { vertical-align: top; }
        .ttd { text-align: center; }
    </style>
</head>
<body>
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(file_exists(public_path('img/logo.png')))
                    <img src="{{ public_path('img/logo.png') }}" style="width:60px;" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <div class="instansi">PEMERINTAH KOTA BEKASI</div>
                <div class="sub">Laporan Agenda Kegiatan</div>
            </td>
            <td style="width:70px;"></td>
        </tr>
    </table>
    <hr class="kop-rule">
    <hr class="kop-rule2">

    <h2 class="title">REKAPITULASI AGENDA</h2>
    <p class="periode">Periode {{ $periode->translatedFormat('F Y') }} &middot; Dicetak {{ now()->translatedFormat('d F Y, H:i') }}</p>

    <div class="section">Ringkasan</div>
    <table class="rekap">
        <tr><th style="width:60%;">Uraian</th><th>Jumlah</th></tr>
        <tr><td>Total agenda</td><td>{{ number_format($total) }}</td></tr>
        @foreach(['published' => 'Published', 'pending' => 'Pending', 'draft' => 'Draft', 'scheduled' => 'Scheduled'] as $key => $label)
            <tr><td>{{ $label }}</td><td>{{ number_format($perStatus->get($key, 0)) }}</td></tr>
        @endforeach
        <tr><td>Offline / Online</td><td>{{ number_format($perJenis->get('offline', 0)) }} / {{ number_format($perJenis->get('online', 0)) }}</td></tr>
        <tr><td>Total dilihat</td><td>{{ number_format($totalViews) }}</td></tr>
    </table>

    @if($perKategori->isNotEmpty())
        <div class="section">Per Kategori</div>
        <table class="rekap">
            <tr><th style="width:60%;">Kategori</th><th>Jumlah</th></tr>
            @foreach($perKategori as $nama => $jumlah)
                <tr><td>{{ $nama }}</td><td>{{ number_format($jumlah) }}</td></tr>
            @endforeach
        </table>
    @endif

    @if($isApprover && $perOpd->isNotEmpty())
        <div class="section">Top Pengirim</div>
        <table class="rekap">
            <tr><th style="width:60%;">OPD / Penulis</th><th>Jumlah</th></tr>
            @foreach($perOpd as $nama => $jumlah)
                <tr><td>{{ $nama }}</td><td>{{ number_format($jumlah) }}</td></tr>
            @endforeach
        </table>
    @endif

    <div class="section">Daftar Agenda</div>
    <table class="daftar">
        <tr><th style="width:24px;">No</th><th>Judul</th><th style="width:70px;">Jenis</th><th style="width:90px;">Waktu</th><th style="width:60px;">Status</th><th style="width:50px;">Lamp.</th></tr>
        @forelse($items as $i => $a)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $a->title }}<br><small>{{ $a->category->name ?? '-' }} &middot; {{ $a->user->name ?? '-' }}</small></td>
                <td class="text-capitalize">{{ $a->jenis_agenda ?? 'offline' }}</td>
                <td>{{ optional($a->waktu_mulai)->format('d/m/y H:i') ?? '-' }}</td>
                <td class="text-capitalize">{{ $a->status }}</td>
                <td class="text-center">{{ $a->attachments->count() }}</td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;">Tidak ada agenda pada periode ini.</td></tr>
        @endforelse
    </table>

    <table class="footer">
        <tr>
            <td style="width:60%;"></td>
            <td class="ttd">Bekasi, {{ now()->translatedFormat('d F Y') }}<br><br><br><br><br>( ........................................ )</td>
        </tr>
    </table>
</body>
</html>
