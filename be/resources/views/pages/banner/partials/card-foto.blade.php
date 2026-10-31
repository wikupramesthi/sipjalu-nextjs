<div class="media-card" data-name="{{ \Illuminate\Support\Str::lower($item->nama) }}">
    <div class="media-thumb">
        <img src="{{ $item->gambar() }}" alt="{{ $item->nama }}" loading="lazy">
        <span class="media-badge media-badge--{{ $item->posisiBadge() }}">
            <i class="bx bx-photo-album"></i> {{ $item->posisiLabel() }}
        </span>
        <span class="media-status {{ $item->status }}">
            <i class="bi bi-circle-fill"></i> {{ ucfirst($item->status) }}
        </span>
        <span class="media-filetype"><i class="bi bi-image"></i></span>
        <div class="media-actions">
            @can('banner.update')
            <button type="button" class="btn btn-edit js-edit-foto" data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama }}" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
            @endcan
            @can('banner.destroy')
            <button type="button" class="btn btn-delete js-delete-foto" data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama }}" title="Hapus">
                <i class="bi bi-trash"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="media-info">
        <div class="media-title" title="{{ $item->nama }}">{{ $item->nama }}</div>
        <div class="media-meta">
            <i class="bx bx-calendar"></i> {{ $item->created_at?->format('d M Y') }}
            @if ($item->albums_count > 0)
            <span>·</span><i class="bx bx-collection"></i>{{ $item->albums_count }} album
            @endif
        </div>
    </div>
</div>