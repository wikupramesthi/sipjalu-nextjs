{{-- Form dipakai create & edit. Variabel: $service (nullable), $action, $method --}}
<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $service->name ?? '') }}" maxlength="150" required placeholder="cth: WBS Kota Bekasi">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="category" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach (\App\Models\Service::CATEGORIES as $key => $label)
            <option value="{{ $key }}" @selected(old('category', $service->category ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold d-block">Status</label>
        <div class="form-check form-switch mt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" role="switch"
                @checked(old('is_active', ($service->is_active ?? true) ? '1' : '') === '1')>
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>

    <div class="col-12">
        <label for="url" class="form-label fw-semibold">URL</label>
        <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror"
            value="{{ old('url', $service->url ?? '') }}" maxlength="255" placeholder="https://...">
        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Opsional. Untuk kategori eksternal, isi tautan layanan.</small>
    </div>

    <div class="col-12">
        <label for="image" class="form-label fw-semibold">Gambar</label>
        @if (!empty($service->image_url))
        <div class="mb-2">
            <img src="{{ $service->image_url }}" alt="Gambar layanan" class="rounded border" style="height:90px;object-fit:cover;">
            <div class="form-check mt-2">
                <input type="checkbox" name="remove_image" id="remove_image" value="1" class="form-check-input">
                <label for="remove_image" class="form-check-label small text-danger">Hapus gambar saat ini</label>
            </div>
        </div>
        @endif
        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">JPG/PNG/WebP, maks 2 MB.</small>
    </div>

    <div class="col-12">
        <label for="description" class="form-label fw-semibold">Deskripsi</label>
        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror"
            placeholder="Deskripsi singkat layanan...">{{ old('description', $service->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
    <a href="{{ route('services.index') }}" class="btn btn-light">Batal</a>
</div>
