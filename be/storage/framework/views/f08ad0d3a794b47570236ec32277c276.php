<?php $__env->startSection('title', 'Kelola Berita'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Berita','page' => 'Publikasi','active' => ''.e($trash ? 'Arsip Berita' : 'Daftar Berita').'','route' => ''.e(route('beritas.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    .berita-actions { display: flex; justify-content: center; align-items: center; gap: 6px; white-space: nowrap; }
    .berita-actions form { display: inline-flex; margin: 0; }
    .btn-berita-action {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        box-shadow: 0 2px 5px rgba(30, 41, 59, .12);
    }
    .btn-berita-view { color: #087990; background: #cff4fc; border-color: #9eeaf9; }
    .btn-berita-edit { color: #146c43; background: #d1e7dd; border-color: #a3cfbb; }
    .btn-berita-copy { color: #495057; background: #e2e3e5; border-color: #c6c7c8; }
    .btn-berita-archive { color: #fff; background: #f87171; border-color: #f87171; }
    .btn-berita-restore { color: #146c43; background: #d1e7dd; border-color: #a3cfbb; }
    .btn-berita-force { color: #fff; background: #f87171; border-color: #f87171; }
    .btn-berita-action:hover { filter: brightness(.96); transform: translateY(-1px); }
    .berita-stat-card { color: inherit; text-decoration: none; }
    .berita-stat-card .card { transition: transform .15s ease, box-shadow .15s ease; }
    .berita-stat-card:hover .card { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important; }
    .berita-stat-icon {
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }
</style>

<section class="section">
    <?php $__currentLoopData = ['success' => 'success', 'error' => 'danger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(session($key)): ?>
            <div class="alert alert-<?php echo e($class); ?> alert-dismissible fade show" role="alert">
                <?php echo e(session($key)); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e($errors->first()); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(! $trash): ?>
        <div class="row g-3 mb-3">
            <?php
                $statCards = [
                    ['label' => 'Total Berita', 'value' => $stats['total'], 'icon' => 'bi-newspaper', 'color' => 'primary', 'url' => route('beritas.index')],
                    ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'bi-check-circle', 'color' => 'success', 'url' => route('beritas.index', ['status' => 'published'])],
                    ['label' => 'Draft', 'value' => $stats['draft'], 'icon' => 'bi-file-earmark-text', 'color' => 'secondary', 'url' => route('beritas.index', ['status' => 'draft'])],
                    ['label' => 'Menunggu Persetujuan', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'url' => route('beritas.index', ['status' => 'pending'])],
                    ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'bi-star-fill', 'color' => 'info', 'url' => route('beritas.index', ['featured' => 1])],
                    ['label' => 'Arsip', 'value' => $stats['trash'], 'icon' => 'bi-archive-fill', 'color' => 'danger', 'url' => $isAdmin ? route('beritas.index', ['trash' => 1]) : null],
                ];
            ?>
            <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-md-4">
                    <?php if($card['url']): ?>
                        <a href="<?php echo e($card['url']); ?>" class="berita-stat-card">
                    <?php else: ?>
                        <div class="berita-stat-card">
                    <?php endif; ?>
                        <div class="card shadow-sm h-100">
                            <div class="card-body py-3 d-flex align-items-center gap-3">
                                <span class="berita-stat-icon bg-<?php echo e($card['color']); ?>-subtle text-<?php echo e($card['color']); ?>">
                                    <i class="bi <?php echo e($card['icon']); ?>"></i>
                                </span>
                                <div>
                                    <div class="text-muted small"><?php echo e($card['label']); ?></div>
                                    <div class="fs-4 fw-bold"><?php echo e(number_format($card['value'])); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php if($card['url']): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <form method="GET" action="<?php echo e(route('beritas.index')); ?>" class="d-flex flex-wrap align-items-end gap-2">
                <?php if($trash): ?><input type="hidden" name="trash" value="1"><?php endif; ?>
                <div class="flex-grow-1" style="min-width:200px">
                    <label class="form-label small mb-0" for="beritaSearch">Cari</label>
                    <input type="search" id="beritaSearch" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Judul berita...">
                </div>
                <div>
                    <label class="form-label small mb-0" for="beritaStatus">Status</label>
                    <select id="beritaStatus" name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = ['draft' => 'Draft', 'published' => 'Published', 'scheduled' => 'Terjadwal', 'pending' => 'Pending']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="beritaSort">Urutkan</label>
                    <select id="beritaSort" name="sort" class="form-select form-select-sm">
                        <option value="terbaru" <?php if($sort === 'terbaru'): echo 'selected'; endif; ?>>Terbaru</option>
                        <option value="terlama" <?php if($sort === 'terlama'): echo 'selected'; endif; ?>>Terlama</option>
                        <option value="populer" <?php if($sort === 'populer'): echo 'selected'; endif; ?>>Paling dilihat</option>
                        <option value="judul" <?php if($sort === 'judul'): echo 'selected'; endif; ?>>Judul A-Z</option>
                    </select>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="<?php echo e(route('beritas.index', $trash ? ['trash' => 1] : [])); ?>" class="btn btn-sm btn-secondary">Reset</a>
                    <a href="<?php echo e(route('beritas.export', request()->query())); ?>" class="btn btn-sm btn-success" title="Ekspor CSV sesuai filter"><i class="bi bi-download"></i></a>
                    <?php if(! $trash): ?>
                        <a href="<?php echo e(route('beritas.import')); ?>" class="btn btn-sm btn-dark" title="Import Excel/CSV"><i class="bi bi-upload"></i></a>
                    <?php endif; ?>
                    <?php if($isAdmin): ?>
                        <a href="<?php echo e(route('beritas.logAudit')); ?>" class="btn btn-sm btn-info" title="Log Audit Berita"><i class="bi bi-clock-history"></i></a>
                    <?php endif; ?>
                    <?php if(! $trash): ?>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#beritaAdvFilter" title="Filter lanjutan dan laporan" aria-expanded="<?php echo e(request()->filled('start_date') || request()->filled('end_date') || request()->filled('category_uuid') ? 'true' : 'false'); ?>">
                            <i class="bi bi-sliders"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-1 ms-auto">
                    <?php if(! $trash): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.create')): ?>
                            <a href="<?php echo e(route('beritas.create')); ?>" class="btn btn-sm btn-primary text-nowrap"><i class="bi bi-plus-lg"></i> Tambah</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($isAdmin): ?>
                        <?php if($trash): ?>
                            <a href="<?php echo e(route('beritas.index')); ?>" class="btn btn-sm btn-danger text-nowrap"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('beritas.index', ['trash' => 1])); ?>" class="btn btn-sm btn-danger text-nowrap">
                                <i class="bi bi-archive"></i> Arsip <span class="badge text-bg-light"><?php echo e($stats['trash']); ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if(! $trash): ?>
                    <div class="collapse w-100 mt-2 <?php echo e(request()->filled('start_date') || request()->filled('end_date') || request()->filled('category_uuid') ? 'show' : ''); ?>" id="beritaAdvFilter">
                        <div class="d-flex flex-wrap align-items-end gap-2 border-top pt-2">
                            <div>
                                <label class="form-label small mb-0" for="beritaStartDate">Publikasi dari</label>
                                <input type="date" id="beritaStartDate" name="start_date" class="form-control form-control-sm" value="<?php echo e(request('start_date')); ?>">
                            </div>
                            <div>
                                <label class="form-label small mb-0" for="beritaEndDate">Publikasi sampai</label>
                                <input type="date" id="beritaEndDate" name="end_date" class="form-control form-control-sm" value="<?php echo e(request('end_date')); ?>">
                            </div>
                            <div>
                                <label class="form-label small mb-0" for="beritaCategory">Kategori</label>
                                <select id="beritaCategory" name="category_uuid" class="form-select form-select-sm">
                                    <option value="">Semua kategori</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->uuid); ?>" <?php if(request('category_uuid') === $category->uuid): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="submit" formaction="<?php echo e(route('beritas.laporan')); ?>" formmethod="get" formtarget="_blank" class="btn btn-sm btn-danger" title="Unduh laporan PDF">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                                <button type="submit" formaction="<?php echo e(route('beritas.laporanExcel')); ?>" formmethod="get" class="btn btn-sm btn-success" title="Unduh laporan Excel">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                                </button>
                                <button type="submit" formaction="<?php echo e(route('beritas.laporanPptx')); ?>" formmethod="get" class="btn btn-sm btn-warning" title="Unduh laporan PowerPoint">
                                    <i class="bi bi-file-earmark-ppt"></i> PowerPoint
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo e($trash ? 'Arsip Berita' : 'Daftar Berita'); ?></h5>
            <span class="text-muted small"><?php echo e(number_format($beritas->total())); ?> berita</span>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.destroy')): ?>
            <div id="beritaBulkBar" class="card-body border-bottom d-none align-items-center gap-2">
                <span><strong id="beritaSelectedCount">0</strong> dipilih</span>
                <?php if($trash): ?>
                    <?php if($isAdmin): ?>
                        <button type="button" class="btn btn-sm btn-success" id="restoreSelectedBerita"><i class="bi bi-arrow-counterclockwise"></i> Pulihkan terpilih</button>
                    <?php endif; ?>
                <?php else: ?>
                    <button type="button" class="btn btn-sm btn-danger" id="archiveSelectedBerita"><i class="bi bi-archive"></i> Arsipkan terpilih</button>
                <?php endif; ?>
                <button type="button" class="btn btn-sm btn-light border" id="clearBeritaSelection">Batal pilih</button>
            </div>
            <form id="bulkArchiveBeritaForm" action="<?php echo e(route('beritas.bulkDestroy')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><div id="bulkArchiveBeritaIds"></div></form>
            <?php if($trash): ?>
                <form id="bulkRestoreBeritaForm" action="<?php echo e(route('beritas.bulkRestore')); ?>" method="POST" class="d-none"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?><div id="bulkRestoreBeritaIds"></div></form>
            <?php endif; ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.destroy')): ?>
                            <th class="text-center" style="width:38px"><input type="checkbox" class="form-check-input" id="checkAllBerita" aria-label="Pilih semua berita"></th>
                        <?php endif; ?>
                        <th>Berita</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <?php if($trash): ?><th>Diarsipkan</th><?php endif; ?>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_2 = true; $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.destroy')): ?>
                                <td class="text-center"><input type="checkbox" class="form-check-input berita-check" value="<?php echo e($berita->uuid); ?>" aria-label="Pilih berita"></td>
                            <?php endif; ?>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($berita->featured_image): ?>
                                        <img src="<?php echo e(asset('storage/' . $berita->featured_image)); ?>" alt="" class="rounded" width="64" height="48" style="object-fit:cover">
                                    <?php else: ?>
                                        <span class="bg-light rounded d-inline-flex align-items-center justify-content-center" style="width:64px;height:48px"><i class="bi bi-newspaper text-muted"></i></span>
                                    <?php endif; ?>
                                    <div>
                                        <?php if(! $trash): ?>
                                            <a href="<?php echo e(route('beritas.show', $berita->uuid)); ?>" class="fw-semibold text-decoration-none"><?php echo e($berita->title); ?></a>
                                        <?php else: ?>
                                            <span class="fw-semibold"><?php echo e($berita->title); ?></span>
                                        <?php endif; ?>
                                        <?php if($berita->is_featured): ?><i class="bi bi-star-fill text-warning ms-1" title="Featured"></i><?php endif; ?>
                                        <?php if($berita->is_popular): ?><i class="bi bi-fire text-danger ms-1" title="Populer"></i><?php endif; ?>
                                        <?php if($berita->is_urgent): ?><i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Mendesak"></i><?php endif; ?>
                                        <div class="small text-muted"><?php echo e(Str::limit($berita->excerpt, 80)); ?></div>
                                        <div class="small text-muted d-flex flex-wrap gap-3 mt-1">
                                            <span><i class="bi bi-person me-1"></i><?php echo e($berita->user?->name ?? '-'); ?></span>
                                            <span><i class="bi bi-calendar3 me-1"></i><?php echo e($berita->scheduled_at?->format('d M Y H:i') ?? '-'); ?></span>
                                            <span><i class="bi bi-eye me-1"></i><?php echo e(number_format($berita->views)); ?> dilihat</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($berita->category?->name ?? '-'); ?></td>
                            <td>
                                <?php if($berita->status === 'published'): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
                                <?php elseif($berita->status === 'pending'): ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle" title="Menunggu persetujuan admin">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                <?php elseif($berita->status === 'scheduled'): ?>
                                    <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle">Scheduled</span>
                                <?php elseif($berita->status === 'draft'): ?>
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle"><?php echo e(ucfirst($berita->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if($trash): ?><td><?php echo e($berita->deleted_at?->format('d M Y H:i')); ?></td><?php endif; ?>
                            <td class="text-center text-nowrap">
                                <div class="berita-actions">
                                <?php if($trash): ?>
                                    <?php if($isAdmin): ?>
                                        <form action="<?php echo e(route('beritas.restore', $berita->uuid)); ?>" method="POST" class="d-inline"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <button class="btn btn-sm btn-berita-action btn-berita-restore" title="Pulihkan" aria-label="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                        <form action="<?php echo e(route('beritas.forceDestroy', $berita->uuid)); ?>" method="POST" class="d-inline" data-confirm="Hapus permanen beserta semua media? Tindakan ini tidak dapat dibatalkan."><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-berita-action btn-berita-force" title="Hapus permanen" aria-label="Hapus permanen"><i class="bi bi-trash"></i></button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.index')): ?>
                                        <a href="<?php echo e(route('beritas.show', $berita->uuid)); ?>" class="btn btn-sm btn-berita-action btn-berita-view" title="Lihat" aria-label="Lihat"><i class="bi bi-eye"></i></a>
                                    <?php endif; ?>
                                    <?php if($isAdmin && auth()->user()->can('agendas.approve') && $berita->status === 'pending'): ?>
                                        <form action="<?php echo e(route('beritas.approve', $berita->uuid)); ?>" method="POST" class="d-inline" data-confirm="Setujui dan publikasikan berita ini?">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-berita-action btn-berita-edit" title="Setujui dan publikasikan" aria-label="Setujui dan publikasikan"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-berita-action btn-berita-force" title="Tolak dan kembalikan ke draft" aria-label="Tolak dan kembalikan ke draft"
                                            data-review-action="reject" data-review-url="<?php echo e(route('beritas.reject', $berita->uuid)); ?>"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        <button type="button" class="btn btn-sm btn-berita-action btn-berita-view" title="Minta revisi" aria-label="Minta revisi"
                                            data-review-action="revision" data-review-url="<?php echo e(route('beritas.mintaRevisi', $berita->uuid)); ?>"><i class="bi bi-pencil-square"></i></button>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.update')): ?>
                                        <a href="<?php echo e(route('beritas.edit', $berita->uuid)); ?>" class="btn btn-sm btn-berita-action btn-berita-edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.index')): ?>
                                        <button type="button" class="btn btn-sm btn-berita-action btn-berita-copy" title="Salin tautan berita" aria-label="Salin tautan berita" data-copy-url="<?php echo e(route('beritas.show', $berita->uuid)); ?>">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('agendas.destroy')): ?>
                                        <form action="<?php echo e(route('beritas.destroy', $berita->uuid)); ?>" method="POST" class="d-inline" data-confirm="Pindahkan berita ini ke arsip?">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-berita-action btn-berita-archive" title="Arsipkan" aria-label="Arsipkan"><i class="bi bi-trash"></i></button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <?php ($columnCount = ($trash ? 5 : 4) + (auth()->user()->can('agendas.destroy') ? 1 : 0)); ?>
                        <tr><td colspan="<?php echo e($columnCount); ?>" class="text-center text-muted py-5"><?php echo e($trash ? 'Arsip berita kosong.' : 'Belum ada berita.'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Menampilkan <?php echo e($beritas->firstItem() ?? 0); ?>-<?php echo e($beritas->lastItem() ?? 0); ?> dari <?php echo e($beritas->total()); ?></small>
            <?php echo e($beritas->links('pagination::minimal')); ?>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-review-action]').forEach((button) => {
        button.addEventListener('click', async () => {
            const isReject = button.dataset.reviewAction === 'reject';
            const result = await Swal.fire({
                title: isReject ? 'Tolak berita?' : 'Minta revisi berita?',
                text: isReject
                    ? 'Berita akan dikembalikan ke status draft. Tuliskan alasan untuk penulis.'
                    : 'Catatan revisi akan dikirim kepada penulis. Status berita tetap pending.',
                input: 'textarea',
                inputPlaceholder: isReject ? 'Alasan penolakan...' : 'Catatan revisi...',
                inputAttributes: {maxlength: 500, rows: 3},
                icon: isReject ? 'warning' : 'info',
                showCancelButton: true,
                confirmButtonText: isReject ? 'Tolak dan kembalikan' : 'Kirim catatan revisi',
                inputValidator: (value) => !value || !value.trim() ? 'Catatan wajib diisi.' : undefined,
            });
            if (!result.isConfirmed) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = button.dataset.reviewUrl;
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = <?php echo json_encode(csrf_token(), 15, 512) ?>;
            const note = document.createElement('input');
            note.type = 'hidden';
            note.name = 'catatan';
            note.value = result.value.trim();
            form.append(token, note);
            document.body.appendChild(form);
            form.submit();
        });
    });

    document.querySelectorAll('[data-copy-url]').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.copyUrl);
                Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Tautan berita disalin', showConfirmButton: false, timer: 1800});
            } catch (error) {
                Swal.fire({icon: 'error', title: 'Tidak dapat menyalin tautan', text: 'Silakan salin URL halaman berita secara manual.'});
            }
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi tindakan',
                text: form.dataset.confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    const checkAll = document.getElementById('checkAllBerita');
    const bulkBar = document.getElementById('beritaBulkBar');
    if (!checkAll || !bulkBar) return;

    const checks = () => Array.from(document.querySelectorAll('.berita-check'));
    const selected = () => checks().filter((checkbox) => checkbox.checked);
    const update = () => {
        const count = selected().length;
        document.getElementById('beritaSelectedCount').textContent = count;
        bulkBar.classList.toggle('d-none', count === 0);
        bulkBar.classList.toggle('d-flex', count > 0);
        checkAll.checked = count > 0 && count === checks().length;
        checkAll.indeterminate = count > 0 && count < checks().length;
    };

    checkAll.addEventListener('change', () => {
        checks().forEach((checkbox) => checkbox.checked = checkAll.checked);
        update();
    });
    document.addEventListener('change', (event) => {
        if (event.target.matches('.berita-check')) update();
    });
    document.getElementById('clearBeritaSelection').addEventListener('click', () => {
        checks().forEach((checkbox) => checkbox.checked = false);
        update();
    });

    const submitSelected = (formId, containerId) => {
        const box = document.getElementById(containerId);
        box.replaceChildren(...selected().map((checkbox) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = checkbox.value;
            return input;
        }));
        document.getElementById(formId).submit();
    };
    const archiveButton = document.getElementById('archiveSelectedBerita');
    if (archiveButton) archiveButton.addEventListener('click', () => {
        Swal.fire({
            title: 'Arsipkan ' + selected().length + ' berita?',
            text: 'Berita akan dapat dipulihkan dari arsip.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, arsipkan',
        }).then((result) => {
            if (result.isConfirmed) submitSelected('bulkArchiveBeritaForm', 'bulkArchiveBeritaIds');
        });
    });
    const restoreButton = document.getElementById('restoreSelectedBerita');
    if (restoreButton) restoreButton.addEventListener('click', () => {
        Swal.fire({
            title: 'Pulihkan ' + selected().length + ' berita?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, pulihkan',
        }).then((result) => {
            if (result.isConfirmed) submitSelected('bulkRestoreBeritaForm', 'bulkRestoreBeritaIds');
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\beritas\index.blade.php ENDPATH**/ ?>