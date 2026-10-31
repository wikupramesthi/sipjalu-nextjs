<div id="editSubcategory{{ $item->uuid }}" class="modal fade" tabindex="-1"
    aria-labelledby="editSubcategoryLabel{{ $item->uuid }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('document-subcategories.update', $item->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubcategoryLabel{{ $item->uuid }}">Edit Subkategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="parent_uuid_{{ $item->uuid }}" class="form-label">Kategori Induk <span class="text-danger">*</span></label>
                        <select name="parent_uuid" id="parent_uuid_{{ $item->uuid }}" class="form-select" required>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->uuid }}" @selected($item->parent_uuid === $parent->uuid)>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_name_{{ $item->uuid }}" class="form-label">Nama Subkategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="subcategory_name_{{ $item->uuid }}" value="{{ $item->name }}"
                            class="form-control" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_description_{{ $item->uuid }}" class="form-label">Deskripsi</label>
                        <textarea name="description" id="subcategory_description_{{ $item->uuid }}" rows="3"
                            class="form-control">{{ $item->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="subcategory_status_{{ $item->uuid }}" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="subcategory_status_{{ $item->uuid }}" class="form-select" required>
                            <option value="active" @selected($item->status === 'active')>Aktif</option>
                            <option value="inactive" @selected($item->status === 'inactive')>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
