@extends('layouts.app')
@section('title', 'Menu Group')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Menu Manager" page="Pengaturan" active="Menu Manager" route="{{ route('menu.index') }}" />
@endsection

<!-- Content -->
<section class="section mt-2">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center ">
        <h4 class="fw-normal mb-0 text-body">Menu Group</h4>

        @can('menu-group.store')
        <button type="button" class="btn btn-primary btn-md " data-bs-toggle="modal"
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
              <th>Icon</th>
              <th>Status</th>
              <th>Menu Item</th>
              <th>Edit</th>
              <th>Hapus</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($menuGroups as $menuGroup)
            <tr>
              <td>{{ $menuGroup->name }}</td>
              <td>{{ $menuGroup->permission_name }}</td>
              <td><i class='bx {{ $menuGroup->icon }}'></i></td>
              <td>
                @if ($menuGroup->status)
                <span class="badge bg-light-primary me-1">Show</span>
                @else
                <span class="badge bg-light-danger me-1">Hide</span>
                @endif
              </td>
              <td>
                @can('menu-item.index')
                <a href="{{ route('menu.item.index', $menuGroup) }}" class="btn btn-icon btn-primary"
                  title="add menu item">
                  <i class="bi bi-plus-square"></i>
                </a>
                @endcan
              </td>

              <td>
                @can('menu-group.update')
                <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-menu-{{ $menuGroup->id }}"
                  class="btn btn-icon btn-success text-white">
                  <i class="bi bi-pencil-square"></i>
                </a>
                @include('management-access.menu-group.modal-edit')
                @endcan
              </td>

              <td>

                @can('menu-group.destroy')
                <a onclick="showSweetAlert('{{ $menuGroup->id }}')" title="Delete"
                  class="btn btn-icon btn-danger text-white">
                  <i class="bi bi-x-square"></i>
                </a>
                <form id="deleteForm_{{ $menuGroup->id }}"
                  action="{{ route('menu.destroy', encrypt($menuGroup->id)) }}" method="POST">
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
@include('management-access.menu-group.modal-create')
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