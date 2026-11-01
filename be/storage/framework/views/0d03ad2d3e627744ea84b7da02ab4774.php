<?php $__env->startSection('title', 'Subkategori Dokumen'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Subkategori Dokumen','page' => 'Dokumen','active' => 'Subkategori Dokumen','route' => ''.e(route('document-subcategories.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0">Subkategori Dokumen</h6>
                    <small class="text-muted">Kelola subkategori di bawah kategori utama.</small>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <form method="GET" action="<?php echo e(route('document-subcategories.index')); ?>" class="d-flex gap-1">
                        <input type="text" name="search" value="<?php echo e($search); ?>" class="form-control form-control-sm"
                            placeholder="Cari subkategori..." aria-label="Cari subkategori">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        <?php if($search !== ''): ?>
                            <a href="<?php echo e(route('document-subcategories.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                        <?php endif; ?>
                    </form>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.store')): ?>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSubcategoryModal">
                            <i class="bi bi-plus-lg"></i> Tambah Subkategori
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama Subkategori</th>
                        <th>Kategori Induk</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Total Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_2 = true; $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <td><?php echo e($subcategories->firstItem() + $loop->index); ?></td>
                            <td class="fw-semibold"><?php echo e($subcategory->name); ?></td>
                            <td><?php echo e($subcategory->parent->name); ?></td>
                            <td><small class="text-muted"><?php echo e(Str::limit($subcategory->description, 60) ?? '-'); ?></small></td>
                            <td>
                                <?php if($subcategory->status === 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary"><?php echo e(number_format($subcategory->documents_count)); ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.update')): ?>
                                        <button type="button" class="btn btn-sm btn-success" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#editSubcategory<?php echo e($subcategory->uuid); ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.destroy')): ?>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="showSubcategoryDeleteAlert('<?php echo e($subcategory->uuid); ?>', <?php echo \Illuminate\Support\Js::from($subcategory->name)->toHtml() ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="deleteSubcategoryForm_<?php echo e($subcategory->uuid); ?>"
                                            action="<?php echo e(route('document-subcategories.destroy', $subcategory->uuid)); ?>"
                                            method="POST" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.update')): ?>
                                        <?php echo $__env->make('pages.document-subcategories.modal-edit', ['item' => $subcategory], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada subkategori dokumen.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($subcategories->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <small class="text-muted">Menampilkan <?php echo e($subcategories->firstItem()); ?>-<?php echo e($subcategories->lastItem()); ?> dari <?php echo e($subcategories->total()); ?> subkategori</small>
            <div><?php echo e($subcategories->links('pagination::minimal')); ?></div>
        </div>
    <?php endif; ?>
</section>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('document-categories.store')): ?>
    <?php echo $__env->make('pages.document-subcategories.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php $__env->startPush('after-script'); ?>
<script>
    function showSubcategoryDeleteAlert(uuid, name) {
        Swal.fire({
            title: 'Hapus subkategori?',
            text: `Subkategori "${name}" akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f43f5e',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteSubcategoryForm_' + uuid).submit();
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\document-subcategories\index.blade.php ENDPATH**/ ?>