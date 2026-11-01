<div class="container-fluid pb-5">

    <!-- Hero -->
    <div class="dash-hero mb-4">
        <div class="hero-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="hero-date">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?php echo e(now()->translatedFormat('l, j F Y')); ?>

                </span>
                <h3 class="fw-bold text-white mb-1">Halo, <?php echo e(auth()->user()->name); ?></h3>
                <p class="mb-0">
                    Sampaikan laporan kerusakan infrastruktur dan pantau status penanganannya di sini.
                </p>
            </div>
            <div class="d-flex gap-2 hero-action">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.store')): ?>
                <a href="<?php echo e(route('aduans.create')); ?>" class="btn btn-light fw-bold px-4">
                    <i class="bi bi-plus-lg me-1"></i> Buat Pengaduan
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('aduans.index')); ?>" class="btn btn-warning fw-bold px-4">
                    <i class="bi bi-clock-history me-1"></i> Riwayat Saya
                </a>
            </div>
        </div>
    </div>

    <?php if(($belumDinilai ?? 0) > 0): ?>
    <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-star me-1"></i> <?php echo e($belumDinilai); ?> aduan selesai menunggu penilaian Anda.</span>
        <a href="<?php echo e(route('aduans.index', ['status' => 'selesai'])); ?>" class="btn btn-sm btn-warning text-white">Nilai Sekarang</a>
    </div>
    <?php endif; ?>

    <!-- Statistik -->
    <div class="row g-3 g-lg-4 mb-4">

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon blue">
                        <i class='bi bi-megaphone'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Total Pengaduan</div>
                        <div class="stat-value"><?php echo e($statTotal ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon yellow">
                        <i class='bi bi-hourglass-split'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Menunggu</div>
                        <a href="<?php echo e(route('aduans.index', ['status' => 'menunggu'])); ?>" class="text-decoration-none">
                            <div class="stat-value"><?php echo e($statMenunggu ?? 0); ?></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon green">
                        <i class='bi bi-gear'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Diproses</div>
                        <div class="stat-value"><?php echo e($statDiproses ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon red">
                        <i class='bi bi-check-circle'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Selesai</div>
                        <a href="<?php echo e(route('aduans.index', ['status' => 'selesai'])); ?>" class="text-decoration-none">
                            <div class="stat-value"><?php echo e($statSelesai ?? 0); ?></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan terbaru -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Pengaduan Terbaru Saya</h5>
            <a href="<?php echo e(route('aduans.index')); ?>" class="btn btn-sm btn-light">Lihat Semua</a>
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor</th>
                            <th>Judul</th>
                            <th class="hide-xs">Kategori</th>
                            <th>Status</th>
                            <th class="hide-xs">Dilaporkan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $recentAduan ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aduan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <td><code style="font-size:0.75rem;"><?php echo e($aduan->nomor_aduan); ?></code></td>
                            <td style="min-width:140px;"><span class="fw-medium"><?php echo e(Str::limit($aduan->judul, 45)); ?></span></td>
                            <td class="hide-xs"><span class="badge bg-info"><?php echo e($aduan->kategori); ?></span></td>
                            <td>
                                <?php
                                $badge = ['menunggu' => 'secondary','diverifikasi' => 'info','diproses' => 'warning','selesai' => 'success','ditolak' => 'danger'][$aduan->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($aduan->status)); ?></span>
                            </td>
                            <td class="hide-xs"><small class="text-muted"><?php echo e($aduan->tanggal_pengaduan?->format('d M Y') ?? '-'); ?></small></td>
                            <td>
                                <a href="<?php echo e(route('aduans.show', $aduan->uuid)); ?>"
                                    class="btn btn-sm btn-info text-white" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-2">Belum ada pengaduan. Yuk, laporkan temuan Anda!</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.store')): ?>
                                <a href="<?php echo e(route('aduans.create')); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Buat Pengaduan Pertama
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php if(blank(Auth::user()->no_hp)): ?>
<div class="modal fade"
    id="completeProfileModal"
    tabindex="-1"
    aria-labelledby="completeProfileModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="<?php echo e(route('dashboard.submitSumber')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="completeProfileModalLabel">
                        Lengkapi Informasi Anda
                    </h5>
                </div>

                <div class="modal-body">

                    <p class="text-secondary">
                        Lengkapi informasi berikut sebelum melanjutkan.
                    </p>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">
                            Nomor WhatsApp
                        </label>

                        <input type="text"
                            name="no_hp"
                            id="no_hp"
                            class="form-control <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="08xxxxxxxxxx"
                            value="<?php echo e(old('no_hp')); ?>"
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

                    <div class="mb-3">
                        <label for="sumber_informasi" class="form-label">
                            Dari mana Anda tahu layanan ini?
                        </label>

                        <select name="sumber_informasi"
                            id="sumber_informasi"
                            class="form-select <?php $__errorArgs = ['sumber_informasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                            <option value="">Pilih salah satu</option>
                            <option value="google">Pencarian Google</option>
                            <option value="sosmed">Media Sosial</option>
                            <option value="teman">Teman / Keluarga</option>
                            <option value="komunitas">Komunitas</option>
                            <option value="event">Acara / Sosialisasi</option>
                            <option value="website">Website</option>
                            <option value="lainnya">Lainnya</option>
                        </select>

                        <?php $__errorArgs = ['sumber_informasi'];
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
                    <button type="submit" class="btn btn-primary w-100">
                        Simpan Informasi
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<?php endif; ?>

<?php if(blank(Auth::user()->no_hp)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('completeProfileModal');

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });

            modal.show();
        }
    });
</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\dashboard\member.blade.php ENDPATH**/ ?>