<?php $__env->startSection('title', 'Kelola Layanan'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Kelola Layanan','page' => 'Layanan','active' => 'Daftar Layanan','route' => ''.e(route('services.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<style>
    .service-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllService { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarService { border: 1px solid rgba(244, 63, 94, .25); }
</style>

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

    
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-briefcase"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div><div class="ml-stat-label">Total Layanan</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['active'])); ?></div><div class="ml-stat-label">Aktif</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['inactive'])); ?></div><div class="ml-stat-label">Nonaktif</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-globe"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['external'])); ?></div><div class="ml-stat-label">Eksternal</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-danger"><i class="bx bx-building"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['internal'])); ?></div><div class="ml-stat-label">Internal</div></div></div></div></div></div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="<?php echo e(route('services.index')); ?>" class="d-flex flex-wrap align-items-end gap-2">
                    <div>
                        <label class="form-label small mb-0">Cari</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama..." value="<?php echo e(request('search')); ?>" style="min-width:160px;">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Kategori</label>
                        <select name="category" class="form-select form-select-sm" style="min-width:150px;">
                            <option value="">Semua</option>
                            <option value="external" <?php if(request('category')==='external'): echo 'selected'; endif; ?>>Eksternal</option>
                            <option value="internal" <?php if(request('category')==='internal'): echo 'selected'; endif; ?>>Internal</option>
                            <option value="other" <?php if(request('category')==='other'): echo 'selected'; endif; ?>>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-0">Status</label>
                        <select name="status" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="">Semua</option>
                            <option value="active" <?php if(request('status')==='active'): echo 'selected'; endif; ?>>Aktif</option>
                            <option value="inactive" <?php if(request('status')==='inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        <a href="<?php echo e(route('services.index')); ?>" class="btn btn-sm btn-light">Reset</a>
                    </div>
                </form>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('services.store')): ?>
                <a href="<?php echo e(route('services.create')); ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Daftar Layanan</h6>
                <span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($stats['total'])); ?> layanan</span>
            </div>
            <small class="text-muted d-none d-md-block"><?php echo e($services->firstItem()); ?>-<?php echo e($services->lastItem()); ?> dari <?php echo e($services->total()); ?></small>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('services.destroy')): ?>
        <div id="bulkBarService" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 bg-danger-subtle">
            <span class="small fw-semibold text-danger"><i class="bi bi-check-square me-1"></i><span id="bulkCountService">0</span> layanan dipilih</span>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-light" onclick="clearServiceSelection()">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkDeleteService()"><i class="bi bi-trash"></i> Hapus terpilih</button>
            </div>
        </div>
        <form id="bulkDeleteServiceForm" action="<?php echo e(route('services.bulkDestroy')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div id="bulkIdsService"></div>
        </form>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive mx-2">
                <table class="table table-hover mb-0" style="font-size:0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('services.destroy')): ?><th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllService" class="form-check-input" title="Pilih semua"></th><?php endif; ?>
                            <th>No.</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th class="hide-xs">URL</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('services.destroy')): ?><td class="text-center"><input type="checkbox" class="form-check-input service-check" value="<?php echo e($item->uuid); ?>"></td><?php endif; ?>
                            <td><?php echo e(($services->currentPage()-1)*$services->perPage() + $loop->iteration); ?></td>
                            <td>
                                <?php if($item->image_url): ?>
                                <img src="<?php echo e($item->image_url); ?>" alt="<?php echo e($item->name); ?>" class="rounded" style="width:60px;height:42px;object-fit:cover;" loading="lazy">
                                <?php else: ?>
                                <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted" style="width:60px;height:42px;"><i class="bi bi-image"></i></span>
                                <?php endif; ?>
                            </td>
                            <td style="min-width:180px;">
                                <span class="fw-semibold d-block"><?php echo e(Str::limit($item->name, 50)); ?></span>
                                <?php if($item->description): ?><small class="text-muted d-block"><?php echo e(Str::limit($item->description, 60)); ?></small><?php endif; ?>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary"><?php echo e($item->category_label); ?></span></td>
                            <td class="hide-xs">
                                <?php if($item->url): ?>
                                <a href="<?php echo e($item->url); ?>" target="_blank" rel="noopener" class="small text-truncate d-inline-block" style="max-width:160px;"><?php echo e($item->url); ?></a>
                                <?php else: ?>
                                <small class="text-muted">-</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($item->is_active): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('services.update')): ?>
                                    <a href="<?php echo e(route('services.edit', $item->uuid)); ?>" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('services.destroy')): ?>
                                    <a onclick="showSweetAlert('<?php echo e($item->uuid); ?>')" title="Hapus" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    <form id="deleteForm_<?php echo e($item->uuid); ?>" action="<?php echo e(route('services.destroy', $item->uuid)); ?>" method="POST" class="d-none">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada layanan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">Menampilkan <?php echo e($services->firstItem()); ?>-<?php echo e($services->lastItem()); ?> dari <?php echo e($services->total()); ?></small>
                <div><?php echo e($services->links('pagination::minimal')); ?></div>
            </div>
        </div>
    </div>
</section>

<script>
    function showSweetAlert(getId) {
        Swal.fire({title:'Hapus layanan?', text:'Akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!'}).then(r=>{ if(r.isConfirmed) document.getElementById('deleteForm_'+getId).submit(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAllService');
        const bulkBar = document.getElementById('bulkBarService');
        const bulkCount = document.getElementById('bulkCountService');
        if (!checkAll || !bulkBar) return;

        function updateBulkBar() {
            const checks = document.querySelectorAll('.service-check');
            const n = document.querySelectorAll('.service-check:checked').length;
            bulkCount.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
            checks.forEach(c => c.closest('tr').classList.toggle('row-selected', c.checked));
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.service-check').forEach(c => { c.checked = this.checked; });
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('service-check')) updateBulkBar();
        });

        window.clearServiceSelection = function () {
            document.querySelectorAll('.service-check:checked').forEach(c => { c.checked = false; });
            updateBulkBar();
        };

        window.bulkDeleteService = function () {
            const ids = Array.from(document.querySelectorAll('.service-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Hapus ' + ids.length + ' layanan?', text:'Data terpilih akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkDeleteServiceForm');
                    const box = document.getElementById('bulkIdsService');
                    box.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        updateBulkBar();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\services\index.blade.php ENDPATH**/ ?>