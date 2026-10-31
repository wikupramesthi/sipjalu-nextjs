@extends('layouts.app')
@section('title', 'Health Check')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Sistem" page="Monitoring" active="Health Check" route="{{ route('health.page') }}" />
@endsection

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="fw-normal mb-0 text-body">Status Kesehatan Sistem</h4>
            <button class="btn btn-sm btn-outline-primary" id="btn-refresh">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
        </div>
        <div class="card-body">
            <div id="health-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memeriksa...</span>
                </div>
                <p class="mt-2 text-muted">Memeriksa status sistem...</p>
            </div>

            <div id="health-result" class="d-none">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card h-100 border-0 shadow-sm bg-light">
                            <div class="card-body text-center p-4">
                                <div id="overall-icon" class="fs-1 mb-2"></div>
                                <h5 class="mb-1" id="overall-status" role="button" tabindex="0"
                                    aria-controls="health-details-modal" aria-haspopup="dialog">Memeriksa...</h5>
                                <small class="text-muted d-block">Klik status untuk melihat rincian</small>
                                <small class="text-muted" id="overall-time"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3" id="checks-container">
                    <!-- Dynamic checks will be inserted here -->
                </div>

                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="last-check">Terakhir diperiksa: -</small>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="copyToClipboard()">
                            <i class="bi bi-clipboard me-1"></i> Salin JSON
                        </button>
                        <a href="{{ route('health') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Raw JSON
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="health-details-modal" tabindex="-1" aria-labelledby="health-details-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="health-details-title">Rincian Health Check</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="health-details-content">
                <div class="text-center py-3">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="ms-2">Memuat rincian...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-script')
