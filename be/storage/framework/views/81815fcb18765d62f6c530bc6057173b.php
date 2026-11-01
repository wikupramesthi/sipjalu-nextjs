<?php $__env->startSection('title', 'Menu Website'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Menu Website','page' => 'Pengaturan','active' => 'Menu Website','route' => ''.e(route('website-menu.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<?php $__env->startSection('content'); ?>
<div class="row mb-4 g-3 ml-stats">
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="ml-stat">
                    <div class="ml-stat-icon ic-primary"><i class="bx bx-menu"></i></div>
                    <div>
                        <div class="ml-stat-value"><?php echo e($menus->total()); ?></div>
                        <div class="ml-stat-label">Total Menu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="ml-stat">
                    <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                    <div>
                        <div class="ml-stat-value"><?php echo e(number_format($stats['active'])); ?></div>
                        <div class="ml-stat-label">Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="ml-stat">
                    <div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div>
                    <div>
                        <div class="ml-stat-value"><?php echo e(number_format($stats['inactive'])); ?></div>
                        <div class="ml-stat-label">Nonaktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="fw-normal mb-0 text-body">Daftar Menu Website</h4>
        <form method="GET" action="<?php echo e(route('website-menu.index')); ?>" class="d-flex gap-1">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Cari menu..." style="min-width:200px;">
            <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
            <?php if(request('search')): ?>
            <a href="<?php echo e(route('website-menu.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </form>
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#modal-form-add">
            <i class="bi bi-plus-lg"></i> Tambah Menu
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Posisi</th>
                        <th>Item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($menus->firstItem() ?? 0) + $loop->index); ?></td>
                        <td><?php echo e($menu->name); ?></td>
                        <td><code><?php echo e($menu->slug); ?></code></td>
                        <td>
                            <span class="badge bg-<?php echo e(match($menu->location) {
                                'header' => 'primary',
                                'footer' => 'secondary',
                                'sidebar' => 'success',
                                'mobile' => 'info',
                                default => 'dark'
                            }); ?>">
                                <?php echo e(ucfirst($menu->location)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($menu->status): ?>
                            <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                            <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($menu->position); ?></td>
                        <td>
                            <a href="<?php echo e(route('website-menu.items.index', $menu)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-list-ul"></i> Kelola Item
                            </a>
                        </td>
                        <td>

                            <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-<?php echo e($menu->id); ?>" class="btn btn-icon btn-success text-white">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <?php echo $__env->make('pages.website-menu.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <a onclick="showSweetAlert('<?php echo e($menu->id); ?>')" title="Hapus" class="btn btn-icon btn-danger text-white">
                                <i class="bi bi-trash"></i>
                            </a>
                            <form id="deleteForm_<?php echo e($menu->id); ?>" action="<?php echo e(route('website-menu.destroy', $menu)); ?>" method="POST">
                                <?php echo method_field('DELETE'); ?>
                                <?php echo csrf_field(); ?>
                            </form>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($menus->hasPages()): ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
            <div class="text-muted small">
                Menampilkan <strong><?php echo e($menus->firstItem()); ?></strong> -
                <strong><?php echo e($menus->lastItem()); ?></strong> dari
                <strong><?php echo e($menus->total()); ?></strong> data
            </div>
            <div><?php echo e($menus->links('pagination::minimal')); ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('pages.website-menu.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus secara permanen beserta item-nya. Apakah Anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\website-menu\index.blade.php ENDPATH**/ ?>