@extends('layouts.app')
@section('title', 'Detail Aduan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Detail Aduan" page="Aduan" active="{{ $item->nomor_aduan }}" route="{{ route('aduans.index') }}" />
@endsection

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $item->nomor_aduan }} — {{ $item->judul }}</h5>
            <a href="{{ route('aduans.index') }}" class="btn btn-sm btn-light">Kembali</a>
        </div>
        <div class="card-body">
            @if ($item->trashed())
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <span>Aduan ini ada di <strong>Tempat Sampah</strong> (dihapus {{ $item->deleted_at?->format('d M Y H:i') }}).</span>
                @can('aduans.restore')
                <form action="{{ route('aduans.restore', $item->uuid) }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning text-white">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Kembalikan
                    </button>
                </form>
                @endcan
            </div>
            @endif
            @if ($item->archived_at)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <span>Aduan ini <strong>diarsipkan</strong> ({{ $item->archived_at?->format('d M Y H:i') }}). Tidak tampil di daftar aktif &amp; API publik.</span>
                @can('aduans.batalArsip')
                <form action="{{ route('aduans.batalArsip', $item->uuid) }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning text-white">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Aktifkan Lagi
                    </button>
                </form>
                @endcan
            </div>
            @endif
            <div class="row">
                <div class="col-md-7">
                    <table class="table table-bordered">
                        <tr><th width="220">Nomor Aduan</th><td><code>{{ $item->nomor_aduan }}</code></td></tr>
                        <tr><th>Kategori</th><td>{{ $item->kategori }}</td></tr>
                        <tr><th>Judul</th><td>{{ $item->judul }}</td></tr>
                        <tr><th>Isi Aduan</th><td style="white-space: pre-wrap;">{{ $item->isi_aduan }}</td></tr>
                        <tr><th>Lokasi</th><td>{{ $item->lokasi ?? '-' }}</td></tr>
                        <tr><th>Kecamatan / Kelurahan</th><td>{{ $item->kecamatan->nama ?? '-' }} / {{ $item->kelurahan->nama ?? '-' }}</td></tr>
                        <tr><th>Pelapor</th><td>{{ $item->user->name ?? '-' }} ({{ $item->user->email ?? '-' }})
                                @if ($item->is_anonim)
                                <span class="badge bg-dark ms-1">Anonim — disembunyikan dari publik</span>
                                @endif
                            </td></tr>
                        <tr><th>Tanggal Kejadian</th><td>{{ $item->tanggal_kejadian?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th>Tanggal Pengaduan</th><td>{{ $item->tanggal_pengaduan?->format('d M Y H:i') ?? '-' }}</td></tr>
                        <tr><th>Status / Prioritas / Sifat</th>
                            <td>{{ ucfirst($item->status) }} / {{ ucfirst($item->prioritas) }} / {{ ucfirst($item->sifat) }}</td>
                        </tr>
                        <tr><th>Koordinat</th><td>{{ $item->latitude ?? '-' }}, {{ $item->longitude ?? '-' }}</td></tr>
                    </table>
                </div>
                <div class="col-md-5">
                    <h6>Dokumentasi Foto</h6>
                    <div class="row g-2">
                        @foreach (['foto_1', 'foto_2', 'foto_3'] as $field)
                        <div class="col-12">
                            @if ($item->{$field})
                            <a href="{{ asset('storage/' . $item->{$field}) }}" target="_blank">
                                <img src="{{ asset('storage/' . $item->{$field}) }}" class="img-fluid rounded border" alt="{{ $field }}">
                            </a>
                            @else
                            <div class="border rounded p-3 text-center text-muted">{{ $field }}: tidak ada foto</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @if ($item->latitude && $item->longitude)
                    <a class="btn btn-sm btn-outline-primary mt-3" target="_blank"
                        href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}">
                        Buka di Google Maps
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('pages.aduan._timeline')

    {{-- Penilaian warga --}}
    @if ($item->rating || ($item->status === 'selesai' && auth()->check() && $item->user_uuid === auth()->user()->uuid))
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-star me-1"></i> Penilaian Masyarakat</h5>
        </div>
        <div class="card-body">
            @if ($item->rating)
            <div class="fs-4 text-warning">{{ str_repeat('★', $item->rating) }}{{ str_repeat('☆', 5 - $item->rating) }}</div>
            <div class="small text-muted mb-2">Dinilai {{ $item->rated_at?->format('d M Y H:i') ?? '-' }}</div>
            @if ($item->ulasan)
            <p class="mb-0" style="white-space: pre-wrap;">{{ $item->ulasan }}</p>
            @endif
            @endif

            @if ($item->status === 'selesai' && auth()->check() && $item->user_uuid === auth()->user()->uuid)
            @if ($item->rating)
            <hr>
            @endif
            <form action="{{ route('aduans.rate', $item->uuid) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        {{ $item->rating ? 'Ubah penilaian' : 'Beri penilaian' }}
                        <span id="rating-angka" class="ms-2 fw-bold text-warning small"></span>
                    </label>
                    <div class="rating-input">
                        @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                            {{ old('rating', $item->rating) == $i ? 'checked' : '' }} required>
                        <label for="star{{ $i }}" title="{{ $i }} bintang" data-nilai="{{ $i }}">★</label>
                        @endfor
                    </div>
                    @error('rating')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="ulasan" class="form-label fw-semibold">Ulasan (opsional)</label>
                    <textarea id="ulasan" name="ulasan" rows="3" class="form-control" maxlength="1000"
                        placeholder="Ceritakan pengalaman Anda...">{{ old('ulasan', $item->ulasan) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Kirim Penilaian</button>
            </form>
            <style>
                .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
                .rating-input input { display: none; }
                .rating-input label { font-size: 1.8rem; color: #dee2e6; cursor: pointer; line-height: 1; }
                .rating-input input:checked ~ label,
                .rating-input label:hover,
                .rating-input label:hover ~ label { color: #ffc107; }
            </style>
            <script>
                // Kunci warna bintang + tampilkan angka pilihan (cadangan bila CSS :checked tak jalan)
                (function() {
                    const wrap = document.querySelector('.rating-input');
                    if (!wrap) return;
                    const out = document.getElementById('rating-angka');
                    const paint = (val) => {
                        wrap.querySelectorAll('label').forEach(lb => {
                            const v = parseInt(lb.dataset.nilai || '0', 10);
                            lb.style.color = (val > 0 && v <= val) ? '#ffc107' : '#dee2e6';
                        });
                        if (out) out.textContent = val > 0 ? val + ' dari 5 bintang' : '';
                    };
                    wrap.querySelectorAll('input[name="rating"]').forEach(i => {
                        i.addEventListener('change', () => paint(parseInt(i.value, 10)));
                    });
                    const checked = wrap.querySelector('input[name="rating"]:checked');
                    paint(checked ? parseInt(checked.value, 10) : 0);
                })();
            </script>
            @endif
        </div>
    </div>
    @endif

    {{-- Jejak audit --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history me-1"></i> Jejak Perubahan
                <span class="badge bg-secondary ms-1">{{ $jejak->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:150px;">Waktu</th>
                            <th style="width:110px;">Aksi</th>
                            <th style="width:160px;">Oleh</th>
                            <th>Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jejak as $log)
                        <tr>
                            <td class="text-muted small">{{ $log->created_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td><span class="badge {{ $log->event_badge }}">{{ $log->event_label }}</span></td>
                            <td class="small">{{ $log->user_name ?? 'Sistem' }}</td>
                            <td class="small">
                                <div class="fw-semibold">{{ $log->auditable_label }}</div>
                                @foreach ($log->changes() as $field => $ubah)
                                @php
                                $label = ['judul' => 'Judul','isi_aduan' => 'Isi aduan','kategori' => 'Kategori','lokasi' => 'Lokasi','status' => 'Status','prioritas' => 'Prioritas','sifat' => 'Sifat','catatan' => 'Catatan','tanggal' => 'Tanggal','rating' => 'Rating','ulasan' => 'Ulasan','archived_at' => 'Arsip'][$field] ?? ucfirst(str_replace('_', ' ', $field));
                                $lama = is_string($ubah['old']) && str_contains($ubah['old'], '/') ? basename($ubah['old']) : $ubah['old'];
                                $baru = is_string($ubah['new']) && str_contains($ubah['new'], '/') ? basename($ubah['new']) : $ubah['new'];
                                @endphp
                                <div>{{ $label }}: <span class="text-muted">{{ $lama === null || $lama === '' ? '—' : \Illuminate\Support\Str::limit((string) $lama, 60) }}</span> → <strong>{{ $baru === null || $baru === '' ? '—' : \Illuminate\Support\Str::limit((string) $baru, 60) }}</strong></div>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada riwayat perubahan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
