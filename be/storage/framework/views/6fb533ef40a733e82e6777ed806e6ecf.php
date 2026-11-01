<?php $__env->startSection('title', 'Aduan'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Aduan','page' => 'Layanan','active' => 'Semua Aduan','route' => ''.e(route('aduans.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    .aduan-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllAduan { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarAduan { border: 1px solid rgba(244, 63, 94, .25); }
    #bulkBarAduanRestore { border: 1px solid rgba(251, 191, 36, .4); }
</style>

<section class="section">
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white"> <?php echo e(session('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white"> <?php echo e(session('error')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-inbox"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['total'])); ?></div><div class="ml-stat-label">Total Aduan</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-time"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['menunggu'])); ?></div><div class="ml-stat-label">Menunggu</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-search-alt"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['diverifikasi'])); ?></div><div class="ml-stat-label">Diverifikasi</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-danger"><i class="bx bx-cog"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['diproses'])); ?></div><div class="ml-stat-label">Diproses</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['selesai'])); ?></div><div class="ml-stat-label">Selesai</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-secondary"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value"><?php echo e(number_format($stats['ditolak'])); ?></div><div class="ml-stat-label">Ditolak</div></div></div></div></div></div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="btn-group" role="group" aria-label="Tampilan data">
                    <a href="<?php echo e(route('aduans.index')); ?>"
                        class="btn btn-sm <?php echo e($tampil === 'aktif' ? 'btn-primary' : 'btn-light'); ?>">
                        <i class="bi bi-inbox me-1"></i> Data Aktif
                    </a>
                    <a href="<?php echo e(route('aduans.index', ['tampil' => 'sampah'])); ?>"
                        class="btn btn-sm <?php echo e($tampil === 'sampah' ? 'btn-primary' : 'btn-light'); ?>">
                        <i class="bi bi-trash me-1"></i> Tempat Sampah
                        <?php if($trashCount > 0): ?>
                        <span class="badge bg-danger ms-1"><?php echo e($trashCount); ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo e(route('aduans.index', ['tampil' => 'arsip'])); ?>"
                        class="btn btn-sm <?php echo e($tampil === 'arsip' ? 'btn-primary' : 'btn-light'); ?>">
                        <i class="bi bi-archive me-1"></i> Arsip
                        <?php if($arsipCount > 0): ?>
                        <span class="badge bg-secondary ms-1"><?php echo e($arsipCount); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <?php if($tampil === 'aktif'): ?>
                <div class="d-flex gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.export')): ?>
                    <a href="<?php echo e(route('aduans.exportPdf', request()->query())); ?>" class="btn btn-danger btn-md">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Laporan
                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.store')): ?>
                    <a href="<?php echo e(route('aduans.create')); ?>" class="btn btn-primary btn-md">
                        <i class="bi bi-plus-lg"></i> Tambah Aduan
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <?php if($tampil === 'sampah'): ?>
            <div class="alert alert-warning py-2">
                Data di tempat sampah tidak tampil di laporan &amp; API. Kembalikan untuk mengaktifkan lagi,
                atau hapus permanen (foto ikut terhapus dan tidak bisa dikembalikan).
            </div>
            <?php elseif($tampil === 'arsip'): ?>
            <div class="alert alert-info py-2">
                Arsip berisi aduan selesai yang sudah lama. Data arsip tidak tampil di daftar aktif &amp; API publik,
                tetapi tetap bisa dicari di sini dan dilacak lewat nomor aduan.
            </div>
            <?php endif; ?>

            
            <form method="GET" action="<?php echo e(route('aduans.index')); ?>" class="mb-4">
                <input type="hidden" name="tampil" value="<?php echo e($tampil); ?>">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="filter-search" class="form-label small fw-semibold">Pencarian</label>
                        <input type="text" id="filter-search" name="search" class="form-control"
                            placeholder="Nomor / judul / lokasi" value="<?php echo e(request('search')); ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="filter-kategori" class="form-label small fw-semibold">Kategori</label>
                        <select id="filter-kategori" name="kategori" class="form-select">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = \App\Models\Aduan::KATEGORI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kat); ?>" <?php echo e(request('kategori') == $kat ? 'selected' : ''); ?>>
                                <?php echo e($kat); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="filter-kecamatan" class="form-label small fw-semibold">Kecamatan</label>
                        <select id="filter-kecamatan" name="kecamatan_id" class="form-select">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kec->id); ?>" <?php echo e(request('kecamatan_id') == $kec->id ? 'selected' : ''); ?>>
                                <?php echo e($kec->nama); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="filter-status" class="form-label small fw-semibold">Status</label>
                        <select id="filter-status" name="status" class="form-select">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = ['menunggu','diverifikasi','diproses','selesai','ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st); ?>" <?php echo e(request('status') == $st ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($st)); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="filter-prioritas" class="form-label small fw-semibold">Prioritas</label>
                        <select id="filter-prioritas" name="prioritas" class="form-select">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = ['rendah','sedang','tinggi','darurat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pr); ?>" <?php echo e(request('prioritas') == $pr ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($pr)); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Periode Pengaduan</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" id="filter-dari" name="tanggal_mulai" class="form-control"
                                    value="<?php echo e(request('tanggal_mulai')); ?>" title="Dari tanggal">
                            </div>
                            <div class="col-6">
                                <input type="date" id="filter-sampai" name="tanggal_selesai" class="form-control"
                                    value="<?php echo e(request('tanggal_selesai')); ?>" title="Sampai tanggal">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <?php if(request()->except(['tampil', 'page'])): ?>
                        <a href="<?php echo e(route('aduans.index', ['tampil' => $tampil])); ?>" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            
            <?php
            $bulkMode = $tampil === 'aktif' ? 'trash' : ($tampil === 'sampah' ? 'restore' : null);
            $canBulk = ($bulkMode === 'trash' && auth()->user()->can('aduans.destroy'))
                || ($bulkMode === 'restore' && auth()->user()->can('aduans.restore'));
            ?>
            <?php if($canBulk): ?>
            <div id="bulkBarAduan<?php echo e($bulkMode === 'restore' ? 'Restore' : ''); ?>" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 mb-2 rounded <?php echo e($bulkMode === 'restore' ? 'bg-warning-subtle' : 'bg-danger-subtle'); ?>">
                <span class="small fw-semibold <?php echo e($bulkMode === 'restore' ? 'text-warning-emphasis' : 'text-danger'); ?>"><i class="bi bi-check-square me-1"></i><span id="bulkCountAduan">0</span> aduan dipilih</span>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-light" onclick="clearAduanSelection()">Batal</button>
                    <?php if($bulkMode === 'trash'): ?>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkTrashAduan()"><i class="bi bi-trash"></i> Pindah ke sampah</button>
                    <?php else: ?>
                    <button type="button" class="btn btn-sm btn-warning text-white" onclick="bulkRestoreAduan()"><i class="bi bi-arrow-counterclockwise"></i> Kembalikan</button>
                    <?php endif; ?>
                </div>
            </div>
            <form id="bulkAduanForm" action="<?php echo e($bulkMode === 'trash' ? route('aduans.bulkDestroy') : route('aduans.bulkRestore')); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?>
                <div id="bulkIdsAduan"></div>
            </form>
            <?php endif; ?>
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php if($canBulk): ?><th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllAduan" class="form-check-input" title="Pilih semua"></th><?php endif; ?>
                            <th>No.</th>
                            <th>Nomor Aduan</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pelapor</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th><?php echo e($tampil === 'sampah' ? 'Dihapus Pada' : 'Tgl Pengaduan'); ?></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_2 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <tr>
                            <?php if($canBulk): ?><td class="text-center"><input type="checkbox" class="form-check-input aduan-check" value="<?php echo e($item->uuid); ?>"></td><?php endif; ?>
                            <td><?php echo e($items->firstItem() + $loop->index); ?></td>
                            <td><code><?php echo e($item->nomor_aduan); ?></code></td>
                            <td><?php echo e($item->judul); ?></td>
                            <td><span class="badge bg-info"><?php echo e($item->kategori); ?></span></td>
                            <td><?php echo e($item->user->name ?? '-'); ?>

                                <?php if($item->is_anonim): ?>
                                <br><span class="badge bg-dark">Anonim</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($item->lokasi ?? '-'); ?><br>
                                <small class="text-muted"><?php echo e($item->kecamatan->nama ?? ''); ?><?php echo e($item->kelurahan ? ' - ' . $item->kelurahan->nama : ''); ?></small>
                            </td>
                            <td>
                                <?php
                                $badge = ['menunggu' => 'secondary','diverifikasi' => 'info','diproses' => 'warning','selesai' => 'success','ditolak' => 'danger'][$item->status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($item->status)); ?></span>
                            </td>
                            <td>
                                <?php
                                $prio = ['rendah' => 'secondary','sedang' => 'primary','tinggi' => 'warning','darurat' => 'danger'][$item->prioritas] ?? 'primary';
                                ?>
                                <span class="badge bg-<?php echo e($prio); ?>"><?php echo e(ucfirst($item->prioritas)); ?></span>
                            </td>
                            <td>
                                <?php echo e($tampil === 'sampah'
                                    ? $item->deleted_at?->format('d M Y H:i')
                                    : ($tampil === 'arsip'
                                        ? $item->archived_at?->format('d M Y H:i')
                                        : $item->tanggal_pengaduan?->format('d M Y H:i') ?? '-')); ?>

                            </td>
                            <td>
                                <?php if($tampil === 'sampah'): ?>
                                <div class="d-flex gap-1">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.restore')): ?>
                                    <form action="<?php echo e(route('aduans.restore', $item->uuid)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-warning text-white" title="Kembalikan">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.forceDelete')): ?>
                                    <a onclick="confirmForceDelete('<?php echo e($item->uuid); ?>')" title="Hapus permanen"
                                        class="btn btn-sm btn-danger text-white">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="forceDeleteForm_<?php echo e($item->uuid); ?>"
                                        action="<?php echo e(route('aduans.forceDestroy', $item->uuid)); ?>" method="POST">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php elseif($tampil === 'arsip'): ?>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo e(route('aduans.show', $item->uuid)); ?>"
                                        class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.batalArsip')): ?>
                                    <form action="<?php echo e(route('aduans.batalArsip', $item->uuid)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-warning text-white" title="Kembalikan ke aktif">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo e(route('aduans.show', $item->uuid)); ?>"
                                        class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.tindaklanjut.store')): ?>
                                    <a data-bs-toggle="modal"
                                        data-bs-target="#modal-tindak-lanjut-<?php echo e($item->uuid); ?>"
                                        class="btn btn-sm btn-warning text-white" title="Tindak Lanjut">
                                        <i class="bi bi-tools"></i><?php if($item->tindak_lanjuts_count > 0): ?> <?php echo e($item->tindak_lanjuts_count); ?><?php endif; ?>
                                    </a>
                                    <?php echo $__env->make('pages.aduan.modal-tindak-lanjut', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.arsip')): ?>
                                    <a onclick="confirmArsip('<?php echo e($item->uuid); ?>')" title="Arsipkan"
                                        class="btn btn-sm btn-secondary text-white">
                                        <i class="bi bi-archive"></i>
                                    </a>
                                    <form id="arsipForm_<?php echo e($item->uuid); ?>"
                                        action="<?php echo e(route('aduans.arsip', $item->uuid)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    <?php endif; ?>
                                    <?php
                                    // Warga hanya boleh ubah/hapus selagi status masih menunggu.
                                    $bisaUbah = auth()->user()->hasAnyRole(['super-admin', 'admin', 'uptd'])
                                        || $item->status === 'menunggu';
                                    ?>
                                    <?php if($bisaUbah): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.update')): ?>
                                    <a href="<?php echo e(route('aduans.edit', $item->uuid)); ?>"
                                        class="btn btn-sm btn-success text-white" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('aduans.destroy')): ?>
                                    <a onclick="moveToTrash('<?php echo e($item->uuid); ?>')" title="Pindah ke sampah"
                                        class="btn btn-sm btn-danger text-white">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="trashForm_<?php echo e($item->uuid); ?>"
                                        action="<?php echo e(route('aduans.destroy', $item->uuid)); ?>" method="POST">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr>
                            <td colspan="<?php echo e($canBulk ? 11 : 10); ?>" class="text-center">
                                <?php echo e($tampil === 'sampah' ? 'Tempat sampah kosong.' : ($tampil === 'arsip' ? 'Belum ada arsip.' : 'Belum ada data aduan.')); ?>

                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <?php echo e($items->links('pagination::minimal')); ?>

            </div>
        </div>
    </div>
</section>

<script>
    function moveToTrash(getId) {
        Swal.fire({
            title: 'Pindahkan ke Sampah?',
            text: 'Aduan akan disembunyikan dan bisa dikembalikan lagi dari Tempat Sampah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Pindahkan'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('trashForm_' + getId).submit();
            }
        });
    }

    function confirmArsip(getId) {
        Swal.fire({
            title: 'Arsipkan Aduan?',
            text: 'Aduan keluar dari daftar aktif tetapi tetap bisa dicari di tab Arsip.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Arsipkan'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('arsipForm_' + getId).submit();
            }
        });
    }

    function confirmForceDelete(getId) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Data dan foto aduan akan dihapus permanen dan tidak bisa dikembalikan!',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Permanen!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('forceDeleteForm_' + getId).submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAllAduan');
        const bulkBar = document.getElementById('bulkBarAduan') || document.getElementById('bulkBarAduanRestore');
        const bulkCount = document.getElementById('bulkCountAduan');
        if (!checkAll || !bulkBar) return;

        function updateBulkBar() {
            const checks = document.querySelectorAll('.aduan-check');
            const n = document.querySelectorAll('.aduan-check:checked').length;
            bulkCount.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
            checks.forEach(c => c.closest('tr').classList.toggle('row-selected', c.checked));
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.aduan-check').forEach(c => { c.checked = this.checked; });
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('aduan-check')) updateBulkBar();
        });

        window.clearAduanSelection = function () {
            document.querySelectorAll('.aduan-check:checked').forEach(c => { c.checked = false; });
            updateBulkBar();
        };

        function submitBulkIds(title, text, confirmText, confirmColor) {
            const ids = Array.from(document.querySelectorAll('.aduan-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title: title.replace('{n}', ids.length), text: text, icon: 'warning', showCancelButton: true, confirmButtonText: confirmText, confirmButtonColor: confirmColor}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkAduanForm');
                    const box = document.getElementById('bulkIdsAduan');
                    box.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        }

        window.bulkTrashAduan = function () {
            submitBulkIds('Pindahkan {n} aduan ke sampah?', 'Aduan bisa dikembalikan lagi dari Tempat Sampah.', 'Ya, Pindahkan', '#f43f5e');
        };

        window.bulkRestoreAduan = function () {
            submitBulkIds('Kembalikan {n} aduan?', 'Aduan terpilih akan aktif kembali.', 'Ya, Kembalikan', '#f59e0b');
        };

        updateBulkBar();
    });
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\aduan\index.blade.php ENDPATH**/ ?>