<script>
    const HEALTH_URL = '{{ route("health") }}';
    const HEALTH_DETAILS_URL = '{{ route("health.details") }}';

    async function checkHealth() {
        document.getElementById('health-loading').classList.remove('d-none');
        document.getElementById('health-result').classList.add('d-none');

        try {
            const response = await fetch(HEALTH_URL);
            const data = await response.json();
            renderHealth(data, response.ok);
        } catch (err) {
            renderError(err);
        }
    }

    function renderHealth(data, httpOk) {
        if (!data || typeof data !== 'object' || Array.isArray(data)) {
            renderError(new Error('Respons health check tidak valid.'));
            return;
        }

        document.getElementById('health-loading').classList.add('d-none');
        document.getElementById('health-result').classList.remove('d-none');

        // Overall status
        const overallIcon = document.getElementById('overall-icon');
        const overallStatus = document.getElementById('overall-status');
        const overallTime = document.getElementById('overall-time');

        if (data.status === 'ok') {
            overallIcon.innerHTML = '<i class="bi bi-shield-check text-success"></i>';
            overallStatus.innerHTML = '<span class="text-success fw-bold">SEHAT</span>';
            overallStatus.className = 'mb-1 text-success';
        } else {
            overallIcon.innerHTML = '<i class="bi bi-shield-x text-danger"></i>';
            overallStatus.innerHTML = '<span class="text-danger fw-bold">TERGANGGU</span>';
            overallStatus.className = 'mb-1 text-danger';
        }
        overallTime.textContent = data.waktu ? `Diperiksa: ${data.waktu}` : 'Waktu pemeriksaan tidak tersedia';

        // Individual checks
        const container = document.getElementById('checks-container');
        container.innerHTML = '';
        const checks = data.pemeriksaan && typeof data.pemeriksaan === 'object' && !Array.isArray(data.pemeriksaan)
            ? data.pemeriksaan
            : null;

        if (!checks) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        Endpoint health check hanya menyediakan status keseluruhan; rincian komponen tidak tersedia.
                    </div>
                </div>
            `;
        }

        for (const [key, check] of Object.entries(checks || {})) {
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6 col-lg-4';
            col.innerHTML = `
                <div class="card h-100 ${check.ok ? 'border-success' : 'border-danger'} shadow-sm">
                    <div class="card-header ${check.ok ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10'} border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold capitalize">${key}</h6>
                            <span class="badge ${check.ok ? 'bg-success' : 'bg-danger'}">
                                ${check.ok ? 'OK' : 'GAGAL'}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        ${check.pesan ? `<p class="small text-muted mb-2">${escapeHtml(check.pesan)}</p>` : ''}
                        ${check.jalan_terakhir ? `<small class="text-muted">Terakhir: ${check.jalan_terakhir}</small>` : ''}
                    </div>
                </div>
            `;
            container.appendChild(col);
        }

        document.getElementById('last-check').textContent = `Terakhir diperiksa: ${new Date().toLocaleString('id-ID')}`;
        window.lastHealthData = data;
    }

    function renderError(err) {
        document.getElementById('health-loading').classList.add('d-none');
        document.getElementById('health-result').classList.remove('d-none');

        document.getElementById('overall-icon').innerHTML = '<i class="bi bi-wifi-off text-warning"></i>';
        document.getElementById('overall-status').innerHTML = '<span class="text-warning fw-bold">TIDAK DAPAT DIJANGKAU</span>';
        document.getElementById('overall-time').textContent = `Error: ${err.message}`;

        document.getElementById('checks-container').innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    Gagal mengambil data health check. Pastikan endpoint <code>/health</code> accessible.
                    <pre class="mt-2 small">${escapeHtml(err.stack || err.message)}</pre>
                </div>
            </div>
        `;
    }

    async function showHealthDetails() {
        const modalElement = document.getElementById('health-details-modal');
        const content = document.getElementById('health-details-content');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        content.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ms-2">Memuat rincian...</span></div>';
        modal.show();

        try {
            const response = await fetch(HEALTH_DETAILS_URL, {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) {
                throw new Error(`Permintaan rincian gagal (HTTP ${response.status}).`);
            }

            const data = await response.json();
            const checks = data.pemeriksaan;
            if (!checks || typeof checks !== 'object' || Array.isArray(checks)) {
                throw new Error('Data rincian health check tidak valid.');
            }

            const entries = Object.entries(checks);
            content.innerHTML = entries.length
                ? entries.map(([key, check]) => `
                    <div class="d-flex justify-content-between align-items-start gap-3 py-3 border-bottom">
                        <div>
                            <strong class="text-capitalize">${escapeHtml(key)}</strong>
                            <div class="small text-muted mt-1">${escapeHtml(check.pesan || (check.ok ? 'Berjalan normal.' : 'Pemeriksaan gagal tanpa keterangan tambahan.'))}</div>
                            ${check.jalan_terakhir ? `<div class="small text-muted mt-1">Terakhir berjalan: ${escapeHtml(check.jalan_terakhir)}</div>` : ''}
                        </div>
                        <span class="badge ${check.ok ? 'bg-success' : 'bg-danger'}">${check.ok ? 'OK' : 'GAGAL'}</span>
                    </div>
                `).join('')
                : '<div class="alert alert-info mb-0">Tidak ada komponen untuk diperiksa.</div>';

            if (data.waktu) {
                content.insertAdjacentHTML('beforeend', `<small class="text-muted d-block mt-3">Diperiksa: ${escapeHtml(data.waktu)}</small>`);
            }
        } catch (error) {
            content.innerHTML = `<div class="alert alert-warning mb-0">${escapeHtml(error.message)}</div>`;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function copyToClipboard() {
        if (!window.lastHealthData) return;
        navigator.clipboard.writeText(JSON.stringify(window.lastHealthData, null, 2))
            .then(() => {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'JSON disalin!', timer: 1500, showConfirmButton: false });
            });
    }

    // Auto-refresh every 60 seconds
    let autoRefreshInterval = setInterval(checkHealth, 60000);

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => clearInterval(autoRefreshInterval));

    // Manual refresh button
    document.getElementById('btn-refresh').addEventListener('click', checkHealth);
    const overallStatus = document.getElementById('overall-status');
    overallStatus.addEventListener('click', showHealthDetails);
    overallStatus.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            showHealthDetails();
        }
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', checkHealth);
</script>
<style>
    .capitalize { text-transform: capitalize; }
    #overall-status { cursor: pointer; text-decoration: underline dotted; text-underline-offset: 3px; }
    .card-header.bg-success.bg-opacity-10 { background-color: rgba(45, 206, 137, 0.1) !important; }
    .card-header.bg-danger.bg-opacity-10 { background-color: rgba(245, 54, 92, 0.1) !important; }
</style>
@endpush
@endsection