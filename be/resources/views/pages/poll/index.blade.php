@extends('layouts.app')
@section('title', 'Poling Publik')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Pusat Informasi" page="Pusat Informasi" active="Poling Publik" route="{{ route('poll.index') }}" />
@endsection
<!-- Content -->
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="fw-normal mb-0 text-body">Poling Publik</h4>
                <form method="GET" action="{{ route('poll.index') }}" class="d-flex gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari poling..." style="min-width:200px;">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                    <a href="{{ route('poll.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                    @endif
                </form>
                @can('poll.store')
                <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                    data-bs-target="#modal-form-add-polls">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Baru
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
                            <th>Pertanyaan</th>
                            <th>Opsi</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polls as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->question }}</td>
                            <td>
                                @foreach($item->options as $option)
                                <span class="badge bg-primary">{{ $option }}</span>
                                @endforeach
                            </td>
                            <td>{{ $item->status }}</td>
                            <td>
                                @can('poll.update')
                                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-polls-{{  $item->uuid }}"
                                    class="btn btn-icon btn-success text-white">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                @include('pages.poll.modal-edit')
                                @endcan
                            </td>

                            <td>
                                @can('poll.destroy')
                                <a onclick="showSweetAlert('{{  $item->uuid }}')" title="Delete"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"></i> Hapus
                                </a>
                                <form id="deleteForm_{{  $item->uuid }}" action="{{ route('poll.destroy',  $item->uuid) }}"
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
@if ($polls->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">Menampilkan {{ $polls->firstItem() }}-{{ $polls->lastItem() }} dari {{ $polls->total() }} poling</small>
    <div>{{ $polls->links('pagination::minimal') }}</div>
</div>
@endif
<!-- / Content -->

<!--/ Basic Bootstrap Table -->
@include('pages.poll.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Data ini akan dihapus secara permanen dan tidak bisa dikembalikan. Apakah Anda yakin ingin menghapusnya?',
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


<script>
document.getElementById('add-option').addEventListener('click', function() {
    let wrapper = document.getElementById('options-wrapper');
    let input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.classList.add('form-control', 'mb-2');
    input.placeholder = 'Opsi tambahan';
    wrapper.appendChild(input);
});
</script>

@endsection