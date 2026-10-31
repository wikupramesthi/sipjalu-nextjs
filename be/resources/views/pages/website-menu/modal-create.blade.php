<div class="modal fade" id="modal-form-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('website-menu.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Menu Website</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nama Menu <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" placeholder="Contoh: Header Utama" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Slug <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="slug" placeholder="header-utama" required>
                            <div class="form-text">Unique identifier, hanya huruf kecil, angka, dan strip</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Lokasi <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select" name="location" required>
                                <option value="header">Header</option>
                                <option value="footer">Footer</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="mobile">Mobile Menu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                <label class="form-check-label" for="status">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Posisi</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="position" value="0" min="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description" rows="2" placeholder="Deskripsi menu (opsional)"></textarea>
                        </div>
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