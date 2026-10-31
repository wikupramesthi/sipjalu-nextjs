@extends('layouts.app')
@section('title', 'Semua Kategori')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Kategori" page="Kategori" active="Semua Kategori" route="{{ route('categories.index') }}" />
@endsection
<!-- Content -->
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="fw-normal mb-0 text-body">Semua Kategori</h4>
                <form method="GET" action="{{ route('categories.index') }}" class="d-flex gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari kategori..." style="min-width:200px;">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                    @endif
                </form>
                @can('categories.store')
                <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                    data-bs-target="#modal-form-add-categories">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Kategori
                </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Total Agenda</th>
                            <th>Edit</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description  }}</td>
                            <td>{{ $item->agendas_count  }}</td>
                            <td>
                                @can('categories.update')
                                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-categories-{{  $item->uuid }}"
                                    class="btn btn-icon btn-success text-white">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                @include('pages.categories.modal-edit')
                                @endcan
                            </td>

                            <td>
                                @can('categories.destroy')
                                <a onclick="showSweetAlert('{{  $item->uuid }}')" title="Delete"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"></i> Hapus
                                </a>
                                <form id="deleteForm_{{  $item->uuid }}" action="{{ route('categories.destroy',  $item->uuid) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                @endcan
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@if ($categories->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">Menampilkan {{ $categories->firstItem() }}-{{ $categories->lastItem() }} dari {{ $categories->total() }} kategori</small>
    <div>{{ $categories->links('pagination::minimal') }}</div>
</div>
@endif
<!-- / Content -->

<!--/ Basic Bootstrap Table -->
@include('pages.categories.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Hapus media ini?',
            text: 'Media akan dihapus permanen dan tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks "Yes, delete it!", submit the corresponding form
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection