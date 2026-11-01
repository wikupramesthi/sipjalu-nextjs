<?php $__env->startSection('title', 'Kategori Dokumen'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Kategori Dokumen','page' => 'Kategori Dokumen','active' => 'Kategori','route' => ''.e(route('document-categories.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    .doccat-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllDocCat { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarDocCat { border: 1px solid rgba(244, 63, 94, .25); }
</style>

<!-- Content -->
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
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-folder"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div><div class="ml-stat-label">Total Kategori</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['active'])); ?></div><div class="ml-stat-label">Aktif</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['inactive'])); ?></div><div class="ml-stat-label">Tidak Aktif</div></div></div></div></div></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0">Kategori Dokumen</h6>
                    <span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($stats['total'])); ?> kategori</span>
                </div>
                <form method="GET" action="<?php echo e(route('document-categories.index')); ?>" class="d-flex gap-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Cari kategori..." style="min-width:200px;">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    <?php if(request('search')): ?>
                    <a href="<?php echo e(route('document-categories.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.store')): ?>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('document-subcategories.index')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-diagram-3"></i> Kelola Subkategori
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modal-form-add-categories">
                        <i class="bi bi-plus-lg"></i> Tambah Kategori
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.destroy')): ?>
        <div id="bulkBarDocCat" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 bg-danger-subtle">
            <span class="small fw-semibold text-danger"><i class="bi bi-check-square me-1"></i><span id="bulkCountDocCat">0</span> kategori dipilih</span>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-light" onclick="clearDocCatSelection()">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkDeleteDocCat()"><i class="bi bi-trash"></i> Hapus terpilih</button>
            </div>
        </div>
        <form id="bulkDeleteDocCatForm" action="<?php echo e(route('document-categories.bulkDestroy')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div id="bulkIdsDocCat"></div>
        </form>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive mx-2">
                <table class="table table-hover mb-0" style="font-size:0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.destroy')): ?><th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllDocCat" class="form-check-input" title="Pilih semua"></th><?php endif; ?>
                            <th>No.</th>
                            <th>Nama Kategori</th>
                            <th class="hide-xs">Deskripsi</th>
                            <th>Status</th>
                            <th>Total Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.destroy')): ?><td class="text-center"><input type="checkbox" class="form-check-input doccat-check" value="<?php echo e($item->uuid); ?>"></td><?php endif; ?>
                            <td><?php echo e($categories->firstItem() + $loop->index); ?></td>
                            <td><span class="fw-semibold"><?php echo e($item->name); ?></span></td>
                            <td class="hide-xs"><small class="text-muted"><?php echo e(Str::limit($item->description, 60) ?? '-'); ?></small></td>
                            <td>
                                <?php if($item->status === 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($item->documents_count)); ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.update')): ?>
                                    <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-categories-<?php echo e($item->uuid); ?>"
                                        class="btn btn-sm btn-success" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php echo $__env->make('pages.document-categories.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.destroy')): ?>
                                    <a onclick="showSweetAlert('<?php echo e($item->uuid); ?>')" title="Hapus"
                                        class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="deleteForm_<?php echo e($item->uuid); ?>" action="<?php echo e(route('document-categories.destroy', $item->uuid)); ?>"
                                        method="POST" class="d-none">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php if($categories->hasPages()): ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">Menampilkan <?php echo e($categories->firstItem()); ?>-<?php echo e($categories->lastItem()); ?> dari <?php echo e($categories->total()); ?> kategori</small>
    <div><?php echo e($categories->links('pagination::minimal')); ?></div>
</div>
<?php endif; ?>
</section>
<!-- / Content -->

<?php echo $__env->make('pages.document-categories.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus secara permanen dan tidak bisa dikembalikan. Apakah Anda yakin ingin menghapusnya?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAllDocCat');
        const bulkBar = document.getElementById('bulkBarDocCat');
        const bulkCount = document.getElementById('bulkCountDocCat');
        if (!checkAll || !bulkBar) return;

        function updateBulkBar() {
            const checks = document.querySelectorAll('.doccat-check');
            const n = document.querySelectorAll('.doccat-check:checked').length;
            bulkCount.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
            checks.forEach(c => c.closest('tr').classList.toggle('row-selected', c.checked));
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.doccat-check').forEach(c => { c.checked = this.checked; });
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('doccat-check')) updateBulkBar();
        });

        window.clearDocCatSelection = function () {
            document.querySelectorAll('.doccat-check:checked').forEach(c => { c.checked = false; });
            updateBulkBar();
        };

        window.bulkDeleteDocCat = function () {
            const ids = Array.from(document.querySelectorAll('.doccat-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Hapus ' + ids.length + ' kategori?', text:'Kategori yang masih memiliki dokumen akan dilewati.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkDeleteDocCatForm');
                    const box = document.getElementById('bulkIdsDocCat');
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\document-categories\index.blade.php ENDPATH**/ ?>