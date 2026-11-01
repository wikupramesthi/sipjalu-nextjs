<?php $__env->startSection('title', 'All Members'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'All Members','page' => 'Settings','active' => 'All Members','route' => ''.e(route('pengguna.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Breadcrumb::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $attributes = $__attributesOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__attributesOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $component = $__componentOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__componentOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<!-- Content -->
<section class="section mt-2">
    
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-user"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div><div class="ml-stat-label">Total Pengguna</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['verified'])); ?></div><div class="ml-stat-label">Terverifikasi</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-time"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['unverified'])); ?></div><div class="ml-stat-label">Belum Verifikasi</div></div></div></div></div></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <div class="col-12">
                <form action="<?php echo e(route('pengguna.index')); ?>" method="GET" class="d-flex flex-wrap align-items-end gap-2">
                    <div>
                        <label class="form-label small mb-0">Dari</label>
                        <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Sampai</label>
                        <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="">Semua Tahun</option>
                            <?php for($tahun = date('Y'); $tahun >= 2025; $tahun--): ?>
                                <option value="<?php echo e($tahun); ?>" <?php echo e(request('tahun') == $tahun ? 'selected' : ''); ?>><?php echo e($tahun); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                        <a href="<?php echo e(route('pengguna.index')); ?>" class="btn btn-sm btn-light">Reset</a>
                        <a href="<?php echo e(route('pengguna.export', request()->all())); ?>" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mx-2">
                <table class="table table-hover mb-0" style="font-size:0.9rem;" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Daftar</th>
                            <th>Nama</th>
                            <th class="hide-xs">Email</th>
                            <th class="hide-sm">No. HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><small><?php echo e($user->created_at?->format('d/m/Y') ?? '-'); ?></small></td>
                                <td><span class="fw-semibold"><?php echo e($user->name); ?></span></td>
                                <td class="hide-xs"><small><?php echo e($user->email); ?></small></td>
                                <td class="hide-sm"><small><?php echo e($user->no_hp ?? '-'); ?></small></td>
                                <td>
                                    <?php if($user->email_verified_at): ?>
                                        <span class="badge bg-success">Terverifikasi</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Verifikasi</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#cekProfilModal-<?php echo e($user->uuid); ?>" title="Lihat detail">
                                        <i class="bi bi-eye"></i> Lihat
                                    </button>
                                    <?php echo $__env->make('pages.dashboard.modal-detail-user', ['user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pengguna.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($users->hasPages()): ?>
                <div class="card-footer bg-transparent d-flex justify-content-center">
                    <?php echo e($users->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- / Content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\dashboard\pengguna.blade.php ENDPATH**/ ?>