<?php $__env->startSection('title', 'Edit Item Menu: ' . $item->name); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Edit Item Menu','page' => 'Pengaturan','active' => 'Item Menu: '.e($websiteMenu->name).'','route' => ''.e(route('website-menu.items.index', $websiteMenu)).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?php echo e(route('website-menu.items.index', $websiteMenu)); ?>" class="btn btn-light">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="fw-normal mb-0 text-body">Edit Item Menu</h4>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('website-menu.items.update', [$websiteMenu, $item])); ?>" method="POST">
            <?php echo method_field('PUT'); ?>
            <?php echo csrf_field(); ?>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="<?php echo e($item->name); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">URL</label>
                <div class="col-sm-10">
                    <input type="url" class="form-control" name="url" value="<?php echo e($item->url); ?>" placeholder="https://example.com/halaman">
                    <div class="form-text">Isi URL atau Route, tidak perlu keduanya</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Route</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="route" value="<?php echo e($item->route); ?>" placeholder="home.index">
                    <div class="form-text">Nama route Laravel, kosongkan jika pakai URL</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Route Params (JSON)</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="route_params" rows="2"><?php echo e($item->route_params ? json_encode($item->route_params, JSON_PRETTY_PRINT) : ''); ?></textarea>
                    <div class="form-text">Parameter untuk route, format JSON</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Icon</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="icon" value="<?php echo e($item->icon); ?>" placeholder="bx bx-home">
                    <div class="form-text">Class icon Boxicons, contoh: bx bx-home, bx bx-user</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Parent</label>
                <div class="col-sm-10">
                    <select class="form-select" name="parent_id">
                        <option value="">-- Menu Utama (Top Level) --</option>
                        <?php $__currentLoopData = $parentItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($parent->id); ?>" <?php echo e($item->parent_id == $parent->id ? 'selected' : ''); ?>><?php echo e($parent->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Pilih parent untuk membuat sub-menu (dropdown). Tidak bisa memilih dirinya sendiri atau anaknya.</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Buka di Tab Baru</label>
                <div class="col-sm-10">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="target_blank" id="target_blank" <?php echo e($item->target_blank ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="target_blank">Ya, buka di tab baru (_blank)</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status" <?php echo e($item->status ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="status">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Posisi</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="position" value="<?php echo e($item->position); ?>" min="0">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Deskripsi</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="description" rows="2"><?php echo e($item->description); ?></textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('website-menu.items.index', $websiteMenu)); ?>" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\website-menu\items\edit.blade.php ENDPATH**/ ?>