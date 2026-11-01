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
    <p class="period">Periode <?php echo e($startDate ?: 'awal'); ?> s/d <?php echo e($endDate ?: 'sekarang'); ?> &middot; Dicetak <?php echo e(now()->format('d-m-Y H:i')); ?></p>

    <h2>Ringkasan</h2>
    <table class="summary">
        <tr><th>Metrik</th><th>Jumlah</th></tr>
        <tr><td>Total berita</td><td><?php echo e(number_format($total)); ?></td></tr>
        <?php $__currentLoopData = ['published' => 'Published', 'pending' => 'Pending', 'draft' => 'Draft', 'scheduled' => 'Scheduled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr><td><?php echo e($label); ?></td><td><?php echo e(number_format($perStatus->get($key, 0))); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr><td>Total dilihat</td><td><?php echo e(number_format($totalViews)); ?></td></tr>
    </table>

    <?php if($perCategory->isNotEmpty()): ?>
        <h2>Berita per Kategori</h2>
        <table>
            <tr><th>Kategori</th><th>Jumlah</th></tr>
            <?php $__currentLoopData = $perCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr><td><?php echo e($category); ?></td><td><?php echo e(number_format($count)); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php endif; ?>

    <h2>Daftar Berita</h2>
    <table>
        <tr><th style="width:28px">No</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal Publikasi</th><th>Dilihat</th></tr>
        <?php $__empty_2 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($berita->title); ?></td>
                <td><?php echo e($berita->category?->name ?? '-'); ?></td>
                <td><?php echo e($berita->user?->name ?? '-'); ?></td>
                <td><?php echo e(ucfirst($berita->status)); ?></td>
                <td><?php echo e($berita->scheduled_at?->format('d/m/Y H:i') ?? '-'); ?></td>
                <td><?php echo e(number_format($berita->views)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
            <tr><td colspan="7" style="text-align:center">Tidak ada berita pada periode ini.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\beritas\laporan.blade.php ENDPATH**/ ?>