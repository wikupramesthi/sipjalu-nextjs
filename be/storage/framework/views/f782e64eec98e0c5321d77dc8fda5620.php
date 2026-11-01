<?php $__env->startSection('title', 'Menu Group'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Menu Manager','page' => 'Pengaturan','active' => 'Menu Manager','route' => ''.e(route('menu.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
      <div class="d-flex justify-content-between align-items-center ">
        <h4 class="fw-normal mb-0 text-body">Menu Group</h4>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-group.store')): ?>
        <button type="button" class="btn btn-primary btn-md " data-bs-toggle="modal"
          data-bs-target="#modal-form-add-menu">
          <i class="bi bi-plus-lg"></i>
          Tambah Menu
        </button>
        <?php endif; ?>

      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap mx-2">
        <table class="table" id="table1">
          <thead>
            <tr>
              <th>Name</th>
              <th>Permission</th>
              <th>Icon</th>
              <th>Status</th>
              <th>Menu Item</th>
              <th>Edit</th>
              <th>Hapus</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            <?php $__currentLoopData = $menuGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($menuGroup->name); ?></td>
              <td><?php echo e($menuGroup->permission_name); ?></td>
              <td><i class='bx <?php echo e($menuGroup->icon); ?>'></i></td>
              <td>
                <?php if($menuGroup->status): ?>
                <span class="badge bg-light-primary me-1">Show</span>
                <?php else: ?>
                <span class="badge bg-light-danger me-1">Hide</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-item.index')): ?>
                <a href="<?php echo e(route('menu.item.index', $menuGroup)); ?>" class="btn btn-icon btn-primary"
                  title="add menu item">
                  <i class="bi bi-plus-square"></i>
                </a>
                <?php endif; ?>
              </td>

              <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-group.update')): ?>
                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-menu-<?php echo e($menuGroup->id); ?>"
                  class="btn btn-icon btn-success text-white">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <?php echo $__env->make('management-access.menu-group.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
              </td>

              <td>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-group.destroy')): ?>
                <a onclick="showSweetAlert('<?php echo e($menuGroup->id); ?>')" title="Delete"
                  class="btn btn-icon btn-danger text-white">
                  <i class="bi bi-x-square"></i>
                </a>
                <form id="deleteForm_<?php echo e($menuGroup->id); ?>"
                  action="<?php echo e(route('menu.destroy', encrypt($menuGroup->id))); ?>" method="POST">
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
<?php echo $__env->make('management-access.menu-group.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\menu-group\index.blade.php ENDPATH**/ ?>