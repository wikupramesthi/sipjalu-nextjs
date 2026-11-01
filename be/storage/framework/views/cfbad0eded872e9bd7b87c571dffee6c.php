<?php
    $agenda = $agenda ?? null;
    $featuredImage = $agenda->featured_image ?? null;
    $jenis = old('jenis_agenda', $agenda->jenis_agenda ?? 'offline');
    $waktuMulai = old('waktu_mulai', optional($agenda->waktu_mulai ?? null)->format('Y-m-d\TH:i') ?? '');
    $waktuAkhir = old('waktu_akhir', optional($agenda->waktu_akhir ?? null)->format('Y-m-d\TH:i') ?? '');
?>

<style>
    .agenda-info-card {
        border-radius: 1.25rem;
        padding: 1.25rem;
        color: #fff;
        background: linear-gradient(135deg, #3b5bdb 0%, #748ffc 45%, #9775fa 100%);
        box-shadow: 0 10px 30px rgba(59, 91, 219, 0.25);
    }
    .agenda-info-head {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .agenda-info-head strong { display: block; font-size: 1.05rem; line-height: 1.2; }
    .agenda-info-head small { opacity: 0.85; font-size: 0.85rem; }
    .agenda-info-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.25rem; border-radius: 0.9rem;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }
    .agenda-info-item {
        display: flex; gap: 0.75rem; align-items: flex-start;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 1rem;
        padding: 0.8rem 0.9rem;
    }
    .agenda-info-item + .agenda-info-item { margin-top: 0.7rem; }
    .agenda-info-item-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #3b5bdb;
        background: #fff; border-radius: 0.75rem;
    }
    .agenda-info-item strong { display: block; font-size: 0.95rem; }
    .agenda-info-item p { margin: 0; font-size: 0.83rem; line-height: 1.5; opacity: 0.92; }
    .agenda-photo-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .agenda-photo-item { position: relative; border-radius: 10px; overflow: hidden; border: 1px solid #e9eef5; }
    .agenda-photo-item img { width: 100%; height: 90px; object-fit: cover; display: block; }
    .agenda-photo-remove { position: absolute; top: 4px; right: 4px; width: 24px; height: 24px; border: none; border-radius: 50%; background: rgba(220, 38, 38, 0.9); color: #fff; font-size: 11px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
    .agenda-photo-item.is-removed { opacity: 0.35; }
    .agenda-photo-item.is-removed img { filter: grayscale(1); }
    @media (max-width: 767.98px) {
        .agenda-info-card { padding: 1rem; border-radius: 1rem; }
        .agenda-photo-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>

<form action="<?php echo e($action); ?>" method="post" enctype="multipart/form-data" id="agendaForm">
    <?php echo csrf_field(); ?>
    <?php if(($method ?? 'POST') !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-lg-8 d-flex flex-column gap-4">
            
            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary rounded-circle p-2"><i class="bi bi-newspaper"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Konten Agenda</h5>
                        <small class="text-muted">Judul, ringkasan, dan isi agenda</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Judul Agenda <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                            class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('title', $agenda->title ?? '')); ?>" placeholder="cth: Musrenbang Kota Bekasi 2026" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label fw-semibold">Ringkasan Singkat</label>
                        <input type="text" name="excerpt" id="excerpt"
                            class="form-control <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('excerpt', $agenda->excerpt ?? '')); ?>"
                            placeholder="Tampil pada kartu agenda (opsional, maks 255 karakter)">
                        <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3" id="videoForm" style="display: none;">
                        <label for="video" class="form-label fw-semibold">Link YouTube
                            <span class="text-muted fw-normal">(contoh: PlNOD--gPQU)</span>
                        </label>
                        <input type="text" name="video" id="video" class="form-control"
                            value="<?php echo e(old('video', $agenda->video ?? '')); ?>" placeholder="Masukkan link YouTube">
                    </div>

                    <div class="mb-0">
                        <label for="deskripsi" class="form-label fw-semibold">Isi Agenda <span class="text-danger">*</span></label>
                        <textarea name="content" id="deskripsi" cols="30" rows="8"
                            class="form-control summernote <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('content', $agenda->content ?? '')); ?></textarea>
                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="card shadow-sm mb-0 border-primary-subtle">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success rounded-circle p-2"><i class="bi bi-calendar-event"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Detail Pelaksanaan</h5>
                        <small class="text-muted">Jenis, lokasi / tautan, dan waktu kegiatan</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="jenis_agenda" class="form-label fw-semibold">Jenis Agenda <span class="text-danger">*</span></label>
                            <select name="jenis_agenda" id="jenis_agenda"
                                class="form-select <?php $__errorArgs = ['jenis_agenda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="offline" <?php if($jenis === 'offline'): echo 'selected'; endif; ?>>📍 Offline</option>
                                <option value="online" <?php if($jenis === 'online'): echo 'selected'; endif; ?>>🌐 Online</option>
                            </select>
                            <?php $__errorArgs = ['jenis_agenda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-8" id="lokasiWrap">
                            <label for="lokasi" class="form-label fw-semibold">Lokasi <span class="text-danger" id="lokasiStar">*</span></label>
                            <input type="text" name="lokasi" id="lokasi"
                                class="form-control <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('lokasi', $agenda->lokasi ?? '')); ?>"
                                placeholder="cth: Aula Nonon Sonthanie, Plaza Pemkot Bekasi">
                            <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-12" id="urlWrap">
                            <label for="url" class="form-label fw-semibold">URL / Tautan <span class="text-danger" id="urlStar">*</span></label>
                            <input type="url" name="url" id="url"
                                class="form-control <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('url', $agenda->url ?? '')); ?>"
                                placeholder="https://zoom.us/j/... atau https://maps.google.com/...">
                            <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Untuk agenda online wajib diisi (link Zoom / GMeet / live streaming). Untuk offline opsional (link maps / pendaftaran).</div>
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_mulai" class="form-label fw-semibold">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" id="waktu_mulai"
                                class="form-control <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e($waktuMulai); ?>">
                            <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_akhir" class="form-label fw-semibold">Waktu Akhir</label>
                            <input type="datetime-local" name="waktu_akhir" id="waktu_akhir"
                                class="form-control <?php $__errorArgs = ['waktu_akhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e($waktuAkhir); ?>">
                            <?php $__errorArgs = ['waktu_akhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <?php if(($agenda ?? null) === null): ?>
                            <div class="col-md-6">
                                <label for="recurrence_rule" class="form-label fw-semibold">Pengulangan</label>
                                <select name="recurrence_rule" id="recurrence_rule"
                                    class="form-select <?php $__errorArgs = ['recurrence_rule'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Tidak berulang</option>
                                    <option value="harian" <?php if(old('recurrence_rule') === 'harian'): echo 'selected'; endif; ?>>Harian</option>
                                    <option value="mingguan" <?php if(old('recurrence_rule') === 'mingguan'): echo 'selected'; endif; ?>>Mingguan</option>
                                    <option value="bulanan" <?php if(old('recurrence_rule') === 'bulanan'): echo 'selected'; endif; ?>>Bulanan</option>
                                </select>
                                <?php $__errorArgs = ['recurrence_rule'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="recurrence_end" class="form-label fw-semibold">Berulang sampai</label>
                                <input type="date" name="recurrence_end" id="recurrence_end"
                                    class="form-control <?php $__errorArgs = ['recurrence_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('recurrence_end')); ?>">
                                <?php $__errorArgs = ['recurrence_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Otomatis buatkan jadwal lanjutan (maks 12). Hanya saat tambah baru.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-info-subtle text-info rounded-circle p-2"><i class="bi bi-image"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Gambar Utama</h5>
                        <small class="text-muted">Cover agenda, maks 2 MB</small>
                    </div>
                </div>
                <div class="card-body">
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                        class="form-control <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <?php if($featuredImage): ?>
                        <input type="hidden" id="existingCoverUrl" value="<?php echo e(asset('storage/' . $featuredImage)); ?>">
                        <img src="<?php echo e(asset('storage/' . $featuredImage)); ?>" alt="Featured"
                            class="img-fluid rounded-3 mt-3 border" style="max-height:220px;object-fit:cover;">
                    <?php endif; ?>
                    <div class="form-text"><i class="bi bi-info-circle"></i> Kosongkan jika tidak ingin mengubah gambar.</div>
                </div>
            </div>

            
            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning-subtle text-warning rounded-circle p-2"><i class="bi bi-images"></i></span>
                        <div>
                            <h5 class="card-title mb-0">Foto Slider Agenda</h5>
                            <small class="text-muted">Tampil sebagai slider di halaman detail</small>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary"><i class="bi bi-plus-lg"></i> Bisa lebih dari 1 foto</span>
                </div>
                <div class="card-body">
                    <?php if($agenda && $agenda->images->isNotEmpty()): ?>
                        <div class="agenda-photo-grid mb-3" id="agendaExistingImages">
                            <?php $__currentLoopData = $agenda->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="agenda-photo-item" draggable="true" data-uuid="<?php echo e($image->uuid); ?>">
                                    <img src="<?php echo e($image->url()); ?>" alt="Foto agenda">
                                    <button type="button" class="agenda-photo-remove" title="Hapus foto">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <input type="checkbox" name="remove_images[]" value="<?php echo e($image->uuid); ?>"
                                        class="agenda-photo-remove-input" hidden>
                                    <input type="hidden" name="image_order[]" value="<?php echo e($image->uuid); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="form-text mb-2"><i class="bi bi-arrows-move"></i> Seret foto untuk mengubah urutan.</div>
                    <?php endif; ?>

                    <input type="file" name="images[]" id="agendaImages" accept="image/*" multiple
                        class="form-control <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text"><i class="bi bi-info-circle"></i> Format JPG, PNG, WEBP. Maks 2 MB per foto. Bisa pilih beberapa sekaligus.</div>

                    <div class="agenda-photo-grid mt-3" id="agendaImagesPreview"></div>
                </div>
            </div>

            
            <div class="card shadow-sm mb-0">
                <div class="card-header bg-transparent py-3 d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary rounded-circle p-2"><i class="bi bi-paperclip"></i></span>
                    <div>
                        <h5 class="card-title mb-0">Lampiran Dokumen</h5>
                        <small class="text-muted">TOR, undangan, rundown, materi (opsional)</small>
                    </div>
                </div>
                <div class="card-body">
                            <?php if($agenda && $agenda->attachments->isNotEmpty()): ?>
                                <div class="mb-2"><button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteAttachments"><i class="bi bi-trash"></i> Hapus terpilih</button></div>
                            <?php endif; ?>
                            <?php if($agenda && $agenda->attachments->isNotEmpty()): ?>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php $__currentLoopData = $agenda->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0" data-attachment-row>
                                            <span><input type="checkbox" class="attachment-check me-2" value="<?php echo e(route('agendas.attachments.destroy', [$agenda->uuid, $file->uuid])); ?>">
                                                <a href="<?php echo e($file->url()); ?>" target="_blank" rel="noopener"><i class="bi bi-file-earmark me-1"></i><?php echo e($file->nama_asli); ?></a></span>
                                    <span>
                                        <span class="badge bg-light text-dark border"><?php echo e($file->ukuran_label); ?></span>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-2 attachment-delete"
                                            data-url="<?php echo e(route('agendas.attachments.destroy', [$agenda->uuid, $file->uuid])); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>

                    <input type="file" name="attachments[]" id="agendaAttachments" multiple
                        class="form-control <?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text"><i class="bi bi-info-circle"></i> PDF, Office, ZIP, atau gambar. Maks 5 MB per file.</div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="position-sticky" style="top: 90px;">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-send me-1 text-primary"></i> Publikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <?php if(auth()->user()->hasAnyRole(['super-admin', 'admin'])): ?>
                            <select name="status" id="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="published" <?php if(old('status', $agenda->status ?? 'published') === 'published'): echo 'selected'; endif; ?>>Published</option>
                                <option value="pending" <?php if(old('status', $agenda->status ?? '') === 'pending'): echo 'selected'; endif; ?>>Pending (menunggu approval)</option>
                                <option value="draft" <?php if(old('status', $agenda->status ?? '') === 'draft'): echo 'selected'; endif; ?>>Draft</option>
                                <option value="scheduled" <?php if(old('status', $agenda->status ?? '') === 'scheduled'): echo 'selected'; endif; ?>>Scheduled</option>
                            </select>
                            <?php else: ?>
                            <input type="hidden" name="status" value="pending">
                            <div class="alert alert-warning mb-0 py-2 px-3 small">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Agenda yang Anda kirim otomatis berstatus <strong>Pending</strong> dan menunggu persetujuan admin sebelum dipublikasikan.
                            </div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="scheduled_at" class="form-label fw-semibold">Tanggal Terbit</label>
                            <input type="date" name="scheduled_at" id="scheduled_at"
                                class="form-control <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('scheduled_at', optional($agenda->scheduled_at ?? null)->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>">
                            <?php $__errorArgs = ['scheduled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="search_engine" class="form-label fw-semibold">Search Engine <span class="text-danger">*</span></label>
                            <select name="search_engine" id="search_engine"
                                class="form-select <?php $__errorArgs = ['search_engine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="index" <?php if(old('search_engine', $agenda->search_engine ?? 'index') === 'index'): echo 'selected'; endif; ?>>Index</option>
                                <option value="noindex" <?php if(old('search_engine', $agenda->search_engine ?? '') === 'noindex'): echo 'selected'; endif; ?>>No Index</option>
                            </select>
                            <?php $__errorArgs = ['search_engine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <hr>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_featured"
                                id="is_featured" value="1" <?php if(old('is_featured', $agenda->is_featured ?? false)): echo 'checked'; endif; ?>>
                            <label class="form-check-label" for="is_featured">
                                <i class="bi bi-star-fill text-warning"></i> Agenda <strong>Featured</strong>
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_popular"
                                id="is_popular" value="1" <?php if(old('is_popular', $agenda->is_popular ?? false)): echo 'checked'; endif; ?>>
                            <label class="form-check-label" for="is_popular">
                                <i class="bi bi-fire text-danger"></i> Agenda <strong>Populer</strong>
                            </label>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_urgent"
                                id="is_urgent" value="1" <?php if(old('is_urgent', $agenda->is_urgent ?? false)): echo 'checked'; endif; ?>>
                            <label class="form-check-label" for="is_urgent">
                                <i class="bi bi-exclamation-triangle-fill text-danger"></i> Agenda <strong>Urgent</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-tags me-1 text-success"></i> Kategori &amp; Tag</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_uuid" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_uuid" id="category_uuid"
                                class="form-select <?php $__errorArgs = ['category_uuid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->uuid); ?>"
                                        data-name="<?php echo e(strtolower(str_replace(' ', '-', $category->name))); ?>"
                                        <?php if(old('category_uuid', $agenda->category_uuid ?? '') == $category->uuid): echo 'selected'; endif; ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_uuid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-0">
                            <label for="tagging" class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tagging" id="tagging"
                                class="form-control <?php $__errorArgs = ['tagging'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('tagging', $agenda->tagging ?? '')); ?>"
                                placeholder="Pisahkan dengan koma: agenda, kota bekasi">
                            <?php $__errorArgs = ['tagging'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-save"></i> <?php echo e($submitLabel); ?>

                    </button>
                    <button class="btn btn-outline-primary" type="button" onclick="previewAgenda()">
                        <i class="bi bi-eye"></i> Pratinjau
                    </button>
                    <a href="<?php echo e(route('agendas.index')); ?>" class="btn btn-light border">Batal</a>
                </div>

                <div class="agenda-info-card mt-3">
                    <div class="agenda-info-head">
                        <span class="agenda-info-icon"><i class="bi bi-stars"></i></span>
                        <div>
                            <strong>Informasi Penting</strong>
                            <small>Panduan pengisian agenda</small>
                        </div>
                    </div>
                    <div class="agenda-info-item">
                        <span class="agenda-info-item-icon"><i class="bi bi-geo-alt"></i></span>
                        <div>
                            <strong>Jenis &amp; Lokasi</strong>
                            <p>Offline wajib isi lokasi kegiatan. Online wajib isi URL (Zoom / GMeet / live streaming).</p>
                        </div>
                    </div>
                    <div class="agenda-info-item">
                        <span class="agenda-info-item-icon"><i class="bi bi-clock"></i></span>
                        <div>
                            <strong>Waktu Kegiatan</strong>
                            <p>Isi waktu mulai &amp; akhir kegiatan. Waktu akhir tidak boleh lebih awal dari waktu mulai.</p>
                        </div>
                    </div>
                    <div class="agenda-info-item">
                        <span class="agenda-info-item-icon"><i class="bi bi-image"></i></span>
                        <div>
                            <strong>Gambar</strong>
                            <p>Featured &amp; foto slider maks 2 MB per file (JPG / PNG / WEBP). Foto pertama jadi cover bila featured kosong.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const form = document.getElementById('agendaForm');
            if (form) form.submit();
        }
    });
</script>

<style>
    #agendaPreviewBody img { max-width: 100%; height: auto; }
    #agendaPreviewBody table { max-width: 100%; }
    #agendaPreviewBody .note-editable img { max-width: 100%; height: auto; }
</style>

<div class="modal fade" id="agendaPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Pratinjau Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="agendaPreviewBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function previewEsc(v) {
        return (v || '').toString().replace(/[&<>"']/g, function (m) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m];
        });
    }

    function previewFmtDate(v) {
        if (!v) return '-';
        const d = new Date(v);
        if (isNaN(d.getTime())) return '-';
        return d.toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });
    }

    function previewAgenda() {
        const val = function (id) {
            const el = document.getElementById(id);
            return el ? el.value.trim() : '';
        };
        const checked = function (id) {
            const el = document.getElementById(id);
            return el ? el.checked : false;
        };

        let content = val('deskripsi');
        if (window.jQuery && jQuery('#deskripsi').data('summernote')) {
            content = jQuery('#deskripsi').summernote('code');
        }

        const categorySelect = document.getElementById('category_uuid');
        const category = categorySelect && categorySelect.selectedIndex > 0
            ? categorySelect.options[categorySelect.selectedIndex].text
            : '-';

        const jenisSelect = document.getElementById('jenis_agenda');
        const jenis = jenisSelect && jenisSelect.selectedIndex >= 0
            ? jenisSelect.options[jenisSelect.selectedIndex].text
            : '-';
        const isOnline = (jenisSelect && jenisSelect.value === 'online');

        let coverHtml = '';
        const featuredInput = document.getElementById('featured_image');
        if (featuredInput && featuredInput.files && featuredInput.files[0]) {
            coverHtml = '<img src="' + URL.createObjectURL(featuredInput.files[0]) + '" alt="Cover" class="img-fluid rounded mb-3" style="max-height:260px;object-fit:cover;width:100%;">';
        } else if (document.getElementById('existingCoverUrl')) {
            coverHtml = '<img src="' + document.getElementById('existingCoverUrl').value + '" alt="Cover" class="img-fluid rounded mb-3" style="max-height:260px;object-fit:cover;width:100%;">';
        }

        let badges = '';
        if (checked('is_featured')) badges += '<span class="badge bg-warning text-dark">Unggulan</span> ';
        if (checked('is_urgent')) badges += '<span class="badge bg-danger">Mendesak</span> ';
        if (checked('is_popular')) badges += '<span class="badge bg-success">Populer</span> ';

        const tags = val('tagging');
        const tagsHtml = tags ? '<div class="mt-3 small text-muted"><i class="bi bi-tags me-1"></i>' +
            tags.split(',').map(function (t) { return '<span class="badge bg-light text-dark border me-1">' + previewEsc(t.trim()) + '</span>'; }).join('') +
            '</div>' : '';

        const timeText = (val('waktu_mulai') || val('waktu_akhir'))
            ? previewFmtDate(val('waktu_mulai')) + ' — ' + previewFmtDate(val('waktu_akhir'))
            : '-';

        const locationText = isOnline ? (val('url') || '-') : (val('lokasi') || '-');

        document.getElementById('agendaPreviewBody').innerHTML =
            '<div class="border rounded p-3">' +
                coverHtml +
                '<div class="d-flex gap-2 flex-wrap mb-2">' +
                    '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">' + previewEsc(category) + '</span>' +
                    '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">' + previewEsc(jenis) + '</span>' +
                    badges +
                '</div>' +
                '<h4 class="fw-bold mb-1">' + (previewEsc(val('title')) || '<span class="text-muted">Judul belum diisi</span>') + '</h4>' +
                '<p class="text-muted mb-3">' + (previewEsc(val('excerpt')) || '<em>Ringkasan belum diisi</em>') + '</p>' +
                '<div class="mb-2"><i class="bi bi-calendar-event me-2 text-primary"></i><strong>' + timeText + '</strong></div>' +
                '<div class="mb-2"><i class="bi ' + (isOnline ? 'bi-camera-video' : 'bi-geo-alt') + ' me-2 text-primary"></i>' + previewEsc(locationText) + '</div>' +
                (val('video') ? '<div class="mb-2"><i class="bi bi-play-circle me-2 text-primary"></i><a href="' + previewEsc(val('video')) + '" target="_blank" rel="noopener">' + previewEsc(val('video')) + '</a></div>' : '') +
                '<hr>' +
                '<div class="agenda-preview-content">' + (content || '<span class="text-muted"><em>Isi agenda belum diisi</em></span>') + '</div>' +
                tagsHtml +
            '</div>';

        const modalEl = document.getElementById('agendaPreviewModal');
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (window.jQuery) {
            jQuery(modalEl).modal('show');
        }
    }
</script>

<?php $__env->startPush('after-script'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category_uuid');
            const videoForm = document.getElementById('videoForm');
            const videoInput = document.getElementById('video');
            const jenisSelect = document.getElementById('jenis_agenda');
            const lokasiWrap = document.getElementById('lokasiWrap');
            const urlWrap = document.getElementById('urlWrap');
            const lokasiStar = document.getElementById('lokasiStar');
            const urlStar = document.getElementById('urlStar');

            function toggleVideoForm() {
                if (!categorySelect) return;
                const selected = categorySelect.options[categorySelect.selectedIndex];
                const categoryName = selected?.getAttribute('data-name')?.toLowerCase() || '';

                if (categoryName.includes('video-galeri')) {
                    videoForm.style.display = 'block';
                } else {
                    videoForm.style.display = 'none';
                    if (videoInput) videoInput.value = '';
                }
            }

            function toggleJenis() {
                if (!jenisSelect) return;
                const isOnline = jenisSelect.value === 'online';
                // Online: URL wajib & menonjol, lokasi disembunyikan
                if (lokasiWrap) lokasiWrap.style.display = isOnline ? 'none' : '';
                if (urlWrap) urlWrap.classList.toggle('col-12', true);
                if (lokasiStar) lokasiStar.style.display = isOnline ? 'none' : '';
                if (urlStar) urlStar.style.display = isOnline ? '' : 'none';
            }

            // Validasi ringan waktu akhir >= waktu mulai
            const mulai = document.getElementById('waktu_mulai');
            const akhir = document.getElementById('waktu_akhir');
            if (mulai && akhir) {
                akhir.addEventListener('change', function () {
                    if (mulai.value && akhir.value && akhir.value < mulai.value) {
                        akhir.setCustomValidity('Waktu akhir tidak boleh lebih awal dari waktu mulai.');
                        akhir.reportValidity();
                    } else {
                        akhir.setCustomValidity('');
                    }
                });
                mulai.addEventListener('change', function () {
                    akhir.setCustomValidity('');
                    if (akhir.value) akhir.dispatchEvent(new Event('change'));
                });
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', toggleVideoForm);
                toggleVideoForm();
            }

            if (jenisSelect) {
                jenisSelect.addEventListener('change', toggleJenis);
                toggleJenis();
            }

            // Hapus foto yang sudah tersimpan
            document.querySelectorAll('.agenda-photo-remove').forEach(function (button) {
                button.addEventListener('click', function () {
                    const item = this.closest('.agenda-photo-item');
                    const checkbox = item.querySelector('.agenda-photo-remove-input');
                    checkbox.checked = !checkbox.checked;
                    item.classList.toggle('is-removed', checkbox.checked);
                });
            });

            // Preview foto yang baru dipilih (akumulatif: tiap pilih bisa nambah terus)
            const input = document.getElementById('agendaImages');
            const preview = document.getElementById('agendaImagesPreview');

                preview.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    const dragging = preview.querySelector('.dragging');
                    if (!dragging) return;
                    const afterElement = Array.from(preview.querySelectorAll('.agenda-photo-item:not(.dragging)'))
                        .find(function (el) { return e.clientY < el.getBoundingClientRect().top + el.offsetHeight / 2; });
                    if (afterElement) { preview.insertBefore(dragging, afterElement); } else { preview.appendChild(dragging); }
                });

                if (input && preview) {
                let pendingFiles = [];

                function syncInputFiles() {
                    const dt = new DataTransfer();
                    pendingFiles.forEach(function (file) {
                        dt.items.add(file);
                    });
                    input.files = dt.files;
                }

                function renderPreview() {
                    preview.innerHTML = '';

                    if (pendingFiles.length === 0) return;

                    pendingFiles.forEach(function (file, index) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            const item = document.createElement('div');
                            item.className = 'agenda-photo-item';
                            item.draggable = true;
                            item.dataset.index = index;
                            item.innerHTML = '<img src="' + event.target.result + '" alt="preview">' +
                                '<button type="button" class="agenda-photo-remove" title="Batalkan foto">' +
                                '<i class="bi bi-x-lg"></i></button>';

                            item.addEventListener('dragstart', function () { item.classList.add('dragging'); });
                            item.addEventListener('dragend', function () {
                                item.classList.remove('dragging');
                                const newOrder = Array.from(preview.children).map(function (el) {
                                    return pendingFiles[parseInt(el.dataset.index, 10)];
                                });
                                pendingFiles = newOrder.filter(function (f) { return !!f; });
                                syncInputFiles();
                                renderPreview();
                            });

                            item.querySelector('.agenda-photo-remove').addEventListener('click', function () {
                                pendingFiles.splice(index, 1);
                                syncInputFiles();
                                renderPreview();
                            });

                            preview.appendChild(item);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                input.addEventListener('change', function () {
                    Array.from(this.files).forEach(function (file) {
                        if (!file.type.startsWith('image/')) return;
                        pendingFiles.push(file);
                    });

                    syncInputFiles();
                    renderPreview();
                });
            }
        });

        const existingGrid = document.getElementById('agendaExistingImages');
        if (existingGrid) {
            let dragEl = null;
            existingGrid.querySelectorAll('.agenda-photo-item').forEach(function (item) {
                item.addEventListener('dragstart', function () { dragEl = item; item.classList.add('dragging'); });
                item.addEventListener('dragend', function () {
                    item.classList.remove('dragging');
                    dragEl = null;
                    existingGrid.querySelectorAll('input[name="image_order[]"]').forEach(function (input, idx) {
                        input.value = existingGrid.children[idx].dataset.uuid;
                    });
                });
            });
            existingGrid.addEventListener('dragover', function (e) {
                e.preventDefault();
                if (!dragEl) return;
                const after = Array.from(existingGrid.querySelectorAll('.agenda-photo-item:not(.dragging)'))
                    .find(function (el) { return e.clientX < el.getBoundingClientRect().left + el.offsetWidth / 2; });
                if (after) { existingGrid.insertBefore(dragEl, after); } else { existingGrid.appendChild(dragEl); }
            });
        }

        const bulkBtn = document.getElementById('bulkDeleteAttachments');
        if (bulkBtn) {
            bulkBtn.addEventListener('click', function () {
                const checked = Array.from(document.querySelectorAll('.attachment-check:checked'));
                if (!checked.length) { alert('Pilih lampiran dulu.'); return; }
                if (!confirm('Hapus ' + checked.length + ' lampiran terpilih?')) return;
                checked.forEach(function (cb) {
                    fetch(cb.value, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
                        .then(function (r) { if (r.ok) { cb.closest('[data-attachment-row]').remove(); } });
                });
            });
        }

        document.querySelectorAll('.attachment-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!confirm('Hapus lampiran ini?')) return;
                fetch(btn.dataset.url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                }).then(function (r) {
                    if (r.ok) {
                        btn.closest('[data-attachment-row]').remove();
                    } else {
                        alert('Gagal menghapus lampiran.');
                    }
                }).catch(function () { alert('Gagal menghapus lampiran.'); });
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views/pages/agendas/partials/form.blade.php ENDPATH**/ ?>