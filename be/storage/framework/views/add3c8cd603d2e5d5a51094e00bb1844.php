<?php $__env->startSection('title', 'Static Pages'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Master Data','page' => 'Master Data','active' => 'Static Pages','route' => ''.e(route('pages.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<!-- Content -->
<section class="section">
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible mb-3 mt-3 fade show" role="alert">
        <span class="alert-text text-white"> <?php echo e(session('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show position-relative" role="alert">
        <div class="d-flex">
            <i class="bi-exclamation-triangle-fill text-white fs-1 me-3 flex-shrink-0 align-self-start"></i>
            <div class="text-white mt-0">
                <strong>Attention!</strong> Changing the <em>sidebar</em> status will affect the appearance and layout of the page.
                <br>
                Please make sure to review the changes before saving.
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="fw-normal mb-0 text-body">All Pages</h4>
                <form method="GET" action="<?php echo e(route('pages.index')); ?>" class="d-flex gap-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Cari halaman..." style="min-width:200px;">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    <?php if(request('search')): ?>
                    <a href="<?php echo e(route('pages.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pages.store')): ?>
                <a href="<?php echo e(route('pages.create')); ?>" class="btn btn-primary btn-md"><i class="bi bi-plus-lg"></i>
                    Add New Page</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Sidebar</th>
                            <th>Status</th>
                            <th>Publish Date</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td>
                                <img src="/storage/<?php echo e($item->featured_image); ?>" class="img-fluid"
                                    style="max-height:80px" alt="<?php echo e($item->title); ?>">
                            </td>
                            <td><?php echo e($item->title); ?></td>
                            <td><?php echo e($item->slug); ?></td>
                            <td>
                                <?php
                                $statusClass = $item->has_sidebar ? 'btn-info' : 'btn-danger';
                                $statusText = $item->has_sidebar ? 'Ya' : 'Tidak';
                                ?>

                                <button type="button" class="btn <?php echo e($statusClass); ?> btn-sm text-white"
                                    data-bs-toggle="modal" data-bs-target="#modalUpdateSidebar-<?php echo e($item->uuid); ?>">
                                    <?php echo e($statusText); ?>

                                </button>

                                <?php echo $__env->make('pages.halaman.modal-update-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                            <td>
                                <?php echo e($item->is_published ? 'Aktif' : 'Tidak Aktif'); ?>

                            </td>
                            <td> <?php echo e($item->created_at->format('d-m-Y')); ?></td>

                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pages.update')): ?>
                                <a href="<?php echo e(route('pages.edit', $item->uuid)); ?>"
                                    class="btn btn-icon btn-success text-white"><i class="bi bi-pencil-square"></i>
                                    Edit</a>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pages.destroy')): ?>
                                <a onclick="showSweetAlert('<?php echo e($item->uuid); ?>')" title="Delete"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"></i> Hapus
                                </a>
                                <form id="deleteForm_<?php echo e($item->uuid); ?>"
                                    action="<?php echo e(route('pages.destroy', $item->uuid)); ?>" method="POST">
                                    <?php echo method_field('DELETE'); ?>
                                    <?php echo csrf_field(); ?>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php if($pages->hasPages()): ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">Menampilkan <?php echo e($pages->firstItem()); ?>-<?php echo e($pages->lastItem()); ?> dari <?php echo e($pages->total()); ?> halaman</small>
    <div><?php echo e($pages->links('pagination::minimal')); ?></div>
</div>
<?php endif; ?>
<!-- / Content -->

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
                // If the user clicks "Yes, delete it!", submit the corresponding form
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/pages/halaman/index.blade.php ENDPATH**/ ?>