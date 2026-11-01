<?php $__env->startSection('title', 'Import Berita'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Import Berita','page' => 'Berita','active' => 'Import Berita','route' => ''.e(route('beritas.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<section class="section">
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-upload me-1"></i>Unggah File</h5></div>
                <div class="card-body">
                    <form action="<?php echo e(route('beritas.importPreview')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="importFile" class="form-label fw-semibold">File Excel / CSV</label>
                            <input type="file" id="importFile" name="file" accept=".xlsx,.xls,.csv,.txt" class="form-control" required>
                            <div class="form-text">Maksimal 5 MB dan 200 baris berita.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Pratinjau &amp; Validasi</button>
                            <a href="<?php echo e(route('beritas.importTemplate')); ?>" class="btn btn-success"><i class="bi bi-download me-1"></i>Unduh Template CSV</a>
                            <a href="<?php echo e(route('beritas.index')); ?>" class="btn btn-light border">Kembali</a>
                        </div>
                    </form>
                    <hr>
                    <small class="text-muted d-block fw-semibold">Kolom template</small>
                    <small class="text-muted">judul*, ringkasan, isi*, kategori* (nama/slug), tanggal_terbit, status, tags.</small>
                    <small class="text-muted d-block mt-1">Status: draft, published, scheduled, atau pending. User non-admin akan disimpan sebagai pending.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h5 class="mb-0"><i class="bi bi-list-check me-1"></i>Hasil Pratinjau</h5>
                    <?php if(isset($validCount)): ?><span class="badge text-bg-success"><?php echo e($validCount); ?> baris valid</span><?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(isset($preview)): ?>
                        <?php if(count($preview)): ?>
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover align-middle">
                                    <thead class="table-light">
                                        <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Validasi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $preview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="<?php echo e($row['errors'] ? 'table-danger' : ''); ?>">
                                                <td><?php echo e($row['line']); ?></td>
                                                <td>
                                                    <span class="fw-semibold"><?php echo e($row['data']['title'] ?: '-'); ?></span>
                                                    <small class="d-block text-muted"><?php echo e(Str::limit($row['data']['excerpt'], 80)); ?></small>
                                                </td>
                                                <td><?php echo e($row['data']['category'] ?: '-'); ?></td>
                                                <td><?php echo e(ucfirst($row['data']['status'])); ?></td>
                                                <td>
                                                    <?php $__empty_2 = true; $__currentLoopData = $row['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                        <small class="text-danger d-block"><?php echo e($error); ?></small>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                        <span class="badge text-bg-success">Valid</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if($validCount > 0): ?>
                                <form action="<?php echo e(route('beritas.importConfirm')); ?>" method="POST" onsubmit="return confirm('Import <?php echo e($validCount); ?> baris valid? Baris tidak valid akan dilewati.')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Import <?php echo e($validCount); ?> Baris Valid</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">Tidak ada baris valid untuk diimpor.</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">File tidak berisi baris data.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Unggah file untuk memvalidasi isi dan kategori sebelum menyimpan berita.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\beritas\import.blade.php ENDPATH**/ ?>