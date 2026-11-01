<?php $__env->startSection('title', 'Failed Login'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Failed Login','page' => 'Keamanan','active' => 'Failed Login','route' => ''.e(route('security.failed-login.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white"><?php echo e(session('success')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white"><?php echo e(session('error')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bx bx-shield-x"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div>
                            <div class="ml-stat-label">Total Gagal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-time-five"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['last24'])); ?></div>
                            <div class="ml-stat-label">24 Jam Terakhir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-globe"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['unique_ip'])); ?></div>
                            <div class="ml-stat-label">IP Unik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-envelope"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['unique_email'])); ?></div>
                            <div class="ml-stat-label">Email Unik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-alarm-exclamation"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['suspicious_ip'])); ?></div>
                            <div class="ml-stat-label">IP Berisiko</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card ml-toolbar security-toolbar mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="<?php echo e(route('security.failed-login.index')); ?>"
                    class="security-filter-form d-flex flex-wrap align-items-end gap-3">
                    <div>
                        <label class="security-filter-label" for="filterSearch">Cari</label>
                        <input type="text" name="search" id="filterSearch" class="form-control"
                            placeholder="Email atau IP..." value="<?php echo e($search); ?>" style="min-width: 200px;">
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterStart">Dari Tanggal</label>
                        <input type="date" name="start_date" id="filterStart" class="form-control"
                            value="<?php echo e($startDate); ?>">
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterEnd">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="filterEnd" class="form-control"
                            value="<?php echo e($endDate); ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="<?php echo e(route('security.failed-login.index')); ?>" class="btn btn-light">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('failed-login.destroy')): ?>
                    <?php if($stats['total'] > 0): ?>
                        <button type="button" class="btn btn-outline-danger" onclick="clearFailedLogins()">
                            <i class="bi bi-eraser"></i> Bersihkan Semua
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Login Gagal</h5>
                <span class="badge bg-danger-subtle text-danger"><?php echo e(number_format($attempts->total())); ?>

                    percobaan</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <p class="text-muted small mb-0 d-none d-md-block">Percobaan login yang gagal beserta alamat IP-nya.</p>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('failed-login.destroy')): ?>
                <button type="button" id="bulkDeleteFailedBtn" class="btn btn-sm btn-danger d-none" onclick="bulkDeleteFailed()">
                    <i class="bi bi-trash"></i> Hapus terpilih (<span id="bulkCountFailed">0</span>)
                </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <form id="bulk-delete-failed-form" action="<?php echo e(route('security.failed-login.bulkDestroy')); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <div id="bulk-ids-failed"></div>
            </form>
            <div class="table-responsive">
                <table class="table security-table">
                    <thead>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('failed-login.destroy')): ?>
                            <th style="width:36px;"><input type="checkbox" id="checkAllFailed" class="form-check-input"></th>
                            <?php endif; ?>
                            <th>Email</th>
                            <th>Perangkat</th>
                            <th>IP Address</th>
                            <th>Tanda</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $__empty_2 = true; $__currentLoopData = $attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php $flags = $detector->failedFlags($attempt, $suspiciousIps); ?>
                            <tr>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('failed-login.destroy')): ?>
                                <td><input type="checkbox" class="form-check-input failed-check" value="<?php echo e($attempt->id); ?>"></td>
                                <?php endif; ?>
                                <td>
                                    <span class="d-block"><?php echo e($attempt->email ?? 'Tidak diketahui'); ?></span>
                                    <?php if($attempt->user): ?>
                                        <small class="log-muted"><i class="bi bi-person-check"></i>
                                            <?php echo e($attempt->user->name); ?></small>
                                    <?php else: ?>
                                        <small class="log-muted">Akun tidak terdaftar</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="d-block"><?php echo e($attempt->browser()); ?></span>
                                    <small class="log-muted">
                                        <?php echo e($attempt->platform()); ?>

                                        <span class="badge bg-light text-dark ms-1"><?php echo e($attempt->deviceType()); ?></span>
                                    </small>
                                </td>
                                <td><span class="font-monospace small"><?php echo e($attempt->ip_address ?? '-'); ?></span></td>
                                <td>
                                    <?php if($flags): ?>
                                        <?php $__currentLoopData = $flags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1 mb-1">
                                                <i class="bi bi-exclamation-triangle"></i><?php echo e($flag); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <span class="log-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="d-block"><?php echo e(optional($attempt->attempted_at)->translatedFormat('d M Y H:i') ?? '-'); ?></span>
                                    <small class="log-muted"><?php echo e(optional($attempt->attempted_at)?->diffForHumans()); ?></small>
                                </td>
                                <td class="text-end">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('failed-login.destroy')): ?>
                                        <button type="button" class="btn btn-sm btn-danger text-white" title="Hapus"
                                            onclick="deleteFailedLogin(<?php echo e($attempt->id); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-failed-login-<?php echo e($attempt->id); ?>"
                                            action="<?php echo e(route('security.failed-login.destroy', $attempt->id)); ?>"
                                            method="POST" class="d-none">
                                            <?php echo method_field('DELETE'); ?>
                                            <?php echo csrf_field(); ?>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-shield-check fs-3 d-block mb-2"></i>
                                    Tidak ada percobaan login gagal.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($attempts->hasPages()): ?>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($attempts->firstItem()); ?></strong> -
                        <strong><?php echo e($attempts->lastItem()); ?></strong> dari
                        <strong><?php echo e($attempts->total()); ?></strong> percobaan
                    </div>
                    <div><?php echo e($attempts->links('pagination::minimal')); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<form id="clear-failed-login-form" action="<?php echo e(route('security.failed-login.clear')); ?>" method="POST" class="d-none">
    <?php echo method_field('DELETE'); ?>
    <?php echo csrf_field(); ?>
</form>

<?php $__env->startPush('after-script'); ?>
    <script>
        function deleteFailedLogin(id) {
            Swal.fire({
                title: 'Hapus catatan ini?',
                text: 'Catatan percobaan login ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-failed-login-' + id).submit();
                }
            });
        }

        function clearFailedLogins() {
            Swal.fire({
                title: 'Bersihkan semua catatan?',
                text: 'Seluruh riwayat login gagal akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bersihkan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clear-failed-login-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAllFailed');
            const bulkBtn = document.getElementById('bulkDeleteFailedBtn');
            const bulkCount = document.getElementById('bulkCountFailed');
            function updateBulk() {
                const checks = document.querySelectorAll('.failed-check');
                const checked = document.querySelectorAll('.failed-check:checked');
                const n = checked.length;
                if (bulkCount) bulkCount.textContent = n;
                if (bulkBtn) bulkBtn.classList.toggle('d-none', n === 0);
                if (checkAll) {
                    checkAll.checked = n > 0 && n === checks.length;
                    checkAll.indeterminate = n > 0 && n < checks.length;
                }
            }
            if (checkAll) checkAll.addEventListener('change', function() {
                document.querySelectorAll('.failed-check').forEach(c => c.checked = this.checked);
                updateBulk();
            });
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('failed-check')) updateBulk();
            });
        });
        function bulkDeleteFailed() {
            const ids = Array.from(document.querySelectorAll('.failed-check:checked')).map(c => c.value);
            if (ids.length === 0) return;
            Swal.fire({
                title: 'Hapus ' + ids.length + ' catatan terpilih?',
                text: 'Data yang dipilih akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('bulk-delete-failed-form');
                    const container = document.getElementById('bulk-ids-failed');
                    container.innerHTML = '';
                    ids.forEach(id => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = id;
                        container.appendChild(inp);
                    });
                    form.submit();
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\security\failed-login\index.blade.php ENDPATH**/ ?>