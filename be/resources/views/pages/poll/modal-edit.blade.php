<!-- Modals add menu -->
<div id="modal-form-edit-polls-{{ $item->uuid }}" class="modal fade modal-form-polls-edit" tabindex="-1"
    aria-labelledby="modal-form-edit-polls-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('poll.update', $item->uuid) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-edit-polls-{{ $item->uuid }}-label">Edit Poling
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="question" class="mb-2">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror"
                            id="question" name="question" value="{{ old('question', $item->question) }}" required>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status" class="mb-2">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih --</option>
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

                    <div class="form-group mb-3">
                        <label class="mb-2">Pilihan Jawaban <span class="text-danger">*</span></label>
                        <div id="options-wrapper">
                            @foreach ($item->options as $option)
                                <input type="text" name="options[]" class="form-control mb-2"
                                    value="{{ $option }}" required>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-option">+ Tambah Opsi</button>
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
