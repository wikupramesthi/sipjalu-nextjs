<?php $__env->startSection('title', 'Profil'); ?>
<?php $__env->startSection('breadcrumb'); ?>

<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Profil','page' => 'Pengaturan Akun','active' => 'Profil','route' => ''.e(route('profile.edit')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>

<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    <span class="text-white">
        <?php echo e(session('success')); ?>

    </span>
    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Tutup">
    </button>
</div>

<?php endif; ?>



<?php if($errors->updatePassword->any()): ?>

<?php $__currentLoopData = $errors->updatePassword->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="alert alert-danger alert-dismissible fade show mb-4"
    role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <span class="text-white">
        <?php echo e($error); ?>

    </span>
    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Tutup">
    </button>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>



<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">

            
            <div class="col-auto">
                <div class="position-relative">
                    <img src="<?php echo e(Auth::user()->avatar
                            ? (Str::startsWith(Auth::user()->avatar, 'http')
                                ? Auth::user()->avatar
                                : asset('storage/' . Auth::user()->avatar))
                            : asset('dist/assets/images/avatar.jpg')); ?>"
                        alt="<?php echo e(auth()->user()->name); ?>"
                        class="rounded-circle border border-4 border-white shadow"
                        style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            </div>


            
            <div class="col">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h3 class="fw-semibold mb-0">
                        <?php echo e(auth()->user()->name); ?>

                    </h3>

                    <?php if(auth()->user()->getRoleNames()->isNotEmpty()): ?>
                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                        <?php echo e(ucfirst(auth()->user()->getRoleNames()[0])); ?>

                    </span>
                    <?php endif; ?>
                </div>

                <div class="text-muted mb-3">
                    <i class="bi bi-envelope me-1"></i>
                    <?php echo e(auth()->user()->email); ?>

                </div>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Akun Aktif
                    </span>

                    <span class="badge rounded-pill bg-light text-muted border px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        Bergabung <?php echo e(auth()->user()->created_at->format('d M Y')); ?>

                    </span>
                </div>
            </div>


            
            <div class="col-auto">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button"
                        class="btn btn-light border px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-form-edit-password-<?php echo e(auth()->user()->getAuthIdentifier()); ?>">
                        <i class="bi bi-lock me-1"></i>
                        Ubah Kata Sandi
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>



