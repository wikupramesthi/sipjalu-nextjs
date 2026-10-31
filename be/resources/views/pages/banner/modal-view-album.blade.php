<div id="modal-view-album-{{ $album->uuid }}" class="modal fade" tabindex="-1" aria-labelledby="modal-view-album-{{ $album->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-view-album-{{ $album->uuid }}-label">
                    <i class="bx bx-photo-album text-success"></i> {{ $album->nama }}
                    <span class="badge bg-primary ms-2">{{ $album->fotos_count ?? $album->fotos()->count() }} foto</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- $viewFotos dikirim dari controller (sudah order banners.created_at, anti ambiguous) --}}
                @if (($viewFotos ?? collect())->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-image-alt fs-1"></i>
                        <p class="mb-0">Album ini belum berisi foto.</p>
                    </div>
                @else
                    <div class="media-modal-photos" style="max-height: 60vh; overflow-y: auto;">
                        @foreach ($viewFotos as $foto)
                            <div class="media-photo-pick" style="cursor: default;" title="{{ $foto->nama }}">
                                <img src="{{ $foto->gambar() }}" alt="{{ $foto->nama }}" loading="lazy">
                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-1 small text-truncate" style="font-size: 11px;">{{ $foto->nama }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($album->deskripsi)
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p class="mb-0 text-muted">{{ $album->deskripsi }}</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary js-edit-album-from-view" data-uuid="{{ $album->uuid }}">
                    <i class="bi bi-pencil"></i> Edit Album
                </button>
            </div>
        </div>
    </div>
</div>
