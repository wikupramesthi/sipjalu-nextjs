<?php $__env->startSection('title', 'Log Audit Berita'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Log Audit Berita','page' => 'Berita','active' => 'Log Audit','route' => ''.e(route('beritas.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<section class="section">
    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0"><i class="bi bi-clock-history me-1 text-primary"></i>Riwayat Berita</h6>
                <span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($logs->total())); ?> riwayat</span>
            </div>
            <a href="<?php echo e(route('beritas.index')); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>

        <div class="card-body border-bottom">
            <form method="GET" action="<?php echo e(route('beritas.logAudit')); ?>" class="d-flex flex-wrap align-items-end gap-2">
                <div>
                    <label class="form-label small mb-0" for="auditEvent">Aksi</label>
                    <select name="event" id="auditEvent" class="form-select form-select-sm">
                        <option value="">Semua Aksi</option>
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(request('event') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="auditUser">Pengguna</label>
                    <select name="user_uuid" id="auditUser" class="form-select form-select-sm">
                        <option value="">Semua Pengguna</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->user_uuid); ?>" <?php if(request('user_uuid') === $user->user_uuid): echo 'selected'; endif; ?>><?php echo e($user->user_name ?? 'Sistem'); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="auditStart">Dari tanggal</label>
                    <input type="date" name="start_date" id="auditStart" class="form-control form-control-sm" value="<?php echo e(request('start_date')); ?>">
                </div>
                <div>
                    <label class="form-label small mb-0" for="auditEnd">Sampai tanggal</label>
                    <input type="date" name="end_date" id="auditEnd" class="form-control form-control-sm" value="<?php echo e(request('end_date')); ?>">
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="<?php echo e(route('beritas.logAudit')); ?>" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Waktu</th><th>Berita</th><th>Aksi</th><th>Pengguna</th><th>Alamat IP</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_2 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <td class="text-nowrap">
                                <small class="text-muted"><?php echo e($log->created_at?->format('d M Y')); ?></small><br>
                                <span class="small"><?php echo e($log->created_at?->format('H:i:s')); ?></span>
                            </td>
                            <td><span class="fw-semibold"><?php echo e($log->auditable_label ?? '(berita)'); ?></span></td>
                            <td><span class="badge <?php echo e($log->event_badge); ?>"><?php echo e($log->event_label); ?></span></td>
                            <td><?php echo e($log->user_name ?? 'Sistem'); ?></td>
                            <td><small class="text-muted"><?php echo e($log->ip_address ?? '-'); ?></small></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="5" class="text-center text-muted py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada riwayat berita.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Menampilkan <?php echo e($logs->firstItem() ?? 0); ?>-<?php echo e($logs->lastItem() ?? 0); ?> dari <?php echo e($logs->total()); ?></small>
            <?php echo e($logs->links('pagination::minimal')); ?>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\beritas\log-audit.blade.php ENDPATH**/ ?>