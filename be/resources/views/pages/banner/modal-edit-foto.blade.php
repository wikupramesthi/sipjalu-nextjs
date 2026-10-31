<div id="modal-edit-foto-{{ $item->uuid }}" class="modal fade" tabindex="-1" aria-labelledby="modal-edit-foto-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('banner.update', $item->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipe" value="foto">

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-foto-{{ $item->uuid }}-label">
                        <i class="bx bx-image-edit text-primary"></i> Edit Foto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="form-label">Foto</label>
                            <div class="upload-preview has-img" id="editFotoPreview{{ $item->uuid }}">
                                <span class="upload-preview-label">
                                    <i class="bx bx-cloud-upload fs-2"></i>
                                    Klik untuk ganti gambar
                                </span>
                                <img src="{{ $item->gambar() }}" class="upload-preview-img">
                            </div>
                            <input type="file" name="gambar" class="form-control mt-2 media-image-input" data-preview="#editFotoPreview{{ $item->uuid }}" accept="image/*">
                            <div class="form-text"><i class="bi bi-info-circle"></i> Kosongkan jika tidak ingin mengganti foto.</div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label for="editFotoNama{{ $item->uuid }}" class="form-label">Nama Media <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="editFotoNama{{ $item->uuid }}" class="form-control" value="{{ $item->nama }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="editFotoPosisi{{ $item->uuid }}" class="form-label">Posisi / Penempatan <span class="text-danger">*</span></label>
                                <select name="posisi" id="editFotoPosisi{{ $item->uuid }}" class="form-select" required>
                                    <option value="slider" @selected($item->posisi === 'slider')>Banner / Slider ( 1400 x 622)</option>
                                    <option value="pengumuman" @selected($item->posisi === 'pengumuman')>Pengumuman</option>
                                    <option value="infografis" @selected($item->posisi === 'infografis')>Struktur Organisasi</option>
                                    <option value="galeri" @selected($item->posisi === 'galeri')>Galeri Foto</option>
                                    <option value="popup" @selected($item->posisi === 'popup')>Popup</option>
                                    <option value="mitra" @selected($item->posisi === 'mitra')>Destinasi</option>
                                    <option value="lainnya" @selected($item->posisi === 'lainnya')>Lainnya</option>
                                </select>
                                <div class="form-text">Foto ini bisa dipakai sebagai banner, pengumuman, galeri, dll.</div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="editFotoAlbum{{ $item->uuid }}" class="form-label">Masukkan ke Album <span class="text-muted">(opsional)</span></label>
                                <select name="album_ids[]" id="editFotoAlbum{{ $item->uuid }}" class="form-select media-album-select" multiple>
                                    @foreach ($albums as $album)
                                    <option value="{{ $album->uuid }}" @selected($item->albums->pluck('uuid')->contains($album->uuid))>{{ $album->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="editFotoStatus{{ $item->uuid }}" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="editFotoStatus{{ $item->uuid }}" class="form-select" required>
                                            <option value="active" @selected($item->status === 'active')>Aktif</option>
                                            <option value="inactive" @selected($item->status === 'inactive')>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="editFotoLink{{ $item->uuid }}" class="form-label">Link</label>
                                        <input type="text" name="link" id="editFotoLink{{ $item->uuid }}" class="form-control" value="{{ $item->link }}" placeholder="https://...">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="editFotoDeskripsi{{ $item->uuid }}" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="editFotoDeskripsi{{ $item->uuid }}" class="form-control" rows="2" placeholder="Deskripsi singkat (opsional)">{{ $item->deskripsi }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>