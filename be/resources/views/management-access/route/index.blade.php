@extends('layouts.app')
@section('title', 'Route')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Module Aplikasi" page="Pengaturan" active="Module Aplikasi"
  route="{{ route('route.index') }}" />
@endsection
<!-- Content -->
<section class="section mt-2">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center ">
        <h4 class="fw-normal mb-0 text-body">Module Aplikasi</h4>
        @can('route.store')
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
          data-bs-target="#modal-form-add-route">
          <i class="bi bi-plus-lg"></i>
          Tambah Module
        </button>
        @endcan
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap mx-2">
        <table class="table" id="table1">
          <thead>
            <tr>
              <th>Route</th>
              <th>Permission</th>
              <th>Description</th>
              <th>Status</th>
              <th>Edit</th>
              <th>Hapus</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($routes as $route)
            <tr>
              <td>{{ $route->route }}</td>
              <td>{{ $route->permission_name }}</td>
              <td>{{ $route->description }}</td>
              <td>
                @if ($route->status)
                <span class="badge bg-light-primary me-1">Show</span>
                @else
                <span class="badge bg-light-danger me-1">Hide</span>
                @endif
              </td>
              <td>
                @can('route.update')
                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-route-{{ $route->id }}"
                  class="btn btn-icon btn-success text-white">
                  <i class="bi bi-pencil-square"></i>
                </a>
                @include('management-access.route.modal-edit')
                @endcan

              </td>

              <td>
                @can('route.destroy')
                <a onclick="showSweetAlert('{{ $route->id }}')" title="Delete"
                  class="btn btn-icon btn-danger text-white">
                  <i class="bi bi-x-square"></i>
                </a>
                <form id="deleteForm_{{ $route->id }}" action="{{ route('route.destroy', $route->id) }}"
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
@include('management-access.route.modal-create')
<!-- / Content -->
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