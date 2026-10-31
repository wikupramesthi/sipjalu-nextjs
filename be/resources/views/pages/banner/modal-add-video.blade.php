<div id="modal-add-video" class="modal fade" tabindex="-1" aria-labelledby="modal-add-video-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tipe" value="video">

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-video-label">
                        <i class="bx bx-video-plus text-danger"></i> Tambah Video
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Preview</label>
                            <div class="video-preview-empty" id="addVideoEmpty">
                                <div class="text-center">
                                    <i class="bx bx-link-alt fs-2 d-block mb-1"></i>
                                    Masukkan link video untuk preview
                                </div>
                            </div>
                            <iframe id="addVideoPreview" class="video-preview" src="" title="Preview" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label for="addVideoNama" class="form-label">Nama Video <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="addVideoNama" class="form-control" placeholder="contoh: Profil Perusahaan" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="addVideoUrl" class="form-label">Link Video (YouTube / Vimeo) <span class="text-danger">*</span></label>
                                <input type="url" name="video_url" id="addVideoUrl" class="form-control media-video-input"
                                    data-preview="#addVideoPreview" data-empty="#addVideoEmpty"
                                    placeholder="https://www.youtube.com/watch?v=..." required>
                                <div class="form-text"><i class="bi bi-info-circle"></i> Tempel link YouTube, youtu.be, atau Vimeo.</div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="addVideoStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="addVideoStatus" class="form-select" required>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="addVideoLink" class="form-label">Link Tujuan</label>
                                        <input type="text" name="link" id="addVideoLink" class="form-control" placeholder="https://... (opsional)">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="addVideoDeskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="addVideoDeskripsi" class="form-control" rows="2" placeholder="Deskripsi singkat (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-arrow-up"></i> Simpan Video</button>
                </div>
            </form>
        </div>
    </div>
</div>