<form action="<?php echo e(route('account.update', $user->uuid)); ?>"
    method="POST"
    enctype="multipart/form-data">

    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="row g-4">


        
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="fw-semibold mb-1">
                                Foto Profil
                            </h5>

                            <small class="text-muted">
                                Foto profil akun Anda
                            </small>
                        </div>

                        <div class="bg-primary-subtle rounded-3 p-2">
                            <i class="bi bi-camera text-primary fs-5"></i>
                        </div>
                    </div>


                    
                    <div class="text-center mb-4">
                        <img src="<?php echo e(Auth::user()->avatar
                                ? (Str::startsWith(Auth::user()->avatar, 'http')
                                    ? Auth::user()->avatar
                                    : asset('storage/' . Auth::user()->avatar))
                                : asset('dist/assets/images/avatar.jpg')); ?>"
                            alt="<?php echo e(auth()->user()->name); ?>"
                            class="rounded-circle border border-4 border-light shadow-sm"
                            style="width: 170px; height: 170px; object-fit: cover;">
                    </div>


                    <div class="bg-primary-subtle rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-image text-primary fs-4"></i>

                            <div>
                                <div class="fw-semibold">
                                    Unggah Foto Baru
                                </div>

                                <small class="text-muted">
                                    JPG atau PNG, maksimal 1 MB
                                </small>
                            </div>
                        </div>
                    </div>


                    <input
                        class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="avatar"
                        type="file"
                        name="avatar"
                        accept="image/png,image/jpeg">

                    <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>
            </div>
        </div>


        
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="fw-semibold mb-1">
                                Informasi Pribadi
                            </h5>

                            <small class="text-muted">
                                Pastikan informasi pribadi Anda selalu benar dan terbaru.
                            </small>
                        </div>

                        <div class="bg-success-subtle rounded-3 p-2">
                            <i class="bi bi-person-vcard text-success fs-5"></i>
                        </div>
                    </div>


                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-medium">
                            Nama Lengkap
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="name"
                            type="text"
                            name="name"
                            value="<?php echo e(old('name', $user->name)); ?>"
                            placeholder="Masukkan nama lengkap Anda"
                            required>

                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium">
                                Alamat Email
                            </label>

                            <input
                                class="form-control bg-light"
                                id="email"
                                type="email"
                                value="<?php echo e($user->email); ?>"
                                disabled>

                            <small class="text-muted">
                                Alamat email tidak dapat diubah.
                            </small>
                        </div>


                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-medium">
                                Nomor WhatsApp
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                class="form-control <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="no_hp"
                                type="text"
                                name="no_hp"
                                value="<?php echo e(old('no_hp', $user->no_hp)); ?>"
                                placeholder="Contoh: 08123456789"
                                required>

                            <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>


                    
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label for="tempat_lahir" class="form-label fw-medium">
                                Tempat Lahir
                            </label>

                            <input
                                class="form-control <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="tempat_lahir"
                                type="text"
                                name="tempat_lahir"
                                value="<?php echo e(old('tempat_lahir', $user->tempat_lahir)); ?>"
                                placeholder="Masukkan tempat lahir Anda">

                            <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label fw-medium">
                                Tanggal Lahir
                            </label>

                            <input
                                class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="tanggal_lahir"
                                type="date"
                                name="tanggal_lahir"
                                value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir)); ?>">

                            <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>


                    
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label fw-medium">
                                Jenis Kelamin
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                                <option value="">
                                    Pilih Jenis Kelamin
                                </option>

                                <option value="L"
                                    <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : ''); ?>>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : ''); ?>>
                                    Perempuan
                                </option>

                            </select>

                            <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                        <div class="col-md-6">
                            <label for="agama" class="form-label fw-medium">
                                Agama
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="agama"
                                id="agama"
                                class="form-select <?php $__errorArgs = ['agama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                                <option value="">
                                    Pilih Agama
                                </option>

                                <?php $__currentLoopData = [
                                'Islam',
                                'Kristen',
                                'Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu',
                                'Lainnya'
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($agama); ?>"
                                    <?php echo e(old('agama', $user->agama) == $agama ? 'selected' : ''); ?>>
                                    <?php echo e($agama); ?>

                                </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>

                            <?php $__errorArgs = ['agama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="kecamatan" class="small mb-1">Kecamatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="kecamatan" name="kecamatan_id" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                <?php $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kecamatan->id); ?>"
                                    <?php echo e(old('kecamatan_id', $user->kecamatan_id) == $kecamatan->id ? 'selected' : ''); ?>>
                                    <?php echo e($kecamatan->nama); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="kelurahan" class="small mb-1">Kelurahan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="kelurahan" name="kelurahan_id" required>
                                <option value="">-- Pilih Kelurahan --</option>
                                <?php if(!empty($user->kecamatan_id)): ?>
                                <?php
                                $kelurahans = \App\Models\Kelurahan::where(
                                'kecamatan_id',
                                $user->kecamatan_id,
                                )->get();
                                ?>
                                <?php $__currentLoopData = $kelurahans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelurahan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kelurahan->id); ?>"
                                    <?php echo e(old('kelurahan_id', $user->kelurahan_id) == $kelurahan->id ? 'selected' : ''); ?>>
                                    <?php echo e($kelurahan->nama); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>


                    
                    <div class="mb-4">
                        <label for="alamat" class="form-label fw-medium">
                            Alamat
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat tempat tinggal Anda"
                            required><?php echo e(old('alamat', $user->alamat)); ?></textarea>

                        <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'user')): ?>

                    

                    <?php else: ?>

                    <div class="border-top pt-4 mt-4">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="fw-semibold mb-1">
                                    Informasi Profesional
                                </h5>

                                <small class="text-muted">
                                    Status keaktifan akun Anda.
                                </small>
                            </div>

                            <div class="bg-warning-subtle rounded-3 p-2">
                                <i class="bi bi-award text-warning fs-5"></i>
                            </div>
                        </div>


                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium d-block">
                                    Status Akun
                                </label>

                                <input type="hidden" name="is_active" value="inactive">

                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        type="checkbox"
                                        role="switch"
                                        id="is_active"
                                        name="is_active"
                                        value="active"
                                        <?php echo e(old('is_active', $user->is_active ?? 'active') === 'active' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active">
                                        Akun Aktif
                                    </label>
                                </div>

                                <small class="text-muted">
                                    Nonaktifkan untuk menonaktifkan akun ini.
                                </small>

                                <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1">
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>


                    </div>

                    <?php endif; ?>

                    
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">

                        <button
                            type="button"
                            class="btn btn-light border px-4"
                            onclick="window.history.back()">
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-1"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </div>
            </div>
        </div>

    </div>

</form>


<?php echo $__env->make('profile.modals-file-preview', ['user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('profile.partials.change-password', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('after-script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kecSelect = document.getElementById('kecamatan');
        const kelSelect = document.getElementById('kelurahan');
        if (!kecSelect || !kelSelect) return;

        const selectedKel = "<?php echo e(old('kelurahan_id', $user->kelurahan_id)); ?>";
        const baseUrl = "<?php echo e(url('backend/get-kelurahan')); ?>";

        kecSelect.addEventListener('change', function() {
            const id = this.value;
            kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            if (!id) return;
            kelSelect.innerHTML = '<option value="">Memuat...</option>';
            fetch(baseUrl + '/' + id, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                    Object.entries(data).forEach(function([val, label]) {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = label;
                        if (String(val) === String(selectedKel)) opt.selected = true;
                        kelSelect.appendChild(opt);
                    });
                })
                .catch(function() {
                    kelSelect.innerHTML = '<option value="">Gagal memuat kelurahan</option>';
                });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\profile\update.blade.php ENDPATH**/ ?>