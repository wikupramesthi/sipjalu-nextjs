<!-- Modals add menu -->
<?php ($passwordModalId = 'modal-form-edit-password-' . auth()->user()->getAuthIdentifier()); ?>
<div id="<?php echo e($passwordModalId); ?>" class="modal fade" tabindex="-1"
  aria-labelledby="<?php echo e($passwordModalId); ?>-label" aria-hidden="true" style="display: none;"
  data-show-on-load="<?php echo e(($showOnLoad ?? false) ? 'true' : 'false'); ?>">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?php echo e(route('password.update')); ?>" method="post" data-password-change-form
        data-reopen-password-modal="<?php echo e($errors->getBag('updatePassword')->any() ? 'true' : 'false'); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="modal-header">
          <h5 class="modal-title" id="<?php echo e($passwordModalId); ?>-label">Ubah Password
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info" role="note">
            <?php if($showOnLoad ?? false): ?>
              Akun Anda masih menggunakan password bawaan. Demi keamanan, segera ganti dengan password baru.
              Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.
            <?php else: ?>
              Password baru harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.
            <?php endif; ?>
            Setelah password berubah, sesi login di perangkat lain akan dikeluarkan.
          </div>
          <div class="form-group my-2">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password"
              class="form-control <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              placeholder="Enter your current password" autocomplete="current-password" required>
            <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="form-group my-2">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group password-change-input-group">
              <input type="password" name="password" id="password"
                class="form-control <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
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
            <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="form-group my-2">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
              placeholder="Enter confirm password" autocomplete="new-password" minlength="8" required>
          </div>

          <div class="form-group my-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          
      </form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php $__env->startPush('after-script'); ?>
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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/profile/partials/change-password.blade.php ENDPATH**/ ?>