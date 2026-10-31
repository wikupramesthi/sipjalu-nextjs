<div id="createSubcategoryModal" class="modal fade" tabindex="-1" aria-labelledby="createSubcategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('document-subcategories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubcategoryModalLabel">Tambah Subkategori Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_parent_uuid" class="form-label">Kategori Induk <span class="text-danger">*</span></label>
                        <select name="parent_uuid" id="create_parent_uuid" class="form-select @error('parent_uuid') is-invalid @enderror" required>
                            <option value="">-- Pilih kategori induk --</option>
                            @foreach ($activeParentCategories as $parent)
                                <option value="{{ $parent->uuid }}" @selected(old('parent_uuid') === $parent->uuid)>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_uuid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_subcategory_name" class="form-label">Nama Subkategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_subcategory_name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" maxlength="255" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_subcategory_description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="create_subcategory_description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_subcategory_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="create_subcategory_status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" @selected(old('status', 'active') === 'active')>Aktif</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Tidak Aktif</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
