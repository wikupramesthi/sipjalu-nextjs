<?php $__env->startSection('title', 'Import Agenda'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Import Agenda','page' => 'Agenda','active' => 'Import Agenda','route' => ''.e(route('agendas.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white"><?php echo e(session('success')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white"><?php echo e(session('error')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white"><?php echo e($errors->first()); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-upload me-1 text-primary"></i>Unggah File</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('agendas.importPreview')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold">File Excel / CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv,.txt" class="form-control" required>
                            <div class="form-text">Maks 5 MB, maks 200 baris. Baris pertama boleh header template.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Pratinjau &amp; Validasi</button>
                            <a href="<?php echo e(route('agendas.importTemplate')); ?>" class="btn btn-success"><i class="bi bi-download me-1"></i>Unduh Template CSV</a>
                            <a href="<?php echo e(route('agendas.index')); ?>" class="btn btn-light border">Kembali</a>
                        </div>
                    </form>
                    <hr>
                    <small class="text-muted d-block mb-1 fw-semibold">Kolom template:</small>
                    <small class="text-muted d-block">judul*, isi*, kategori* (nama/slug), jenis (offline/online), lokasi, waktu_mulai, waktu_akhir, url, tanggal_terbit, status, tags.</small>
                    <small class="text-muted d-block mt-1">Format waktu contoh: 2026-11-01 09:00. Offline wajib lokasi, online wajib URL.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-1 text-success"></i>Hasil Pratinjau</h5>
                    <?php if(isset($validCount)): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle"><?php echo e($validCount); ?> baris valid</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(isset($preview)): ?>
                        <?php if(count($preview) > 0): ?>
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
                                    <thead class="table-light">
                                        <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Jenis</th><th>Mulai</th><th>Status</th></tr>
                                    </thead>
                                    <tbody>
                                         <?php $__currentLoopData = $preview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <tr class="<?php echo e(count($row['errors']) > 0 ? 'table-danger' : ''); ?>">
                                                 <td class="text-muted"><?php echo e($row['line']); ?></td>
                                                 <td>
                                                     <span class="fw-semibold d-block"><?php echo e(Str::limit($row['data']['judul'] !== '' ? $row['data']['judul'] : '-', 45)); ?></span>
                                                     <?php $__currentLoopData = $row['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                         <small class="text-danger d-block"><i class="bi bi-x-circle me-1"></i><?php echo e($err); ?></small>
                                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                 </td>
                                                 <td><small><?php echo e($row['data']['kategori'] !== '' ? $row['data']['kategori'] : '-'); ?></small></td>
                                                 <td><small class="text-capitalize"><?php echo e($row['data']['jenis_agenda']); ?></small></td>
                                                 <td><small><?php echo e($row['data']['waktu_mulai'] !== '' ? $row['data']['waktu_mulai'] : '-'); ?></small></td>
                                                 <td>
                                                     <?php if(count($row['errors']) > 0): ?>
                                                         <span class="badge bg-danger">Dilewati</span>
                                                     <?php else: ?>
                                                         <span class="badge bg-success">Valid</span>
                                                     <?php endif; ?>
                                                 </td>
                                                 <td class="text-center">
                                                     <button type="button" class="btn btn-sm btn-outline-primary" onclick="editImportRow(<?php echo e($row['line']); ?>, 'judul', '<?php echo e(addslashes($row['data']['judul'])); ?>')" title="Edit judul"><i class="bi bi-pencil"></i></button>
                                                     <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editImportRow(<?php echo e($row['line']); ?>, 'kategori', '<?php echo e(addslashes($row['data']['kategori'])); ?>')" title="Edit kategori"><i class="bi bi-tag"></i></button>
                                                     <?php if(count($row['errors']) > 0): ?>
                                                         <button type="button" class="btn btn-sm btn-outline-warning" onclick="editImportRow(<?php echo e($row['line']); ?>, 'isi', '<?php echo e(addslashes(Str::limit($row['data']['content'], 100))); ?>')" title="Edit isi"><i class="bi bi-file-text"></i></button>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if($validCount > 0): ?>
                                <form action="<?php echo e(route('agendas.importConfirm')); ?>" method="POST" onsubmit="return confirm('Import <?php echo e($validCount); ?> baris valid? Baris tidak valid dilewati.');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Import <?php echo e($validCount); ?> Baris Valid</button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">Tidak ada baris valid. Perbaiki file lalu unggah ulang.</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">File kosong / tidak ada baris data.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Belum ada pratinjau. Unggah file untuk melihat validasi per baris sebelum diimport.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function editImportRow(line, field, currentValue) {
        const newValue = prompt('Edit ' + field + ':', currentValue);
        if (newValue === null) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("agendas.importEdit")); ?>';
        form.style.display = 'none';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = 'token';
        token.value = '<?php echo e($token ?? ''); ?>';
        form.appendChild(token);

        const lineInput = document.createElement('input');
        lineInput.type = 'hidden';
        lineInput.name = 'line';
        lineInput.value = line;
        form.appendChild(lineInput);

        const fieldInput = document.createElement('input');
        fieldInput.type = 'hidden';
        fieldInput.name = 'field';
        fieldInput.value = field;
        form.appendChild(fieldInput);

        const valueInput = document.createElement('input');
        valueInput.type = 'hidden';
        valueInput.name = 'value';
        valueInput.value = newValue;
        form.appendChild(valueInput);

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrf);

        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views/pages/agendas/import.blade.php ENDPATH**/ ?>