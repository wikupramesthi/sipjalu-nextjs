@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Pesan Masuk" page="Pengaturan" active="Pesan Masuk" route="{{ route('layanan.kontak') }}" />
@endsection

<div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show position-relative" role="alert">
    <div class="d-flex">
        <i class="bi-bell-fill text-white fs-1 me-3 flex-shrink-0 align-self-start"></i>

        <div class="text-white mt-0">
            <strong>Manajemen Pesan</strong> <br>
            Kelola dan tinjau pesan, pertanyaan, serta masukan yang disampaikan oleh pengunjung melalui formulir kontak.
        </div>
    </div>
</div>

<!-- Content -->
<section class="section">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible mb-3 mt-3 fade show" role="alert">
        <span class="alert-text text-white"> {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="fw-normal mb-0 text-body">Pesan Masuk</h4>
                <form method="GET" action="{{ route('layanan.kontak') }}" class="d-flex gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari pesan..." style="min-width:200px;">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                    <a href="{{ route('layanan.kontak') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Nomor Handphone</th>
                            <th>Isi Pesan</th>
                            <th>Tanggal dibuat</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kontaks as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_telp }}</td>
                            <td style="white-space: normal; word-break: break-word; max-width: 500px;">
                                {{ $item->isi }}
                            </td>

                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a onclick="showSweetAlert('{{ $item->uuid }}')" title="Delete"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"> Deleted</i>
                                </a>
                                <form id="deleteForm_{{ $item->uuid }}"
                                    action="{{ route('kontak.destroy', $item->uuid) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
            </div>
        </div>
    </section>
@if ($kontaks->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <small class="text-muted">Menampilkan {{ $kontaks->firstItem() }}-{{ $kontaks->lastItem() }} dari {{ $kontaks->total() }} pesan</small>
    <div>{{ $kontaks->links('pagination::minimal') }}</div>
</div>
@endif
<!-- / Content -->

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
                // If the user clicks "Yes, delete it!", submit the corresponding form
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection