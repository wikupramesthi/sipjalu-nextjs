<?php $__env->startSection('title', 'Preview Laporan Analitik'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Laporan Analitik','page' => 'Dashboard','active' => 'Preview Laporan','route' => ''.e(route('dashboard.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('after-style'); ?>
<style>
    .report-paper { background: #fff; max-width: 920px; margin: 0 auto; box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.12); border-radius: 6px; overflow: hidden; }
    .report-kop { border-bottom: 4px double #1e293b; }
    .report-kop h4 { letter-spacing: 1px; }
    .report-sec-title { border-left: 4px solid #0a4d8e; padding-left: 10px; font-weight: 700; }
    .report-kpi { border: 1px solid #e2e8f0; border-radius: 10px; }
    .report-bar-track { height: 8px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
    .report-bar-fill { height: 100%; border-radius: 99px; }
    .report-daily { display: flex; align-items: flex-end; gap: 3px; height: 130px; }
    .report-daily .bar { flex: 1; min-width: 4px; background: linear-gradient(180deg, #60a5fa, #0a4d8e); border-radius: 3px 3px 0 0; }
    @media print {
        .report-toolbar, .page-title, .breadcrumb { display: none !important; }
        .report-paper { box-shadow: none; max-width: 100%; }
    }
</style>
<?php $__env->stopPush(); ?>

<div class="container-fluid pb-5">

    
    <div class="report-toolbar d-flex flex-wrap align-items-center gap-2 mb-3">
        <a href="<?php echo e(route('dashboard.index', request()->only(['start_date', 'end_date']))); ?>" class="btn btn-sm btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <span class="badge bg-info text-dark ms-1"><i class="bi bi-calendar3 me-1"></i><?php echo e($dateRangeLabel); ?> (<?php echo e($daysDiff); ?> hari)</span>
        <div class="ms-auto d-flex gap-2">
            <a href="<?php echo e(route('dashboard.analytics.pdf', request()->only(['start_date', 'end_date']))); ?>" class="btn btn-sm btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    
    <div class="report-paper">
        <div class="p-4 p-md-5">

            
            <div class="report-kop d-flex align-items-center gap-3 pb-3 mb-4">
                <?php if($identity->logoUrl()): ?>
                    <img src="<?php echo e($identity->logoUrl()); ?>" alt="Logo" style="height:72px; width:auto; object-fit:contain;">
                <?php endif; ?>
                <div class="text-center flex-grow-1">
                    <h4 class="fw-bold mb-0">PEMERINTAH KOTA BEKASI</h4>
                    <h5 class="fw-semibold mb-1"><?php echo e($identity->site_name ?? 'Portal Resmi'); ?></h5>
                    <div class="small text-muted"><?php echo e($identity->address ?? ''); ?></div>
                    <div class="small text-muted"><?php echo e($identity->phone ?? ''); ?><?php echo e($identity->phone && $identity->email ? ' • ' : ''); ?><?php echo e($identity->email ?? ''); ?></div>
                </div>
            </div>

            
            <div class="text-center mb-4">
                <h5 class="fw-bold mb-1 text-decoration-underline">LAPORAN ANALITIK WEBSITE</h5>
                <div class="small text-muted">Periode: <?php echo e($dateRangeLabel); ?> (<?php echo e($daysDiff); ?> hari)</div>
                <div class="small text-muted">Dibuat pada <?php echo e($generatedAt->translatedFormat('d F Y H:i')); ?> WIB oleh <?php echo e($generatedBy); ?></div>
            </div>

            
            <h6 class="report-sec-title mb-3">I. RINGKASAN EKSEKUTIF</h6>
            <div class="row g-3 mb-4">
                <div class="col-6 <?php if($isAdminViewer): ?> col-lg-3 <?php else: ?> col-lg-4 <?php endif; ?>">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Total Kunjungan</div>
                        <div class="fw-bold fs-4"><?php echo e(number_format($visitorStats['total_visits'])); ?></div>
                        <span class="badge <?php echo e($visitorGrowth >= 0 ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($visitorGrowth >= 0 ? '+' : ''); ?><?php echo e($visitorGrowth); ?>%</span>
                    </div>
                </div>
                <div class="col-6 <?php if($isAdminViewer): ?> col-lg-3 <?php else: ?> col-lg-4 <?php endif; ?>">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Pengunjung Unik</div>
                        <div class="fw-bold fs-4"><?php echo e(number_format($visitorStats['unique_visitors'])); ?></div>
                        <span class="badge <?php echo e($uniqueGrowth >= 0 ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($uniqueGrowth >= 0 ? '+' : ''); ?><?php echo e($uniqueGrowth); ?>%</span>
                    </div>
                </div>
                <div class="col-6 <?php if($isAdminViewer): ?> col-lg-3 <?php else: ?> col-lg-4 <?php endif; ?>">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Total Agenda</div>
                        <div class="fw-bold fs-4"><?php echo e(number_format($totalAgendas)); ?></div>
                        <span class="badge bg-info text-dark"><?php echo e($publishedAgendas); ?> tayang • <?php echo e($pendingAgendas); ?> pending</span>
                    </div>
                </div>
                <?php if($isAdminViewer): ?>
                <div class="col-6 col-lg-3">
                    <div class="report-kpi p-3 text-center h-100">
                        <div class="small text-muted">Pesan Masuk</div>
                        <div class="fw-bold fs-4"><?php echo e(number_format($totalMessages)); ?></div>
                        <span class="badge bg-warning text-dark"><?php echo e($unreadMessages); ?> belum dibaca</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <h6 class="report-sec-title mb-1">II. TREN KUNJUNGAN HARIAN</h6>
            <p class="small text-muted mb-2">30 hari terakhir pada periode laporan.</p>
            <?php
                $dLabels = array_slice($visitorStats['daily']['labels'] ?? [], -30);
                $dTotals = array_slice($visitorStats['daily']['total'] ?? [], -30);
                $dMax = max(1, max($dTotals ?: [0]));
            ?>
            <div class="border rounded p-3 mb-1">
                <div class="report-daily mb-2">
                    <?php $__currentLoopData = $dTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bar" style="height: <?php echo e(max(3, round($v / $dMax * 100))); ?>%;" title="<?php echo e(number_format($v)); ?>"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span><?php echo e($dLabels[0] ?? ''); ?></span>
                    <span>Puncak: <?php echo e(number_format($dMax)); ?> kunjungan/hari</span>
                    <span><?php echo e(end($dLabels) ?: ''); ?></span>
                </div>
            </div>
            <div class="mb-4"></div>

            
            <h6 class="report-sec-title mb-3">III. TREN AGENDA & PERANGKAT PENGUNJUNG</h6>
            <div class="row g-3 mb-4">
                <div class="col-lg-7">
                    <div class="border rounded p-3 h-100">
                        <div class="small fw-semibold mb-2">Agenda masuk 6 bulan terakhir</div>
                        <?php $__currentLoopData = $agendaTrenLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="small text-muted" style="width:64px;"><?php echo e($bln); ?></div>
                                <div class="report-bar-track flex-grow-1">
                                    <div class="report-bar-fill" style="width: <?php echo e(round(($agendaTrenData[$i] ?? 0) / $trenMax * 100)); ?>%; background: #139a8d;"></div>
                                </div>
                                <div class="small fw-bold" style="width:36px; text-align:right;"><?php echo e($agendaTrenData[$i] ?? 0); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border rounded p-3 h-100">
                        <div class="small fw-semibold mb-2">Komposisi perangkat</div>
                        <?php $__currentLoopData = $deviceStats['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $pct = round(($deviceStats['values'][$i] ?? 0) / $deviceTotal * 100, 1); ?>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="small text-muted" style="width:64px;"><?php echo e($dev); ?></div>
                                <div class="report-bar-track flex-grow-1">
                                    <div class="report-bar-fill" style="width: <?php echo e($pct); ?>%; background: #0a4d8e;"></div>
                                </div>
                                <div class="small fw-bold" style="width:52px; text-align:right;"><?php echo e($pct); ?>%</div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="small fw-semibold mt-3 mb-1">Browser teratas</div>
                        <?php $__empty_2 = true; $__currentLoopData = $browserStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <div class="d-flex justify-content-between small border-bottom py-1">
                                <span><?php echo e($name ?: 'Lainnya'); ?></span><strong><?php echo e(number_format($total)); ?></strong>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <div class="small text-muted">Belum ada data.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <h6 class="report-sec-title mb-3">IV. KINERJA OPD & KONTEN POPULER</h6>
            <div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="small fw-semibold mb-2">Top 5 OPD pengirim agenda</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <thead class="table-light"><tr><th style="width:32px;">No</th><th>OPD</th><th class="text-end">Agenda</th><th class="text-end">Pending</th></tr></thead>
                        <tbody>
                            <?php $__empty_2 = true; $__currentLoopData = $topOpd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $opd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <tr><td><?php echo e($i + 1); ?></td><td><?php echo e($opd->name); ?></td><td class="text-end"><?php echo e($opd->total_agenda); ?></td><td class="text-end"><?php echo e($opd->pending_agenda); ?></td></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <div class="small fw-semibold mb-2">5 Agenda paling banyak dilihat</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <thead class="table-light"><tr><th style="width:32px;">No</th><th>Judul</th><th class="text-end">Dilihat</th></tr></thead>
                        <tbody>
                            <?php $__empty_2 = true; $__currentLoopData = $topViewedAgendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <tr><td><?php echo e($i + 1); ?></td><td><?php echo e(\Illuminate\Support\Str::limit($a->title, 50)); ?></td><td class="text-end"><?php echo e(number_format($a->views)); ?></td></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <h6 class="report-sec-title mb-3">V. KATEGORI, WILAYAH & PERLU PERHATIAN</h6>
            <div class="row g-3 mb-4">
                <div class="<?php if($isAdminViewer): ?> col-lg-4 <?php else: ?> col-lg-6 <?php endif; ?>">
                    <div class="small fw-semibold mb-2">Agenda per kategori</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            <?php $__empty_2 = true; $__currentLoopData = $agendaKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <tr><td><?php echo e($kat->name); ?></td><td class="text-end" style="width:56px;"><?php echo e($kat->agendas_count); ?></td></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <tr><td class="text-center text-muted">Belum ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="<?php if($isAdminViewer): ?> col-lg-4 <?php else: ?> col-lg-6 <?php endif; ?>">
                    <div class="small fw-semibold mb-2">Kota asal pengunjung</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            <?php $__empty_2 = true; $__currentLoopData = $kotaStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kota => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <tr><td><?php echo e($kota); ?></td><td class="text-end" style="width:56px;"><?php echo e(number_format($total)); ?></td></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <tr><td class="text-center text-muted">Belum ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="small fw-semibold mt-3 mb-2">Inventaris konten</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            <tr><td>Halaman</td><td class="text-end" style="width:56px;"><?php echo e($totalPages); ?></td></tr>
                            <tr><td>Dokumen</td><td class="text-end" style="width:56px;"><?php echo e($totalDocuments); ?></td></tr>
                            <tr><td>FAQ aktif</td><td class="text-end" style="width:56px;"><?php echo e($totalFaq); ?></td></tr>
                            <tr><td>OPD aktif</td><td class="text-end" style="width:56px;"><?php echo e($totalOpdAktif); ?></td></tr>
                        </tbody>
                    </table>
                </div>
                <?php if($isAdminViewer): ?>
                <div class="col-lg-4">
                    <div class="small fw-semibold mb-2">OPD 7 hari tidak aktif (<?php echo e($opdTidakAktif->count()); ?>)</div>
                    <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                        <tbody>
                            <?php $__empty_2 = true; $__currentLoopData = $opdTidakAktif->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <tr>
                                    <td><?php echo e($opd['name']); ?></td>
                                    <td class="text-end small text-muted" style="width:110px;"><?php echo e($opd['last_agenda'] ? $opd['last_agenda']->diffForHumans() : 'Belum pernah'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <tr><td class="text-center text-muted">Semua OPD aktif. 🎉</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="row mt-5">
                <div class="col-6 small text-muted">
                    Dibuat oleh: <strong><?php echo e($generatedBy); ?></strong><br>
                    Pada: <?php echo e($generatedAt->translatedFormat('d F Y, H:i')); ?> WIB<br>
                    <span style="font-size:0.75rem;">Laporan dibuat otomatis oleh sistem.</span>
                </div>
                <div class="col-6 text-center small">
                    Bekasi, <?php echo e($generatedAt->translatedFormat('d F Y')); ?><br>
                    Mengetahui,<br><br><br><br>
                    ( .............................................. )
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\dashboard\analytics-preview.blade.php ENDPATH**/ ?>