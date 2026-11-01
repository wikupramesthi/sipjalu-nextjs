

<div class="modal fade" id="modal-form-edit-permission-<?php echo e($permission->id); ?>" tabindex="-1" aria-hidden="true"
  aria-labelledby="modal-form-edit-permission-<?php echo e($permission->id); ?>-label" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-6">
          <h4 class="role-title mb-2">Edit Permission</h4>
          <p>Set role permissions</p>
        </div>
        <!-- Edit role form -->
        <form action="<?php echo e(route('permission.update', $permission->id)); ?>" method="post">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          <div class="col-12 mb-6">
            <label class="form-label" for="name">Permission Name</label>
            <input type="text" id="name" name="name"
              class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              is-invalid
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              placeholder="Enter a permission name" value="<?php echo e($permission->name); ?>" / required>
            <?php $__errorArgs = ['name'];
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
          <div class="col-12 mb-6">
            <label class="form-label" for="guard_name">Guard Name</label>
            <input type="text" id="guard_name" name="guard_name" class="form-control"
              placeholder="Enter a guard name" value="<?php echo e($permission->guard_name); ?>" />
          </div>
          <div class="col-12">
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing mb-0">
                <tbody>
                  <tr>
                    <td colspan="2" class="text-nowrap fw-medium text-heading">
                      <h5>Role Permissions</h5>
                    </td>
                  </tr>
                  
                  <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td class="text-nowrap fw-medium text-heading" colspan="2">
                        <div class="d-flex ">
                          <div class="form-check mb-0 me-4 me-lg-12">
                            <input class="form-check-input role-checkbox-<?php echo e($role->id); ?>" name="roles[]"
                              value="<?php echo e($role->name); ?>" type="checkbox" id="<?php echo e($role->name); ?>-<?php echo e($role->id); ?>"
                              <?php if($permission->hasRole($role->name)): echo 'checked'; endif; ?> />
                            <label class="form-check-label" for="<?php echo e($role->name); ?>-<?php echo e($role->id); ?>">
                              <?php echo e($role->name); ?>

                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <!--/ Edit role form -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\permission\modal-edit.blade.php ENDPATH**/ ?>