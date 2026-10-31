@extends('layouts.app')
@section('title', 'Daftar Pengguna')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Daftar Pengguna" page="Pengaturan" active="Daftar Pengguna" route="{{ route('user.index') }}" />
@endsection
<!-- Content -->
<section class="section mt-2">
    <div class="card">
        <div class="card-header">

            <div class="col-12 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3">
                <div class="d-flex justify-content-between align-items-center ">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 w-100">

                        <form action="{{ route('user.index') }}" method="GET"
                            class="row g-2 align-items-center flex-grow-1">

                            {{-- Tanggal Mulai --}}
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Dari</span>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                                        class="form-control">
                                </div>
                            </div>

                            {{-- Tanggal Akhir --}}
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Sampai</span>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                                        class="form-control">
                                </div>
                            </div>

                            {{-- Filter Role --}}
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <select name="role" class="form-select" style="min-width:160px;">
                                        <option value="">Semua Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                                        @endforeach
                                        <option value="__none" @selected(request('role') === '__none')>Tanpa Role</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-sm btn-success" type="submit">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-3">
                    @can('user.store')
                        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                            data-bs-target="#modal-form-add-user">
                            <i class="bi bi-plus-lg"></i>
                            Tambah Baru
                        </button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Daftar</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Hapus</th>

                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->email_verified_at ? $user->email_verified_at->format('d-m-Y H:i:s') : ($user->created_at ? $user->created_at->format('d-m-Y H:i:s') : '-') }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <span class="badge bg-light-info me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge bg-light-secondary">Tanpa Role</span>
                                    @endforelse
                                </td>
                                <td>
                                    @if (!blank($user->email_verified_at))
                                        <span class="badge bg-light-primary me-1">Active</span>
                                    @else
                                        <span class="badge bg-light-danger me-1">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @can('user.update')
                                        <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-user-{{ $user->uuid }}"
                                            class="btn btn-icon btn-success text-white">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        @include('management-access.user.modal-edit')
                                    @endcan
                                </td>
                                <td>
                                    @can('user.destroy')
                                        <a onclick="showSweetAlert('{{ $user->uuid }}')" title="Delete"
                                            class="btn btn-icon btn-danger text-white">
                                            <i class="bi bi-x-square"></i> Hapus
                                        </a>
                                        <form id="deleteForm_{{ $user->uuid }}"
                                            action="{{ route('user.destroy', $user->uuid) }}" method="POST">
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
<!-- / Content -->

<!--/ Basic Bootstrap Table -->
@include('management-access.user.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks "Yes, delete it!", submit the corresponding form
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection
