@extends('layouts.app')
@section('title', 'Menu Item')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Menu Item Manager" page="Pengaturan" active="Menu Item Manager"
  route="{{ route('menu.index') }}" />
@endsection

<!-- Content -->
<section class="section mt-2">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-normal mb-0 text-body">Menu Item - {{ $menu->name }}</h4>

        @can('menu-item.store')
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
          data-bs-target="#modal-form-add-menu">
          <i class="bi bi-plus-lg"></i>
          Tambah Menu
        </button>
        @endcan

      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap mx-2">
        <table class="table" id="table1">
          <thead>
            <tr>
              <th>Name</th>
              <th>Permission</th>
              <th>Route</th>
              <th>Status</th>
              <th>Edit</th>
              <th>Hapus</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($menuItems as $menuItem)
            <tr>
              <td>{{ $menuItem->name }}</td>
              <td>{{ $menuItem->permission_name }}</td>
              <td>{{ $menuItem->route }}</td>
              <td>
                @if ($menuItem->status)
                <span class="badge bg-light-primary me-1">Show</span>
                @else
                <span class="badge bg-light-danger me-1">Hide</span>
                @endif
              </td>
              <td>
                @can('menu-item.update')
                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-menu-{{ $menuItem->id }}"
                  class="btn btn-icon btn-success text-white">
                  <i class="bi bi-pencil-square"></i>
                </a>
                @include('management-access.menu-item.modal-edit')
                @endcan
              </td>
              <td>

                @can('menu-item.destroy')
                <a onclick="showSweetAlert('{{ $menuItem->id }}')" title="Delete"
                  class="btn btn-icon btn-danger text-white">
                  <i class="bi bi-x-square"></i>
                </a>
                <form id="deleteForm_{{ $menuItem->id }}"
                  action="{{ route('menu.item.destroy', [$menu->id, $menuItem->id]) }}" method="POST">
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
@include('management-access.menu-item.modal-create')
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