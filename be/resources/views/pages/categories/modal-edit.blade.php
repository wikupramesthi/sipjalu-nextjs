<!-- Modals add menu -->
<div id="modal-form-edit-categories-{{ $item->uuid }}" class="modal fade modal-form-categories-edit" tabindex="-1"
    aria-labelledby="modal-form-edit-categories-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('categories.update', $item->uuid) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-edit-categories-{{ $item->uuid }}-label">Edit Kategori
                        ({{ $item->name }})
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama Kategori<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Nama Kategori" value="{{ old('name', $item->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="icon" class="mb-2">Ikon (opsional) <span class="text-danger">*contoh: fa-user
                                or fa-book</span></label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon"
                            name="icon" placeholder="example: fa-user or fa-book"
                            value="{{ old('icon', $item->icon) }}">
                        @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="mb-2">Deskripsi (opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" placeholder="Jelaskan kategori ini...”">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->