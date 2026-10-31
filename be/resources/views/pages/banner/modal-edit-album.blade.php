<div id="modal-edit-album-{{ $album->uuid }}" class="modal fade" tabindex="-1" aria-labelledby="modal-edit-album-{{ $album->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('banner.updateAlbum', $album->uuid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-album-{{ $album->uuid }}-label">
                        <i class="bx bx-folder text-success"></i> Edit Album
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="editAlbumNama{{ $album->uuid }}" class="form-label">Nama Album <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="editAlbumNama{{ $album->uuid }}" class="form-control" value="{{ $album->nama }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="editAlbumDeskripsi{{ $album->uuid }}" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="editAlbumDeskripsi{{ $album->uuid }}" class="form-control" rows="2" placeholder="Deskripsi album (opsional)">{{ $album->deskripsi }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="editAlbumStatus{{ $album->uuid }}" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="editAlbumStatus{{ $album->uuid }}" class="form-select" required>
                                    <option value="active" @selected($album->status === 'active')>Aktif</option>
                                    <option value="inactive" @selected($album->status === 'inactive')>Nonaktif</option>
                                </select>
                            </div>

                            @if ($fotos->isEmpty())
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i> Belum ada foto yang tersedia untuk album.
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2" data-picker-scope="editAlbumScope{{ $album->uuid }}">
                                <label class="form-label mb-0">Pilih Foto <span class="text-danger">*</span></label>
                                <span class="media-picker-count badge bg-primary">0 foto dipilih</span>
                            </div>
                            <input type="text" class="form-control form-control-sm mb-2 media-picker-search" data-target="#editAlbumScope{{ $album->uuid }}" placeholder="Cari foto..." autocomplete="off">
                            <div class="media-modal-photos" id="editAlbumScope{{ $album->uuid }}">
                                @php $editPicker = $pickerFotos ?? $fotos; @endphp
                                @forelse ($editPicker as $foto)
                                <label class="media-photo-pick{{ $album->fotos->pluck('uuid')->contains($foto->uuid) ? ' checked' : '' }}" title="{{ $foto->nama }}" data-name="{{ \Illuminate\Support\Str::lower($foto->nama) }}">
                                    <img src="{{ $foto->gambar() }}" alt="{{ $foto->nama }}" loading="lazy">
                                    <span class="pick-check"><i class="bi bi-check"></i></span>
                                    <input type="checkbox" name="foto_ids[]" value="{{ $foto->uuid }}" @checked($album->fotos->pluck('uuid')->contains($foto->uuid))>
                                </label>
                                @empty
                                <p class="text-muted small mb-0">Tidak ada foto tersedia.</p>
                                @endforelse
                            </div>
                            <div class="form-text mt-1"><i class="bi bi-info-circle"></i> Foto pertama yang dipilih akan menjadi sampul album. Menampilkan 100 foto terbaru.</div>
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