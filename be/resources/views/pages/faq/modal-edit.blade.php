<!-- Modals add menu -->
<div id="modal-form-edit-faq-{{ $item->uuid }}" class="modal fade modal-form-faq-edit" tabindex="-1"
    aria-labelledby="modal-form-edit-faq-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('faq.update', $item->uuid) }}" method="post">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-edit-faq-{{ $item->uuid }}-label">Edit Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="pertanyaan" class="mb-2">Peryanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pertanyaan') is-invalid @enderror"
                            id="pertanyaan" placeholder="Pertanyaan" name="pertanyaan"
                            value="{{ $item->pertanyaan ?? old('pertanyaan') }}" required>
                        @error('pertanyaan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="jawaban" class="mb-2">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote @error('jawaban') is-invalid @enderror" id="jawaban" rows="6"
                            placeholder="Jawaban" name="jawaban" required>{{ $item->jawaban ?? old('jawaban') }}</textarea>
                        @error('jawaban')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="kategori" class="mb-2">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror">
                            <option value="">-- Tanpa Kategori --</option>
                            <option value="tentang-agenda" {{ ($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'tentang-agenda' ? 'selected' : '' }}>Tentang Agenda</option>
                            <option value="jadwal" {{ ($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'jadwal' ? 'selected' : '' }}>Jadwal</option>
                            <option value="lokasi" {{ ($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'lokasi' ? 'selected' : '' }}>Lokasi</option>
                            <option value="publikasi" {{ ($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'publikasi' ? 'selected' : '' }}>Publikasi</option>
                            <option value="lainnya" {{ ($item->kategori instanceof \BackedEnum ? $item->kategori->value : $item->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="urutan" class="mb-2">Urutan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('urutan') is-invalid @enderror" id="urutan"
                            placeholder="Urutan" name="urutan" value="{{ $item->urutan ?? old('urutan') }}" required>
                        @error('urutan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
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

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
