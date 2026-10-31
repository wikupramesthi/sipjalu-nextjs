<!-- Modals add menu -->
<div id="modal-form-edit-user-{{ $user->uuid }}" class="modal fade modal-form-user-edit" tabindex="-1"
  aria-labelledby="modal-form-edit-user-{{ $user->uuid }}-label" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('user.update', $user->uuid) }}" method="post">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="modal-form-edit-user-{{ $user->uuid }}-label">Edit Pengguna ({{ $user->name }})
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
              placeholder="User Name" name="name" value="{{ $user->name }}">
            @error('name')
            <div class='invalid-feedback'>
              {{ $message }}
            </div>
            </a>
            @enderror
            {{-- <x-form.validation.error name="name" /> --}}
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control  @error('email') is-invalid @enderror" id="email"
              placeholder="Email" name="email" value="{{ $user->email }}">
            @error('email')
            <div class='invalid-feedback'>
              {{ $message }}
            </div>
            </a>
            @enderror
            {{-- <x-form.validation.error name="email" /> --}}
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role Name</label>
            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" data-choices
              data-choices-removeItem>
              <option value="" disabled selected>Choose</option>
              @foreach ($roles as $role)
              <option @selected($user->hasRole($role->name)) value="{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
            @error('role')
            <a style="color: red">
              <small>
                {{ $message }}
              </small>
            </a>
            @enderror
            {{-- <x-form.validation.error name="role" /> --}}
          </div>

          <div class="mb-3">
            <div class="form-check form-switch form-switch-right form-switch-md">
              <label for="verified" class="form-label">Verified</label>
              <input class="form-check-input code-switcher" type="checkbox" id="tables-small-showcode" name="verified"
                value="1" @checked(!blank($user->email_verified_at))>
            </div>
            {{-- <x-form.validation.error name="verified" /> --}}
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary ">Update</button>
        </div>
      </form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->