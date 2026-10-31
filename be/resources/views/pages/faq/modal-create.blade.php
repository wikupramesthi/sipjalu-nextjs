<div id="modal-form-add-faq" class="modal fade modal-form-faq" tabindex="-1" aria-labelledby="modal-form-add-faq-label"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="modal-form" action="{{ route('faq.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-form-add-faq-label">Tambah Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="pertanyaan" class="mb-2">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pertanyaan') is-invalid @enderror"
                            id="pertanyaan" placeholder="Pertanyaan" name="pertanyaan" value="{{ old('pertanyaan') }}"
                            required>
                        @error('pertanyaan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="jawaban" class="mb-2">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote @error('jawaban') is-invalid @enderror" id="jawaban" placeholder="Jawaban"
                            name="jawaban" required>{{ old('jawaban') }}</textarea>
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
                            <option value="tentang-agenda" {{ old('kategori')==='tentang-agenda'?'selected':'' }}>Tentang Agenda</option>
                            <option value="jadwal" {{ old('kategori')==='jadwal'?'selected':'' }}>Jadwal</option>
                            <option value="lokasi" {{ old('kategori')==='lokasi'?'selected':'' }}>Lokasi</option>
                            <option value="publikasi" {{ old('kategori')==='publikasi'?'selected':'' }}>Publikasi</option>
                            <option value="lainnya" {{ old('kategori')==='lainnya'?'selected':'' }}>Lainnya</option>
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
                            placeholder="Urutan" name="urutan" value="{{ old('urutan') }}">
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
                                {{ old('status', $item->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive"
                                {{ old('status', $item->status ?? '') === 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
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
                    <button type="submit" class="btn btn-primary ">Simpan</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
