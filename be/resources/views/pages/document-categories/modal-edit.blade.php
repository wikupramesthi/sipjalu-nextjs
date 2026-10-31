<!-- Modals add menu -->
<div id="modal-form-edit-categories-{{ $item->uuid }}" class="modal fade modal-form-categories-edit" tabindex="-1"
    aria-labelledby="modal-form-edit-categories-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('document-categories.update', $item->uuid) }}" method="post">
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
                        <label for="name" class="mb-2">Nama Kategori Dokumen<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Nama Kategori" value="{{ old('name', $item->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="mb-2">Deskripsi (otsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" placeholder="Deskripsi dokumen...”">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status" class="mb-2">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                            required>
                            <option value="">-- Select --</option>
                            <option value="active"
                                {{ (isset($item) && $item->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="inactive"
                                {{ (isset($item) && $item->status == 'inactive') || old('status') == 'inactive' ? 'selected' : '' }}>
                                Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
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
