<?php $__env->startSection('title', 'Detail Agenda'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Detail Agenda','page' => 'Agenda','active' => 'Detail Agenda','route' => ''.e(route('agendas.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<?php
    $jenis = $agenda->jenis_agenda ?? 'offline';
?>

<style>
    .agenda-detail-content { overflow-wrap: break-word; word-break: break-word; }
    .agenda-detail-content img { max-width: 100%; height: auto; border-radius: 10px; }
    .agenda-detail-content table { width: 100%; max-width: 100%; display: block; overflow-x: auto; }
    .agenda-detail-content iframe { max-width: 100%; }
    @media (max-width: 767.98px) {
        .agenda-detail-content { font-size: .9rem; }
        .card-img-top { max-height: 220px !important; }
    }
</style>

<section class="section">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <a href="<?php echo e(route('agendas.index')); ?>" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <div class="d-flex flex-wrap gap-1">
            <?php if($agenda->status==='published'): ?>
                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
            <?php elseif($agenda->status==='pending'): ?>
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i>Pending Approval</span>
            <?php elseif($agenda->status==='draft'): ?>
                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
            <?php else: ?>
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Scheduled</span>
            <?php endif; ?>
            <?php if($agenda->trashed()): ?>
                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-archive me-1"></i>Diarsipkan<?php echo e($agenda->alasan_arsip ? ' · ' . ucfirst($agenda->alasan_arsip) : ''); ?></span>
            <?php endif; ?>
            <?php if($agenda->is_selesai): ?>
                <span class="badge rounded-pill bg-secondary text-white" title="Waktu kegiatan sudah lewat">Selesai</span>
            <?php endif; ?>
            <span class="badge rounded-pill <?php echo e($jenis === 'online' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle'); ?>"><i class="bi <?php echo e($jenis === 'online' ? 'bi-globe' : 'bi-geo-alt'); ?> me-1"></i><?php echo e($jenis === 'online' ? 'Online' : 'Offline'); ?></span>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <?php if($agenda->thumbnail_url): ?>
                    <img src="<?php echo e($agenda->thumbnail_url); ?>" alt="<?php echo e($agenda->title); ?>" class="card-img-top" style="max-height:360px;object-fit:cover;border-top-left-radius:.7rem;border-top-right-radius:.7rem;">
                <?php endif; ?>
                <div class="card-body">
                    <h4 class="fw-bold mb-2"><?php echo e($agenda->title); ?></h4>
                    <?php if($agenda->excerpt): ?>
                        <p class="text-muted"><?php echo e($agenda->excerpt); ?></p>
                    <?php endif; ?>
                    <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                        <span><i class="bi bi-folder2-open me-1 text-primary"></i><?php echo e($agenda->category->name ?? '-'); ?></span>
                        <span><i class="bi bi-person me-1 text-primary"></i><?php echo e($agenda->user->name ?? '-'); ?></span>
                        <span><i class="bi bi-eye me-1 text-primary"></i><?php echo e(number_format($agenda->views)); ?> dilihat</span>
                        <span><i class="bi bi-calendar3 me-1 text-primary"></i><?php echo e(optional($agenda->scheduled_at)->format('d M Y') ?? '-'); ?></span>
                    </div>
                    <hr>
                    <div class="agenda-detail-content">
                        <?php echo $agenda->content; ?>

                    </div>
                    <?php if($agenda->tagging): ?>
                        <hr>
                        <div class="d-flex flex-wrap gap-1">
                            <?php $__currentLoopData = explode(',', $agenda->tagging); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-secondary border">#<?php echo e(trim($tag)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <?php ($shareUrl = urlencode(request()->fullUrl())); ?>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="small text-muted align-self-center">Bagikan:</span>
                        <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo e($shareUrl); ?>"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        <a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" href="mailto:?subject=<?php echo e(urlencode($agenda->title)); ?>&body=<?php echo e($shareUrl); ?>"><i class="bi bi-envelope"></i> Email</a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(window.location.href).then(() => this.innerText = 'Tersalin!')"><i class="bi bi-link-45deg"></i> Salin Link</button>
                    </div>
                </div>
            </div>

            <?php if($agenda->images->isNotEmpty()): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-images me-1 text-warning"></i>Foto Kegiatan (<?php echo e($agenda->images->count()); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <?php $__currentLoopData = $agenda->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4">
                                    <a href="<?php echo e($image->url()); ?>" target="_blank" rel="noopener">
                                        <img src="<?php echo e($image->url()); ?>" alt="Foto agenda" class="img-fluid rounded border" style="height:130px;width:100%;object-fit:cover;" loading="lazy">
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($agenda->attachments->isNotEmpty()): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-paperclip me-1 text-primary"></i>Lampiran (<?php echo e($agenda->attachments->count()); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php $__currentLoopData = $agenda->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php ($ext = strtolower(pathinfo($file->nama_asli, PATHINFO_EXTENSION))); ?>
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?php echo e($file->url()); ?>" target="_blank" rel="noopener"><i class="bi bi-file-earmark-arrow-down me-1"></i><?php echo e($file->nama_asli); ?></a>
                                        <span class="badge bg-light text-dark border"><?php echo e($file->ukuran_label); ?></span>
                                    </div>
                                    <?php if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])): ?>
                                        <img src="<?php echo e($file->url()); ?>" alt="<?php echo e($file->nama_asli); ?>" class="img-fluid rounded border mt-2" style="max-height:200px" loading="lazy">
                                    <?php elseif($ext === 'pdf'): ?>
                                        <details class="mt-2">
                                            <summary class="small text-primary"><i class="bi bi-eye me-1"></i>Pratinjau PDF</summary>
                                            <iframe src="<?php echo e($file->url()); ?>" style="width:100%;height:480px;border:1px solid #e9eef5;border-radius:.5rem"></iframe>
                                        </details>
                                    <?php endif; ?>
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
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-event me-1 text-success"></i>Pelaksanaan</h5>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Jenis</span><strong><?php echo e($jenis === 'online' ? 'Online' : 'Offline'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Mulai</span><strong><?php echo e(optional($agenda->waktu_mulai)->format('d M Y, H:i') ?? '-'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Selesai</span><strong><?php echo e(optional($agenda->waktu_akhir)->format('d M Y, H:i') ?? '-'); ?></strong></li>
                    <?php if($agenda->lokasi): ?>
                        <li class="list-group-item"><span class="text-muted d-block mb-1"><i class="bi bi-pin-map me-1"></i>Lokasi</span><?php echo e($agenda->lokasi); ?></li>
                    <?php endif; ?>
                    <?php if($agenda->url): ?>
                        <li class="list-group-item"><span class="text-muted d-block mb-1"><i class="bi bi-link-45deg me-1"></i>Tautan</span><a href="<?php echo e($agenda->url); ?>" target="_blank" rel="noopener" class="text-break"><?php echo e($agenda->url); ?></a></li>
                    <?php endif; ?>
                    <?php if($agenda->video): ?>
                        <li class="list-group-item"><span class="text-muted d-block mb-1"><i class="bi bi-youtube me-1"></i>Video</span><span class="text-break"><?php echo e($agenda->video); ?></span></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-send me-1 text-primary"></i>Publikasi</h5>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Status</span><strong class="text-capitalize"><?php echo e($agenda->status); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Tanggal terbit</span><strong><?php echo e(optional($agenda->scheduled_at)->format('d M Y') ?? '-'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Search engine</span><strong class="text-capitalize"><?php echo e($agenda->search_engine ?? 'index'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Featured</span><strong><?php echo e($agenda->is_featured ? 'Ya' : 'Tidak'); ?></strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Populer</span><strong><?php echo e($agenda->is_popular ? 'Ya' : 'Tidak'); ?></strong></li>
                </ul>
            </div>

            <div class="d-grid gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.approve')): ?>
                    <?php if($agenda->status==='pending'): ?>
                        <form action="<?php echo e(route('agendas.approve', $agenda->uuid)); ?>" method="POST" class="d-grid"><?php echo csrf_field(); ?><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Setujui &amp; Publikasikan</button></form>
                        <form action="<?php echo e(route('agendas.reject', $agenda->uuid)); ?>" method="POST" class="d-grid">
                            <?php echo csrf_field(); ?>
                            <label for="catatanTolak" class="form-label small fw-semibold mb-1">Alasan penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatanTolak" rows="2" maxlength="500" class="form-control form-control-sm mb-2" placeholder="Contoh: lokasi belum jelas, mohon dilengkapi..." required></textarea>
                            <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-x-lg me-1"></i>Tolak ke Draft</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.update')): ?>
                    <a href="<?php echo e(route('agendas.edit', $agenda->uuid)); ?>" class="btn btn-success"><i class="bi bi-pencil me-1"></i>Edit Agenda</a>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'super-admin|admin')): ?>
                    <form action="<?php echo e(route('agendas.duplicate', $agenda->uuid)); ?>" method="POST" class="d-grid"><?php echo csrf_field(); ?><button type="submit" class="btn btn-secondary"><i class="bi bi-copy me-1"></i>Duplikat Agenda</button></form>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.destroy')): ?>
                    <?php if($agenda->trashed()): ?>
                        <form action="<?php echo e(route('agendas.restore', $agenda->uuid)); ?>" method="POST" class="d-grid"><?php echo method_field('PUT'); ?><?php echo csrf_field(); ?><button type="submit" class="btn btn-success"><i class="bi bi-arrow-counterclockwise me-1"></i>Pulihkan dari Arsip</button></form>
                    <?php else: ?>
                        <form action="<?php echo e(route('agendas.destroy', $agenda->uuid)); ?>" method="POST" class="d-grid" onsubmit="return confirm('Arsipkan agenda ini?');"><?php echo method_field('DELETE'); ?><?php echo csrf_field(); ?><button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Arsipkan</button></form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if(($seri ?? collect())->isNotEmpty()): ?>
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-calendar2-week me-1 text-info"></i>Seri Berulang (<?php echo e($seri->count()); ?>)</h5>
                    </div>
                    <ul class="list-group list-group-flush small">
                        <?php $__currentLoopData = $seri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                                <div>
                                    <a href="<?php echo e(route('agendas.show', $s->uuid)); ?>"><?php echo e(Str::limit($s->title, 45)); ?></a>
                                    <span class="text-muted d-block"><?php echo e(optional($s->waktu_mulai)->translatedFormat('d M Y, H:i') ?? '-'); ?></span>
                                </div>
                                <span class="badge bg-light text-secondary border text-capitalize"><?php echo e($s->status); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history me-1 text-secondary"></i>Riwayat</h5>
                </div>                <?php if($agenda->logs->isNotEmpty()): ?>
                    <ul class="list-group list-group-flush small">
                        <?php $__currentLoopData = $agenda->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <span class="badge bg-light text-secondary border"><?php echo e($log->label); ?></span>
                                <span class="d-block mt-1"><?php echo e($log->user->name ?? 'Sistem'); ?> &middot; <?php echo e($log->created_at->translatedFormat('d M Y, H:i')); ?></span>
                                <?php if($log->catatan): ?>
                                    <span class="text-muted d-block"><?php echo e($log->catatan); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <div class="card-body py-3"><small class="text-muted">Belum ada riwayat tercatat.</small></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\agendas\show.blade.php ENDPATH**/ ?>