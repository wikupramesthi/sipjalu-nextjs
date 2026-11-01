<?php $__env->startSection('title', 'Roles'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Master Role','page' => 'Role & Akses','active' => 'Master Role','route' => ''.e(route('role.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<div class="container-p-y">

  <p class="mb-6">A role provided access to predefined menus and features so that depending on assigned role an
    administrator can have access to what user needs.</p>
  <!-- Role cards -->
  <div class="row g-4">

    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-normal mb-0 text-body">Total <?php echo e($role->users->count()); ?> users</h6>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role.destroy')): ?>
            <a onclick="showSweetAlert('<?php echo e($role->id); ?>')" class="justify-content-end" title="delete role">
              <i class="bi bi-x"></i>
            </a>
            <?php endif; ?>
          </div>
          <form id="deleteForm_<?php echo e($role->id); ?>" action="<?php echo e(route('role.destroy', $role->id)); ?>" method="POST">
            <?php echo method_field('DELETE'); ?>
            <?php echo csrf_field(); ?>
          </form>

          <div class="d-flex justify-content-between align-items-end">
            <div class="role-heading">
              <h5 class="mb-1"><?php echo e($role->name); ?></h5>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role.update')): ?>
              <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#editRoleModal-<?php echo e($role->id); ?>"
                class="role-edit-modal"><span>Edit Role</span></a>
              <?php echo $__env->make('management-access.role.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
              <?php endif; ?>
            </div>
            <a href="javascript:void(0);"><i class="bx bx-copy bx-md text-muted"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role.store')): ?>
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-sm-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4 ps-6">
              <img src="<?php echo e(asset('img/man-with-laptop-light.png')); ?>" class="img-fluid" alt="img" width="120">
            </div>
          </div>
          <div class="col-sm-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">Add New Role</button>
              <p class="mb-0"> Add new role, <br> if it doesn't exist.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="col-12">
      <section class="section">
        <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-normal mb-0 text-body card-title">User</h4>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap mx-2">
            <table class="table" id="table1">
              <thead>
                <tr>
                  <th>#</th>
                  <th>User</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($loop->iteration); ?></td>
                  <td><?php echo e($user->name); ?></td>
                  <td><?php echo e($user->email); ?></td>
                  <td>
                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-light-info me-1"><?php echo e($role->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </td>
                  <td>
                    <?php if(!blank($user->email_verified_at)): ?>
                    <span class="badge bg-light-primary me-1">Active</span>
                    <?php else: ?>
                    <span class="badge bg-light-danger me-1">Inactive</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="demo-inline-spacing">
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.update')): ?>
                      <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-user-<?php echo e($user->uuid); ?>"
                        class="btn btn-icon btn-success text-white">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <?php echo $__env->make('management-access.user.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                      <?php endif; ?>

                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.destroy')): ?>
                      <a onclick="showSweetAlert('<?php echo e($user->uuid); ?>')" title="Delete"
                        class="btn btn-icon btn-danger text-white">
                        <i class="bi bi-x-square"></i>
                      </a>

                      <form id="deleteForm_<?php echo e($user->uuid); ?>" action="<?php echo e(route('user.destroy', $user->uuid)); ?>"
                        method="POST">
                        <?php echo method_field('DELETE'); ?>
                        <?php echo csrf_field(); ?>
                      </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
      </section>
    </div>

  </div>
  <!--/ Role cards -->

  <!-- Add Role Modal -->
  <?php echo $__env->make('management-access.role.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\role\index.blade.php ENDPATH**/ ?>