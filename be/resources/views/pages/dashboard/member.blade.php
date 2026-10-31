@extends('layouts.app')

@section('content')

<div class="container-fluid pb-5">

    <!-- Hero -->
    <div class="dash-hero mb-4">
        <div class="hero-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="hero-date">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ now()->translatedFormat('l, j F Y') }}
                </span>
                <h3 class="fw-bold text-white mb-1">Halo, {{ auth()->user()->name }}</h3>
                <p class="mb-0">
                    Sampaikan laporan kerusakan infrastruktur dan pantau status penanganannya di sini.
                </p>
            </div>
            <div class="d-flex gap-2 hero-action">
                @can('aduans.store')
                <a href="{{ route('aduans.create') }}" class="btn btn-light fw-bold px-4">
                    <i class="bi bi-plus-lg me-1"></i> Buat Pengaduan
                </a>
                @endcan
                <a href="{{ route('aduans.index') }}" class="btn btn-warning fw-bold px-4">
                    <i class="bi bi-clock-history me-1"></i> Riwayat Saya
                </a>
            </div>
        </div>
    </div>

    @if (($belumDinilai ?? 0) > 0)
    <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-star me-1"></i> {{ $belumDinilai }} aduan selesai menunggu penilaian Anda.</span>
        <a href="{{ route('aduans.index', ['status' => 'selesai']) }}" class="btn btn-sm btn-warning text-white">Nilai Sekarang</a>
    </div>
    @endif

    <!-- Statistik -->
    <div class="row g-3 g-lg-4 mb-4">

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon blue">
                        <i class='bi bi-megaphone'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Total Pengaduan</div>
                        <div class="stat-value">{{ $statTotal ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon yellow">
                        <i class='bi bi-hourglass-split'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Menunggu</div>
                        <a href="{{ route('aduans.index', ['status' => 'menunggu']) }}" class="text-decoration-none">
                            <div class="stat-value">{{ $statMenunggu ?? 0 }}</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon green">
                        <i class='bi bi-gear'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Diproses</div>
                        <div class="stat-value">{{ $statDiproses ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon red">
                        <i class='bi bi-check-circle'></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="stat-label">Selesai</div>
                        <a href="{{ route('aduans.index', ['status' => 'selesai']) }}" class="text-decoration-none">
                            <div class="stat-value">{{ $statSelesai ?? 0 }}</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan terbaru -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Pengaduan Terbaru Saya</h5>
            <a href="{{ route('aduans.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor</th>
                            <th>Judul</th>
                            <th class="hide-xs">Kategori</th>
                            <th>Status</th>
                            <th class="hide-xs">Dilaporkan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentAduan ?? [] as $aduan)
                        <tr>
                            <td><code style="font-size:0.75rem;">{{ $aduan->nomor_aduan }}</code></td>
                            <td style="min-width:140px;"><span class="fw-medium">{{ Str::limit($aduan->judul, 45) }}</span></td>
                            <td class="hide-xs"><span class="badge bg-info">{{ $aduan->kategori }}</span></td>
                            <td>
                                @php
                                $badge = ['menunggu' => 'secondary','diverifikasi' => 'info','diproses' => 'warning','selesai' => 'success','ditolak' => 'danger'][$aduan->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($aduan->status) }}</span>
                            </td>
                            <td class="hide-xs"><small class="text-muted">{{ $aduan->tanggal_pengaduan?->format('d M Y') ?? '-' }}</small></td>
                            <td>
                                <a href="{{ route('aduans.show', $aduan->uuid) }}"
                                    class="btn btn-sm btn-info text-white" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-2">Belum ada pengaduan. Yuk, laporkan temuan Anda!</p>
                                @can('aduans.store')
                                <a href="{{ route('aduans.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Buat Pengaduan Pertama
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@if (blank(Auth::user()->no_hp))
<div class="modal fade"
    id="completeProfileModal"
    tabindex="-1"
    aria-labelledby="completeProfileModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('dashboard.submitSumber') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="completeProfileModalLabel">
                        Lengkapi Informasi Anda
                    </h5>
                </div>

                <div class="modal-body">

                    <p class="text-secondary">
                        Lengkapi informasi berikut sebelum melanjutkan.
                    </p>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">
                            Nomor WhatsApp
                        </label>

                        <input type="text"
                            name="no_hp"
                            id="no_hp"
                            class="form-control @error('no_hp') is-invalid @enderror"
                            placeholder="08xxxxxxxxxx"
                            value="{{ old('no_hp') }}"
                            required>

                        @error('no_hp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sumber_informasi" class="form-label">
                            Dari mana Anda tahu layanan ini?
                        </label>

                        <select name="sumber_informasi"
                            id="sumber_informasi"
                            class="form-select @error('sumber_informasi') is-invalid @enderror"
                            required>
                            <option value="">Pilih salah satu</option>
                            <option value="google">Pencarian Google</option>
                            <option value="sosmed">Media Sosial</option>
                            <option value="teman">Teman / Keluarga</option>
                            <option value="komunitas">Komunitas</option>
                            <option value="event">Acara / Sosialisasi</option>
                            <option value="website">Website</option>
                            <option value="lainnya">Lainnya</option>
                        </select>

                        @error('sumber_informasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Simpan Informasi
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif

@if (blank(Auth::user()->no_hp))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('completeProfileModal');

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });

            modal.show();
        }
    });
</script>
@endif

@endsection
