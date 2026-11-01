<!-- Modals add menu -->
<div id="modal-form-edit-route-<?php echo e($route->id); ?>" class="modal fade" tabindex="-1"
  aria-labelledby="modal-form-edit-route-<?php echo e($route->id); ?>-label" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?php echo e(route('route.update', $route->id)); ?>" method="post">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="modal-header">
          <h5 class="modal-title" id="modal-form-edit-route-<?php echo e($route->id); ?>-label">Edit Route (<?php echo e($route->route); ?>)
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label for="route <?php $__errorArgs = ['route'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              is-invalid
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" class="form-label">Route
              Name</label>
            <select class="form-control choices" id="route" name="route" required>
              <option value="" disabled selected>Choose</option>
              <?php $__currentLoopData = $facadesRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facadesRoute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if(!blank($facadesRoute->getName())): ?>
              <option <?php if($route->route == $facadesRoute->getName()): echo 'selected'; endif; ?> value="<?php echo e($facadesRoute->getName()); ?>">
                <?php echo e($facadesRoute->getName()); ?>

              </option>
              <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['route'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <a style="color: red">
              <small>
                <?php echo e($message); ?>

              </small>
            </a>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            
          </div>

          <div class="mb-3">
            <label for="permission_name" class="form-label">Permission Name</label>
            <select
              class="form-control choices <?php $__errorArgs = ['permission_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              is-invalid
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="permission_name" name="permission_name" required>
              <option value="" disabled selected>Choose</option>
              <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option <?php if($permission->name == $route->permission_name): echo 'selected'; endif; ?> value="<?php echo e($permission->name); ?>"><?php echo e($permission->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['permission_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <a style="color: red">
              <small>
                <?php echo e($message); ?>

              </small>
            </a>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea type="text" class="form-control" id="description" placeholder="Role description" name="description"><?php echo e($route->description); ?></textarea>
            
          </div>

          <div class="mb-3">
            <div class="form-check form-switch form-switch-right form-switch-md">
              <label for="status" class="form-label">Status</label>
              <input class="form-check-input code-switcher" type="checkbox" id="tables-small-showcode" name="status"
                value="1" <?php if($route->status): echo 'checked'; endif; ?>>
            </div>
            
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary ">Save Changes</button>
        </div>
      </form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal --><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\route\modal-edit.blade.php ENDPATH**/ ?>