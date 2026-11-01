<?php $__env->startSection('title', 'Log Audit Agenda'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Log Audit Agenda','page' => 'Agenda','active' => 'Log Audit','route' => ''.e(route('agendas.logAudit')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                <h6 class="mb-0"><i class="bi bi-clock-history me-1 text-primary"></i>Log Audit Approval Agenda</h6>
                <span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($logs->total())); ?> riwayat</span>
            </div>
            <a href="<?php echo e(route('agendas.index')); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>

        <div class="card-body border-bottom">
            <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
                <div>
                    <label class="form-label small mb-0" for="filterAdmin">Admin</label>
                    <select name="admin" id="filterAdmin" class="form-select form-select-sm" style="min-width:180px;">
                        <option value="">Semua Admin</option>
                        <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($admin->uuid); ?>" <?php if(request('admin') === $admin->uuid): echo 'selected'; endif; ?>><?php echo e($admin->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="filterAksi">Aksi</label>
                    <select name="aksi" id="filterAksi" class="form-select form-select-sm" style="min-width:180px;">
                        <option value="">Semua Aksi</option>
                        <?php $__currentLoopData = $aksiLabel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(request('aksi') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="<?php echo e(route('agendas.logAudit')); ?>" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 agenda-table" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th style="min-width:130px;">Waktu</th>
                            <th>Agenda</th>
                            <th style="min-width:150px;">Aksi</th>
                            <th style="min-width:150px;">Admin</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <tr>
                                <td class="text-nowrap">
                                    <small class="text-muted"><?php echo e($log->created_at->format('d M Y')); ?></small><br>
                                    <span class="small"><?php echo e($log->created_at->format('H:i')); ?></span>
                                </td>
                                <td>
                                    <span class="fw-semibold d-block"><?php echo e(Str::limit($log->agenda?->title ?? '(agenda dihapus)', 50)); ?></span>
                                    <small class="text-muted"><?php echo e($log->agenda?->user?->name ?? '-'); ?></small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-<?php echo e($aksiColor[$log->aksi] ?? 'secondary'); ?>-subtle text-<?php echo e($aksiColor[$log->aksi] ?? 'secondary'); ?> border border-<?php echo e($aksiColor[$log->aksi] ?? 'secondary'); ?>-subtle">
                                        <?php echo e($aksiLabel[$log->aksi] ?? $log->aksi); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="agenda-meta-inline"><i class="bi bi-person"></i><?php echo e($log->user?->name ?? 'Sistem'); ?></span>
                                </td>
                                <td>
                                    <?php if($log->catatan): ?>
                                        <small class="text-muted" title="<?php echo e($log->catatan); ?>"><?php echo e(Str::limit($log->catatan, 60)); ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada riwayat approval.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($logs->hasPages()): ?>
                <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">Menampilkan <?php echo e($logs->firstItem()); ?>-<?php echo e($logs->lastItem()); ?> dari <?php echo e($logs->total()); ?></small>
                    <div><?php echo e($logs->links('pagination::minimal')); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\agendas\log-audit.blade.php ENDPATH**/ ?>