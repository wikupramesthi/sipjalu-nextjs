<?php $__env->startSection('title', 'Edit Aduan'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Edit Aduan','page' => 'Aduan','active' => ''.e($item->nomor_aduan).'','route' => ''.e(route('aduans.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

    <form action="<?php echo e(route('aduans.update', $item->uuid)); ?>" method="POST" enctype="multipart/form-data">

        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row g-4">

            
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">Informasi Aduan <code><?php echo e($item->nomor_aduan); ?></code></h5>
                        <p class="text-muted small mb-4">Perbarui detail aduan yang dilaporkan.</p>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="kategori" class="form-label fw-semibold">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select id="kategori" name="kategori"
                                    class="form-select <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php $__currentLoopData = \App\Models\Aduan::KATEGORI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kat); ?>" <?php echo e(old('kategori', $item->kategori) == $kat ? 'selected' : ''); ?>>
                                        <?php echo e($kat); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['kategori'];
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

                            <div class="col-md-6 mb-4">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="judul" name="judul"
                                    class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('judul', $item->judul)); ?>" required>
                                <?php $__errorArgs = ['judul'];
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

                        <div class="mb-4">
                            <label for="isi_aduan" class="form-label fw-semibold">
                                Isi Aduan <span class="text-danger">*</span>
                            </label>
                            <textarea id="isi_aduan" name="isi_aduan" rows="5"
                                class="form-control <?php $__errorArgs = ['isi_aduan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required><?php echo e(old('isi_aduan', $item->isi_aduan)); ?></textarea>
                            <?php $__errorArgs = ['isi_aduan'];
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

                        <div class="mb-4">
                            <label for="lokasi" class="form-label fw-semibold">Alamat / Lokasi</label>
                            <input type="text" id="lokasi" name="lokasi"
                                class="form-control <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('lokasi', $item->lokasi)); ?>"
                                placeholder="Terisi otomatis dari peta, bisa diubah manual">
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

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="tanggal_kejadian" class="form-label fw-semibold">Tanggal Kejadian</label>
                                <input type="date" id="tanggal_kejadian" name="tanggal_kejadian"
                                    class="form-control <?php $__errorArgs = ['tanggal_kejadian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('tanggal_kejadian', $item->tanggal_kejadian?->format('Y-m-d'))); ?>">
                                <?php $__errorArgs = ['tanggal_kejadian'];
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

                            <div class="col-md-6 mb-4">
                                <label for="tanggal_pengaduan" class="form-label fw-semibold">Tanggal & Jam Pengaduan</label>
                                <input type="datetime-local" id="tanggal_pengaduan" name="tanggal_pengaduan"
                                    class="form-control <?php $__errorArgs = ['tanggal_pengaduan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('tanggal_pengaduan', $item->tanggal_pengaduan?->format('Y-m-d\\TH:i'))); ?>">
                                <?php $__errorArgs = ['tanggal_pengaduan'];
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

                        <div class="row">
                            <div class="col-md-6 mb-0">
                                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                                <input type="text" id="latitude" name="latitude"
                                    class="form-control <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('latitude', $item->latitude)); ?>">
                                <?php $__errorArgs = ['latitude'];
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

                            <div class="col-md-6 mb-0">
                                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                                <input type="text" id="longitude" name="longitude"
                                    class="form-control <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('longitude', $item->longitude)); ?>">
                                <?php $__errorArgs = ['longitude'];
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
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Foto Bukti</h5>
                        <p class="text-muted small mb-4">JPG, JPEG, PNG, WEBP. Maksimal 2 MB per foto. Upload baru untuk mengganti, atau centang hapus.</p>

                        <div class="row">
                            <?php $__currentLoopData = ['foto_1' => 'Foto 1', 'foto_2' => 'Foto 2', 'foto_3' => 'Foto 3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 mb-3">
                                <label for="<?php echo e($field); ?>" class="form-label fw-semibold"><?php echo e($label); ?></label>
                                <?php if($item->{$field}): ?>
                                <div class="mb-2">
                                    <img src="<?php echo e(asset('storage/' . $item->{$field})); ?>" alt="<?php echo e($label); ?>"
                                        class="img-thumbnail w-100" style="max-height: 140px; object-fit: cover;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="hapus_<?php echo e($field); ?>"
                                            value="1" id="hapus_<?php echo e($field); ?>">
                                        <label class="form-check-label" for="hapus_<?php echo e($field); ?>">Hapus foto</label>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <input type="file" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>"
                                    class="form-control <?php $__errorArgs = [$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    accept="image/jpg,image/jpeg,image/png,image/webp">
                                <?php $__errorArgs = [$field];
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <div id="duplikat-warning" class="alert alert-warning d-none mt-4">
                    <strong><i class="bi bi-exclamation-triangle me-1"></i> Ditemukan laporan serupa!</strong>
                    <span>Kemungkinan duplikat — periksa dulu sebelum menyimpan agar tidak dobel penanganan:</span>
                    <ul id="duplikat-list" class="mb-0 mt-2"></ul>
                </div>

                <?php echo $__env->make('pages.aduan.map-picker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </div>

            
            <div class="col-12 col-lg-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Privasi Pelapor</h5>
                        <p class="text-muted small mb-3">Pilih apakah nama pelapor ditampilkan ke publik.</p>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="is_anonim" id="tampil_nama"
                                value="0" <?php echo e(old('is_anonim', $item->is_anonim) == '0' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="tampil_nama">
                                <strong>Tampilkan nama</strong><br>
                                <small class="text-muted">Nama pelapor terlihat di daftar publik.</small>
                            </label>
                        </div>

                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="is_anonim" id="lapor_anonim"
                                value="1" <?php echo e(old('is_anonim', $item->is_anonim) == '1' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="lapor_anonim">
                                <strong>Anonim</strong><br>
                                <small class="text-muted">Nama disembunyikan, tampil sebagai "Anonim".</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Wilayah</h5>
                        <p class="text-muted small mb-4">Kecamatan dan kelurahan lokasi kejadian.</p>

                        <div class="mb-3">
                            <label for="kecamatan_id" class="form-label fw-semibold">Kecamatan</label>
                            <select id="kecamatan_id" name="kecamatan_id"
                                class="form-select <?php $__errorArgs = ['kecamatan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">-- Pilih Kecamatan --</option>
                                <?php $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kec->id); ?>" <?php echo e(old('kecamatan_id', $item->kecamatan_id) == $kec->id ? 'selected' : ''); ?>>
                                    <?php echo e($kec->nama); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['kecamatan_id'];
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
                            <label for="kelurahan_id" class="form-label fw-semibold">Kelurahan</label>
                            <select id="kelurahan_id" name="kelurahan_id"
                                class="form-select <?php $__errorArgs = ['kelurahan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">-- Pilih Kelurahan --</option>
                                <?php $__currentLoopData = $kelurahans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kel->id); ?>" <?php echo e(old('kelurahan_id', $item->kelurahan_id) == $kel->id ? 'selected' : ''); ?>>
                                    <?php echo e($kel->nama); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['kelurahan_id'];
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

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Penanganan</h5>
                        <p class="text-muted small mb-4">Status, prioritas, dan sifat aduan.</p>

                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin|admin')): ?>
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status"
                                class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php $__currentLoopData = ['menunggu','diverifikasi','diproses','selesai','ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($st); ?>" <?php echo e(old('status', $item->status) == $st ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($st)); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
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
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="prioritas" class="form-label fw-semibold">Prioritas <span class="text-danger">*</span></label>
                            <select id="prioritas" name="prioritas"
                                class="form-select <?php $__errorArgs = ['prioritas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php $__currentLoopData = ['rendah','sedang','tinggi','darurat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pr); ?>" <?php echo e(old('prioritas', $item->prioritas) == $pr ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($pr)); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['prioritas'];
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
                            <label for="sifat" class="form-label fw-semibold">Sifat <span class="text-danger">*</span></label>
                            <select id="sifat" name="sifat"
                                class="form-select <?php $__errorArgs = ['sifat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php $__currentLoopData = ['biasa','penting','segera','rahasia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sf); ?>" <?php echo e(old('sifat', $item->sifat) == $sf ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($sf)); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['sifat'];
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

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Aduan
                        </button>
                        <a href="<?php echo e(route('aduans.index')); ?>" class="btn btn-light w-100">Batal</a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-lightbulb me-1 text-warning"></i> Tips Pengaduan
                        </h5>
                        <p class="text-muted small mb-3">
                            Laporan yang lengkap akan lebih cepat ditangani petugas.
                        </p>
                        <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Gunakan judul yang jelas, mis. lokasi + masalahnya.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Jelaskan kronologi: kapan terjadi, seberapa parah, dan dampaknya.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Pilih kategori dan wilayah yang tepat agar diteruskan ke bidang yang benar.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Lampirkan foto bukti yang jelas (maksimal 3 foto).</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Tandai titik lokasi di peta seakurat mungkin.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Satu laporan untuk satu masalah, lalu simpan nomor aduan untuk melacak status.</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </form>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kecSelect = document.getElementById('kecamatan_id');
        const kelSelect = document.getElementById('kelurahan_id');
        const currentKel = "<?php echo e(old('kelurahan_id', $item->kelurahan_id)); ?>";

        kecSelect.addEventListener('change', function() {
            const id = this.value;
            kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            if (!id) return;
            fetch("<?php echo e(url('backend/aduans/kelurahan')); ?>/" + id)
                .then(r => r.json())
                .then(data => {
                    Object.entries(data).forEach(([val, label]) => {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = label;
                        if (String(val) === String(currentKel)) opt.selected = true;
                        kelSelect.appendChild(opt);
                    });
                });
        });
    });
</script>

<script>
    // Peringatan duplikat saat mengisi form (kecualikan data ini sendiri)
    document.addEventListener('DOMContentLoaded', function() {
        const box = document.getElementById('duplikat-warning');
        const list = document.getElementById('duplikat-list');
        if (!box || !list) return;

        let timer = null;
        const cekDuplikat = () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const params = new URLSearchParams({
                    kategori: document.getElementById('kategori')?.value || '',
                    latitude: document.getElementById('latitude')?.value || '',
                    longitude: document.getElementById('longitude')?.value || '',
                    kecamatan_id: document.getElementById('kecamatan_id')?.value || '',
                    tanggal_kejadian: document.getElementById('tanggal_kejadian')?.value || '',
                    kecuali_uuid: "<?php echo e($item->uuid); ?>",
                });
                fetch("<?php echo e(url('backend/aduans/cek-duplikat')); ?>?" + params.toString())
                    .then(r => r.json())
                    .then(res => {
                        const data = res.data || [];
                        if (data.length === 0) {
                            box.classList.add('d-none');
                            return;
                        }
                        list.innerHTML = '';
                        data.forEach(d => {
                            const li = document.createElement('li');
                            const a = document.createElement('a');
                            a.href = d.url;
                            a.target = '_blank';
                            a.textContent = d.nomor_aduan + ' — ' + d.judul + ' (' + d.status + ', ' + d.tanggal + ')';
                            li.appendChild(a);
                            list.appendChild(li);
                        });
                        box.classList.remove('d-none');
                    })
                    .catch(() => {});
            }, 600);
        };

        ['kategori', 'latitude', 'longitude', 'kecamatan_id', 'tanggal_kejadian'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', cekDuplikat);
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\aduan\edit.blade.php ENDPATH**/ ?>