<div class="media-card" data-name="{{ \Illuminate\Support\Str::lower($item->nama) }}">
    <div class="media-thumb media-thumb--video">
        @if ($thumb = $item->videoThumb())
        <img src="{{ $thumb }}" alt="{{ $item->nama }}" loading="lazy">
        @else
        <span class="media-thumb--nothumb"><i class="bx bx-video-recording"></i></span>
        @endif
        <a href="javascript:void(0)" class="media-play" data-embed="{{ $item->videoEmbedUrl() }}" data-title="{{ $item->nama }}">
            <span class="play-circle"><i class="bi bi-play-fill"></i></span>
        </a>
        <span class="media-badge media-badge--danger"><i class="bx bx-video"></i> Video</span>
        <span class="media-status {{ $item->status }}">
            <i class="bi bi-circle-fill"></i> {{ ucfirst($item->status) }}
        </span>
        <div class="media-actions">
            @can('banner.update')
            <button type="button" class="btn btn-edit js-edit-video" data-uuid="{{ $item->uuid }}" data-name="{{ $item->nama }}" title="Edit">
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
        </div>
    </div>
</div>