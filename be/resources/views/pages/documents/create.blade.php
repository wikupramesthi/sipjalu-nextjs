@extends('layouts.app')
@section('title', 'Tambah Dokumen')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Tambah Dokumen" page="Dokumen" active="Tambah Dokumen" route="{{ route('documents.index') }}" />
@endsection

<section class="section">

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Informasi dokumen
                        </h5>

                        <p class="text-muted small mb-4">
                            Masukkan informasi dasar tentang dokumen ini.
                        </p>

                        {{-- Category --}}
                        <div class="mb-4">

                            <label for="category_uuid" class="form-label fw-semibold">
                                Kategori Dokumen
                                <span class="text-danger">*</span>
                            </label>

                            <select id="category_uuid" name="category_uuid"
                                class="form-select @error('category_uuid') is-invalid @enderror" required>

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->uuid }}"
                                        {{ old('category_uuid') == $category->uuid ? 'selected' : '' }}>
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

                            <label for="subcategory_uuid" class="form-label fw-semibold">
                                Subkategori Dokumen
                            </label>

                            <select id="subcategory_uuid" name="subcategory_uuid"
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

                            <label for="title" class="form-label fw-semibold">
                                Nama Dokumen
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                placeholder="Masukkan Nama Dokumen" required>

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Excerpt --}}
                        <div class="mb-4">

                            <label for="excerpt" class="form-label fw-semibold">
                                Ringkasan
                            </label>

                            <textarea id="excerpt" name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror"
                                placeholder="Deskripsi singkat dokumen ini...">{{ old('excerpt') }}</textarea>

                            @error('excerpt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="mb-0">

                            <label for="description" class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea id="description" name="description" rows="8"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Tuliskan deskripsi dokumen lengkapnya...">{{ old('description') }}</textarea>

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
                            Tetapkan tanggal publikasi dokumen ini.
                        </p>

                        <div class="mb-0">

                            <label for="published_at" class="form-label fw-semibold">
                                Tanggal Publikasi
                            </label>

                            <input type="date" id="published_at" name="published_at"
                                class="form-control @error('published_at') is-invalid @enderror"
                                value="{{ old('published_at') }}">

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
                                <option value="upload" @selected(old('file_source', 'upload') === 'upload')>Unggah file</option>
                                <option value="url" @selected(old('file_source') === 'url')>Link URL</option>
                            </select>
                            @error('file_source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="fileUrlField" class="mb-3 d-none">
                            <label for="file_url" class="form-label fw-semibold">URL Dokumen</label>
                            <input type="url" id="file_url" name="file_url"
                                class="form-control @error('file_url') is-invalid @enderror"
                                value="{{ old('file_url') }}" placeholder="https://...">
                            @error('file_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="fileUploadField" class="mb-3">
                            <label for="file" class="form-label fw-semibold">Pilih File</label>
                            <input type="file" id="file" name="file"
                                class="form-control @error('file') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.csv,.txt,.odt,.ods">
                            <div class="form-text">
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
                            Upload thumbnail untuk dokumen ini.
                        </p>

                        <div class="mb-3">

                            <div id="imagePreview"
                                class="rounded overflow-hidden bg-light d-flex align-items-center justify-content-center mb-3"
                                style="height: 220px;">

                                <div class="text-center text-muted">

                                    <i class="bi bi-image fs-1 d-block mb-2"></i>

                                    <small>
                                        No image selected
                                    </small>

                                </div>

                            </div>

                            <input type="file" id="thumbnail" name="thumbnail"
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
                            Atur status publikasi dokumen ini..
                        </p>

                        <div class="mb-3">

                            <label for="status" class="form-label fw-semibold">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror" required>

                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
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

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-lg me-1"></i>
                            Simpan Dokumen
                        </button>

                        <a href="{{ route('documents.index') }}" class="btn btn-light w-100">

                            Batal

                        </a>

                    </div>

                </div>

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

            preview.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-image fs-1 d-block mb-2"></i>
                <small>No image selected</small>
            </div>
        `;

            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.innerHTML = `
            <img src="${e.target.result}"
                alt="Document Thumbnail Preview"
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
            const updateFileFields = () => {
                const useUrl = fileSourceSelect.value === 'url';
                fileUrlField.classList.toggle('d-none', !useUrl);
                fileUploadField.classList.toggle('d-none', useUrl);
                fileUrlInput.required = useUrl;
                fileInput.required = !useUrl;
            };
            fileSourceSelect.addEventListener('change', updateFileFields);
            updateFileFields();
        });

    </script>

@endsection