@extends('layouts.app')
@section('title', 'Peta Aduan - GIS Live')
@section('breadcrumb')
<x-breadcrumb title="Peta GIS" page="Peta Aduan Live" active="GIS" route="{{ route('gis.index') }}" />
@endsection
@section('content')
<div class="page-content">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3 d-flex flex-wrap gap-2 align-items-end">
            <form method="GET" action="{{ route('gis.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small mb-0">Kategori</label>
                    <select name="kategori" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach(\App\Models\Aduan::KATEGORI as $k)
                            <option value="{{ $k }}" @selected(request('kategori')==$k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach(\App\Models\Aduan::STATUS as $s)
                            <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0">Kecamatan</label>
                    <select name="kecamatan_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" @selected(request('kecamatan_id')==$kec->id)>{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('gis.index') }}" class="btn btn-light btn-sm">Reset</a>
                <a href="{{ route('gis.export', request()->query()) }}" class="btn btn-success btn-sm"><i class="bi bi-download"></i> GeoJSON</a>
            </form>
            <div class="ms-auto small text-muted">
                <span class="badge bg-primary">{{ $aduanCount ?? 0 }} titik</span> dengan koordinat
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div id="gisMap" style="height: 520px; border-radius: 0.5rem;"></div>
        </div>
        <div class="card-footer small text-muted d-flex justify-content-between flex-wrap gap-2">
            <span>Cluster otomatis, klik marker untuk detail. Data: {{ $periodeLabel ?? '' }}</span>
            <span>Total: {{ number_format($aduanCount) }} | Menunggu: {{ $perStatus['menunggu'] ?? 0 }} | Selesai: {{ $perStatus['selesai'] ?? 0 }}</span>
        </div>
    </div>
</div>

@push('after-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    .gis-popup b { color: #1B3A5F; }
    .gis-legend { background: #fff; padding: 8px 10px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,.15); font-size: 11px; }
</style>
@endpush
@push('after-script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
    const aduans = @json($aduanGeo);
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
@endpush
@endsection
