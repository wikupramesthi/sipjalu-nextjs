<div class="modal fade" id="editRoleModal-<?php echo e($role->id); ?>" tabindex="-1" aria-hidden="true"
  aria-labelledby="editRoleModal-<?php echo e($role->id); ?>-label" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-6">
          <h4 class="role-title mb-2">Edit Role</h4>
          <p>Set role permissions</p>
        </div>
        <!-- Edit role form -->
        <form action="<?php echo e(route('role.update', $role->id)); ?>" method="post">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>
          <div class="col-12 mb-6">
            <label class="form-label" for="name">Role Name</label>
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
              placeholder="Enter a role name" value="<?php echo e($role->name); ?>" required />
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
            <input type="text" id="guard_name" name="guard_name" class="form-control" placeholder="Enter a role name"
              value="<?php echo e($role->guard_name); ?>" />
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
                  <tr>
                    <td class="text-nowrap fw-medium text-heading">Administrator Access <i class="bx bx-info-circle"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Allows full access to the system"></i>
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="selectAll-<?php echo e($role->id); ?>" />
                          <label class="form-check-label" for="selectAll-<?php echo e($role->id); ?>">
                            Select All
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>
                  

                  <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td class="text-nowrap fw-medium text-heading"><?php echo e(ucfirst($key)); ?></td>
                      <td>
                        <div class="d-flex justify-content-end">
                          <?php $__empty_2 = true; $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <div class="form-check mb-0 me-4 me-lg-12">
                              <input class="form-check-input permission-checkbox-<?php echo e($role->id); ?>" type="checkbox"
                                name="permissions[]" value="<?php echo e($permission->name); ?>"
                                id="<?php echo e($permission->name); ?>-<?php echo e($role->id); ?>" <?php if($role->hasPermissionTo($permission->name)): echo 'checked'; endif; ?> />
                              <label class="form-check-label" for="<?php echo e($permission->name); ?>-<?php echo e($role->id); ?>">
                                <?php echo e(Str::after($permission->name, Str::before($permission->name, '.') . '.')); ?>


                              </label>
                            </div>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <p>
                              No permission in this group!.
                            </p>
                          <?php endif; ?>
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');

    modals.forEach(modal => {
      const roleId = modal.id.split('-').pop(); // Get the role ID from the modal ID

      modal.addEventListener('shown.bs.modal', function() {
        const selectAllCheckbox = document.getElementById(`selectAll-${roleId}`);
        const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox-${roleId}`);

        // Function to update the "Select All" checkbox state
        const updateSelectAllState = () => {
          const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
          selectAllCheckbox.checked = allChecked;
        };

        // Check the initial state when the modal is shown
        updateSelectAllState();

        // Event listener for the "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
          permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
          });
        });

        // Event listeners for each permission checkbox
        permissionCheckboxes.forEach(checkbox => {
          checkbox.addEventListener('change', function() {
            updateSelectAllState();
          });
        });
      });
    });
  });
</script>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\management-access\role\modal-edit.blade.php ENDPATH**/ ?>