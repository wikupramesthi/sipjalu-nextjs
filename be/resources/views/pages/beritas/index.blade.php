@extends('layouts.app')
@section('title', 'Kelola Berita')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Berita" page="Publikasi" active="{{ $trash ? 'Arsip Berita' : 'Daftar Berita' }}" route="{{ route('beritas.index') }}" />
@endsection

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
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $class)
        @if (session($key))
            <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (! $trash)
        <div class="row g-3 mb-3">
            @php
                $statCards = [
                    ['label' => 'Total Berita', 'value' => $stats['total'], 'icon' => 'bi-newspaper', 'color' => 'primary', 'url' => route('beritas.index')],
                    ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'bi-check-circle', 'color' => 'success', 'url' => route('beritas.index', ['status' => 'published'])],
                    ['label' => 'Draft', 'value' => $stats['draft'], 'icon' => 'bi-file-earmark-text', 'color' => 'secondary', 'url' => route('beritas.index', ['status' => 'draft'])],
                    ['label' => 'Menunggu Persetujuan', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'url' => route('beritas.index', ['status' => 'pending'])],
                    ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'bi-star-fill', 'color' => 'info', 'url' => route('beritas.index', ['featured' => 1])],
                    ['label' => 'Arsip', 'value' => $stats['trash'], 'icon' => 'bi-archive-fill', 'color' => 'danger', 'url' => $isAdmin ? route('beritas.index', ['trash' => 1]) : null],
                ];
            @endphp
            @foreach ($statCards as $card)
                <div class="col-12 col-md-4">
                    @if ($card['url'])
                        <a href="{{ $card['url'] }}" class="berita-stat-card">
                    @else
                        <div class="berita-stat-card">
                    @endif
                        <div class="card shadow-sm h-100">
                            <div class="card-body py-3 d-flex align-items-center gap-3">
                                <span class="berita-stat-icon bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }}">
                                    <i class="bi {{ $card['icon'] }}"></i>
                                </span>
                                <div>
                                    <div class="text-muted small">{{ $card['label'] }}</div>
                                    <div class="fs-4 fw-bold">{{ number_format($card['value']) }}</div>
                                </div>
                            </div>
                        </div>
                    @if ($card['url'])
                        </a>
                    @else
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <form method="GET" action="{{ route('beritas.index') }}" class="d-flex flex-wrap align-items-end gap-2">
                @if ($trash)<input type="hidden" name="trash" value="1">@endif
                <div class="flex-grow-1" style="min-width:200px">
                    <label class="form-label small mb-0" for="beritaSearch">Cari</label>
                    <input type="search" id="beritaSearch" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Judul berita...">
                </div>
                <div>
                    <label class="form-label small mb-0" for="beritaStatus">Status</label>
                    <select id="beritaStatus" name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach (['draft' => 'Draft', 'published' => 'Published', 'scheduled' => 'Terjadwal', 'pending' => 'Pending'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="beritaSort">Urutkan</label>
                    <select id="beritaSort" name="sort" class="form-select form-select-sm">
                        <option value="terbaru" @selected($sort === 'terbaru')>Terbaru</option>
                        <option value="terlama" @selected($sort === 'terlama')>Terlama</option>
                        <option value="populer" @selected($sort === 'populer')>Paling dilihat</option>
                        <option value="judul" @selected($sort === 'judul')>Judul A-Z</option>
                    </select>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('beritas.index', $trash ? ['trash' => 1] : []) }}" class="btn btn-sm btn-secondary">Reset</a>
                    <a href="{{ route('beritas.export', request()->query()) }}" class="btn btn-sm btn-success" title="Ekspor CSV sesuai filter"><i class="bi bi-download"></i></a>
                    @if (! $trash)
                        <a href="{{ route('beritas.import') }}" class="btn btn-sm btn-dark" title="Import Excel/CSV"><i class="bi bi-upload"></i></a>
                    @endif
                    @if ($isAdmin)
                        <a href="{{ route('beritas.logAudit') }}" class="btn btn-sm btn-info" title="Log Audit Berita"><i class="bi bi-clock-history"></i></a>
                    @endif
                    @if (! $trash)
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#beritaAdvFilter" title="Filter lanjutan dan laporan" aria-expanded="{{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('category_uuid') ? 'true' : 'false' }}">
                            <i class="bi bi-sliders"></i>
                        </button>
                    @endif
                </div>
                <div class="d-flex gap-1 ms-auto">
                    @if (! $trash)
                        @can('agendas.create')
                            <a href="{{ route('beritas.create') }}" class="btn btn-sm btn-primary text-nowrap"><i class="bi bi-plus-lg"></i> Tambah</a>
                        @endcan
                    @endif
                    @if ($isAdmin)
                        @if ($trash)
                            <a href="{{ route('beritas.index') }}" class="btn btn-sm btn-danger text-nowrap"><i class="bi bi-arrow-left"></i> Kembali</a>
                        @else
                            <a href="{{ route('beritas.index', ['trash' => 1]) }}" class="btn btn-sm btn-danger text-nowrap">
                                <i class="bi bi-archive"></i> Arsip <span class="badge text-bg-light">{{ $stats['trash'] }}</span>
                            </a>
                        @endif
                    @endif
                </div>
                @if (! $trash)
                    <div class="collapse w-100 mt-2 {{ request()->filled('start_date') || request()->filled('end_date') || request()->filled('category_uuid') ? 'show' : '' }}" id="beritaAdvFilter">
                        <div class="d-flex flex-wrap align-items-end gap-2 border-top pt-2">
                            <div>
                                <label class="form-label small mb-0" for="beritaStartDate">Publikasi dari</label>
                                <input type="date" id="beritaStartDate" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                            </div>
                            <div>
                                <label class="form-label small mb-0" for="beritaEndDate">Publikasi sampai</label>
                                <input type="date" id="beritaEndDate" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                            </div>
                            <div>
                                <label class="form-label small mb-0" for="beritaCategory">Kategori</label>
                                <select id="beritaCategory" name="category_uuid" class="form-select form-select-sm">
                                    <option value="">Semua kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->uuid }}" @selected(request('category_uuid') === $category->uuid)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="submit" formaction="{{ route('beritas.laporan') }}" formmethod="get" formtarget="_blank" class="btn btn-sm btn-danger" title="Unduh laporan PDF">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                                <button type="submit" formaction="{{ route('beritas.laporanExcel') }}" formmethod="get" class="btn btn-sm btn-success" title="Unduh laporan Excel">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                                </button>
                                <button type="submit" formaction="{{ route('beritas.laporanPptx') }}" formmethod="get" class="btn btn-sm btn-warning" title="Unduh laporan PowerPoint">
                                    <i class="bi bi-file-earmark-ppt"></i> PowerPoint
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $trash ? 'Arsip Berita' : 'Daftar Berita' }}</h5>
            <span class="text-muted small">{{ number_format($beritas->total()) }} berita</span>
        </div>
        @can('agendas.destroy')
            <div id="beritaBulkBar" class="card-body border-bottom d-none align-items-center gap-2">
                <span><strong id="beritaSelectedCount">0</strong> dipilih</span>
                @if ($trash)
                    @if ($isAdmin)
                        <button type="button" class="btn btn-sm btn-success" id="restoreSelectedBerita"><i class="bi bi-arrow-counterclockwise"></i> Pulihkan terpilih</button>
                    @endif
                @else
                    <button type="button" class="btn btn-sm btn-danger" id="archiveSelectedBerita"><i class="bi bi-archive"></i> Arsipkan terpilih</button>
                @endif
                <button type="button" class="btn btn-sm btn-light border" id="clearBeritaSelection">Batal pilih</button>
            </div>
            <form id="bulkArchiveBeritaForm" action="{{ route('beritas.bulkDestroy') }}" method="POST" class="d-none">@csrf @method('DELETE')<div id="bulkArchiveBeritaIds"></div></form>
            @if ($trash)
                <form id="bulkRestoreBeritaForm" action="{{ route('beritas.bulkRestore') }}" method="POST" class="d-none">@csrf @method('PUT')<div id="bulkRestoreBeritaIds"></div></form>
            @endif
        @endcan

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        @can('agendas.destroy')
                            <th class="text-center" style="width:38px"><input type="checkbox" class="form-check-input" id="checkAllBerita" aria-label="Pilih semua berita"></th>
                        @endcan
                        <th>Berita</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        @if ($trash)<th>Diarsipkan</th>@endif
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($beritas as $berita)
                        <tr>
                            @can('agendas.destroy')
                                <td class="text-center"><input type="checkbox" class="form-check-input berita-check" value="{{ $berita->uuid }}" aria-label="Pilih berita"></td>
                            @endcan
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($berita->featured_image)
                                        <img src="{{ asset('storage/' . $berita->featured_image) }}" alt="" class="rounded" width="64" height="48" style="object-fit:cover">
                                    @else
                                        <span class="bg-light rounded d-inline-flex align-items-center justify-content-center" style="width:64px;height:48px"><i class="bi bi-newspaper text-muted"></i></span>
                                    @endif
                                    <div>
                                        @if (! $trash)
                                            <a href="{{ route('beritas.show', $berita->uuid) }}" class="fw-semibold text-decoration-none">{{ $berita->title }}</a>
                                        @else
                                            <span class="fw-semibold">{{ $berita->title }}</span>
                                        @endif
                                        @if ($berita->is_featured)<i class="bi bi-star-fill text-warning ms-1" title="Featured"></i>@endif
                                        @if ($berita->is_popular)<i class="bi bi-fire text-danger ms-1" title="Populer"></i>@endif
                                        @if ($berita->is_urgent)<i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Mendesak"></i>@endif
                                        <div class="small text-muted">{{ Str::limit($berita->excerpt, 80) }}</div>
                                        <div class="small text-muted d-flex flex-wrap gap-3 mt-1">
                                            <span><i class="bi bi-person me-1"></i>{{ $berita->user?->name ?? '-' }}</span>
                                            <span><i class="bi bi-calendar3 me-1"></i>{{ $berita->scheduled_at?->format('d M Y H:i') ?? '-' }}</span>
                                            <span><i class="bi bi-eye me-1"></i>{{ number_format($berita->views) }} dilihat</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $berita->category?->name ?? '-' }}</td>
                            <td>
                                @if ($berita->status === 'published')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
                                @elseif ($berita->status === 'pending')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle" title="Menunggu persetujuan admin">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @elseif ($berita->status === 'scheduled')
                                    <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle">Scheduled</span>
                                @elseif ($berita->status === 'draft')
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">{{ ucfirst($berita->status) }}</span>
                                @endif
                            </td>
                            @if ($trash)<td>{{ $berita->deleted_at?->format('d M Y H:i') }}</td>@endif
                            <td class="text-center text-nowrap">
                                <div class="berita-actions">
                                @if ($trash)
                                    @if ($isAdmin)
                                        <form action="{{ route('beritas.restore', $berita->uuid) }}" method="POST" class="d-inline">@csrf @method('PUT')
                                            <button class="btn btn-sm btn-berita-action btn-berita-restore" title="Pulihkan" aria-label="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                        <form action="{{ route('beritas.forceDestroy', $berita->uuid) }}" method="POST" class="d-inline" data-confirm="Hapus permanen beserta semua media? Tindakan ini tidak dapat dibatalkan.">@csrf @method('DELETE')
                                            <button class="btn btn-sm btn-berita-action btn-berita-force" title="Hapus permanen" aria-label="Hapus permanen"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                @else
                                    @can('agendas.index')
                                        <a href="{{ route('beritas.show', $berita->uuid) }}" class="btn btn-sm btn-berita-action btn-berita-view" title="Lihat" aria-label="Lihat"><i class="bi bi-eye"></i></a>
                                    @endcan
                                    @if ($isAdmin && auth()->user()->can('agendas.approve') && $berita->status === 'pending')
                                        <form action="{{ route('beritas.approve', $berita->uuid) }}" method="POST" class="d-inline" data-confirm="Setujui dan publikasikan berita ini?">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-berita-action btn-berita-edit" title="Setujui dan publikasikan" aria-label="Setujui dan publikasikan"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-berita-action btn-berita-force" title="Tolak dan kembalikan ke draft" aria-label="Tolak dan kembalikan ke draft"
                                            data-review-action="reject" data-review-url="{{ route('beritas.reject', $berita->uuid) }}"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        <button type="button" class="btn btn-sm btn-berita-action btn-berita-view" title="Minta revisi" aria-label="Minta revisi"
                                            data-review-action="revision" data-review-url="{{ route('beritas.mintaRevisi', $berita->uuid) }}"><i class="bi bi-pencil-square"></i></button>
                                    @endif
                                    @can('agendas.update')
                                        <a href="{{ route('beritas.edit', $berita->uuid) }}" class="btn btn-sm btn-berita-action btn-berita-edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('agendas.index')
                                        <button type="button" class="btn btn-sm btn-berita-action btn-berita-copy" title="Salin tautan berita" aria-label="Salin tautan berita" data-copy-url="{{ route('beritas.show', $berita->uuid) }}">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    @endcan
                                    @can('agendas.destroy')
                                        <form action="{{ route('beritas.destroy', $berita->uuid) }}" method="POST" class="d-inline" data-confirm="Pindahkan berita ini ke arsip?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-berita-action btn-berita-archive" title="Arsipkan" aria-label="Arsipkan"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endcan
                                @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        @php($columnCount = ($trash ? 5 : 4) + (auth()->user()->can('agendas.destroy') ? 1 : 0))
                        <tr><td colspan="{{ $columnCount }}" class="text-center text-muted py-5">{{ $trash ? 'Arsip berita kosong.' : 'Belum ada berita.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Menampilkan {{ $beritas->firstItem() ?? 0 }}-{{ $beritas->lastItem() ?? 0 }} dari {{ $beritas->total() }}</small>
            {{ $beritas->links('pagination::minimal') }}
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
            token.value = @json(csrf_token());
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
@endsection
