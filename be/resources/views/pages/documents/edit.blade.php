@extends('layouts.app')
@section('title', 'Edit Dokumen')
@section('content')

@section('breadcrumb')
    <x-breadcrumb
        title="Edit Dokumen"
        page="Dokumen"
        active="Edit Dokumen"
        route="{{ route('documents.index') }}"
    />

@endsection

<section class="section">

    <form action="{{ route('documents.update', $document->uuid) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-12 col-lg-8">

                {{-- Informasi Dokumen --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Informasi Dokumen
                        </h5>

                        <p class="text-muted small mb-4">
                            Perbarui informasi dokumen ini.
                        </p>


                        {{-- Category --}}
                        <div class="mb-4">

                            <label for="category_uuid"
                                class="form-label fw-semibold">

                                Kategori Dokumen
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="category_uuid"
                                name="category_uuid"
                                class="form-select @error('category_uuid') is-invalid @enderror"
                                required>

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                @foreach ($categories as $category)

                                    <option
                                        value="{{ $category->uuid }}"
                                        {{ old('category_uuid', $document->category_uuid) == $category->uuid ? 'selected' : '' }}>

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('category_uuid')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Subcategory --}}
                        <div class="mb-4">

                            <label for="subcategory_uuid"
                                class="form-label fw-semibold">

                                Subkategori Dokumen

                            </label>

                            <select
                                id="subcategory_uuid"
                                name="subcategory_uuid"
                                class="form-select @error('subcategory_uuid') is-invalid @enderror">

                                <option value="">
                                    -- Pilih Subkategori --
                                </option>

                            </select>

                            @error('subcategory_uuid')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Title --}}
                        <div class="mb-4">

                            <label for="title"
                                class="form-label fw-semibold">

                                Nama Dokumen
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $document->title) }}"
                                placeholder="Masukkan Nama Dokumen"
                                required>

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Slug --}}
                        <div class="mb-4">

                            <label for="slug"
                                class="form-label fw-semibold">

                                Slug

                            </label>

                            <input
                                type="text"
                                id="slug"
                                name="slug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', $document->slug) }}"
                                placeholder="Masukkan slug dokumen">

                            <div class="form-text">
                                Slug akan digunakan sebagai URL dokumen.
                            </div>

                            @error('slug')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Excerpt --}}
                        <div class="mb-4">

                            <label for="excerpt"
                                class="form-label fw-semibold">

                                Ringkasan

                            </label>

                            <textarea
                                id="excerpt"
                                name="excerpt"
                                rows="3"
                                class="form-control @error('excerpt') is-invalid @enderror"
                                placeholder="Deskripsi singkat dokumen ini...">{{ old('excerpt', $document->excerpt) }}</textarea>

                            @error('excerpt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-0">

                            <label for="description"
                                class="form-label fw-semibold">

                                Deskripsi

                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="8"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Tuliskan deskripsi dokumen lengkapnya...">{{ old('description', $document->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Publication --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Publikasi
                        </h5>

                        <p class="text-muted small mb-4">
                            Perbarui tanggal publikasi dokumen ini.
                        </p>


                        <div class="mb-0">

                            <label for="published_at"
                                class="form-label fw-semibold">

                                Tanggal Publikasi

                            </label>

                            <input
                                type="date"
                                id="published_at"
                                name="published_at"
                                class="form-control @error('published_at') is-invalid @enderror"
                                value="{{ old('published_at', $document->published_at) }}">

                            @error('published_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">


                {{-- Document File --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            File Dokumen
                        </h5>

                        <p class="text-muted small mb-4">
                            Pilih dokumen dari URL atau unggah file.
                        </p>

                        <div class="mb-3">
                            <label for="file_source" class="form-label fw-semibold">Sumber Dokumen</label>
                            <select id="file_source" name="file_source" class="form-select @error('file_source') is-invalid @enderror" required>
                                <option value="upload" @selected(old('file_source', $document->file_url ? 'url' : 'upload') === 'upload')>Unggah file</option>
                                <option value="url" @selected(old('file_source', $document->file_url ? 'url' : 'upload') === 'url')>Link URL</option>
                            </select>
                            @error('file_source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Current File --}}
                        @if ($document->file || $document->file_url)

                            <div class="alert alert-light border mb-3">

                                <div class="d-flex align-items-center gap-2">

                                    <i class="bi bi-file-earmark-text fs-4 text-primary"></i>

                                    <div class="overflow-hidden">

                                        <div class="small text-muted">
                                            File saat ini
                                        </div>

                                        <div class="fw-semibold text-truncate">
                                            {{ $document->file_url ?: basename($document->file) }}
                                        </div>

                                    </div>

                                </div>

                                <a
                                    href="{{ $document->file_url ?: asset('storage/' . $document->file) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-primary mt-3 w-100">

                                    <i class="bi bi-eye me-1"></i>
                                    Lihat File

                                </a>

                            </div>

                        @endif


                        <div id="fileUrlField" class="mb-3 d-none">
                            <label for="file_url" class="form-label fw-semibold">URL Dokumen</label>
                            <input
                                type="url"
                                id="file_url"
                                name="file_url"
                                class="form-control @error('file_url') is-invalid @enderror"
                                value="{{ old('file_url', $document->file_url) }}"
                                placeholder="https://...">
                            @error('file_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="fileUploadField" class="mb-3">
                            <label for="file" class="form-label fw-semibold">Pilih File</label>
                            <input
                                type="file"
                                id="file"
                                name="file"
                                class="form-control @error('file') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.csv,.txt,.odt,.ods">

                            <div class="form-text">
                                Kosongkan jika file saat ini sudah berupa unggahan dan tidak ingin menggantinya.
                                PDF, DOC/DOCX, XLS/XLSX, PPT/PPTX, ZIP, RAR, CSV, TXT, ODT, atau ODS. Maksimal 5 MB.
                            </div>

                            @error('file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                </div>


                {{-- Thumbnail --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Thumbnail (kosongkan jika tidak ada thumbnail)
                        </h5>

                        <p class="text-muted small mb-4">
                            Upload thumbnail baru jika ingin mengganti gambar lama.
                        </p>


                        <div class="mb-3">

                            <div
                                id="imagePreview"
                                class="rounded overflow-hidden bg-light d-flex align-items-center justify-content-center mb-3"
                                style="height: 220px;">

                                @if ($document->thumbnail)

                                    <img
                                        src="{{ asset('storage/' . $document->thumbnail) }}"
                                        alt="{{ $document->title }}"
                                        class="w-100 h-100"
                                        style="object-fit: cover;">

                                @else

                                    <div class="text-center text-muted">

                                        <i class="bi bi-image fs-1 d-block mb-2"></i>

                                        <small>
                                            Belum ada thumbnail
                                        </small>

                                    </div>

                                @endif

                            </div>


                            <input
                                type="file"
                                id="thumbnail"
                                name="thumbnail"
                                class="form-control @error('thumbnail') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp">


                            <div class="form-text">
                                 Ukuran thumbnail 400 x 575px dengan format JPG, JPEG, PNG, or WEBP. Maksimal 1 MB.
                            </div>

                            @error('thumbnail')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Status
                        </h5>

                        <p class="text-muted small mb-4">
                            Atur status publikasi dokumen ini.
                        </p>


                        <div class="mb-3">

                            <label for="status"
                                class="form-label fw-semibold">

                                Status
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                                <option
                                    value="active"
                                    {{ old('status', $document->status) === 'active' ? 'selected' : '' }}>

                                    Aktif

                                </option>

                                <option
                                    value="inactive"
                                    {{ old('status', $document->status) === 'inactive' ? 'selected' : '' }}>

                                    Tidak Aktif

                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-body p-4">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 mb-2">

                            <i class="bi bi-check-lg me-1"></i>
                            Simpan Perubahan

                        </button>

                        <a
                            href="{{ route('documents.index') }}"
                            class="btn btn-light w-100">

                            Batal

                        </a>

                    </div>

                </div>

                {{-- Riwayat Versi --}}
                @if($document->versions && $document->versions->count() > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Riwayat Versi ({{ $document->versions->count() }})</h6>
                        <div class="list-group list-group-flush">
                            @foreach($document->versions as $v)
                                <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold small">v{{ $v->versi }} — {{ $v->title }}</div>
                                        <small class="text-muted">{{ $v->created_at?->format('d M Y H:i') }} • {{ $v->user->name ?? 'Sistem' }}</small>
                                        @if($v->keterangan)<div class="small text-muted">{{ $v->keterangan }}</div>@endif
                                    </div>
                                    <div class="d-flex gap-1">
                                        @if($v->file || $v->file_url)<a href="{{ $v->file_url ?: asset('storage/'.$v->file) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark"></i></a>@endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </form>

</section>


{{-- Image Preview --}}
<script>

    document.getElementById('thumbnail').addEventListener('change', function(event) {

        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.innerHTML = `
                <img
                    src="${e.target.result}"
                    alt="Preview Thumbnail"
                    class="w-100 h-100"
                    style="object-fit: cover;">
            `;

        };

        reader.readAsDataURL(file);

    });

    function loadSubcategories(selectedSubcategoryUuid = '') {
        const categorySelect = document.getElementById('category_uuid');
        const categoryUuid = categorySelect.value;
        const subcategorySelect = document.getElementById('subcategory_uuid');
        const subcategoryOptions = subcategorySelect.querySelectorAll('option:not([value=""])');

        subcategoryOptions.forEach(option => option.remove());

        if (categoryUuid) {
            fetch('{{ route("documents.getSubcategories") }}?category_uuid=' + categoryUuid)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.uuid;
                        option.textContent = subcategory.name;
                        option.selected = subcategory.uuid === selectedSubcategoryUuid;
                        subcategorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading subcategories:', error));
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_uuid');
        categorySelect.addEventListener('change', () => loadSubcategories());
        loadSubcategories(@json(old('subcategory_uuid', '')));

        const fileSourceSelect = document.getElementById('file_source');
        const fileUrlField = document.getElementById('fileUrlField');
        const fileUploadField = document.getElementById('fileUploadField');
        const fileUrlInput = document.getElementById('file_url');
        const fileInput = document.getElementById('file');
        const currentFileIsUrl = @json((bool) $document->file_url);
        const updateFileFields = () => {
            const useUrl = fileSourceSelect.value === 'url';
            fileUrlField.classList.toggle('d-none', !useUrl);
            fileUploadField.classList.toggle('d-none', useUrl);
            fileUrlInput.required = useUrl;
            fileInput.required = !useUrl && currentFileIsUrl;
        };
        fileSourceSelect.addEventListener('change', updateFileFields);
        updateFileFields();
    });

</script>

@endsection