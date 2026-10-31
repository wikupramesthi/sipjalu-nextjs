@extends('layouts.app')
@section('title', 'Detail Agenda')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Detail Agenda" page="Agenda" active="Detail Agenda" route="{{ route('agendas.index') }}" />
@endsection

@php
    $jenis = $agenda->jenis_agenda ?? 'offline';
@endphp

<style>
    .agenda-detail-content { overflow-wrap: break-word; word-break: break-word; }
    .agenda-detail-content img { max-width: 100%; height: auto; border-radius: 10px; }
    .agenda-detail-content table { width: 100%; max-width: 100%; display: block; overflow-x: auto; }
    .agenda-detail-content iframe { max-width: 100%; }
    @media (max-width: 767.98px) {
        .agenda-detail-content { font-size: .9rem; }
        .card-img-top { max-height: 220px !important; }
    }
</style>

<section class="section">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <a href="{{ route('agendas.index') }}" class="btn btn-light border btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <div class="d-flex flex-wrap gap-1">
            @if($agenda->status==='published')
                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Published</span>
            @elseif($agenda->status==='pending')
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i>Pending Approval</span>
            @elseif($agenda->status==='draft')
                <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle">Draft</span>
            @else
                <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Scheduled</span>
            @endif
            @if($agenda->trashed())
                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-archive me-1"></i>Diarsipkan{{ $agenda->alasan_arsip ? ' · ' . ucfirst($agenda->alasan_arsip) : '' }}</span>
            @endif
            @if($agenda->is_selesai)
                <span class="badge rounded-pill bg-secondary text-white" title="Waktu kegiatan sudah lewat">Selesai</span>
            @endif
            <span class="badge rounded-pill {{ $jenis === 'online' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }}"><i class="bi {{ $jenis === 'online' ? 'bi-globe' : 'bi-geo-alt' }} me-1"></i>{{ $jenis === 'online' ? 'Online' : 'Offline' }}</span>
        </div>
    </div>

    <div class="row g-4">
        {{-- Konten utama --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                @if($agenda->thumbnail_url)
                    <img src="{{ $agenda->thumbnail_url }}" alt="{{ $agenda->title }}" class="card-img-top" style="max-height:360px;object-fit:cover;border-top-left-radius:.7rem;border-top-right-radius:.7rem;">
                @endif
                <div class="card-body">
                    <h4 class="fw-bold mb-2">{{ $agenda->title }}</h4>
                    @if($agenda->excerpt)
                        <p class="text-muted">{{ $agenda->excerpt }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                        <span><i class="bi bi-folder2-open me-1 text-primary"></i>{{ $agenda->category->name ?? '-' }}</span>
                        <span><i class="bi bi-person me-1 text-primary"></i>{{ $agenda->user->name ?? '-' }}</span>
                        <span><i class="bi bi-eye me-1 text-primary"></i>{{ number_format($agenda->views) }} dilihat</span>
                        <span><i class="bi bi-calendar3 me-1 text-primary"></i>{{ optional($agenda->scheduled_at)->format('d M Y') ?? '-' }}</span>
                    </div>
                    <hr>
                    <div class="agenda-detail-content">
                        {!! $agenda->content !!}
                    </div>
                    @if($agenda->tagging)
                        <hr>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(explode(',', $agenda->tagging) as $tag)
                                <span class="badge bg-light text-secondary border">#{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif
                    <hr>
                    @php($shareUrl = urlencode(request()->fullUrl()))
                    <div class="d-flex flex-wrap gap-2">
                        <span class="small text-muted align-self-center">Bagikan:</span>
                        <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener" href="https://wa.me/?text={{ $shareUrl }}"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        <a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" href="mailto:?subject={{ urlencode($agenda->title) }}&body={{ $shareUrl }}"><i class="bi bi-envelope"></i> Email</a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(window.location.href).then(() => this.innerText = 'Tersalin!')"><i class="bi bi-link-45deg"></i> Salin Link</button>
                    </div>
                </div>
            </div>

            @if($agenda->images->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-images me-1 text-warning"></i>Foto Kegiatan ({{ $agenda->images->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($agenda->images as $image)
                                <div class="col-6 col-md-4">
                                    <a href="{{ $image->url() }}" target="_blank" rel="noopener">
                                        <img src="{{ $image->url() }}" alt="Foto agenda" class="img-fluid rounded border" style="height:130px;width:100%;object-fit:cover;" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($agenda->attachments->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-paperclip me-1 text-primary"></i>Lampiran ({{ $agenda->attachments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($agenda->attachments as $file)
                                @php($ext = strtolower(pathinfo($file->nama_asli, PATHINFO_EXTENSION)))
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ $file->url() }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-arrow-down me-1"></i>{{ $file->nama_asli }}</a>
                                        <span class="badge bg-light text-dark border">{{ $file->ukuran_label }}</span>
                                    </div>
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                                        <img src="{{ $file->url() }}" alt="{{ $file->nama_asli }}" class="img-fluid rounded border mt-2" style="max-height:200px" loading="lazy">
                                    @elseif($ext === 'pdf')
                                        <details class="mt-2">
                                            <summary class="small text-primary"><i class="bi bi-eye me-1"></i>Pratinjau PDF</summary>
                                            <iframe src="{{ $file->url() }}" style="width:100%;height:480px;border:1px solid #e9eef5;border-radius:.5rem"></iframe>
                                        </details>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-event me-1 text-success"></i>Pelaksanaan</h5>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Jenis</span><strong>{{ $jenis === 'online' ? 'Online' : 'Offline' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Mulai</span><strong>{{ optional($agenda->waktu_mulai)->format('d M Y, H:i') ?? '-' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Selesai</span><strong>{{ optional($agenda->waktu_akhir)->format('d M Y, H:i') ?? '-' }}</strong></li>
                    @if($agenda->lokasi)
                        <li class="list-group-item"><span class="text-muted d-block mb-1"><i class="bi bi-pin-map me-1"></i>Lokasi</span>{{ $agenda->lokasi }}</li>
                    @endif
                    @if($agenda->url)
                        <li class="list-group-item"><span class="text-muted d-block mb-1"><i class="bi bi-link-45deg me-1"></i>Tautan</span><a href="{{ $agenda->url }}" target="_blank" rel="noopener" class="text-break">{{ $agenda->url }}</a></li>
                    @endif
                    @if($agenda->video)
                        <li class="list-group-item"><span class="text-muted d-block mb-1"><i class="bi bi-youtube me-1"></i>Video</span><span class="text-break">{{ $agenda->video }}</span></li>
                    @endif
                </ul>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-send me-1 text-primary"></i>Publikasi</h5>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Status</span><strong class="text-capitalize">{{ $agenda->status }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Tanggal terbit</span><strong>{{ optional($agenda->scheduled_at)->format('d M Y') ?? '-' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Search engine</span><strong class="text-capitalize">{{ $agenda->search_engine ?? 'index' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Featured</span><strong>{{ $agenda->is_featured ? 'Ya' : 'Tidak' }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between gap-2"><span class="text-muted">Populer</span><strong>{{ $agenda->is_popular ? 'Ya' : 'Tidak' }}</strong></li>
                </ul>
            </div>

            <div class="d-grid gap-2">
                @can('agendas.approve')
                    @if($agenda->status==='pending')
                        <form action="{{ route('agendas.approve', $agenda->uuid) }}" method="POST" class="d-grid">@csrf<button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Setujui &amp; Publikasikan</button></form>
                        <form action="{{ route('agendas.reject', $agenda->uuid) }}" method="POST" class="d-grid">
                            @csrf
                            <label for="catatanTolak" class="form-label small fw-semibold mb-1">Alasan penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatanTolak" rows="2" maxlength="500" class="form-control form-control-sm mb-2" placeholder="Contoh: lokasi belum jelas, mohon dilengkapi..." required></textarea>
                            <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-x-lg me-1"></i>Tolak ke Draft</button>
                        </form>
                    @endif
                @endcan
                @can('agendas.update')
                    <a href="{{ route('agendas.edit', $agenda->uuid) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i>Edit Agenda</a>
                @endcan
                @hasanyrole('super-admin|admin')
                    <form action="{{ route('agendas.duplicate', $agenda->uuid) }}" method="POST" class="d-grid">@csrf<button type="submit" class="btn btn-secondary"><i class="bi bi-copy me-1"></i>Duplikat Agenda</button></form>
                @endhasanyrole
                @can('agendas.destroy')
                    @if($agenda->trashed())
                        <form action="{{ route('agendas.restore', $agenda->uuid) }}" method="POST" class="d-grid">@method('PUT')@csrf<button type="submit" class="btn btn-success"><i class="bi bi-arrow-counterclockwise me-1"></i>Pulihkan dari Arsip</button></form>
                    @else
                        <form action="{{ route('agendas.destroy', $agenda->uuid) }}" method="POST" class="d-grid" onsubmit="return confirm('Arsipkan agenda ini?');">@method('DELETE')@csrf<button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Arsipkan</button></form>
                    @endif
                @endcan
            </div>

            @if(($seri ?? collect())->isNotEmpty())
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0"><i class="bi bi-calendar2-week me-1 text-info"></i>Seri Berulang ({{ $seri->count() }})</h5>
                    </div>
                    <ul class="list-group list-group-flush small">
                        @foreach($seri as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                                <div>
                                    <a href="{{ route('agendas.show', $s->uuid) }}">{{ Str::limit($s->title, 45) }}</a>
                                    <span class="text-muted d-block">{{ optional($s->waktu_mulai)->translatedFormat('d M Y, H:i') ?? '-' }}</span>
                                </div>
                                <span class="badge bg-light text-secondary border text-capitalize">{{ $s->status }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history me-1 text-secondary"></i>Riwayat</h5>
                </div>                @if($agenda->logs->isNotEmpty())
                    <ul class="list-group list-group-flush small">
                        @foreach($agenda->logs as $log)
                            <li class="list-group-item">
                                <span class="badge bg-light text-secondary border">{{ $log->label }}</span>
                                <span class="d-block mt-1">{{ $log->user->name ?? 'Sistem' }} &middot; {{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                                @if($log->catatan)
                                    <span class="text-muted d-block">{{ $log->catatan }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="card-body py-3"><small class="text-muted">Belum ada riwayat tercatat.</small></div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
