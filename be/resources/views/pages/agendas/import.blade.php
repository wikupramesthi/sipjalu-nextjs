@extends('layouts.app')
@section('title', 'Import Agenda')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Import Agenda" page="Agenda" active="Import Agenda" route="{{ route('agendas.index') }}" />
@endsection

<section class="section">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ $errors->first() }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-upload me-1 text-primary"></i>Unggah File</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('agendas.importPreview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold">File Excel / CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv,.txt" class="form-control" required>
                            <div class="form-text">Maks 5 MB, maks 200 baris. Baris pertama boleh header template.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Pratinjau &amp; Validasi</button>
                            <a href="{{ route('agendas.importTemplate') }}" class="btn btn-success"><i class="bi bi-download me-1"></i>Unduh Template CSV</a>
                            <a href="{{ route('agendas.index') }}" class="btn btn-light border">Kembali</a>
                        </div>
                    </form>
                    <hr>
                    <small class="text-muted d-block mb-1 fw-semibold">Kolom template:</small>
                    <small class="text-muted d-block">judul*, isi*, kategori* (nama/slug), jenis (offline/online), lokasi, waktu_mulai, waktu_akhir, url, tanggal_terbit, status, tags.</small>
                    <small class="text-muted d-block mt-1">Format waktu contoh: 2026-11-01 09:00. Offline wajib lokasi, online wajib URL.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-1 text-success"></i>Hasil Pratinjau</h5>
                    @if(isset($validCount))
                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $validCount }} baris valid</span>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($preview))
                        @if(count($preview) > 0)
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
                                    <thead class="table-light">
                                        <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Jenis</th><th>Mulai</th><th>Status</th></tr>
                                    </thead>
                                    <tbody>
                                         @foreach($preview as $row)
                                             <tr class="{{ count($row['errors']) > 0 ? 'table-danger' : '' }}">
                                                 <td class="text-muted">{{ $row['line'] }}</td>
                                                 <td>
                                                     <span class="fw-semibold d-block">{{ Str::limit($row['data']['judul'] !== '' ? $row['data']['judul'] : '-', 45) }}</span>
                                                     @foreach($row['errors'] as $err)
                                                         <small class="text-danger d-block"><i class="bi bi-x-circle me-1"></i>{{ $err }}</small>
                                                     @endforeach
                                                 </td>
                                                 <td><small>{{ $row['data']['kategori'] !== '' ? $row['data']['kategori'] : '-' }}</small></td>
                                                 <td><small class="text-capitalize">{{ $row['data']['jenis_agenda'] }}</small></td>
                                                 <td><small>{{ $row['data']['waktu_mulai'] !== '' ? $row['data']['waktu_mulai'] : '-' }}</small></td>
                                                 <td>
                                                     @if(count($row['errors']) > 0)
                                                         <span class="badge bg-danger">Dilewati</span>
                                                     @else
                                                         <span class="badge bg-success">Valid</span>
                                                     @endif
                                                 </td>
                                                 <td class="text-center">
                                                     <button type="button" class="btn btn-sm btn-outline-primary" onclick="editImportRow({{ $row['line'] }}, 'judul', '{{ addslashes($row['data']['judul']) }}')" title="Edit judul"><i class="bi bi-pencil"></i></button>
                                                     <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editImportRow({{ $row['line'] }}, 'kategori', '{{ addslashes($row['data']['kategori']) }}')" title="Edit kategori"><i class="bi bi-tag"></i></button>
                                                     @if(count($row['errors']) > 0)
                                                         <button type="button" class="btn btn-sm btn-outline-warning" onclick="editImportRow({{ $row['line'] }}, 'isi', '{{ addslashes(Str::limit($row['data']['content'], 100)) }}')" title="Edit isi"><i class="bi bi-file-text"></i></button>
                                                     @endif
                                                 </td>
                                             </tr>
                                         @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($validCount > 0)
                                <form action="{{ route('agendas.importConfirm') }}" method="POST" onsubmit="return confirm('Import {{ $validCount }} baris valid? Baris tidak valid dilewati.');">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Import {{ $validCount }} Baris Valid</button>
                                </form>
                            @else
                                <div class="alert alert-warning mb-0">Tidak ada baris valid. Perbaiki file lalu unggah ulang.</div>
                            @endif
                        @else
                            <p class="text-muted mb-0">File kosong / tidak ada baris data.</p>
                        @endif
                    @else
                        <p class="text-muted mb-0">Belum ada pratinjau. Unggah file untuk melihat validasi per baris sebelum diimport.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function editImportRow(line, field, currentValue) {
        const newValue = prompt('Edit ' + field + ':', currentValue);
        if (newValue === null) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("agendas.importEdit") }}';
        form.style.display = 'none';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = 'token';
        token.value = '{{ $token ?? '' }}';
        form.appendChild(token);

        const lineInput = document.createElement('input');
        lineInput.type = 'hidden';
        lineInput.name = 'line';
        lineInput.value = line;
        form.appendChild(lineInput);

        const fieldInput = document.createElement('input');
        fieldInput.type = 'hidden';
        fieldInput.name = 'field';
        fieldInput.value = field;
        form.appendChild(fieldInput);

        const valueInput = document.createElement('input');
        valueInput.type = 'hidden';
        valueInput.name = 'value';
        valueInput.value = newValue;
        form.appendChild(valueInput);

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        document.body.appendChild(form);
        form.submit();
    }
</script>

@endsection
