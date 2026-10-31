@extends('layouts.app')
@section('title', 'Detail Berita')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Detail Berita" page="Berita" active="Detail Berita" route="{{ route('beritas.index') }}" />
@endsection

<style>
    .berita-detail-content { overflow-wrap: break-word; word-break: break-word; }
    .berita-detail-content img { max-width: 100%; height: auto; border-radius: 10px; }
    .berita-detail-content table { width: 100%; max-width: 100%; display: block; overflow-x: auto; }
    .berita-detail-content iframe { max-width: 100%; }
    @media (max-width: 767.98px) {
        .berita-detail-content { font-size: .9rem; }
        .berita-detail-image { max-height: 220px !important; }
    }
</style>

<section class="section">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <a href="{{ route('beritas.index') }}" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <div class="d-flex flex-wrap gap-1">
            @if ($berita->status === 'published')
                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
            @elseif ($berita->status === 'pending')
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i>Pending Approval</span>
            @elseif ($berita->status === 'draft')
                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
            @else
                <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle">Scheduled</span>
            @endif
            @if ($berita->is_featured)
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-star-fill me-1"></i>Featured</span>
            @endif
            @if ($berita->is_popular)
                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-fire me-1"></i>Populer</span>
            @endif
            @if ($berita->is_urgent)
                <span class="badge rounded-pill bg-danger text-white"><i class="bi bi-exclamation-circle me-1"></i>Urgent</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                @if ($berita->featured_image)
                    <img src="{{ asset('storage/' . $berita->featured_image) }}" alt="{{ $berita->title }}"
                        class="card-img-top berita-detail-image" style="max-height:360px;object-fit:cover;border-top-left-radius:.7rem;border-top-right-radius:.7rem;">
                @endif
                <div class="card-body">
                    <h4 class="fw-bold mb-2">{{ $berita->title }}</h4>
                    @if ($berita->excerpt)
                        <p class="text-muted">{{ $berita->excerpt }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                        <span><i class="bi bi-folder2-open me-1 text-primary"></i>{{ $berita->category?->name ?? '-' }}</span>
                        <span><i class="bi bi-person me-1 text-primary"></i>{{ $berita->user?->name ?? '-' }}</span>
                        <span><i class="bi bi-eye me-1 text-primary"></i>{{ number_format($berita->views) }} dilihat</span>
                        <span><i class="bi bi-calendar3 me-1 text-primary"></i>{{ optional($berita->scheduled_at ?? $berita->created_at)->format('d M Y') ?? '-' }}</span>
                    </div>
                    <hr>
                    <div class="berita-detail-content">{!! $berita->content !!}</div>
                    @if ($berita->tagging)
                        <hr>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach (explode(',', $berita->tagging) as $tag)
                                <span class="badge bg-light text-secondary border">#{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif
                    @if ($berita->video)
                        <hr>
                        <div><i class="bi bi-play-circle me-1 text-primary"></i><a href="{{ $berita->video }}" target="_blank" rel="noopener noreferrer">Lihat video berita</a></div>
                    @endif
                    <hr>
                    @php($shareUrl = urlencode(request()->fullUrl()))
                    <div class="d-flex flex-wrap gap-2">
                        <span class="small text-muted align-self-center">Bagikan:</span>
                        <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener" href="https://wa.me/?text={{ $shareUrl }}"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        <a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" href="mailto:?subject={{ urlencode($berita->title) }}&body={{ $shareUrl }}"><i class="bi bi-envelope"></i> Email</a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(window.location.href).then(() => this.innerText = 'Tersalin!')"><i class="bi bi-link-45deg"></i> Salin Link</button>
                    </div>
                </div>
            </div>

            @if ($berita->images->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-images me-1 text-warning"></i>Foto Slider ({{ $berita->images->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach ($berita->images as $image)
                                <div class="col-6 col-md-4">
                                    <a href="{{ $image->url() }}" target="_blank" rel="noopener">
                                        <img src="{{ $image->url() }}" alt="Foto berita" class="img-fluid rounded border" style="height:130px;width:100%;object-fit:cover;" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if ($berita->attachments->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-paperclip me-1 text-primary"></i>Lampiran ({{ $berita->attachments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach ($berita->attachments as $file)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center gap-2">
                                    <a href="{{ $file->url() }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-arrow-down me-1"></i>{{ $file->nama_asli }}</a>
                                    <span class="badge bg-light text-dark border">{{ $file->ukuran_label }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-send me-1 text-primary"></i>Publikasi</h5>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Status</span><strong class="text-capitalize">{{ $berita->status }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Tanggal terbit</span><strong>{{ optional($berita->scheduled_at)->format('d M Y, H:i') ?? '-' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Search engine</span><strong class="text-capitalize">{{ $berita->search_engine ?? 'index' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Featured</span><strong>{{ $berita->is_featured ? 'Ya' : 'Tidak' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Populer</span><strong>{{ $berita->is_popular ? 'Ya' : 'Tidak' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Urgent</span><strong>{{ $berita->is_urgent ? 'Ya' : 'Tidak' }}</strong></li>
                </ul>
            </div>

            <div class="d-grid gap-2">
                @can('agendas.approve')
                    @if ($berita->status === 'pending')
                        <form action="{{ route('beritas.approve', $berita->uuid) }}" method="POST" class="d-grid" data-confirm="Setujui dan publikasikan berita ini?">
                            @csrf
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Setujui &amp; Publikasikan</button>
                        </form>
                        <form action="{{ route('beritas.reject', $berita->uuid) }}" method="POST" class="d-grid">
                            @csrf
                            <label for="catatanTolakBerita" class="form-label small fw-semibold mb-1">Alasan penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatanTolakBerita" rows="2" maxlength="500" class="form-control form-control-sm mb-2" placeholder="Tuliskan alasan untuk penulis..." required></textarea>
                            <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-arrow-counterclockwise me-1"></i>Tolak ke Draft</button>
                        </form>
                        <form action="{{ route('beritas.mintaRevisi', $berita->uuid) }}" method="POST" class="d-grid">
                            @csrf
                            <label for="catatanRevisiBerita" class="form-label small fw-semibold mb-1">Catatan revisi <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatanRevisiBerita" rows="2" maxlength="500" class="form-control form-control-sm mb-2" placeholder="Tuliskan bagian yang perlu direvisi..." required></textarea>
                            <button type="submit" class="btn btn-info text-dark"><i class="bi bi-pencil-square me-1"></i>Minta Revisi</button>
                        </form>
                    @endif
                @endcan
                @can('agendas.update')
                    <a href="{{ route('beritas.edit', $berita->uuid) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i>Edit Berita</a>
                @endcan
                @can('agendas.destroy')
                    <form action="{{ route('beritas.destroy', $berita->uuid) }}" method="POST" class="d-grid" data-confirm="Pindahkan berita ini ke arsip?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Arsipkan</button>
                    </form>
                @endcan
            </div>

            @if ($logs->isNotEmpty())
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-clock-history me-1 text-secondary"></i>Riwayat</h5>
                    </div>
                    <ul class="list-group list-group-flush small">
                        @foreach ($logs as $log)
                            <li class="list-group-item">
                                <span class="badge {{ $log->event_badge }}">{{ $log->event_label }}</span>
                                <span class="d-block mt-1">{{ $log->user_name ?? 'Sistem' }} &middot; {{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                                @if (isset($log->new_values['status']))
                                    <span class="text-muted d-block">Status: {{ $log->new_values['status'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-clock-history me-1 text-secondary"></i>Riwayat</h5>
                    </div>
                    <div class="card-body py-3"><small class="text-muted">Belum ada riwayat tercatat.</small></div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
