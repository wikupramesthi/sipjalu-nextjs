<div id="editSubcategory<?php echo e($item->uuid); ?>" class="modal fade" tabindex="-1"
    aria-labelledby="editSubcategoryLabel<?php echo e($item->uuid); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('document-subcategories.update', $item->uuid)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubcategoryLabel<?php echo e($item->uuid); ?>">Edit Subkategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="parent_uuid_<?php echo e($item->uuid); ?>" class="form-label">Kategori Induk <span class="text-danger">*</span></label>
                        <select name="parent_uuid" id="parent_uuid_<?php echo e($item->uuid); ?>" class="form-select" required>
                            <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($parent->uuid); ?>" <?php if($item->parent_uuid === $parent->uuid): echo 'selected'; endif; ?>><?php echo e($parent->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_name_<?php echo e($item->uuid); ?>" class="form-label">Nama Subkategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="subcategory_name_<?php echo e($item->uuid); ?>" value="<?php echo e($item->name); ?>"
                            class="form-control" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_description_<?php echo e($item->uuid); ?>" class="form-label">Deskripsi</label>
                        <textarea name="description" id="subcategory_description_<?php echo e($item->uuid); ?>" rows="3"
                            class="form-control"><?php echo e($item->description); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_status_<?php echo e($item->uuid); ?>" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="subcategory_status_<?php echo e($item->uuid); ?>" class="form-select" required>
                            <option value="active" <?php if($item->status === 'active'): echo 'selected'; endif; ?>>Aktif</option>
                            <option value="inactive" <?php if($item->status === 'inactive'): echo 'selected'; endif; ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\document-subcategories\modal-edit.blade.php ENDPATH**/ ?>