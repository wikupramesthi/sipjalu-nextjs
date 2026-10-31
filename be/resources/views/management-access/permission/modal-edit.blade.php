{{-- <script>
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
</script> --}}

<div class="modal fade" id="modal-form-edit-permission-{{ $permission->id }}" tabindex="-1" aria-hidden="true"
  aria-labelledby="modal-form-edit-permission-{{ $permission->id }}-label" style="display: none;">
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
        <form action="{{ route('permission.update', $permission->id) }}" method="post">
          @csrf
          @method('PUT')
          <div class="col-12 mb-6">
            <label class="form-label" for="name">Permission Name</label>
            <input type="text" id="name" name="name"
              class="form-control @error('name')
              is-invalid
            @enderror"
              placeholder="Enter a permission name" value="{{ $permission->name }}" / required>
            @error('name')
              <a style="color: red">
                <small>
                  {{ $message }}
                </small>
              </a>
            @enderror
          </div>
          <div class="col-12 mb-6">
            <label class="form-label" for="guard_name">Guard Name</label>
            <input type="text" id="guard_name" name="guard_name" class="form-control"
              placeholder="Enter a guard name" value="{{ $permission->guard_name }}" />
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
                  {{-- <tr>
                    <td class="text-nowrap fw-medium text-heading">Administrator Access <i class="bx bx-info-circle"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Allows full access to the system"></i>
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="selectAll-{{ $permission->id }}" />
                          <label class="form-check-label" for="selectAll-{{ $permission->id }}">
                            Select All
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr> --}}
                  @foreach ($roles as $role)
                    <tr>
                      <td class="text-nowrap fw-medium text-heading" colspan="2">
                        <div class="d-flex ">
                          <div class="form-check mb-0 me-4 me-lg-12">
                            <input class="form-check-input role-checkbox-{{ $role->id }}" name="roles[]"
                              value="{{ $role->name }}" type="checkbox" id="{{ $role->name }}-{{ $role->id }}"
                              @checked($permission->hasRole($role->name)) />
                            <label class="form-check-label" for="{{ $role->name }}-{{ $role->id }}">
                              {{ $role->name }}
                            </label>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforeach
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
