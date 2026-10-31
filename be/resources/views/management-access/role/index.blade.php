@extends('layouts.app')
@section('title', 'Roles')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Master Role" page="Role & Akses" active="Master Role"
  route="{{ route('role.index') }}" />
@endsection
<!-- Content -->

<div class="container-p-y">

  <p class="mb-6">A role provided access to predefined menus and features so that depending on assigned role an
    administrator can have access to what user needs.</p>
  <!-- Role cards -->
  <div class="row g-4">

    @foreach ($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-normal mb-0 text-body">Total {{ $role->users->count() }} users</h6>
            @can('role.destroy')
            <a onclick="showSweetAlert('{{ $role->id }}')" class="justify-content-end" title="delete role">
              <i class="bi bi-x"></i>
            </a>
            @endcan
          </div>
          <form id="deleteForm_{{ $role->id }}" action="{{ route('role.destroy', $role->id) }}" method="POST">
            @method('DELETE')
            @csrf
          </form>

          <div class="d-flex justify-content-between align-items-end">
            <div class="role-heading">
              <h5 class="mb-1">{{ $role->name }}</h5>
              @can('role.update')
              <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#editRoleModal-{{ $role->id }}"
                class="role-edit-modal"><span>Edit Role</span></a>
              @include('management-access.role.modal-edit')
              @endcan
            </div>
            <a href="javascript:void(0);"><i class="bx bx-copy bx-md text-muted"></i></a>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    @can('role.store')
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-sm-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4 ps-6">
              <img src="{{ asset('img/man-with-laptop-light.png') }}" class="img-fluid" alt="img" width="120">
            </div>
          </div>
          <div class="col-sm-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">Add New Role</button>
              <p class="mb-0"> Add new role, <br> if it doesn't exist.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endcan

    <div class="col-12">
      <section class="section">
        <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-normal mb-0 text-body card-title">User</h4>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap mx-2">
            <table class="table" id="table1">
              <thead>
                <tr>
                  <th>#</th>
                  <th>User</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($users as $user)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @foreach ($user->roles as $role)
                    <span class="badge bg-light-info me-1">{{ $role->name }}</span>
                    @endforeach
                  </td>
                  <td>
                    @if (!blank($user->email_verified_at))
                    <span class="badge bg-light-primary me-1">Active</span>
                    @else
                    <span class="badge bg-light-danger me-1">Inactive</span>
                    @endif
                  </td>
                  <td>
                    <div class="demo-inline-spacing">
                      @can('user.update')
                      <a data-bs-toggle="modal" data-bs-target="#modal-form-edit-user-{{ $user->uuid }}"
                        class="btn btn-icon btn-success text-white">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      @include('management-access.user.modal-edit')
                      @endcan

                      @can('user.destroy')
                      <a onclick="showSweetAlert('{{ $user->uuid }}')" title="Delete"
                        class="btn btn-icon btn-danger text-white">
                        <i class="bi bi-x-square"></i>
                      </a>

                      <form id="deleteForm_{{ $user->uuid }}" action="{{ route('user.destroy', $user->uuid) }}"
                        method="POST">
                        @method('DELETE')
                        @csrf
                      </form>
                      @endcan
                    </div>
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </div>
      </div>
      </section>
    </div>

  </div>
  <!--/ Role cards -->

  <!-- Add Role Modal -->
  @include('management-access.role.modal-create')
  <!--/ Add Role Modal -->

</div>
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