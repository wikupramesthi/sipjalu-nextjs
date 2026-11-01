
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-tools me-1"></i> Riwayat Tindak Lanjut
            <span class="badge bg-primary ms-1"><?php echo e($item->tindakLanjuts->count()); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <?php $__empty_2 = true; $__currentLoopData = $item->tindakLanjuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <strong><?php echo e($tl->tanggal?->format('d M Y H:i') ?? '-'); ?></strong>
                    <?php if($tl->status): ?>
                    <?php
                    $badge = ['menunggu' => 'secondary','diverifikasi' => 'info','diproses' => 'warning','selesai' => 'success','ditolak' => 'danger'][$tl->status] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo e($badge); ?>">Status: <?php echo e(ucfirst($tl->status)); ?></span>
                    <?php endif; ?>
                    <small class="text-muted">oleh <?php echo e($tl->user->name ?? 'Petugas'); ?></small>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.tindaklanjut.destroy')): ?>
                <a onclick="confirmHapusTindakLanjut('<?php echo e($tl->uuid); ?>')" title="Hapus tindak lanjut"
                    class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </a>
                <form id="hapusTlForm_<?php echo e($tl->uuid); ?>"
                    action="<?php echo e(route('aduans.tindak-lanjut.destroy', $tl->uuid)); ?>" method="POST" class="d-none">
                    <?php echo method_field('DELETE'); ?>
                    <?php echo csrf_field(); ?>
                </form>
                <?php endif; ?>
            </div>

            <p class="mb-2" style="white-space: pre-wrap;"><?php echo e($tl->catatan); ?></p>

            <?php if($tl->foto_1 || $tl->foto_2): ?>
            <div class="row g-2">
                <?php $__currentLoopData = ['foto_1', 'foto_2']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($tl->{$field}): ?>
                <div class="col-6 col-md-4">
                    <a href="<?php echo e(asset('storage/' . $tl->{$field})); ?>" target="_blank">
                        <img src="<?php echo e(asset('storage/' . $tl->{$field})); ?>" class="img-fluid rounded border"
                            style="max-height: 140px; width: 100%; object-fit: cover;" alt="Foto tindak lanjut">
                    </a>
                </div>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <p class="text-muted mb-0">Belum ada tindak lanjut untuk aduan ini.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmHapusTindakLanjut(getId) {
        Swal.fire({
            title: 'Hapus Tindak Lanjut?',
            text: 'Catatan dan foto tindak lanjut ini akan dihapus permanen. Status aduan tidak dikembalikan otomatis.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusTlForm_' + getId).submit();
            }
        });
    }
</script>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\aduan\_timeline.blade.php ENDPATH**/ ?>