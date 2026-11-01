<?php $__env->startSection('title', 'Permissions'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Module Role','page' => 'Role & Akses','active' => 'Module Role','route' => ''.e(route('permission.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-normal mb-0 text-body card-title">permissions list</h4>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission.store')): ?>
        <button type="button" class="btn btn-primary btn-md " data-bs-toggle="modal"
          data-bs-target="#modal-form-add-permission">
          Tambah Data
        </button>
        <?php endif; ?>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap mx-2">
        <table class="table" id="table1">
          <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Guard Name</th>
              <th>Assigned To</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($loop->iteration); ?></td>
              <td><?php echo e($permission->name); ?></td>
              <td><?php echo e($permission->guard_name); ?></td>
              <td>
                <?php $__currentLoopData = $permission->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge bg-light-info me-1"><?php echo e($role->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </td>
              <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission.update')): ?>
                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-permission-<?php echo e($permission->id); ?>"
                  class="btn btn-icon btn-success text-white">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <?php echo $__env->make('management-access.permission.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
              </td>

              <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission.destroy')): ?>
                <a onclick="showSweetAlert('<?php echo e($permission->id); ?>')" title="Delete"
                  class="btn btn-icon btn-danger text-white">
                  <i class="bi bi-x-square"></i>
                </a>
                <form id="deleteForm_<?php echo e($permission->id); ?>"
                  action="<?php echo e(route('permission.destroy', $permission->id)); ?>" method="POST">
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

<!-- Add Role Modal -->
<?php echo $__env->make('management-access.permission.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--/ Add Role Modal -->

</div>
<!-- / Content -->

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\permission\index.blade.php ENDPATH**/ ?>