@extends('layouts.app')
@section('title', 'Import Berita')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Import Berita" page="Berita" active="Import Berita" route="{{ route('beritas.index') }}" />
@endsection

<section class="section">
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-upload me-1"></i>Unggah File</h5></div>
                <div class="card-body">
                    <form action="{{ route('beritas.importPreview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="importFile" class="form-label fw-semibold">File Excel / CSV</label>
                            <input type="file" id="importFile" name="file" accept=".xlsx,.xls,.csv,.txt" class="form-control" required>
                            <div class="form-text">Maksimal 5 MB dan 200 baris berita.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Pratinjau &amp; Validasi</button>
                            <a href="{{ route('beritas.importTemplate') }}" class="btn btn-success"><i class="bi bi-download me-1"></i>Unduh Template CSV</a>
                            <a href="{{ route('beritas.index') }}" class="btn btn-light border">Kembali</a>
                        </div>
                    </form>
                    <hr>
                    <small class="text-muted d-block fw-semibold">Kolom template</small>
                    <small class="text-muted">judul*, ringkasan, isi*, kategori* (nama/slug), tanggal_terbit, status, tags.</small>
                    <small class="text-muted d-block mt-1">Status: draft, published, scheduled, atau pending. User non-admin akan disimpan sebagai pending.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h5 class="mb-0"><i class="bi bi-list-check me-1"></i>Hasil Pratinjau</h5>
                    @isset($validCount)<span class="badge text-bg-success">{{ $validCount }} baris valid</span>@endisset
                </div>
                <div class="card-body">
                    @isset($preview)
                        @if (count($preview))
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover align-middle">
                                    <thead class="table-light">
                                        <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Validasi</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($preview as $row)
                                            <tr class="{{ $row['errors'] ? 'table-danger' : '' }}">
                                                <td>{{ $row['line'] }}</td>
                                                <td>
                                                    <span class="fw-semibold">{{ $row['data']['title'] ?: '-' }}</span>
                                                    <small class="d-block text-muted">{{ Str::limit($row['data']['excerpt'], 80) }}</small>
                                                </td>
                                                <td>{{ $row['data']['category'] ?: '-' }}</td>
                                                <td>{{ ucfirst($row['data']['status']) }}</td>
                                                <td>
                                                    @forelse ($row['errors'] as $error)
                                                        <small class="text-danger d-block">{{ $error }}</small>
                                                    @empty
                                                        <span class="badge text-bg-success">Valid</span>
                                                    @endforelse
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($validCount > 0)
                                <form action="{{ route('beritas.importConfirm') }}" method="POST" onsubmit="return confirm('Import {{ $validCount }} baris valid? Baris tidak valid akan dilewati.')">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Import {{ $validCount }} Baris Valid</button>
                                </form>
                            @else
                                <div class="alert alert-warning mb-0">Tidak ada baris valid untuk diimpor.</div>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0">File tidak berisi baris data.</div>
                        @endif
                    @else
                        <p class="text-muted mb-0">Unggah file untuk memvalidasi isi dan kategori sebelum menyimpan berita.</p>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
