<?php $__env->startSection('title', 'Pusat Informasi'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Pusat Informasi','page' => 'Pusat Informasi','active' => 'Faq & Answer','route' => ''.e(route('faq.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    .faq-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllFaq { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarFaq { border: 1px solid rgba(244, 63, 94, .25); }
</style>

<!-- Content -->
<section class="section">

    
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-question-mark"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div><div class="ml-stat-label">Total FAQ</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['active'])); ?></div><div class="ml-stat-label">Aktif</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['inactive'])); ?></div><div class="ml-stat-label">Tidak Aktif</div></div></div></div></div></div>
    </div>

    <div class="alert alert-info alert-dismissible mb-3 fade show position-relative" role="alert">
        <div class="d-flex">
            <i class="bi-bell-fill text-white fs-1 me-3 flex-shrink-0 align-self-start"></i>
            <div class="text-white mt-0">
                Lihat informasi dan jawaban seputar layanan <strong>Pemerintah Kota Bekasi</strong>.<br>
                Panduan ini membantu masyarakat memahami layanan serta program yang tersedia.
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white"> <?php echo e(session('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white"> <?php echo e(session('error')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0">Faq & Answer</h6>
                    <span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($stats['total'])); ?> FAQ</span>
                </div>
                <form method="GET" action="<?php echo e(route('faq.index')); ?>" class="d-flex gap-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Cari FAQ..." style="min-width:200px;">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    <?php if(request('search')): ?>
                    <a href="<?php echo e(route('faq.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#modal-form-add-faq">
                    <i class="bi bi-plus-lg"></i> Tambah Baru
                </button>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq.destroy')): ?>
        <div id="bulkBarFaq" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 bg-danger-subtle">
            <span class="small fw-semibold text-danger"><i class="bi bi-check-square me-1"></i><span id="bulkCountFaq">0</span> FAQ dipilih</span>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-light" onclick="clearFaqSelection()">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkDeleteFaq()"><i class="bi bi-trash"></i> Hapus terpilih</button>
            </div>
        </div>
        <form id="bulkDeleteFaqForm" action="<?php echo e(route('faq.bulkDestroy')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div id="bulkIdsFaq"></div>
        </form>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive mx-2">
                <table class="table table-hover mb-0" style="font-size:0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq.destroy')): ?><th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllFaq" class="form-check-input" title="Pilih semua"></th><?php endif; ?>
                            <th>No.</th>
                            <th>Tanya</th>
                            <th class="hide-xs">Kategori</th>
                            <th class="hide-xs">Urutan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq.destroy')): ?><td class="text-center"><input type="checkbox" class="form-check-input faq-check" value="<?php echo e($item->uuid); ?>"></td><?php endif; ?>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td style="min-width:200px;"><span class="fw-semibold"><?php echo e(Str::limit($item->pertanyaan, 70)); ?></span></td>
                            <td class="hide-xs"><span class="badge bg-primary-subtle text-primary"><?php echo e($item->kategori instanceof \BackedEnum ? $item->kategori->label() : ($item->kategori ?? '—')); ?></span></td>
                            <td class="hide-xs"><?php echo e($item->urutan); ?></td>
                            <td>
                                <span class="badge <?php echo e($item->status === 'active' ? 'bg-success' : 'bg-secondary'); ?>">
                                    <?php echo e($item->status === 'active' ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq.update')): ?>
                                    <a data-bs-toggle="modal" data-bs-target="#modal-form-view-faq-<?php echo e($item->uuid); ?>" class="btn btn-sm btn-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                    <?php echo $__env->make('pages.faq.modal-view', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-faq-<?php echo e($item->uuid); ?>" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <?php echo $__env->make('pages.faq.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq.destroy')): ?>
                                    <a onclick="showSweetAlert('<?php echo e($item->uuid); ?>')" title="Hapus" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    <form id="deleteForm_<?php echo e($item->uuid); ?>" action="<?php echo e(route('faq.destroy', $item->uuid)); ?>" method="POST" class="d-none">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada FAQ.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
</div>
            </div>
        </div>
    </div>
</section>
<?php if($items->hasPages()): ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">Menampilkan <?php echo e($items->firstItem()); ?>-<?php echo e($items->lastItem()); ?> dari <?php echo e($items->total()); ?> FAQ</small>
    <div><?php echo e($items->links('pagination::minimal')); ?></div>
</div>
<?php endif; ?>
<!-- / Content -->

<?php echo $__env->make('pages.faq.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
        const checkAll = document.getElementById('checkAllFaq');
        const bulkBar = document.getElementById('bulkBarFaq');
        const bulkCount = document.getElementById('bulkCountFaq');
        if (!checkAll || !bulkBar) return;

        function updateBulkBar() {
            const checks = document.querySelectorAll('.faq-check');
            const n = document.querySelectorAll('.faq-check:checked').length;
            bulkCount.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
            checks.forEach(c => c.closest('tr').classList.toggle('row-selected', c.checked));
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.faq-check').forEach(c => { c.checked = this.checked; });
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('faq-check')) updateBulkBar();
        });

        window.clearFaqSelection = function () {
            document.querySelectorAll('.faq-check:checked').forEach(c => { c.checked = false; });
            updateBulkBar();
        };

        window.bulkDeleteFaq = function () {
            const ids = Array.from(document.querySelectorAll('.faq-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Hapus ' + ids.length + ' FAQ?', text:'Data terpilih akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkDeleteFaqForm');
                    const box = document.getElementById('bulkIdsFaq');
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\faq\index.blade.php ENDPATH**/ ?>