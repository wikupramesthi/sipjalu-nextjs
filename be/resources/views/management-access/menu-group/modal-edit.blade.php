<!-- Modals add menu -->
<div id="modal-form-edit-menu-{{ $menuGroup->id }}" class="modal fade" tabindex="-1"
  aria-labelledby="modal-form-edit-menu-{{ $menuGroup->id }}-label" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('menu.update', $menuGroup->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="modal-form-edit-menu-{{ $menuGroup->id }}-label">Edit Menu Group
            ({{ $menuGroup->name }})</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
              placeholder="Menu Name" name="name" value="{{ $menuGroup->name }}" required>
            @error('name')
              <a style="color: red">
                <small>{{ $message }}</small>
              </a>
            @enderror
            {{-- <x-form.validation.error name="name" /> --}}
          </div>

          <div class="mb-3">
            <label for="icon" class="form-label">Icon</label>
            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon"
              placeholder="Boxicons (eg: bx-home-smile)" value="{{ $menuGroup->icon }}" name="icon" required>
            @error('icon')
              <a style="color: red">
                <small>{{ $message }}</small>
              </a>
            @enderror
            {{-- <x-form.validation.error name="icon" /> --}}
          </div>

          <div class="mb-3">
            <label for="permission_name" class="form-label">Permission Name</label>
            <select class="form-control choices @error('permission_name') is-invalid @enderror" id="permission_name"
              name="permission_name" required>
              @foreach ($permissions as $permission)
                <option @selected($menuGroup->permission_name == $permission->name) value="{{ $permission->name }}">{{ $permission->name }}</option>
              @endforeach
            </select>
            @error('permission_name')
              <a style="color: red">
                <small>{{ $message }}</small>
              </a>
            @enderror
            {{-- <x-form.validation.error name="permission_name" /> --}}
          </div>

          <div class="mb-3">
            <div class="form-check form-switch form-switch-right form-switch-md">
              <label for="status" class="form-label">Status</label>
              <input class="form-check-input code-switcher" type="checkbox" id="tables-small-showcode" name="status"
                value="1" @checked($menuGroup->status)>
            </div>
            {{-- <x-form.validation.error name="status" /> --}}
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary ">Update</button>
        </div>
      </form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
