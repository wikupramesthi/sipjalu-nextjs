<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Audit Log</title>
    <style>
        @page { margin: 22px 28px 42px 28px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1f2937; line-height: 1.45; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-logo { width: 90px; text-align: center; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-text .instansi { font-size: 14px; font-weight: bold; color: #1B3A5F; letter-spacing: 0.5px; }
        .kop-text .sub { font-size: 9px; color: #4b5563; }
        .kop-rule { border: none; border-top: 3px solid #1B3A5F; margin: 6px 0 1px 0; }
        .kop-rule2 { border: none; border-top: 1px solid #C9A227; margin: 0 0 12px 0; }
        .judul { text-align: center; margin-bottom: 6px; }
        .judul h1 { font-size: 15px; color: #1B3A5F; margin: 0 0 2px 0; letter-spacing: 1px; }
        .judul .periode { font-size: 10px; font-weight: bold; }
        .meta { width: 100%; border-collapse: collapse; margin: 8px 0 12px 0; font-size: 8px; color: #4b5563; }
        .meta td { padding: 2px 4px; }
        .section-title { font-size: 11px; font-weight: bold; color: #1B3A5F; border-left: 4px solid #C9A227; padding-left: 8px; margin: 12px 0 8px 0; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 8px; }
        .data-table th { background: #1B3A5F; color: #ffffff; padding: 5px 4px; text-align: left; }
        .data-table td { padding: 4px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .data-table tr:nth-child(even) td { background: #f3f4f6; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 7px; font-weight: bold; color: #fff; }
        .badge-created { background: #198754; } .badge-updated { background: #0d99c7; } .badge-deleted { background: #dc3545; } .badge-restored { background: #6c757d; }
        .ttd-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .ttd-table td { width: 50%; vertical-align: top; }
        .ttd-box { text-align: center; font-size: 9px; }
        .footer { position: fixed; bottom: -28px; left: 0; right: 0; font-size: 7px; color: #6b7280; border-top: 1px solid #d1d5db; padding-top: 4px; }
        .pagenum:before { content: counter(page); }
        .filter-box { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; margin-bottom: 10px; font-size: 8px; }
        .esign { margin-top: 8px; border: 1px dashed #C9A227; border-radius: 6px; padding: 8px; background: #fffbeb; font-size: 7px; color: #92400e; }
    </style>
</head>
<body>
<div class="footer">
    <table style="width:100%; border-collapse:collapse;"><tr><td>Laporan Audit Log — Pemerintah Kota Bekasi Kota Bekasi</td><td style="text-align:right;">Halaman <span class="pagenum"></span></td></tr></table>
</div>

<table class="kop-table">
    <tr>
        <td class="kop-logo"><img src="<?php echo e(public_path('img/logo.png')); ?>" style="height:60px;" alt="Logo"></td>
        <td class="kop-text">
            <div class="instansi">PEMERINTAH KOTA BEKASI</div>
            <div class="instansi">KOTA BEKASI</div>
            <div class="sub">Jl. Jend. Ahmad Yani No. 1, Kota Bekasi 17141 | Telp. (021) 8896176</div>
        </td>
        <td class="kop-logo"></td>
    </tr>
</table>
<hr class="kop-rule"><hr class="kop-rule2">

<div class="judul">
    <h1>LAPORAN JEJAK AUDIT</h1>
    <div class="periode">Periode: <?php echo e($periode); ?></div>
</div>

<table class="meta">
    <tr><td style="width:22%;">Filter Pengguna</td><td style="width:35%;">: <?php echo e($filterUser); ?></td><td style="width:18%;">Dicetak oleh</td><td>: <?php echo e($dicetakOleh); ?></td></tr>
    <tr><td>Filter Aktivitas</td><td>: <?php echo e($filterEvent); ?></td><td>Waktu cetak</td><td>: <?php echo e($waktuCetak); ?> WIB</td></tr>
    <tr><td>Filter Modul</td><td>: <?php echo e($filterModel); ?></td><td>Jumlah data</td><td>: <?php echo e(number_format($total)); ?> catatan</td></tr>
    <?php if(!empty($search)): ?><tr><td>Pencarian</td><td colspan="3">: <?php echo e($search); ?></td></tr><?php endif; ?>
</table>

<div class="filter-box">
    <strong>Catatan:</strong> Laporan ini dihasilkan otomatis dari <em>Audit Log</em> Pemerintah Kota Bekasi. Setiap perubahan data (created/updated/deleted/restored) tercatat beserta pengguna, IP, dan waktu untuk keperluan audit &amp; e-sign.
</div>

<div class="section-title">DAFTAR AKTIVITAS (<?php echo e(number_format($total)); ?>)</div>
<table class="data-table">
    <thead>
        <tr>
            <th style="width:4%;">No</th>
            <th style="width:13%;">Waktu</th>
            <th style="width:14%;">Pengguna</th>
            <th style="width:8%;">Aktivitas</th>
            <th style="width:12%;">Modul</th>
            <th style="width:20%;">Data</th>
            <th style="width:14%;">IP Address</th>
            <th style="width:15%;">Perubahan</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_2 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr>
            <td><?php echo e($i + 1); ?></td>
            <td><?php echo e($log->created_at?->format('d/m/Y H:i') ?? '-'); ?><br><small style="color:#6b7280;"><?php echo e($log->created_at?->diffForHumans()); ?></small></td>
            <td><?php echo e($log->user_name ?? 'Sistem'); ?><br><small style="color:#6b7280;"><?php echo e($log->user_email ?? '-'); ?></small></td>
            <td><span class="badge badge-<?php echo e($log->event); ?>"><?php echo e(ucfirst($log->event)); ?></span></td>
            <td><?php echo e(class_basename($log->auditable_type)); ?></td>
            <td><?php echo e($log->auditable_label ?? '-'); ?><br><small style="color:#6b7280;">#<?php echo e($log->auditable_id); ?></small></td>
            <td style="font-family: monospace;"><?php echo e($log->ip_address ?? '-'); ?></td>
            <td>
                <?php $changes = $log->changes(); ?>
                <?php if(empty($changes)): ?>
                    <small style="color:#9ca3af;">-</small>
                <?php else: ?>
                    <?php $__currentLoopData = array_slice($changes, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $vals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><strong><?php echo e($field); ?>:</strong> <?php echo e(is_scalar($vals['old'] ?? null) ? \Illuminate\Support\Str::limit((string)($vals['old'] ?? '-'), 20) : '-'); ?> → <?php echo e(is_scalar($vals['new'] ?? null) ? \Illuminate\Support\Str::limit((string)($vals['new'] ?? '-'), 20) : '-'); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(count($changes) > 3): ?><small style="color:#6b7280;">+<?php echo e(count($changes)-3); ?> field lain</small><?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="8" style="text-align:center; padding:12px;">Tidak ada data pada periode/filter ini.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<table class="ttd-table">
    <tr>
        <td>
            <div class="esign">
                <strong>E-Sign / Verifikasi:</strong><br>
                Dokumen ini dicetak otomatis dari sistem Pemerintah Kota Bekasi. Keaslian dapat diverifikasi dengan mencocokkan waktu cetak &amp; filter di atas dengan data di menu <em>Keamanan &gt; Audit Log</em>.<br>
                Hash: <?php echo e(md5($waktuCetak . $total . $periode)); ?>

            </div>
        </td>
        <td>
            <div class="ttd-box">
                Bekasi, <?php echo e(now()->translatedFormat('d F Y')); ?><br>
                Kepala Dinas / Pejabat Berwenang<br><br><br><br>
                <strong><u>( ........................................ )</u></strong><br>
                NIP. ........................................
            </div>
        </td>
    </tr>
</table>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\security\audit-log\laporan.blade.php ENDPATH**/ ?>