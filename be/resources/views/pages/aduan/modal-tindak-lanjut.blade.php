<!-- Modal tambah tindak lanjut -->
<div id="modal-tindak-lanjut-{{ $item->uuid }}" class="modal fade" tabindex="-1"
    aria-labelledby="modal-tindak-lanjut-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('aduans.tindak-lanjut.store', $item->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-tindak-lanjut-{{ $item->uuid }}-label">
                        Tindak Lanjut {{ $item->nomor_aduan }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        Status saat ini: <strong>{{ ucfirst($item->status) }}</strong>.
                        Riwayat ini terlihat oleh masyarakat di halaman detail &amp; pelacakan.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Baru</label>
                            <select name="status" class="form-select">
                                <option value="">— Tidak berubah —</option>
                                @foreach (['menunggu','diverifikasi','diproses','selesai','ditolak'] as $st)
                                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="datetime-local" name="tanggal" class="form-control">
                            <small class="text-muted">Kosongkan untuk waktu saat ini.</small>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">Catatan Hasil <span class="text-danger">*</span></label>
                        <textarea name="catatan" rows="4" class="form-control"
                            placeholder="Tuliskan hasil penanganan, mis. drainase sudah dikeruk sepanjang 20 m..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Foto 1</label>
                            <input type="file" name="foto_1" class="form-control"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Foto 2</label>
                            <input type="file" name="foto_2" class="form-control"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                        </div>
                    </div>
                    <small class="text-muted">JPG, JPEG, PNG, WEBP. Maksimal 2 MB per foto.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
