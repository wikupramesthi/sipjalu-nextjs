@extends('layouts.app')
@section('title', 'Item Menu: ' . $websiteMenu->name)

@section('breadcrumb')
<x-breadcrumb title="Item Menu" page="Pengaturan" active="Item Menu: {{ $websiteMenu->name }}" route="{{ route('website-menu.items.index', $websiteMenu) }}" />
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-normal mb-0 text-body">{{ $websiteMenu->name }}</h4>
        <small class="text-muted">Lokasi: {{ ucfirst($websiteMenu->location) }} | Slug: <code>{{ $websiteMenu->slug }}</code></small>
    </div>

    <form method="GET" action="{{ route('website-menu.items.index', $websiteMenu) }}" class="d-flex gap-1">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari item..." style="min-width:200px;">
        <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
        @if(request('search'))
        <a href="{{ route('website-menu.items.index', $websiteMenu) }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
        @endif
    </form>

    <a href="{{ route('website-menu.items.create', $websiteMenu) }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Item
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            {{-- NOTE: jangan pakai id="table1", lihat penjelasan di pages/website-menu/index.blade.php.
                Pagination pakai server-side Laravel agar tidak ganda dengan DataTables. --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Link</th>
                        <th>Icon</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Posisi</th>
                        <th>Parent</th>
                        <th>Sub-item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ ($items->firstItem() ?? 0) + $loop->index }}</td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if ($item->description)
                            <br><small class="text-muted">{{ $item->description }}</small>
                            @endif
                        </td>
                        <td>
                            @if ($item->route)
                            <code class="text-primary">{{ $item->route }}</code>
                            @else
                            <code class="text-secondary">{{ $item->url ?: '-' }}</code>
                            @endif
                        </td>
                        <td>
                            @if ($item->icon)
                            <i class="bx {{ $item->icon }} fs-4"></i>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->target_blank)
                            <span class="badge bg-info">_blank</span>
                            @else
                            <span class="badge bg-light text-dark">_self</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->status)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $item->position }}</td>
                        <td>
                            <span class="text-muted">-</span>
                        </td>
                        <td>
                            @if ($item->allChildren->count())
                            <span class="badge bg-primary">{{ $item->allChildren->count() }} item</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('website-menu.items.edit', [$websiteMenu, $item]) }}" class="btn btn-icon btn-success text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="showSweetAlert('{{ $item->id }}')" title="Hapus" class="btn btn-icon btn-danger text-white">
                                <i class="bi bi-trash"></i>
                            </a>
                            <form id="deleteForm_{{ $item->id }}" action="{{ route('website-menu.items.destroy', [$websiteMenu, $item]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                            </form>
                        </td>
                    </tr>
                    @if ($item->allChildren->count())
                        @foreach ($item->allChildren as $child)
                        <tr class="table-light">
                            <td class="text-end"><small class="text-muted">↳</small></td>
                            <td>
                                <span class="ms-2">↳ {{ $child->name }}</span>
                                @if ($child->description)
                                <br><small class="text-muted ms-2">{{ $child->description }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($child->route)
                                <code class="text-primary">{{ $child->route }}</code>
                                @else
                                <code class="text-secondary">{{ $child->url ?: '-' }}</code>
                                @endif
                            </td>
                            <td>
                                @if ($child->icon)
                                <i class="bx {{ $child->icon }} fs-4"></i>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($child->target_blank)
                                <span class="badge bg-info">_blank</span>
                                @else
                                <span class="badge bg-light text-dark">_self</span>
                                @endif
                            </td>
                            <td>
                                @if ($child->status)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $child->position }}</td>
                            <td><span class="badge bg-secondary">{{ $item->name }}</span></td>
                            <td><span class="text-muted">-</span></td>
                            <td>
                                <a href="{{ route('website-menu.items.edit', [$websiteMenu, $child]) }}" class="btn btn-icon btn-success text-white btn-sm" title="Edit subitem">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a onclick="showSweetAlert('{{ $child->id }}')" title="Hapus subitem" class="btn btn-icon btn-danger text-white btn-sm">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <form id="deleteForm_{{ $child->id }}" action="{{ route('website-menu.items.destroy', [$websiteMenu, $child]) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
            <div class="text-muted small">
                Menampilkan <strong>{{ $items->firstItem() }}</strong> -
                <strong>{{ $items->lastItem() }}</strong> dari
                <strong>{{ $items->total() }}</strong> data
            </div>
            <div>{{ $items->links('pagination::minimal') }}</div>
        </div>
        @endif
    </div>
</div>

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus secara permanen beserta sub-item-nya. Apakah Anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection
