@extends('layouts.app')
@section('title', 'Kelola Layanan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Kelola Layanan" page="Layanan" active="Daftar Layanan" route="{{ route('services.index') }}" />
@endsection

<style>
    .service-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllService { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarService { border: 1px solid rgba(244, 63, 94, .25); }
</style>

<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white">{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Kartu statistik: angka mengikuti data hasil filter --}}
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-briefcase"></i></div><div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Layanan</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['active']) }}</div><div class="ml-stat-label">Aktif</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['inactive']) }}</div><div class="ml-stat-label">Nonaktif</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-globe"></i></div><div><div class="ml-stat-value">{{ number_format($stats['external']) }}</div><div class="ml-stat-label">Eksternal</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-danger"><i class="bx bx-building"></i></div><div><div class="ml-stat-value">{{ number_format($stats['internal']) }}</div><div class="ml-stat-label">Internal</div></div></div></div></div></div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="{{ route('services.index') }}" class="d-flex flex-wrap align-items-end gap-2">
                    <div>
                        <label class="form-label small mb-0">Cari</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama..." value="{{ request('search') }}" style="min-width:160px;">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Kategori</label>
                        <select name="category" class="form-select form-select-sm" style="min-width:150px;">
                            <option value="">Semua</option>
                            <option value="external" @selected(request('category')==='external')>Eksternal</option>
                            <option value="internal" @selected(request('category')==='internal')>Internal</option>
                            <option value="other" @selected(request('category')==='other')>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-0">Status</label>
                        <select name="status" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="">Semua</option>
                            <option value="active" @selected(request('status')==='active')>Aktif</option>
                            <option value="inactive" @selected(request('status')==='inactive')>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        <a href="{{ route('services.index') }}" class="btn btn-sm btn-light">Reset</a>
                    </div>
                </form>
                @can('services.store')
                <a href="{{ route('services.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Daftar Layanan</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($stats['total']) }} layanan</span>
            </div>
            <small class="text-muted d-none d-md-block">{{ $services->firstItem() }}-{{ $services->lastItem() }} dari {{ $services->total() }}</small>
        </div>

        @can('services.destroy')
        <div id="bulkBarService" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 bg-danger-subtle">
            <span class="small fw-semibold text-danger"><i class="bi bi-check-square me-1"></i><span id="bulkCountService">0</span> layanan dipilih</span>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-light" onclick="clearServiceSelection()">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkDeleteService()"><i class="bi bi-trash"></i> Hapus terpilih</button>
            </div>
        </div>
        <form id="bulkDeleteServiceForm" action="{{ route('services.bulkDestroy') }}" method="POST" class="d-none">
            @csrf @method('DELETE')
            <div id="bulkIdsService"></div>
        </form>
        @endcan

        <div class="card-body p-0">
            <div class="table-responsive mx-2">
                <table class="table table-hover mb-0" style="font-size:0.9rem;">
                    <thead class="table-light">
                        <tr>
                            @can('services.destroy')<th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllService" class="form-check-input" title="Pilih semua"></th>@endcan
                            <th>No.</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th class="hide-xs">URL</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $item)
                        <tr>
                            @can('services.destroy')<td class="text-center"><input type="checkbox" class="form-check-input service-check" value="{{ $item->uuid }}"></td>@endcan
                            <td>{{ ($services->currentPage()-1)*$services->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="rounded" style="width:60px;height:42px;object-fit:cover;" loading="lazy">
                                @else
                                <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted" style="width:60px;height:42px;"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td style="min-width:180px;">
                                <span class="fw-semibold d-block">{{ Str::limit($item->name, 50) }}</span>
                                @if($item->description)<small class="text-muted d-block">{{ Str::limit($item->description, 60) }}</small>@endif
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $item->category_label }}</span></td>
                            <td class="hide-xs">
                                @if($item->url)
                                <a href="{{ $item->url }}" target="_blank" rel="noopener" class="small text-truncate d-inline-block" style="max-width:160px;">{{ $item->url }}</a>
                                @else
                                <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                @if($item->is_active)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('services.update')
                                    <a href="{{ route('services.edit', $item->uuid) }}" class="btn btn-sm btn-success" title="Edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('services.destroy')
                                    <a onclick="showSweetAlert('{{ $item->uuid }}')" title="Hapus" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    <form id="deleteForm_{{ $item->uuid }}" action="{{ route('services.destroy', $item->uuid) }}" method="POST" class="d-none">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada layanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">Menampilkan {{ $services->firstItem() }}-{{ $services->lastItem() }} dari {{ $services->total() }}</small>
                <div>{{ $services->links('pagination::minimal') }}</div>
            </div>
        </div>
    </div>
</section>

<script>
    function showSweetAlert(getId) {
        Swal.fire({title:'Hapus layanan?', text:'Akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!'}).then(r=>{ if(r.isConfirmed) document.getElementById('deleteForm_'+getId).submit(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAllService');
        const bulkBar = document.getElementById('bulkBarService');
        const bulkCount = document.getElementById('bulkCountService');
        if (!checkAll || !bulkBar) return;

        function updateBulkBar() {
            const checks = document.querySelectorAll('.service-check');
            const n = document.querySelectorAll('.service-check:checked').length;
            bulkCount.textContent = n;
            bulkBar.classList.toggle('d-none', n === 0);
            bulkBar.classList.toggle('d-flex', n > 0);
            checkAll.checked = n > 0 && n === checks.length;
            checkAll.indeterminate = n > 0 && n < checks.length;
            checks.forEach(c => c.closest('tr').classList.toggle('row-selected', c.checked));
        }

        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.service-check').forEach(c => { c.checked = this.checked; });
            updateBulkBar();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('service-check')) updateBulkBar();
        });

        window.clearServiceSelection = function () {
            document.querySelectorAll('.service-check:checked').forEach(c => { c.checked = false; });
            updateBulkBar();
        };

        window.bulkDeleteService = function () {
            const ids = Array.from(document.querySelectorAll('.service-check:checked')).map(c => c.value);
            if (!ids.length) return;
            Swal.fire({title:'Hapus ' + ids.length + ' layanan?', text:'Data terpilih akan dihapus permanen.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus!', confirmButtonColor:'#f43f5e'}).then(r => {
                if (r.isConfirmed) {
                    const form = document.getElementById('bulkDeleteServiceForm');
                    const box = document.getElementById('bulkIdsService');
                    box.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
                        box.appendChild(input);
                    });
                    form.submit();
                }
            });
        };

        updateBulkBar();
    });
</script>
@endsection
