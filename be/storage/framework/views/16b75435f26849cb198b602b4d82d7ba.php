<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center; margin-bottom: 5px;">Laporan Data Pengguna</h3>
    <p style="text-align: center; margin: 0;">
        Dicetak pada: <?php echo e(now()->translatedFormat('d F Y')); ?>

    </p>

    
    <?php if(($filters['tahun'] ?? null) || ($filters['start_date'] ?? null) || ($filters['end_date'] ?? null)): ?>
        <p style="margin-top: 10px; font-size: 11px;">
            <strong>Filter:</strong>
            <?php if(!empty($filters['tahun'])): ?>
                Tahun <u><?php echo e($filters['tahun']); ?></u>
            <?php endif; ?>
            <?php if(!empty($filters['start_date'])): ?>
                Dari <u><?php echo e(\Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y')); ?></u>
            <?php endif; ?>
            <?php if(!empty($filters['end_date'])): ?>
                Sampai <u><?php echo e(\Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y')); ?></u>
            <?php endif; ?>
        </p>
    <?php endif; ?>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Handphone</th>
                <th>Status Verifikasi</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
<?php ($safe = fn($v) => is_string($v) && preg_match('/^[\s\t]*[=+\-@]/u', $v) ? "'" . $v : $v); ?>
            <?php $__currentLoopData = $pengguna; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($safe($user->name)); ?></td>
                    <td><?php echo e($safe($user->email)); ?></td>
                    <td><?php echo e($safe($user->no_hp ?? '-')); ?></td>
                    <td><?php echo e($user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi'); ?></td>
                    <td><?php echo e($user->created_at->format('d-m-Y')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\dashboard\exports.blade.php ENDPATH**/ ?>