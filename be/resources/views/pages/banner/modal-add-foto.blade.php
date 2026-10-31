<div id="modal-add-foto" class="modal fade" tabindex="-1" aria-labelledby="modal-add-foto-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tipe" value="foto">

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-foto-label">
                        <i class="bx bx-image-add text-primary"></i> Tambah Foto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="form-label">Foto <span class="text-danger">*</span></label>
                            <div class="upload-preview" id="addFotoPreview">
                                <span class="upload-preview-label">
                                    <i class="bx bx-cloud-upload fs-2"></i>
                                    Klik untuk pilih gambar
                                </span>
                                <img src="" alt="preview" class="upload-preview-img">
                            </div>
                            <input type="file" name="gambar" class="form-control mt-2 media-image-input" data-preview="#addFotoPreview" accept="image/*" required>
                            <div class="form-text"><i class="bi bi-info-circle"></i> Format JPG, PNG, WEBP. Maks 5 MB.</div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label for="addFotoNama" class="form-label">Nama Media <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="addFotoNama" class="form-control" placeholder="contoh: Banner Dies Natalis" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="addFotoPosisi" class="form-label">Posisi / Penempatan <span class="text-danger">*</span></label>
                                <select name="posisi" id="addFotoPosisi" class="form-select" required>
                                    <option value="">-- Pilih penempatan --</option>
                                    <option value="slider">Banner / Slider ( 1400 x 622)</option>
                                    <option value="pengumuman">Pengumuman</option>
                                    <option value="infografis">Struktur Organisasi</option>
                                    <option value="galeri">Galeri Foto</option>
                                    <option value="popup">Popup</option>
                                    <option value="mitra">Destinasi</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                <div class="form-text">Foto ini bisa dipakai sebagai banner, pengumuman, galeri, dll.</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="addFotoAlbum" class="form-label">Masukkan ke Album <span class="text-muted">(opsional)</span></label>
                                <select name="album_ids[]" id="addFotoAlbum" class="form-select media-album-select" multiple>
                                    @foreach (($albumOptions ?? $albums ?? []) as $album)
                                    <option value="{{ $album->uuid }}">{{ $album->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="addFotoStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="addFotoStatus" class="form-select" required>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="addFotoLink" class="form-label">Link</label>
                                        <input type="text" name="link" id="addFotoLink" class="form-control" placeholder="https://...">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="addFotoDeskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="addFotoDeskripsi" class="form-control" rows="2" placeholder="Deskripsi singkat (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-arrow-up"></i> Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>