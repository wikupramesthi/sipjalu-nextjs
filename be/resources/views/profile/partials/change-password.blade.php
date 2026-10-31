<!-- Modals add menu -->
@php($passwordModalId = 'modal-form-edit-password-' . auth()->user()->getAuthIdentifier())
<div id="{{ $passwordModalId }}" class="modal fade" tabindex="-1"
  aria-labelledby="{{ $passwordModalId }}-label" aria-hidden="true" style="display: none;"
  data-show-on-load="{{ ($showOnLoad ?? false) ? 'true' : 'false' }}">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('password.update') }}" method="post" data-password-change-form
        data-reopen-password-modal="{{ $errors->getBag('updatePassword')->any() ? 'true' : 'false' }}">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="{{ $passwordModalId }}-label">Ubah Password
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info" role="note">
            @if ($showOnLoad ?? false)
              Akun Anda masih menggunakan password bawaan. Demi keamanan, segera ganti dengan password baru.
              Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.
            @else
              Password baru harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.
            @endif
            Setelah password berubah, sesi login di perangkat lain akan dikeluarkan.
          </div>
          <div class="form-group my-2">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password"
              class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
              placeholder="Enter your current password" autocomplete="current-password" required>
            @error('current_password', 'updatePassword')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group my-2">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group password-change-input-group">
              <input type="password" name="password" id="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                placeholder="Enter new password" autocomplete="new-password" minlength="8" required>
              <button type="button" class="btn btn-outline-secondary" data-generate-password
                aria-label="Buat password kuat" title="Buat password kuat">
                <i class="bi bi-shuffle" aria-hidden="true"></i>
                <span>Buat</span>
              </button>
              <button type="button" class="btn btn-outline-secondary" data-toggle-password
                aria-label="Tampilkan password baru" title="Tampilkan password baru">
                <i class="bi bi-eye" aria-hidden="true"></i>
              </button>
            </div>
            @error('password', 'updatePassword')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group my-2">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
              placeholder="Enter confirm password" autocomplete="new-password" minlength="8" required>
          </div>

          <div class="form-group my-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          {{-- <div class="mb-3">
            <label for="roles[]" class="form-label">Role Name</label>
            <select class="form-control choices multiple-remove" id="roles[]" name="roles[]" multiple="multiple">
              @foreach ($roles as $role)
                <option @selected($permission->hasRole($role->name)) value="{{ $role->name }}">{{ $role->name }}</option>
              @endforeach
            </select>
            <x-form.validation.error name="roles" />
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea type="text" class="form-control" id="description" placeholder="Permission description" name="description">{{ $permission->description }}</textarea>
            <x-form.validation.error name="description" />
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary ">Update</button>
        </div> --}}
      </form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@push('after-script')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const passwordForm = document.querySelector('[data-password-change-form]');

      if (passwordForm) {
        const passwordInput = passwordForm.querySelector('#password');
        const confirmationInput = passwordForm.querySelector('#password_confirmation');
        const generateButton = passwordForm.querySelector('[data-generate-password]');
        const toggleButton = passwordForm.querySelector('[data-toggle-password]');

        generateButton?.addEventListener('click', () => {
          const characterSets = [
            'ABCDEFGHJKLMNPQRSTUVWXYZ',
            'abcdefghijkmnopqrstuvwxyz',
            '23456789',
            '!@#$%^&*()-_=+',
          ];
          const allCharacters = characterSets.join('');
          const randomIndex = (maximum) => {
            const limit = Math.floor(0x100000000 / maximum) * maximum;
            const randomValues = new Uint32Array(1);

            do {
              window.crypto.getRandomValues(randomValues);
            } while (randomValues[0] >= limit);

            return randomValues[0] % maximum;
          };
          const generatedCharacters = characterSets.map((characterSet) =>
            characterSet[randomIndex(characterSet.length)]
          );

          while (generatedCharacters.length < 8) {
            generatedCharacters.push(allCharacters[randomIndex(allCharacters.length)]);
          }

          for (let index = generatedCharacters.length - 1; index > 0; index--) {
            const swapIndex = randomIndex(index + 1);
            [generatedCharacters[index], generatedCharacters[swapIndex]] =
              [generatedCharacters[swapIndex], generatedCharacters[index]];
          }

          passwordInput.value = generatedCharacters.join('');
          confirmationInput.value = passwordInput.value;
          passwordInput.type = 'text';
          toggleButton?.setAttribute('aria-label', 'Sembunyikan password baru');
          toggleButton?.setAttribute('title', 'Sembunyikan password baru');
          toggleButton?.querySelector('i')?.classList.replace('bi-eye', 'bi-eye-slash');
        });

        toggleButton?.addEventListener('click', () => {
          const shouldShowPassword = passwordInput.type === 'password';
          passwordInput.type = shouldShowPassword ? 'text' : 'password';
          toggleButton.setAttribute(
            'aria-label',
            shouldShowPassword ? 'Sembunyikan password baru' : 'Tampilkan password baru'
          );
          toggleButton.setAttribute(
            'title',
            shouldShowPassword ? 'Sembunyikan password baru' : 'Tampilkan password baru'
          );
          toggleButton.querySelector('i')?.classList.toggle('bi-eye', !shouldShowPassword);
          toggleButton.querySelector('i')?.classList.toggle('bi-eye-slash', shouldShowPassword);
        });

        passwordForm.addEventListener('submit', (event) => {
          event.preventDefault();

          Swal.fire({
            title: 'Ubah password?',
            text: 'Pastikan password baru sudah benar. Sesi di perangkat lain akan dikeluarkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, ubah password',
            cancelButtonText: 'Batal',
          }).then((result) => {
            if (result.isConfirmed) {
              passwordForm.submit();
            }
          });
        });
      }

      const passwordModal = passwordForm?.closest('.modal');
      if (
        passwordModal &&
        (passwordForm?.dataset.reopenPasswordModal === 'true' ||
          passwordModal.dataset.showOnLoad === 'true') &&
        window.bootstrap
      ) {
        bootstrap.Modal.getOrCreateInstance(passwordModal).show();
      }
    });
  </script>
@endpush
