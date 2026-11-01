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
    Laporan Analitik Website &bull; <?php echo e($dateRangeLabel); ?> &nbsp;|&nbsp; Halaman <span class="pagenum"></span> dari <span class="pagecount"></span> &nbsp;|&nbsp; Dibuat otomatis oleh sistem pada <?php echo e($generatedAt->translatedFormat('d F Y H:i')); ?> WIB
</div>


<table class="kop-table">
    <tr>
        <?php if($logoPath): ?>
            <td style="width:84px; vertical-align:middle;"><img src="<?php echo e($logoPath); ?>" style="height:62px;"></td>
        <?php endif; ?>
        <td class="kop-title">
            <h2>PEMERINTAH KOTA BEKASI</h2>
            <h3><?php echo e(mb_strtoupper($identity->site_name ?? 'PORTAL RESMI')); ?></h3>
            <p><?php echo e($identity->address ?? ''); ?></p>
            <p><?php echo e(trim(($identity->phone ?? '') . ($identity->phone && $identity->email ? ' &nbsp;|&nbsp; ' : '') . ($identity->email ?? ''))); ?></p>
        </td>
        <?php if($logoPath): ?><td style="width:84px;"></td><?php endif; ?>
    </tr>
</table>
<div class="kop-rule-navy"></div>
<div class="kop-rule-teal"></div>


<div class="doc-title">
    <h4>LAPORAN ANALITIK WEBSITE</h4>
</div>
<table class="meta-box">
    <tr>
        <td class="lbl">Periode Laporan</td>
        <td><strong><?php echo e($dateRangeLabel); ?></strong> (<?php echo e($daysDiff); ?> hari)</td>
        <td class="lbl">Tanggal Dibuat</td>
        <td><?php echo e($generatedAt->translatedFormat('d F Y, H:i')); ?> WIB</td>
    </tr>
    <tr>
        <td class="lbl">Dibuat Oleh</td>
        <td><?php echo e($generatedBy); ?></td>
        <td class="lbl">Sumber Data</td>
        <td>Sistem Pencatatan Website</td>
    </tr>
</table>


<?php
    $peakIdx = 0;
    $dTotalsAll = $visitorStats['daily']['total'] ?? [];
    foreach ($dTotalsAll as $i => $v) { if ($v > ($dTotalsAll[$peakIdx] ?? 0)) $peakIdx = $i; }
    $dLabelsAll = $visitorStats['daily']['labels'] ?? [];
?>
<div class="sec">SOROTAN UTAMA</div>
<div class="hl-box">
    <p>&bull; Total <strong><?php echo e(number_format($visitorStats['total_visits'])); ?> kunjungan</strong> dari <strong><?php echo e(number_format($visitorStats['unique_visitors'])); ?> pengunjung unik</strong><?php echo e($visitorGrowth >= 0 ? ', naik' : ', turun'); ?> <strong><?php echo e(abs($visitorGrowth)); ?>%</strong> dibanding periode sebelumnya.</p>
    <p>&bull; Puncak kunjungan <strong><?php echo e(number_format($dTotalsAll[$peakIdx] ?? 0)); ?></strong> pada <strong><?php echo e($dLabelsAll[$peakIdx] ?? '-'); ?></strong>.</p>
    <p>&bull; <strong><?php echo e(number_format($totalAgendas)); ?> agenda</strong> diterbitkan (<?php echo e($publishedAgendas); ?> tayang, <?php echo e($pendingAgendas); ?> menunggu persetujuan)<?php if($isAdminViewer): ?> dengan <strong><?php echo e(number_format($totalMessages)); ?> pesan masuk</strong> (<?php echo e($unreadMessages); ?> belum dibaca)<?php endif; ?>.</p>
    <?php if($isAdminViewer): ?>
    <?php if($opdTidakAktif->count() > 0): ?>
    <p>&bull; Perhatian: <strong><?php echo e($opdTidakAktif->count()); ?> OPD</strong> tidak mengisi agenda dalam 7 hari terakhir.</p>
    <?php else: ?>
    <p>&bull; Seluruh OPD aktif mengisi agenda dalam 7 hari terakhir.</p>
    <?php endif; ?>
    <?php endif; ?>
</div>


