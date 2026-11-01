<!-- Modals add menu -->
<div id="modal-form-edit-faq-<?php echo e($item->uuid); ?>" class="modal fade modal-form-faq-edit" tabindex="-1"
    aria-labelledby="modal-form-edit-faq-<?php echo e($item->uuid); ?>-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('faq.update', $item->uuid)); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-edit-faq-<?php echo e($item->uuid); ?>-label">Edit Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="pertanyaan" class="mb-2">Peryanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['pertanyaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="pertanyaan" placeholder="Pertanyaan" name="pertanyaan"
                            value="<?php echo e($item->pertanyaan ?? old('pertanyaan')); ?>" required>
                        <?php $__errorArgs = ['pertanyaan'];
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

                    <div class="form-group mb-3">
                        <label for="jawaban" class="mb-2">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote <?php $__errorArgs = ['jawaban'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="jawaban" rows="6"
                            placeholder="Jawaban" name="jawaban" required><?php echo e($item->jawaban ?? old('jawaban')); ?></textarea>
                        <?php $__errorArgs = ['jawaban'];
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

                    <div class="form-group mb-3">
                        <label for="kategori" class="mb-2">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Tanpa Kategori --</option>
                            <option value="tentang-agenda" <?php echo e(($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'tentang-agenda' ? 'selected' : ''); ?>>Tentang Agenda</option>
                            <option value="jadwal" <?php echo e(($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'jadwal' ? 'selected' : ''); ?>>Jadwal</option>
                            <option value="lokasi" <?php echo e(($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'lokasi' ? 'selected' : ''); ?>>Lokasi</option>
                            <option value="publikasi" <?php echo e(($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'publikasi' ? 'selected' : ''); ?>>Publikasi</option>
                            <option value="lainnya" <?php echo e(($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                        </select>
                        <?php $__errorArgs = ['kategori'];
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

                    <div class="form-group mb-3">
                        <label for="urutan" class="mb-2">Urutan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?php $__errorArgs = ['urutan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="urutan"
                            placeholder="Urutan" name="urutan" value="<?php echo e($item->urutan ?? old('urutan')); ?>" required>
                        <?php $__errorArgs = ['urutan'];
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

                    <div class="form-group mb-3">
                        <label for="status" class="mb-2">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="active"
                                <?php echo e((isset($item) && $item->status == 'active') || old('status') == 'active' ? 'selected' : ''); ?>>
                                Aktif</option>
                            <option value="inactive"
                                <?php echo e((isset($item) && $item->status == 'inactive') || old('status') == 'inactive' ? 'selected' : ''); ?>>
                                Tidak Aktif</option>
                        </select>
                        <?php $__errorArgs = ['status'];
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\faq\modal-edit.blade.php ENDPATH**/ ?>