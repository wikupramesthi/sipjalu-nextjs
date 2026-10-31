@extends('layouts.app')
@section('title', 'Subkategori Dokumen')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Subkategori Dokumen" page="Dokumen" active="Subkategori Dokumen" route="{{ route('document-subcategories.index') }}" />
@endsection

<section class="section">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0">Subkategori Dokumen</h6>
                    <small class="text-muted">Kelola subkategori di bawah kategori utama.</small>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <form method="GET" action="{{ route('document-subcategories.index') }}" class="d-flex gap-1">
                        <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm"
                            placeholder="Cari subkategori..." aria-label="Cari subkategori">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        @if ($search !== '')
                            <a href="{{ route('document-subcategories.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </form>
                    @can('document-categories.store')
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSubcategoryModal">
                            <i class="bi bi-plus-lg"></i> Tambah Subkategori
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama Subkategori</th>
                        <th>Kategori Induk</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Total Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subcategories as $subcategory)
                        <tr>
                            <td>{{ $subcategories->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->parent->name }}</td>
                            <td><small class="text-muted">{{ Str::limit($subcategory->description, 60) ?? '-' }}</small></td>
                            <td>
                                @if ($subcategory->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ number_format($subcategory->documents_count) }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('document-categories.update')
                                        <button type="button" class="btn btn-sm btn-success" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#editSubcategory{{ $subcategory->uuid }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('document-categories.destroy')
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="showSubcategoryDeleteAlert('{{ $subcategory->uuid }}', @js($subcategory->name))">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="deleteSubcategoryForm_{{ $subcategory->uuid }}"
                                            action="{{ route('document-subcategories.destroy', $subcategory->uuid) }}"
                                            method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                    @can('document-categories.update')
                                        @include('pages.document-subcategories.modal-edit', ['item' => $subcategory])
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada subkategori dokumen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($subcategories->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <small class="text-muted">Menampilkan {{ $subcategories->firstItem() }}-{{ $subcategories->lastItem() }} dari {{ $subcategories->total() }} subkategori</small>
            <div>{{ $subcategories->links('pagination::minimal') }}</div>
        </div>
    @endif
</section>

@can('document-categories.store')
    @include('pages.document-subcategories.modal-create')
@endcan

@push('after-script')
<script>
    function showSubcategoryDeleteAlert(uuid, name) {
        Swal.fire({
            title: 'Hapus subkategori?',
            text: `Subkategori "${name}" akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f43f5e',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteSubcategoryForm_' + uuid).submit();
            }
        });
    }
</script>
@endpush
@endsection
