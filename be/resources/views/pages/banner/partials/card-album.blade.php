<div class="media-card media-card--album" data-name="{{ \Illuminate\Support\Str::lower($album->nama) }}">
    <div class="media-thumb js-view-album" data-uuid="{{ $album->uuid }}" data-name="{{ $album->nama }}" style="cursor: pointer;" title="Lihat foto dalam album {{ $album->nama }}">
        @php $coverFoto = $album->coverFoto(); @endphp
        @if ($coverFoto)
        <div class="album-collage">
            <div class="col-main">
                <img src="{{ $coverFoto->gambar() }}" alt="{{ $album->nama }}" loading="lazy">
            </div>
            @php $sideFoto = $album->fotos->first(fn($f) => $f->uuid !== $coverFoto->uuid); @endphp
            @if ($sideFoto)
            <div class="col-side">
                <img src="{{ $sideFoto->gambar() }}" alt="" loading="lazy">
            </div>
            @endif
        </div>
        @else
        <span class="media-thumb--nothumb"><i class="bx bxs-photo-album"></i></span>
        @endif
        <span class="media-count"><i class="bx bx-images"></i> {{ $album->fotos_count }} Foto</span>
        <span class="media-status {{ $album->status }}">
            <i class="bi bi-circle-fill"></i> {{ ucfirst($album->status) }}
        </span>
        <div class="media-actions">
            @can('banner.update')
            <button type="button" class="btn btn-edit js-edit-album" data-uuid="{{ $album->uuid }}" data-name="{{ $album->nama }}" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
            @endcan
            @can('banner.destroy')
            <button type="button" class="btn btn-delete js-delete-album" data-uuid="{{ $album->uuid }}" data-name="{{ $album->nama }}" title="Hapus">
                <i class="bi bi-trash"></i>
            </button>
            @endcan
            <span class="btn btn-light btn-sm js-view-album" data-uuid="{{ $album->uuid }}" data-name="{{ $album->nama }}" title="Klik untuk lihat foto"><i class="bi bi-eye"></i></span>
        </div>
    </div>
    <div class="media-info">
        <div class="media-title" title="{{ $album->nama }}">{{ $album->nama }}</div>
        <div class="media-meta">
            <i class="bx bx-calendar"></i> {{ $album->created_at?->format('d M Y') }}
        </div>
    </div>
</div>