<div class="sec">I. RINGKASAN EKSEKUTIF</div>
<table class="kpi-table">
    <tr>
        <td>
            <div class="kpi-lbl">Total Kunjungan</div>
            <div class="kpi-val"><?php echo e(number_format($visitorStats['total_visits'])); ?></div>
            <div class="<?php echo e($visitorGrowth >= 0 ? 'up' : 'down'); ?>"><?php echo e($visitorGrowth >= 0 ? '▲ +' : '▼ '); ?><?php echo e($visitorGrowth); ?>%</div>
        </td>
        <td>
            <div class="kpi-lbl">Pengunjung Unik</div>
            <div class="kpi-val"><?php echo e(number_format($visitorStats['unique_visitors'])); ?></div>
            <div class="<?php echo e($uniqueGrowth >= 0 ? 'up' : 'down'); ?>"><?php echo e($uniqueGrowth >= 0 ? '▲ +' : '▼ '); ?><?php echo e($uniqueGrowth); ?>%</div>
        </td>
        <td>
            <div class="kpi-lbl">Total Agenda</div>
            <div class="kpi-val"><?php echo e(number_format($totalAgendas)); ?></div>
            <div class="kpi-sub"><?php echo e($publishedAgendas); ?> tayang &bull; <?php echo e($pendingAgendas); ?> pending</div>
        </td>
        <?php if($isAdminViewer): ?>
        <td>
            <div class="kpi-lbl">Pesan Masuk</div>
            <div class="kpi-val"><?php echo e(number_format($totalMessages)); ?></div>
            <div class="kpi-sub"><?php echo e($unreadMessages); ?> belum dibaca</div>
        </td>
        <?php endif; ?>
    </tr>
</table>


<div class="sec">II. TREN KUNJUNGAN HARIAN</div>
<p class="note">30 hari terakhir pada periode laporan. Puncak: <?php echo e(number_format(max(1, max($dTotalsAll ?: [0])))); ?> kunjungan/hari.</p>
<?php
    $dLabels = array_slice($dLabelsAll, -30);
    $dTotals = array_slice($dTotalsAll, -30);
    $dMax = max(1, max($dTotals ?: [0]));
?>
<table class="grid">
    <thead><tr><th style="width:70px;">Tanggal</th><th>Grafik Kunjungan</th><th class="num" style="width:80px;">Jumlah</th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $dTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>">
            <td><?php echo e($dLabels[$i] ?? ''); ?></td>
            <td><div class="bar-track"><div class="bar-fill-blue" style="width: <?php echo e(max(2, round($v / $dMax * 100))); ?>%;"></div></div></td>
            <td class="num"><?php echo e(number_format($v)); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>


<div class="sec">III. TREN AGENDA & PERANGKAT PENGUNJUNG</div>
<div class="sec-sub">Agenda masuk 6 bulan terakhir</div>
<table class="grid">
    <thead><tr><th style="width:90px;">Bulan</th><th>Grafik</th><th class="num" style="width:60px;">Jumlah</th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $agendaTrenLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>">
            <td><?php echo e($bln); ?></td>
            <td><div class="bar-track"><div class="bar-fill-teal" style="width: <?php echo e(round(($agendaTrenData[$i] ?? 0) / $trenMax * 100)); ?>%;"></div></div></td>
            <td class="num"><?php echo e($agendaTrenData[$i] ?? 0); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<div class="sec-sub">Komposisi perangkat pengunjung</div>
