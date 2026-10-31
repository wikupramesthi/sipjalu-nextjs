@extends('layouts.app')
@section('title', 'Aduan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Aduan" page="Layanan" active="Semua Aduan" route="{{ route('aduans.index') }}" />
@endsection

<style>
    .aduan-check { width: 17px; height: 17px; cursor: pointer; }
    #checkAllAduan { width: 17px; height: 17px; cursor: pointer; }
    tr.row-selected > td { background-color: rgba(13, 110, 253, .07) !important; }
    #bulkBarAduan { border: 1px solid rgba(244, 63, 94, .25); }
    #bulkBarAduanRestore { border: 1px solid rgba(251, 191, 36, .4); }
</style>

<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white"> {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
        <span class="alert-text text-white"> {{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- Kartu statistik: angka mengikuti tab & filter yang aktif --}}
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-inbox"></i></div><div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Aduan</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-time"></i></div><div><div class="ml-stat-value">{{ number_format($stats['menunggu']) }}</div><div class="ml-stat-label">Menunggu</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-violet"><i class="bx bx-search-alt"></i></div><div><div class="ml-stat-value">{{ number_format($stats['diverifikasi']) }}</div><div class="ml-stat-label">Diverifikasi</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-danger"><i class="bx bx-cog"></i></div><div><div class="ml-stat-value">{{ number_format($stats['diproses']) }}</div><div class="ml-stat-label">Diproses</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['selesai']) }}</div><div class="ml-stat-label">Selesai</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-secondary"><i class="bx bx-x-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['ditolak']) }}</div><div class="ml-stat-label">Ditolak</div></div></div></div></div></div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="btn-group" role="group" aria-label="Tampilan data">
                    <a href="{{ route('aduans.index') }}"
                        class="btn btn-sm {{ $tampil === 'aktif' ? 'btn-primary' : 'btn-light' }}">
                        <i class="bi bi-inbox me-1"></i> Data Aktif
                    </a>
                    <a href="{{ route('aduans.index', ['tampil' => 'sampah']) }}"
                        class="btn btn-sm {{ $tampil === 'sampah' ? 'btn-primary' : 'btn-light' }}">
                        <i class="bi bi-trash me-1"></i> Tempat Sampah
                        @if ($trashCount > 0)
                        <span class="badge bg-danger ms-1">{{ $trashCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('aduans.index', ['tampil' => 'arsip']) }}"
                        class="btn btn-sm {{ $tampil === 'arsip' ? 'btn-primary' : 'btn-light' }}">
                        <i class="bi bi-archive me-1"></i> Arsip
                        @if ($arsipCount > 0)
                        <span class="badge bg-secondary ms-1">{{ $arsipCount }}</span>
                        @endif
                    </a>
                </div>

                @if ($tampil === 'aktif')
                <div class="d-flex gap-2">
                    @can('aduans.export')
                    <a href="{{ route('aduans.exportPdf', request()->query()) }}" class="btn btn-danger btn-md">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Laporan
                    </a>
                    @endcan
                    @can('aduans.store')
                    <a href="{{ route('aduans.create') }}" class="btn btn-primary btn-md">
                        <i class="bi bi-plus-lg"></i> Tambah Aduan
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if ($tampil === 'sampah')
            <div class="alert alert-warning py-2">
                Data di tempat sampah tidak tampil di laporan &amp; API. Kembalikan untuk mengaktifkan lagi,
                atau hapus permanen (foto ikut terhapus dan tidak bisa dikembalikan).
            </div>
            @elseif ($tampil === 'arsip')
            <div class="alert alert-info py-2">
                Arsip berisi aduan selesai yang sudah lama. Data arsip tidak tampil di daftar aktif &amp; API publik,
                tetapi tetap bisa dicari di sini dan dilacak lewat nomor aduan.
            </div>
            @endif

            {{-- Filter --}}
            <form method="GET" action="{{ route('aduans.index') }}" class="mb-4">
                <input type="hidden" name="tampil" value="{{ $tampil }}">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="filter-search" class="form-label small fw-semibold">Pencarian</label>
                        <input type="text" id="filter-search" name="search" class="form-control"
                            placeholder="Nomor / judul / lokasi" value="{{ request('search') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="filter-kategori" class="form-label small fw-semibold">Kategori</label>
                        <select id="filter-kategori" name="kategori" class="form-select">
                            <option value="">Semua</option>
                            @foreach (\App\Models\Aduan::KATEGORI as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="filter-kecamatan" class="form-label small fw-semibold">Kecamatan</label>
                        <select id="filter-kecamatan" name="kecamatan_id" class="form-select">
                            <option value="">Semua</option>
                            @foreach ($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="filter-status" class="form-label small fw-semibold">Status</label>
                        <select id="filter-status" name="status" class="form-select">
                            <option value="">Semua</option>
                            @foreach (['menunggu','diverifikasi','diproses','selesai','ditolak'] as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="filter-prioritas" class="form-label small fw-semibold">Prioritas</label>
                        <select id="filter-prioritas" name="prioritas" class="form-select">
                            <option value="">Semua</option>
                            @foreach (['rendah','sedang','tinggi','darurat'] as $pr)
                            <option value="{{ $pr }}" {{ request('prioritas') == $pr ? 'selected' : '' }}>
                                {{ ucfirst($pr) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Periode Pengaduan</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" id="filter-dari" name="tanggal_mulai" class="form-control"
                                    value="{{ request('tanggal_mulai') }}" title="Dari tanggal">
                            </div>
                            <div class="col-6">
                                <input type="date" id="filter-sampai" name="tanggal_selesai" class="form-control"
                                    value="{{ request('tanggal_selesai') }}" title="Sampai tanggal">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        @if (request()->except(['tampil', 'page']))
                        <a href="{{ route('aduans.index', ['tampil' => $tampil]) }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Tabel --}}
            @php
            $bulkMode = $tampil === 'aktif' ? 'trash' : ($tampil === 'sampah' ? 'restore' : null);
            $canBulk = ($bulkMode === 'trash' && auth()->user()->can('aduans.destroy'))
                || ($bulkMode === 'restore' && auth()->user()->can('aduans.restore'));
            @endphp
            @if ($canBulk)
            <div id="bulkBarAduan{{ $bulkMode === 'restore' ? 'Restore' : '' }}" class="d-none align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 mb-2 rounded {{ $bulkMode === 'restore' ? 'bg-warning-subtle' : 'bg-danger-subtle' }}">
                <span class="small fw-semibold {{ $bulkMode === 'restore' ? 'text-warning-emphasis' : 'text-danger' }}"><i class="bi bi-check-square me-1"></i><span id="bulkCountAduan">0</span> aduan dipilih</span>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-light" onclick="clearAduanSelection()">Batal</button>
                    @if ($bulkMode === 'trash')
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkTrashAduan()"><i class="bi bi-trash"></i> Pindah ke sampah</button>
                    @else
                    <button type="button" class="btn btn-sm btn-warning text-white" onclick="bulkRestoreAduan()"><i class="bi bi-arrow-counterclockwise"></i> Kembalikan</button>
                    @endif
                </div>
            </div>
            <form id="bulkAduanForm" action="{{ $bulkMode === 'trash' ? route('aduans.bulkDestroy') : route('aduans.bulkRestore') }}" method="POST" class="d-none">
                @csrf
                <div id="bulkIdsAduan"></div>
            </form>
            @endif
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @if ($canBulk)<th class="text-center" style="width:38px;"><input type="checkbox" id="checkAllAduan" class="form-check-input" title="Pilih semua"></th>@endif
                            <th>No.</th>
                            <th>Nomor Aduan</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pelapor</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>{{ $tampil === 'sampah' ? 'Dihapus Pada' : 'Tgl Pengaduan' }}</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                        <tr>
                            @if ($canBulk)<td class="text-center"><input type="checkbox" class="form-check-input aduan-check" value="{{ $item->uuid }}"></td>@endif
                            <td>{{ $items->firstItem() + $loop->index }}</td>
                            <td><code>{{ $item->nomor_aduan }}</code></td>
                            <td>{{ $item->judul }}</td>
                            <td><span class="badge bg-info">{{ $item->kategori }}</span></td>
                            <td>{{ $item->user->name ?? '-' }}
                                @if ($item->is_anonim)
                                <br><span class="badge bg-dark">Anonim</span>
                                @endif
                            </td>
                            <td>{{ $item->lokasi ?? '-' }}<br>
                                <small class="text-muted">{{ $item->kecamatan->nama ?? '' }}{{ $item->kelurahan ? ' - ' . $item->kelurahan->nama : '' }}</small>
                            </td>
                            <td>
                                @php
                                $badge = ['menunggu' => 'secondary','diverifikasi' => 'info','diproses' => 'warning','selesai' => 'success','ditolak' => 'danger'][$item->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td>
                                @php
                                $prio = ['rendah' => 'secondary','sedang' => 'primary','tinggi' => 'warning','darurat' => 'danger'][$item->prioritas] ?? 'primary';
                                @endphp
                                <span class="badge bg-{{ $prio }}">{{ ucfirst($item->prioritas) }}</span>
                            </td>
                            <td>
                                {{ $tampil === 'sampah'
                                    ? $item->deleted_at?->format('d M Y H:i')
                                    : ($tampil === 'arsip'
                                        ? $item->archived_at?->format('d M Y H:i')
                                        : $item->tanggal_pengaduan?->format('d M Y H:i') ?? '-') }}
                            </td>
                            <td>
                                @if ($tampil === 'sampah')
                                <div class="d-flex gap-1">
                                    @can('aduans.restore')
                                    <form action="{{ route('aduans.restore', $item->uuid) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning text-white" title="Kembalikan">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    @endcan
                                    @can('aduans.forceDelete')
                                    <a onclick="confirmForceDelete('{{ $item->uuid }}')" title="Hapus permanen"
                                        class="btn btn-sm btn-danger text-white">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="forceDeleteForm_{{ $item->uuid }}"
                                        action="{{ route('aduans.forceDestroy', $item->uuid) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                    @endcan
                                </div>
                                @elseif ($tampil === 'arsip')
                                <div class="d-flex gap-1">
                                    <a href="{{ route('aduans.show', $item->uuid) }}"
                                        class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('aduans.batalArsip')
                                    <form action="{{ route('aduans.batalArsip', $item->uuid) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning text-white" title="Kembalikan ke aktif">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                                @else
                                <div class="d-flex gap-1">
                                    <a href="{{ route('aduans.show', $item->uuid) }}"
                                        class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('aduans.tindaklanjut.store')
                                    <a data-bs-toggle="modal"
                                        data-bs-target="#modal-tindak-lanjut-{{ $item->uuid }}"
                                        class="btn btn-sm btn-warning text-white" title="Tindak Lanjut">
                                        <i class="bi bi-tools"></i>@if ($item->tindak_lanjuts_count > 0) {{ $item->tindak_lanjuts_count }}@endif
                                    </a>
                                    @include('pages.aduan.modal-tindak-lanjut')
                                    @endcan
                                    @can('aduans.arsip')
                                    <a onclick="confirmArsip('{{ $item->uuid }}')" title="Arsipkan"
                                        class="btn btn-sm btn-secondary text-white">
                                        <i class="bi bi-archive"></i>
                                    </a>
                                    <form id="arsipForm_{{ $item->uuid }}"
                                        action="{{ route('aduans.arsip', $item->uuid) }}" method="POST">
                                        @csrf
                                    </form>
                                    @endcan
                                    @php
                                    // Warga hanya boleh ubah/hapus selagi status masih menunggu.
                                    $bisaUbah = auth()->user()->hasAnyRole(['super-admin', 'admin', 'uptd'])
                                        || $item->status === 'menunggu';
                                    @endphp
                                    @if ($bisaUbah)
                                    @can('aduans.update')
                                    <a href="{{ route('aduans.edit', $item->uuid) }}"
                                        class="btn btn-sm btn-success text-white" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan
                                    @can('aduans.destroy')
                                    <a onclick="moveToTrash('{{ $item->uuid }}')" title="Pindah ke sampah"
                                        class="btn btn-sm btn-danger text-white">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="trashForm_{{ $item->uuid }}"
                                        action="{{ route('aduans.destroy', $item->uuid) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                    @endcan
                                    @endif
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $canBulk ? 11 : 10 }}" class="text-center">
                                {{ $tampil === 'sampah' ? 'Tempat sampah kosong.' : ($tampil === 'arsip' ? 'Belum ada arsip.' : 'Belum ada data aduan.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $items->links('pagination::minimal') }}
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
@endsection


