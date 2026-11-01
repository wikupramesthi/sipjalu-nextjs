<?php $__env->startSection('title', 'Login Activity'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Login Activity','page' => 'Keamanan','active' => 'Login Activity','route' => ''.e(route('security.login-activity.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-log-in-circle"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div>
                            <div class="ml-stat-label">Total Login</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-success"><i class="bx bx-calendar-check"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['today'])); ?></div>
                            <div class="ml-stat-label">Hari Ini</div>
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
                            <div class="ml-stat-value"><?php echo e(number_format($stats['week'])); ?></div>
                            <div class="ml-stat-label">7 Hari Terakhir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-user-check"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['unique'])); ?></div>
                            <div class="ml-stat-label">Pengguna Unik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bx bx-alarm-exclamation"></i></div>
                        <div>
                            <div class="ml-stat-value"><?php echo e(number_format($stats['suspicious'])); ?></div>
                            <div class="ml-stat-label">Mencurigakan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card ml-toolbar security-toolbar mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <form method="GET" action="<?php echo e(route('security.login-activity.index')); ?>"
                class="security-filter-form d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label class="security-filter-label" for="filterSearch">Cari</label>
                    <input type="text" name="search" id="filterSearch" class="form-control"
                        placeholder="Nama, email, atau IP..." value="<?php echo e($search); ?>" style="min-width: 220px;">
                </div>
                <div>
                    <label class="security-filter-label" for="filterStart">Dari Tanggal</label>
                    <input type="date" name="start_date" id="filterStart" class="form-control"
                        value="<?php echo e($startDate); ?>">
                </div>
                <div>
                    <label class="security-filter-label" for="filterEnd">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="filterEnd" class="form-control" value="<?php echo e($endDate); ?>">
                </div>
                <div class="d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="<?php echo e(route('security.login-activity.index')); ?>" class="btn btn-light">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('login-activity.destroy')): ?>
                        <?php if($stats['total'] > 0): ?>
                            <button type="button" class="btn btn-outline-danger" onclick="clearLoginActivities()">
                                <i class="bi bi-eraser"></i> Bersihkan Semua
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Riwayat Login</h5>
                <span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($activities->total())); ?>

                    catatan</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <p class="text-muted small mb-0 d-none d-md-block">Daftar aktivitas login berhasil ke dalam sistem.</p>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('login-activity.destroy')): ?>
                <button type="button" id="bulkDeleteLoginActivityBtn" class="btn btn-sm btn-danger d-none" onclick="bulkDeleteLoginActivity()">
                    <i class="bi bi-trash"></i> Hapus terpilih (<span id="bulkCountLoginActivity">0</span>)
                </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <form id="bulk-delete-login-activity-form" action="<?php echo e(route('security.login-activity.bulkDestroy')); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <div id="bulk-ids-login-activity"></div>
            </form>
            <div class="table-responsive">
                <table class="table security-table">
                    <thead>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('login-activity.destroy')): ?>
                            <th style="width:36px;"><input type="checkbox" id="checkAllLoginActivity" class="form-check-input"></th>
                            <?php endif; ?>
                            <th>Pengguna</th>
                            <th>Perangkat</th>
                            <th>IP Address</th>
                            <th>Tanda</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $__empty_2 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php $flags = $detector->loginFlags($activity, $suspiciousUsers, $suspiciousIps); ?>
                            <tr>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('login-activity.destroy')): ?>
                                <td><input type="checkbox" class="form-check-input login-activity-check" value="<?php echo e($activity->id); ?>"></td>
                                <?php endif; ?>
                                <td class="log-user">
                                    <span class="log-user-name d-block"><?php echo e($activity->name ?? 'Tidak diketahui'); ?></span>
                                    <small class="log-muted"><?php echo e($activity->email ?? '-'); ?></small>
                                </td>
                                <td>
                                    <span class="d-block"><?php echo e($activity->browser()); ?></span>
                                    <small class="log-muted">
                                        <?php echo e($activity->platform()); ?>

                                        <span class="badge bg-light text-dark ms-1"><?php echo e($activity->deviceType()); ?></span>
                                    </small>
                                </td>
                                <td><span class="font-monospace small"><?php echo e($activity->ip_address ?? '-'); ?></span></td>
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
                                    <span class="d-block"><?php echo e(optional($activity->logged_in_at)->translatedFormat('d M Y H:i') ?? '-'); ?></span>
                                    <small class="log-muted"><?php echo e(optional($activity->logged_in_at)?->diffForHumans()); ?></small>
                                </td>
                                <td class="text-end">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('login-activity.destroy')): ?>
                                        <button type="button" class="btn btn-sm btn-danger text-white"
                                            title="Hapus" onclick="deleteLoginActivity(<?php echo e($activity->id); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-login-activity-<?php echo e($activity->id); ?>"
                                            action="<?php echo e(route('security.login-activity.destroy', $activity->id)); ?>"
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
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada aktivitas login yang tercatat.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($activities->hasPages()): ?>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($activities->firstItem()); ?></strong> -
                        <strong><?php echo e($activities->lastItem()); ?></strong> dari
                        <strong><?php echo e($activities->total()); ?></strong> catatan
                    </div>
                    <div><?php echo e($activities->links('pagination::minimal')); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<form id="clear-login-activity-form" action="<?php echo e(route('security.login-activity.clear')); ?>" method="POST" class="d-none">
    <?php echo method_field('DELETE'); ?>
    <?php echo csrf_field(); ?>
</form>

<?php $__env->startPush('after-script'); ?>
    <script>
        function deleteLoginActivity(id) {
            Swal.fire({
                title: 'Hapus catatan login?',
                text: 'Catatan ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-login-activity-' + id).submit();
                }
            });
        }

        function clearLoginActivities() {
            Swal.fire({
                title: 'Bersihkan semua catatan?',
                text: 'Seluruh riwayat login akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bersihkan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clear-login-activity-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAllLoginActivity');
            const bulkBtn = document.getElementById('bulkDeleteLoginActivityBtn');
            const bulkCount = document.getElementById('bulkCountLoginActivity');
            function updateBulk() {
                const checks = document.querySelectorAll('.login-activity-check');
                const checked = document.querySelectorAll('.login-activity-check:checked');
                const n = checked.length;
                if (bulkCount) bulkCount.textContent = n;
                if (bulkBtn) bulkBtn.classList.toggle('d-none', n === 0);
                if (checkAll) {
                    checkAll.checked = n > 0 && n === checks.length;
                    checkAll.indeterminate = n > 0 && n < checks.length;
                }
            }
            if (checkAll) checkAll.addEventListener('change', function() {
                document.querySelectorAll('.login-activity-check').forEach(c => c.checked = this.checked);
                updateBulk();
            });
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('login-activity-check')) updateBulk();
            });
        });
        function bulkDeleteLoginActivity() {
            const ids = Array.from(document.querySelectorAll('.login-activity-check:checked')).map(c => c.value);
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
                    const form = document.getElementById('bulk-delete-login-activity-form');
                    const container = document.getElementById('bulk-ids-login-activity');
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\security\login-activity\index.blade.php ENDPATH**/ ?>