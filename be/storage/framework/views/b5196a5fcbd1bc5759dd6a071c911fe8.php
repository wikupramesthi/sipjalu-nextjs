<?php $__env->startSection('title', 'Detail Berita'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Detail Berita','page' => 'Berita','active' => 'Detail Berita','route' => ''.e(route('beritas.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<style>
    .berita-detail-content { overflow-wrap: break-word; word-break: break-word; }
    .berita-detail-content img { max-width: 100%; height: auto; border-radius: 10px; }
    .berita-detail-content table { width: 100%; max-width: 100%; display: block; overflow-x: auto; }
    .berita-detail-content iframe { max-width: 100%; }
    @media (max-width: 767.98px) {
        .berita-detail-content { font-size: .9rem; }
        .berita-detail-image { max-height: 220px !important; }
    }
</style>

<section class="section">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <a href="<?php echo e(route('beritas.index')); ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <div class="d-flex flex-wrap gap-1">
            <?php if($berita->status === 'published'): ?>
                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
            <?php elseif($berita->status === 'pending'): ?>
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i>Pending Approval</span>
            <?php elseif($berita->status === 'draft'): ?>
                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
            <?php else: ?>
                <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle">Scheduled</span>
            <?php endif; ?>
            <?php if($berita->is_featured): ?>
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-star-fill me-1"></i>Featured</span>
            <?php endif; ?>
            <?php if($berita->is_popular): ?>
                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-fire me-1"></i>Populer</span>
            <?php endif; ?>
            <?php if($berita->is_urgent): ?>
                <span class="badge rounded-pill bg-danger text-white"><i class="bi bi-exclamation-circle me-1"></i>Urgent</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <?php if($berita->featured_image): ?>
                    <img src="<?php echo e(asset('storage/' . $berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>"
                        class="card-img-top berita-detail-image" style="max-height:360px;object-fit:cover;border-top-left-radius:.7rem;border-top-right-radius:.7rem;">
                <?php endif; ?>
                <div class="card-body">
                    <h4 class="fw-bold mb-2"><?php echo e($berita->title); ?></h4>
                    <?php if($berita->excerpt): ?>
                        <p class="text-muted"><?php echo e($berita->excerpt); ?></p>
                    <?php endif; ?>
                    <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                        <span><i class="bi bi-folder2-open me-1 text-primary"></i><?php echo e($berita->category?->name ?? '-'); ?></span>
                        <span><i class="bi bi-person me-1 text-primary"></i><?php echo e($berita->user?->name ?? '-'); ?></span>
                        <span><i class="bi bi-eye me-1 text-primary"></i><?php echo e(number_format($berita->views)); ?> dilihat</span>
                        <span><i class="bi bi-calendar3 me-1 text-primary"></i><?php echo e(optional($berita->scheduled_at ?? $berita->created_at)->format('d M Y') ?? '-'); ?></span>
                    </div>
                    <hr>
                    <div class="berita-detail-content"><?php echo $berita->content; ?></div>
                    <?php if($berita->tagging): ?>
                        <hr>
                        <div class="d-flex flex-wrap gap-1">
                            <?php $__currentLoopData = explode(',', $berita->tagging); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-secondary border">#<?php echo e(trim($tag)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($berita->video): ?>
                        <hr>
                        <div><i class="bi bi-play-circle me-1 text-primary"></i><a href="<?php echo e($berita->video); ?>" target="_blank" rel="noopener noreferrer">Lihat video berita</a></div>
                    <?php endif; ?>
                    <hr>
                    <?php ($shareUrl = urlencode(request()->fullUrl())); ?>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="small text-muted align-self-center">Bagikan:</span>
                        <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo e($shareUrl); ?>"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        <a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" href="mailto:?subject=<?php echo e(urlencode($berita->title)); ?>&body=<?php echo e($shareUrl); ?>"><i class="bi bi-envelope"></i> Email</a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(window.location.href).then(() => this.innerText = 'Tersalin!')"><i class="bi bi-link-45deg"></i> Salin Link</button>
                    </div>
                </div>
            </div>

            <?php if($berita->images->isNotEmpty()): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-images me-1 text-warning"></i>Foto Slider (<?php echo e($berita->images->count()); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <?php $__currentLoopData = $berita->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4">
                                    <a href="<?php echo e($image->url()); ?>" target="_blank" rel="noopener">
                                        <img src="<?php echo e($image->url()); ?>" alt="Foto berita" class="img-fluid rounded border" style="height:130px;width:100%;object-fit:cover;" loading="lazy">
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($berita->attachments->isNotEmpty()): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-paperclip me-1 text-primary"></i>Lampiran (<?php echo e($berita->attachments->count()); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php $__currentLoopData = $berita->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center gap-2">
                                    <a href="<?php echo e($file->url()); ?>" target="_blank" rel="noopener"><i class="bi bi-file-earmark-arrow-down me-1"></i><?php echo e($file->nama_asli); ?></a>
                                    <span class="badge bg-light text-dark border"><?php echo e($file->ukuran_label); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-send me-1 text-primary"></i>Publikasi</h5>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Status</span><strong class="text-capitalize"><?php echo e($berita->status); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Tanggal terbit</span><strong><?php echo e(optional($berita->scheduled_at)->format('d M Y, H:i') ?? '-'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Search engine</span><strong class="text-capitalize"><?php echo e($berita->search_engine ?? 'index'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Featured</span><strong><?php echo e($berita->is_featured ? 'Ya' : 'Tidak'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Populer</span><strong><?php echo e($berita->is_popular ? 'Ya' : 'Tidak'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Urgent</span><strong><?php echo e($berita->is_urgent ? 'Ya' : 'Tidak'); ?></strong></li>
                </ul>
            </div>

            <div class="d-grid gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.approve')): ?>
                    <?php if($berita->status === 'pending'): ?>
                        <form action="<?php echo e(route('beritas.approve', $berita->uuid)); ?>" method="POST" class="d-grid" data-confirm="Setujui dan publikasikan berita ini?">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Setujui &amp; Publikasikan</button>
                        </form>
                        <form action="<?php echo e(route('beritas.reject', $berita->uuid)); ?>" method="POST" class="d-grid">
                            <?php echo csrf_field(); ?>
                            <label for="catatanTolakBerita" class="form-label small fw-semibold mb-1">Alasan penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatanTolakBerita" rows="2" maxlength="500" class="form-control form-control-sm mb-2" placeholder="Tuliskan alasan untuk penulis..." required></textarea>
                            <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-arrow-counterclockwise me-1"></i>Tolak ke Draft</button>
                        </form>
                        <form action="<?php echo e(route('beritas.mintaRevisi', $berita->uuid)); ?>" method="POST" class="d-grid">
                            <?php echo csrf_field(); ?>
                            <label for="catatanRevisiBerita" class="form-label small fw-semibold mb-1">Catatan revisi <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatanRevisiBerita" rows="2" maxlength="500" class="form-control form-control-sm mb-2" placeholder="Tuliskan bagian yang perlu direvisi..." required></textarea>
                            <button type="submit" class="btn btn-info text-dark"><i class="bi bi-pencil-square me-1"></i>Minta Revisi</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.update')): ?>
                    <a href="<?php echo e(route('beritas.edit', $berita->uuid)); ?>" class="btn btn-success"><i class="bi bi-pencil me-1"></i>Edit Berita</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.destroy')): ?>
                    <form action="<?php echo e(route('beritas.destroy', $berita->uuid)); ?>" method="POST" class="d-grid" data-confirm="Pindahkan berita ini ke arsip?">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Arsipkan</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if($logs->isNotEmpty()): ?>
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-clock-history me-1 text-secondary"></i>Riwayat</h5>
                    </div>
                    <ul class="list-group list-group-flush small">
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <span class="badge <?php echo e($log->event_badge); ?>"><?php echo e($log->event_label); ?></span>
                                <span class="d-block mt-1"><?php echo e($log->user_name ?? 'Sistem'); ?> &middot; <?php echo e($log->created_at->translatedFormat('d M Y, H:i')); ?></span>
                                <?php if(isset($log->new_values['status'])): ?>
                                    <span class="text-muted d-block">Status: <?php echo e($log->new_values['status']); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-clock-history me-1 text-secondary"></i>Riwayat</h5>
                    </div>
                    <div class="card-body py-3"><small class="text-muted">Belum ada riwayat tercatat.</small></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\beritas\show.blade.php ENDPATH**/ ?>