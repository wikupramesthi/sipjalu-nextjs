<?php $__env->startSection('title', 'Detail Aduan'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Detail Aduan','page' => 'Aduan','active' => ''.e($item->nomor_aduan).'','route' => ''.e(route('aduans.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo e($item->nomor_aduan); ?> — <?php echo e($item->judul); ?></h5>
            <a href="<?php echo e(route('aduans.index')); ?>" class="btn btn-sm btn-light">Kembali</a>
        </div>
        <div class="card-body">
            <?php if($item->trashed()): ?>
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <span>Aduan ini ada di <strong>Tempat Sampah</strong> (dihapus <?php echo e($item->deleted_at?->format('d M Y H:i')); ?>).</span>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.restore')): ?>
                <form action="<?php echo e(route('aduans.restore', $item->uuid)); ?>" method="POST" class="mb-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-warning text-white">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Kembalikan
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if($item->archived_at): ?>
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <span>Aduan ini <strong>diarsipkan</strong> (<?php echo e($item->archived_at?->format('d M Y H:i')); ?>). Tidak tampil di daftar aktif &amp; API publik.</span>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.batalArsip')): ?>
                <form action="<?php echo e(route('aduans.batalArsip', $item->uuid)); ?>" method="POST" class="mb-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-warning text-white">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Aktifkan Lagi
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-7">
                    <table class="table table-bordered">
                        <tr><th width="220">Nomor Aduan</th><td><code><?php echo e($item->nomor_aduan); ?></code></td></tr>
                        <tr><th>Kategori</th><td><?php echo e($item->kategori); ?></td></tr>
                        <tr><th>Judul</th><td><?php echo e($item->judul); ?></td></tr>
                        <tr><th>Isi Aduan</th><td style="white-space: pre-wrap;"><?php echo e($item->isi_aduan); ?></td></tr>
                        <tr><th>Lokasi</th><td><?php echo e($item->lokasi ?? '-'); ?></td></tr>
                        <tr><th>Kecamatan / Kelurahan</th><td><?php echo e($item->kecamatan->nama ?? '-'); ?> / <?php echo e($item->kelurahan->nama ?? '-'); ?></td></tr>
                        <tr><th>Pelapor</th><td><?php echo e($item->user->name ?? '-'); ?> (<?php echo e($item->user->email ?? '-'); ?>)
                                <?php if($item->is_anonim): ?>
                                <span class="badge bg-dark ms-1">Anonim — disembunyikan dari publik</span>
                                <?php endif; ?>
                            </td></tr>
                        <tr><th>Tanggal Kejadian</th><td><?php echo e($item->tanggal_kejadian?->format('d M Y') ?? '-'); ?></td></tr>
                        <tr><th>Tanggal Pengaduan</th><td><?php echo e($item->tanggal_pengaduan?->format('d M Y H:i') ?? '-'); ?></td></tr>
                        <tr><th>Status / Prioritas / Sifat</th>
                            <td><?php echo e(ucfirst($item->status)); ?> / <?php echo e(ucfirst($item->prioritas)); ?> / <?php echo e(ucfirst($item->sifat)); ?></td>
                        </tr>
                        <tr><th>Koordinat</th><td><?php echo e($item->latitude ?? '-'); ?>, <?php echo e($item->longitude ?? '-'); ?></td></tr>
                    </table>
                </div>
                <div class="col-md-5">
                    <h6>Dokumentasi Foto</h6>
                    <div class="row g-2">
                        <?php $__currentLoopData = ['foto_1', 'foto_2', 'foto_3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12">
                            <?php if($item->{$field}): ?>
                            <a href="<?php echo e(asset('storage/' . $item->{$field})); ?>" target="_blank">
                                <img src="<?php echo e(asset('storage/' . $item->{$field})); ?>" class="img-fluid rounded border" alt="<?php echo e($field); ?>">
                            </a>
                            <?php else: ?>
                            <div class="border rounded p-3 text-center text-muted"><?php echo e($field); ?>: tidak ada foto</div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($item->latitude && $item->longitude): ?>
                    <a class="btn btn-sm btn-outline-primary mt-3" target="_blank"
                        href="https://www.google.com/maps?q=<?php echo e($item->latitude); ?>,<?php echo e($item->longitude); ?>">
                        Buka di Google Maps
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('pages.aduan._timeline', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if($item->rating || ($item->status === 'selesai' && auth()->check() && $item->user_uuid === auth()->user()->uuid)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-star me-1"></i> Penilaian Masyarakat</h5>
        </div>
        <div class="card-body">
            <?php if($item->rating): ?>
            <div class="fs-4 text-warning"><?php echo e(str_repeat('★', $item->rating)); ?><?php echo e(str_repeat('☆', 5 - $item->rating)); ?></div>
            <div class="small text-muted mb-2">Dinilai <?php echo e($item->rated_at?->format('d M Y H:i') ?? '-'); ?></div>
            <?php if($item->ulasan): ?>
            <p class="mb-0" style="white-space: pre-wrap;"><?php echo e($item->ulasan); ?></p>
            <?php endif; ?>
            <?php endif; ?>

            <?php if($item->status === 'selesai' && auth()->check() && $item->user_uuid === auth()->user()->uuid): ?>
            <?php if($item->rating): ?>
            <hr>
            <?php endif; ?>
            <form action="<?php echo e(route('aduans.rate', $item->uuid)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <?php echo e($item->rating ? 'Ubah penilaian' : 'Beri penilaian'); ?>

                        <span id="rating-angka" class="ms-2 fw-bold text-warning small"></span>
                    </label>
                    <div class="rating-input">
                        <?php for($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?php echo e($i); ?>" name="rating" value="<?php echo e($i); ?>"
                            <?php echo e(old('rating', $item->rating) == $i ? 'checked' : ''); ?> required>
                        <label for="star<?php echo e($i); ?>" title="<?php echo e($i); ?> bintang" data-nilai="<?php echo e($i); ?>">★</label>
                        <?php endfor; ?>
                    </div>
                    <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label for="ulasan" class="form-label fw-semibold">Ulasan (opsional)</label>
                    <textarea id="ulasan" name="ulasan" rows="3" class="form-control" maxlength="1000"
                        placeholder="Ceritakan pengalaman Anda..."><?php echo e(old('ulasan', $item->ulasan)); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Kirim Penilaian</button>
            </form>
            <style>
                .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
                .rating-input input { display: none; }
                .rating-input label { font-size: 1.8rem; color: #dee2e6; cursor: pointer; line-height: 1; }
                .rating-input input:checked ~ label,
                .rating-input label:hover,
                .rating-input label:hover ~ label { color: #ffc107; }
            </style>
            <script>
                // Kunci warna bintang + tampilkan angka pilihan (cadangan bila CSS :checked tak jalan)
                (function() {
                    const wrap = document.querySelector('.rating-input');
                    if (!wrap) return;
                    const out = document.getElementById('rating-angka');
                    const paint = (val) => {
                        wrap.querySelectorAll('label').forEach(lb => {
                            const v = parseInt(lb.dataset.nilai || '0', 10);
                            lb.style.color = (val > 0 && v <= val) ? '#ffc107' : '#dee2e6';
                        });
                        if (out) out.textContent = val > 0 ? val + ' dari 5 bintang' : '';
                    };
                    wrap.querySelectorAll('input[name="rating"]').forEach(i => {
                        i.addEventListener('change', () => paint(parseInt(i.value, 10)));
                    });
                    const checked = wrap.querySelector('input[name="rating"]:checked');
                    paint(checked ? parseInt(checked.value, 10) : 0);
                })();
            </script>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history me-1"></i> Jejak Perubahan
                <span class="badge bg-secondary ms-1"><?php echo e($jejak->count()); ?></span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:150px;">Waktu</th>
                            <th style="width:110px;">Aksi</th>
                            <th style="width:160px;">Oleh</th>
                            <th>Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $jejak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <td class="text-muted small"><?php echo e($log->created_at?->format('d M Y H:i') ?? '-'); ?></td>
                            <td><span class="badge <?php echo e($log->event_badge); ?>"><?php echo e($log->event_label); ?></span></td>
                            <td class="small"><?php echo e($log->user_name ?? 'Sistem'); ?></td>
                            <td class="small">
                                <div class="fw-semibold"><?php echo e($log->auditable_label); ?></div>
                                <?php $__currentLoopData = $log->changes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $ubah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $label = ['judul' => 'Judul','isi_aduan' => 'Isi aduan','kategori' => 'Kategori','lokasi' => 'Lokasi','status' => 'Status','prioritas' => 'Prioritas','sifat' => 'Sifat','catatan' => 'Catatan','tanggal' => 'Tanggal','rating' => 'Rating','ulasan' => 'Ulasan','archived_at' => 'Arsip'][$field] ?? ucfirst(str_replace('_', ' ', $field));
                                $lama = is_string($ubah['old']) && str_contains($ubah['old'], '/') ? basename($ubah['old']) : $ubah['old'];
                                $baru = is_string($ubah['new']) && str_contains($ubah['new'], '/') ? basename($ubah['new']) : $ubah['new'];
                                ?>
                                <div><?php echo e($label); ?>: <span class="text-muted"><?php echo e($lama === null || $lama === '' ? '—' : \Illuminate\Support\Str::limit((string) $lama, 60)); ?></span> → <strong><?php echo e($baru === null || $baru === '' ? '—' : \Illuminate\Support\Str::limit((string) $baru, 60)); ?></strong></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada riwayat perubahan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\aduan\show.blade.php ENDPATH**/ ?>