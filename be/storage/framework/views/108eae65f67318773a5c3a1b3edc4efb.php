<?php $__env->startSection('title', 'Peta Aduan - GIS Live'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Peta GIS','page' => 'Peta Aduan Live','active' => 'GIS','route' => ''.e(route('gis.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<div class="page-content">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3 d-flex flex-wrap gap-2 align-items-end">
            <form method="GET" action="<?php echo e(route('gis.index')); ?>" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small mb-0">Kategori</label>
                    <select name="kategori" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = \App\Models\Aduan::KATEGORI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php if(request('kategori')==$k): echo 'selected'; endif; ?>><?php echo e($k); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = \App\Models\Aduan::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>" <?php if(request('status')==$s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0">Kecamatan</label>
                    <select name="kecamatan_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kec->id); ?>" <?php if(request('kecamatan_id')==$kec->id): echo 'selected'; endif; ?>><?php echo e($kec->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="<?php echo e(route('gis.index')); ?>" class="btn btn-light btn-sm">Reset</a>
                <a href="<?php echo e(route('gis.export', request()->query())); ?>" class="btn btn-success btn-sm"><i class="bi bi-download"></i> GeoJSON</a>
            </form>
            <div class="ms-auto small text-muted">
                <span class="badge bg-primary"><?php echo e($aduanCount ?? 0); ?> titik</span> dengan koordinat
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div id="gisMap" style="height: 520px; border-radius: 0.5rem;"></div>
        </div>
        <div class="card-footer small text-muted d-flex justify-content-between flex-wrap gap-2">
            <span>Cluster otomatis, klik marker untuk detail. Data: <?php echo e($periodeLabel ?? ''); ?></span>
            <span>Total: <?php echo e(number_format($aduanCount)); ?> | Menunggu: <?php echo e($perStatus['menunggu'] ?? 0); ?> | Selesai: <?php echo e($perStatus['selesai'] ?? 0); ?></span>
        </div>
    </div>
</div>

<?php $__env->startPush('after-style'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    .gis-popup b { color: #1B3A5F; }
    .gis-legend { background: #fff; padding: 8px 10px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,.15); font-size: 11px; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('after-script'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
    const aduans = <?php echo json_encode($aduanGeo, 15, 512) ?>;
    const centerBekasi = [-6.2416, 106.9924];
    const map = L.map('gisMap').setView(centerBekasi, 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 18
    }).addTo(map);

    const cluster = L.markerClusterGroup();
    const colors = { menunggu: '#6c757d', diverifikasi: '#0d99c7', diproses: '#b8860b', selesai: '#198754', ditolak: '#dc3545' };

    aduans.forEach(a => {
        if (!a.latitude || !a.longitude) return;
        const col = colors[a.status] || '#1B3A5F';
        const icon = L.divIcon({
            html: `<div style="background:${col}; width:14px; height:14px; border-radius:50%; border:2px solid #fff; box-shadow:0 2px 6px rgba(0,0,0,.3)"></div>`,
            className: '',
            iconSize: [14,14]
        });
        const popup = `
            <div class="gis-popup" style="min-width:200px;">
                <b>${a.judul}</b><br>
                <small style="color:#6b7280;">#${a.nomor_aduan} | ${a.kategori} | ${a.status}</small><br>
                <small>${a.lokasi || '-'}<br>${a.kecamatan || ''} ${a.kelurahan || ''}</small><br>
                <small style="color:#6b7280;">${a.tanggal_pengaduan || ''}</small><br>
                <a href="/backend/aduans/${a.uuid}" class="btn btn-sm btn-primary mt-1">Detail</a>
            </div>`;
        L.marker([a.latitude, a.longitude], {icon}).bindPopup(popup).addTo(cluster);
    });
    map.addLayer(cluster);
    if (aduans.length > 0) {
        try { map.fitBounds(cluster.getBounds().pad(0.2)); } catch(e) {}
    }
    // legend
    const legend = L.control({position: 'bottomright'});
    legend.onAdd = function() {
        const div = L.DomUtil.create('div', 'gis-legend');
        div.innerHTML = '<b>Status</b><br>' + Object.entries(colors).map(([k,c])=>`<span style="display:inline-block;width:10px;height:10px;background:${c};border-radius:50%;margin-right:4px;"></span>${k}`).join('<br>');
        return div;
    };
    legend.addTo(map);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\gis\index.blade.php ENDPATH**/ ?>