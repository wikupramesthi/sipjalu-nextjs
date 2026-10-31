@extends('layouts.app')
@section('title', 'Notifikasi')

@section('breadcrumb')
    <x-breadcrumb title="Notifikasi" page="Dashboard" active="Semua Notifikasi" route="{{ route('dashboard.index') }}" />
@endsection

@section('content')
<div class="container-fluid px-md-4">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Semua Notifikasi</h6>
                @if ($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill">{{ $unreadCount }} baru</span>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" aria-label="Filter notifikasi">
                    <a href="{{ route('notifications.index') }}"
                        class="btn {{ request('filter') !== 'unread' ? 'btn-primary' : 'btn-light' }}">Semua</a>
                    <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                        class="btn {{ request('filter') === 'unread' ? 'btn-primary' : 'btn-light' }}">Belum dibaca</a>
                </div>
                @if ($unreadCount > 0)
                    <a href="javascript:void(0)" class="btn btn-sm btn-light mark-all-read">
                        <i class="bi bi-check2-all me-1"></i>Tandai semua dibaca
                    </a>
                @endif
            </div>
        </div>
        <div class="list-group list-group-flush">
            @forelse ($notifications as $notification)
                @php
                    $href = $notification->data['path'] ?? ($notification->data['url'] ?? 'javascript:void(0)');
                    if (! str_starts_with($href, 'javascript')) {
                        $parts = parse_url($href);
                        if (! empty($parts['host']) && $parts['host'] !== request()->getHost()) {
                            $href = request()->root()
                                . ($parts['path'] ?? '/')
                                . (! empty($parts['query']) ? '?' . $parts['query'] : '');
                        }
                    }
                    $icon = $notification->data['icon'] ?? 'bi-bell';
                @endphp
                <a href="{{ $href }}"
                    class="mark-as-read list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                    data-id="{{ $notification->id }}">
                    <span class="notif-icon" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                    <span class="flex-grow-1 min-w-0">
                        <span class="d-flex align-items-center gap-2 flex-wrap">
                            <strong class="mb-0">{{ $notification->data['title'] ?? 'Notifikasi' }}</strong>
                            @if (! $notification->read_at)
                                <span class="badge bg-primary">Baru</span>
                            @endif
                        </span>
                        <span class="text-muted small d-block text-truncate">{{ $notification->data['message'] ?? '-' }}</span>
                        <small class="text-muted" title="{{ $notification->created_at->format('d M Y H:i') }}">
                            <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </small>
                    </span>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            @empty
                <div class="text-center py-5 text-muted">
                    <div class="empty-notif-icon mx-auto mb-2"><i class="bi bi-bell-slash"></i></div>
                    <p class="mb-1 fw-semibold">
                        {{ request('filter') === 'unread' ? 'Semua sudah dibaca' : 'Tidak ada notifikasi' }}
                    </p>
                    <small>Notifikasi agenda yang masuk akan tampil di sini.</small>
                </div>
            @endforelse
        </div>
        @if ($notifications->hasPages())
            <div class="card-footer d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('after-style')
<style>
    .notif-icon {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 0.9rem;
        background: rgba(94, 114, 228, 0.1);
        color: var(--modern-brand);
        font-size: 1.1rem;
    }
    .list-group-item.mark-as-read:hover .notif-icon {
        background: var(--modern-brand);
        color: #fff;
    }
</style>
@endpush
