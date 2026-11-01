<?php $__env->startSection('title', 'Item Menu: ' . $websiteMenu->name); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Item Menu','page' => 'Pengaturan','active' => 'Item Menu: '.e($websiteMenu->name).'','route' => ''.e(route('website-menu.items.index', $websiteMenu)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-normal mb-0 text-body"><?php echo e($websiteMenu->name); ?></h4>
        <small class="text-muted">Lokasi: <?php echo e(ucfirst($websiteMenu->location)); ?> | Slug: <code><?php echo e($websiteMenu->slug); ?></code></small>
    </div>

    <form method="GET" action="<?php echo e(route('website-menu.items.index', $websiteMenu)); ?>" class="d-flex gap-1">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Cari item..." style="min-width:200px;">
        <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
        <?php if(request('search')): ?>
        <a href="<?php echo e(route('website-menu.items.index', $websiteMenu)); ?>" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
        <?php endif; ?>
    </form>

    <a href="<?php echo e(route('website-menu.items.create', $websiteMenu)); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Item
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Link</th>
                        <th>Icon</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Posisi</th>
                        <th>Parent</th>
                        <th>Sub-item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($items->firstItem() ?? 0) + $loop->index); ?></td>
                        <td>
                            <strong><?php echo e($item->name); ?></strong>
                            <?php if($item->description): ?>
                            <br><small class="text-muted"><?php echo e($item->description); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->route): ?>
                            <code class="text-primary"><?php echo e($item->route); ?></code>
                            <?php else: ?>
                            <code class="text-secondary"><?php echo e($item->url ?: '-'); ?></code>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->icon): ?>
                            <i class="bx <?php echo e($item->icon); ?> fs-4"></i>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->target_blank): ?>
                            <span class="badge bg-info">_blank</span>
                            <?php else: ?>
                            <span class="badge bg-light text-dark">_self</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->status): ?>
                            <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                            <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($item->position); ?></td>
                        <td>
                            <span class="text-muted">-</span>
                        </td>
                        <td>
                            <?php if($item->allChildren->count()): ?>
                            <span class="badge bg-primary"><?php echo e($item->allChildren->count()); ?> item</span>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('website-menu.items.edit', [$websiteMenu, $item])); ?>" class="btn btn-icon btn-success text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="showSweetAlert('<?php echo e($item->id); ?>')" title="Hapus" class="btn btn-icon btn-danger text-white">
                                <i class="bi bi-trash"></i>
                            </a>
                            <form id="deleteForm_<?php echo e($item->id); ?>" action="<?php echo e(route('website-menu.items.destroy', [$websiteMenu, $item])); ?>" method="POST">
                                <?php echo method_field('DELETE'); ?>
                                <?php echo csrf_field(); ?>
                            </form>
                        </td>
                    </tr>
                    <?php if($item->allChildren->count()): ?>
                        <?php $__currentLoopData = $item->allChildren; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="table-light">
                            <td class="text-end"><small class="text-muted">↳</small></td>
                            <td>
                                <span class="ms-2">↳ <?php echo e($child->name); ?></span>
                                <?php if($child->description): ?>
                                <br><small class="text-muted ms-2"><?php echo e($child->description); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($child->route): ?>
                                <code class="text-primary"><?php echo e($child->route); ?></code>
                                <?php else: ?>
                                <code class="text-secondary"><?php echo e($child->url ?: '-'); ?></code>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($child->icon): ?>
                                <i class="bx <?php echo e($child->icon); ?> fs-4"></i>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($child->target_blank): ?>
                                <span class="badge bg-info">_blank</span>
                                <?php else: ?>
                                <span class="badge bg-light text-dark">_self</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($child->status): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($child->position); ?></td>
                            <td><span class="badge bg-secondary"><?php echo e($item->name); ?></span></td>
                            <td><span class="text-muted">-</span></td>
                            <td>
                                <a href="<?php echo e(route('website-menu.items.edit', [$websiteMenu, $child])); ?>" class="btn btn-icon btn-success text-white btn-sm" title="Edit subitem">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a onclick="showSweetAlert('<?php echo e($child->id); ?>')" title="Hapus subitem" class="btn btn-icon btn-danger text-white btn-sm">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <form id="deleteForm_<?php echo e($child->id); ?>" action="<?php echo e(route('website-menu.items.destroy', [$websiteMenu, $child])); ?>" method="POST">
                                    <?php echo method_field('DELETE'); ?>
                                    <?php echo csrf_field(); ?>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($items->hasPages()): ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
            <div class="text-muted small">
                Menampilkan <strong><?php echo e($items->firstItem()); ?></strong> -
                <strong><?php echo e($items->lastItem()); ?></strong> dari
                <strong><?php echo e($items->total()); ?></strong> data
            </div>
            <div><?php echo e($items->links('pagination::minimal')); ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus secara permanen beserta sub-item-nya. Apakah Anda yakin?',
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\website-menu\items\index.blade.php ENDPATH**/ ?>