<table class="grid">
    <thead><tr><th>Perangkat</th><th class="num" style="width:80px;">Jumlah</th><th class="num" style="width:60px;">Persen</th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $deviceStats['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>">
            <td><?php echo e($dev); ?></td>
            <td class="num"><?php echo e(number_format($deviceStats['values'][$i] ?? 0)); ?></td>
            <td class="num"><?php echo e(round(($deviceStats['values'][$i] ?? 0) / $deviceTotal * 100, 1)); ?>%</td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<div class="sec-sub">Browser teratas</div>
<table class="grid">
    <thead><tr><th>Browser</th><th class="num" style="width:90px;">Kunjungan</th></tr></thead>
    <tbody>
    <?php $__empty_2 = true; $__currentLoopData = $browserStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>"><td><?php echo e($name ?: 'Lainnya'); ?></td><td class="num"><?php echo e(number_format($total)); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="2">Belum ada data.</td></tr>
    <?php endif; ?>
    </tbody>
</table>


<div class="sec">IV. KINERJA OPD & KONTEN POPULER</div>
<div class="sec-sub">Top 5 OPD pengirim agenda</div>
<table class="grid">
    <thead><tr><th style="width:30px;">No</th><th>Nama OPD</th><th class="num" style="width:60px;">Agenda</th><th class="num" style="width:60px;">Pending</th></tr></thead>
    <tbody>
    <?php $__empty_2 = true; $__currentLoopData = $topOpd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $opd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>"><td><?php echo e($i + 1); ?></td><td><?php echo e($opd->name); ?></td><td class="num"><?php echo e($opd->total_agenda); ?></td><td class="num"><?php echo e($opd->pending_agenda); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="4">Belum ada data.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="sec-sub">5 agenda paling banyak dilihat</div>
<table class="grid">
    <thead><tr><th style="width:30px;">No</th><th>Judul Agenda</th><th class="num" style="width:70px;">Dilihat</th></tr></thead>
    <tbody>
    <?php $__empty_2 = true; $__currentLoopData = $topViewedAgendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>"><td><?php echo e($i + 1); ?></td><td><?php echo e($a->title); ?></td><td class="num"><?php echo e(number_format($a->views)); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="3">Belum ada data.</td></tr>
    <?php endif; ?>
    </tbody>
</table>


<div class="sec">V. KATEGORI, WILAYAH & INVENTARIS</div>
<div class="sec-sub">Agenda per kategori</div>
<table class="grid">
    <thead><tr><th>Kategori</th><th class="num" style="width:70px;">Jumlah</th></tr></thead>
    <tbody>
    <?php $__empty_2 = true; $__currentLoopData = $agendaKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>"><td><?php echo e($kat->name); ?></td><td class="num"><?php echo e($kat->agendas_count); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="2">Belum ada data.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="sec-sub">Kota asal pengunjung</div>
<table class="grid">
    <thead><tr><th>Kota</th><th class="num" style="width:80px;">Kunjungan</th></tr></thead>
    <tbody>
    <?php $__empty_2 = true; $__currentLoopData = $kotaStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kota => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>"><td><?php echo e($kota); ?></td><td class="num"><?php echo e(number_format($total)); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="2">Belum ada data.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="sec-sub">Inventaris konten website</div>
<table class="grid">
    <thead><tr><th>Jenis Konten</th><th class="num" style="width:80px;">Jumlah</th></tr></thead>
    <tbody>
        <tr><td>Halaman</td><td class="num"><?php echo e($totalPages); ?></td></tr>
        <tr class="zebra"><td>Dokumen</td><td class="num"><?php echo e($totalDocuments); ?></td></tr>
        <tr><td>FAQ aktif</td><td class="num"><?php echo e($totalFaq); ?></td></tr>
        <tr><td>OPD aktif</td><td class="num"><?php echo e($totalOpdAktif); ?></td></tr>
    </tbody>
</table>

<?php if($isAdminViewer): ?>
<div class="sec-sub">OPD 7 hari tidak aktif (<?php echo e($opdTidakAktif->count()); ?>)</div>
<table class="grid">
    <thead><tr><th>Nama OPD</th><th style="width:150px;">Terakhir Mengisi</th></tr></thead>
    <tbody>
    <?php $__empty_2 = true; $__currentLoopData = $opdTidakAktif->take(15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr class="<?php echo e($loop->even ? 'zebra' : ''); ?>"><td><?php echo e($opd['name']); ?></td><td><?php echo e($opd['last_agenda'] ? $opd['last_agenda']->translatedFormat('d F Y') : 'Belum pernah'); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="2">Semua OPD aktif.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<?php endif; ?>


<table class="sig-table">
    <tr>
        <td style="width:50%;">
            Dibuat oleh: <strong><?php echo e($generatedBy); ?></strong><br>
            Pada: <?php echo e($generatedAt->translatedFormat('d F Y, H:i')); ?> WIB<br>
            Laporan dibuat otomatis oleh sistem.
        </td>
        <td style="width:50%; text-align:center;">
            Bekasi, <?php echo e($generatedAt->translatedFormat('d F Y')); ?><br>
            Mengetahui,<br><br><br><br><br>
            ( .............................................. )
        </td>
    </tr>
</table>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\dashboard\analytics-pdf.blade.php ENDPATH**/ ?>