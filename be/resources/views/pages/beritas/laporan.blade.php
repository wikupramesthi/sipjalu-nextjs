<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Berita</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }
        h1 { color: #1b3a5f; text-align: center; font-size: 18px; margin-bottom: 4px; }
        .period { color: #64748b; text-align: center; margin: 0 0 18px; }
        h2 { font-size: 12px; color: #1b3a5f; margin: 16px 0 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 7px; text-align: left; vertical-align: top; }
        th { background: #eaf0f6; }
        .summary th { background: #1b3a5f; color: #fff; }
    </style>
</head>
<body>
    <h1>LAPORAN BERITA</h1>
    <p class="period">Periode {{ $startDate ?: 'awal' }} s/d {{ $endDate ?: 'sekarang' }} &middot; Dicetak {{ now()->format('d-m-Y H:i') }}</p>

    <h2>Ringkasan</h2>
    <table class="summary">
        <tr><th>Metrik</th><th>Jumlah</th></tr>
        <tr><td>Total berita</td><td>{{ number_format($total) }}</td></tr>
        @foreach (['published' => 'Published', 'pending' => 'Pending', 'draft' => 'Draft', 'scheduled' => 'Scheduled'] as $key => $label)
            <tr><td>{{ $label }}</td><td>{{ number_format($perStatus->get($key, 0)) }}</td></tr>
        @endforeach
        <tr><td>Total dilihat</td><td>{{ number_format($totalViews) }}</td></tr>
    </table>

    @if ($perCategory->isNotEmpty())
        <h2>Berita per Kategori</h2>
        <table>
            <tr><th>Kategori</th><th>Jumlah</th></tr>
            @foreach ($perCategory as $category => $count)
                <tr><td>{{ $category }}</td><td>{{ number_format($count) }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>Daftar Berita</h2>
    <table>
        <tr><th style="width:28px">No</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal Publikasi</th><th>Dilihat</th></tr>
        @forelse ($items as $index => $berita)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $berita->title }}</td>
                <td>{{ $berita->category?->name ?? '-' }}</td>
                <td>{{ $berita->user?->name ?? '-' }}</td>
                <td>{{ ucfirst($berita->status) }}</td>
                <td>{{ $berita->scheduled_at?->format('d/m/Y H:i') ?? '-' }}</td>
                <td>{{ number_format($berita->views) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center">Tidak ada berita pada periode ini.</td></tr>
        @endforelse
    </table>
</body>
</html>
