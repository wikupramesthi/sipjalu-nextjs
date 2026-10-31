@extends('layouts.app')
@section('title', 'Menu Website')

@section('breadcrumb')
<x-breadcrumb title="Menu Website" page="Pengaturan" active="Menu Website" route="{{ route('website-menu.index') }}" />
@endsection

@section('content')
<div class="row mb-4 g-3 ml-stats">
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="ml-stat">
                    <div class="ml-stat-icon ic-primary"><i class="bx bx-menu"></i></div>
                    <div>
                        <div class="ml-stat-value">{{ $menus->total() }}</div>
                        <div class="ml-stat-label">Total Menu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="ml-stat">
                    <div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div>
                    <div>
                        <div class="ml-stat-value">{{ number_format($stats['active']) }}</div>
                        <div class="ml-stat-label">Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="ml-stat">
                    <div class="ml-stat-icon ic-warning"><i class="bx bx-x-circle"></i></div>
                    <div>
                        <div class="ml-stat-value">{{ number_format($stats['inactive']) }}</div>
                        <div class="ml-stat-label">Nonaktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="fw-normal mb-0 text-body">Daftar Menu Website</h4>
        <form method="GET" action="{{ route('website-menu.index') }}" class="d-flex gap-1">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari menu..." style="min-width:200px;">
            <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
            @if(request('search'))
            <a href="{{ route('website-menu.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
            @endif
        </form>
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#modal-form-add">
            <i class="bi bi-plus-lg"></i> Tambah Menu
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            {{-- NOTE: jangan pakai id="table1" di sini, karena layouts/app.blade.php
                otomatis init DataTables (pagination client-side) pada #table1.
                Halaman ini pakai pagination server-side Laravel, jadi biarkan tanpa id
                agar tidak muncul 2 pagination yang saling konflik. --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Posisi</th>
                        <th>Item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                    <tr>
                        <td>{{ ($menus->firstItem() ?? 0) + $loop->index }}</td>
                        <td>{{ $menu->name }}</td>
                        <td><code>{{ $menu->slug }}</code></td>
                        <td>
                            <span class="badge bg-{{ match($menu->location) {
                                'header' => 'primary',
                                'footer' => 'secondary',
                                'sidebar' => 'success',
                                'mobile' => 'info',
                                default => 'dark'
                            } }}">
                                {{ ucfirst($menu->location) }}
                            </span>
                        </td>
                        <td>
                            @if ($menu->status)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $menu->position }}</td>
                        <td>
                            <a href="{{ route('website-menu.items.index', $menu) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-list-ul"></i> Kelola Item
                            </a>
                        </td>
                        <td>

                            <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-{{ $menu->id }}" class="btn btn-icon btn-success text-white">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @include('pages.website-menu.modal-edit')

                            <a onclick="showSweetAlert('{{ $menu->id }}')" title="Hapus" class="btn btn-icon btn-danger text-white">
                                <i class="bi bi-trash"></i>
                            </a>
                            <form id="deleteForm_{{ $menu->id }}" action="{{ route('website-menu.destroy', $menu) }}" method="POST">
                                @method('DELETE')
                                @csrf
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($menus->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
            <div class="text-muted small">
                Menampilkan <strong>{{ $menus->firstItem() }}</strong> -
                <strong>{{ $menus->lastItem() }}</strong> dari
                <strong>{{ $menus->total() }}</strong> data
            </div>
            <div>{{ $menus->links('pagination::minimal') }}</div>
        </div>
        @endif
    </div>
</div>

@include('pages.website-menu.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus secara permanen beserta item-nya. Apakah Anda yakin?',
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
