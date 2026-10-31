<div id="modal-add-album" class="modal fade" tabindex="-1" aria-labelledby="modal-add-album-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('banner.storeAlbum') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-album-label">
                        <i class="bx bx-folder-plus text-success"></i> Buat Album
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="addAlbumNama" class="form-label">Nama Album <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="addAlbumNama" class="form-control" placeholder="contoh: Kegiatan Rapat Kerja 2026" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="addAlbumDeskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="addAlbumDeskripsi" class="form-control" rows="2" placeholder="Deskripsi album (opsional)"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="addAlbumStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="addAlbumStatus" class="form-select" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                            <div class="alert alert-info mb-0 py-2 small">
                                <i class="bi bi-info-circle"></i> Centang foto di kanan. Foto pertama yang dipilih jadi sampul.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Pilih Foto <span class="text-danger">*</span></label>
                                <span class="media-picker-count badge bg-primary">memuat...</span>
                            </div>
                            <input type="text" class="form-control form-control-sm mb-2 media-picker-search" data-target="#addAlbumScope" placeholder="Cari foto..." autocomplete="off">
                            <div class="media-modal-photos" id="addAlbumScope" data-selected="">
                                <p class="text-muted small mb-0">Memuat foto...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-folder-check"></i> Buat Album</button>
                </div>
            </form>
        </div>
    </div>
</div>
