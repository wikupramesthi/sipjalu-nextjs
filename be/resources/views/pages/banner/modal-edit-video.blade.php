<div id="modal-edit-video-{{ $item->uuid }}" class="modal fade" tabindex="-1" aria-labelledby="modal-edit-video-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('banner.update', $item->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipe" value="video">

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-video-{{ $item->uuid }}-label">
                        <i class="bx bx-video text-danger"></i> Edit Video
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Preview</label>
                            <div class="video-preview-empty {{ $item->videoEmbedUrl() ? 'd-none' : '' }}" id="editVideoEmpty{{ $item->uuid }}">
                                <div class="text-center">
                                    <i class="bx bx-link-alt fs-2 d-block mb-1"></i>
                                    Masukkan link video untuk preview
                                </div>
                            </div>
                            <iframe id="editVideoPreview{{ $item->uuid }}" class="video-preview {{ $item->videoEmbedUrl() ? 'show' : '' }}" src="{{ $item->videoEmbedUrl() }}" title="Preview" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label for="editVideoNama{{ $item->uuid }}" class="form-label">Nama Video <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="editVideoNama{{ $item->uuid }}" class="form-control" value="{{ $item->nama }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="editVideoUrl{{ $item->uuid }}" class="form-label">Link Video (YouTube / Vimeo) <span class="text-danger">*</span></label>
                                <input type="url" name="video_url" id="editVideoUrl{{ $item->uuid }}" class="form-control media-video-input"
                                    data-preview="#editVideoPreview{{ $item->uuid }}" data-empty="#editVideoEmpty{{ $item->uuid }}"
                                    value="{{ $item->video_url }}" required>
                                <div class="form-text"><i class="bi bi-info-circle"></i> Tempel link YouTube, youtu.be, atau Vimeo.</div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="editVideoStatus{{ $item->uuid }}" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="editVideoStatus{{ $item->uuid }}" class="form-select" required>
                                            <option value="active" @selected($item->status === 'active')>Aktif</option>
                                            <option value="inactive" @selected($item->status === 'inactive')>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="editVideoLink{{ $item->uuid }}" class="form-label">Link Tujuan</label>
                                        <input type="text" name="link" id="editVideoLink{{ $item->uuid }}" class="form-control" value="{{ $item->link }}" placeholder="https://... (opsional)">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="editVideoDeskripsi{{ $item->uuid }}" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="editVideoDeskripsi{{ $item->uuid }}" class="form-control" rows="2" placeholder="Deskripsi singkat (opsional)">{{ $item->deskripsi }}</textarea>
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