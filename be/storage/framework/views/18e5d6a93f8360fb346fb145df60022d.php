<?php $__env->startSection('title', 'Daftar Pengguna'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Daftar Pengguna','page' => 'Pengaturan','active' => 'Daftar Pengguna','route' => ''.e(route('user.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<section class="section mt-2">
    <div class="card">
        <div class="card-header">

            <div class="col-12 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3">
                <div class="d-flex justify-content-between align-items-center ">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 w-100">

                        <form action="<?php echo e(route('user.index')); ?>" method="GET"
                            class="row g-2 align-items-center flex-grow-1">

                            
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Dari</span>
                                    <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                                        class="form-control">
                                </div>
                            </div>

                            
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Sampai</span>
                                    <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>"
                                        class="form-control">
                                </div>
                            </div>

                            
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <select name="role" class="form-select" style="min-width:160px;">
                                        <option value="">Semua Role</option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->name); ?>" <?php if(request('role') === $role->name): echo 'selected'; endif; ?>><?php echo e($role->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <option value="__none" <?php if(request('role') === '__none'): echo 'selected'; endif; ?>>Tanpa Role</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-sm btn-success" type="submit">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <a href="<?php echo e(route('user.index')); ?>" class="btn btn-sm btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.store')): ?>
                        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                            data-bs-target="#modal-form-add-user">
                            <i class="bi bi-plus-lg"></i>
                            Tambah Baru
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Daftar</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Hapus</th>

                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($user->email_verified_at ? $user->email_verified_at->format('d-m-Y H:i:s') : ($user->created_at ? $user->created_at->format('d-m-Y H:i:s') : '-')); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span class="badge bg-light-info me-1"><?php echo e($role->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <span class="badge bg-light-secondary">Tanpa Role</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!blank($user->email_verified_at)): ?>
                                        <span class="badge bg-light-primary me-1">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-light-danger me-1">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.update')): ?>
                                        <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-user-<?php echo e($user->uuid); ?>"
                                            class="btn btn-icon btn-success text-white">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <?php echo $__env->make('management-access.user.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.destroy')): ?>
                                        <a onclick="showSweetAlert('<?php echo e($user->uuid); ?>')" title="Delete"
                                            class="btn btn-icon btn-danger text-white">
                                            <i class="bi bi-x-square"></i> Hapus
                                        </a>
                                        <form id="deleteForm_<?php echo e($user->uuid); ?>"
                                            action="<?php echo e(route('user.destroy', $user->uuid)); ?>" method="POST">
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
<!-- / Content -->

<!--/ Basic Bootstrap Table -->
<?php echo $__env->make('management-access.user.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks "Yes, delete it!", submit the corresponding form
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\user\index.blade.php ENDPATH**/ ?>