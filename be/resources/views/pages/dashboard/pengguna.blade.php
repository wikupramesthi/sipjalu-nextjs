@extends('layouts.app')
@section('title', 'All Members')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="All Members" page="Settings" active="All Members" route="{{ route('pengguna.index') }}" />
@endsection

<!-- Content -->
<section class="section mt-2">
    {{-- Kartu statistik: angka mengikuti data hasil filter --}}
    <div class="row mb-3 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-primary"><i class="bx bx-user"></i></div><div><div class="ml-stat-value">{{ number_format($stats['total']) }}</div><div class="ml-stat-label">Total Pengguna</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-success"><i class="bx bx-check-circle"></i></div><div><div class="ml-stat-value">{{ number_format($stats['verified']) }}</div><div class="ml-stat-label">Terverifikasi</div></div></div></div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="card shadow-sm h-100"><div class="card-body py-2 px-3"><div class="ml-stat"><div class="ml-stat-icon ic-warning"><i class="bx bx-time"></i></div><div><div class="ml-stat-value">{{ number_format($stats['unverified']) }}</div><div class="ml-stat-label">Belum Verifikasi</div></div></div></div></div></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-2 px-3">
            <div class="col-12">
                <form action="{{ route('pengguna.index') }}" method="GET" class="d-flex flex-wrap align-items-end gap-2">
                    <div>
                        <label class="form-label small mb-0">Dari</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label class="form-label small mb-0">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm" style="min-width:130px;">
                            <option value="">Semua Tahun</option>
                            @for ($tahun = date('Y'); $tahun >= 2025; $tahun--)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                        <a href="{{ route('pengguna.index') }}" class="btn btn-sm btn-light">Reset</a>
                        <a href="{{ route('pengguna.export', request()->all()) }}" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mx-2">
                <table class="table table-hover mb-0" style="font-size:0.9rem;" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Daftar</th>
                            <th>Nama</th>
                            <th class="hide-xs">Email</th>
                            <th class="hide-sm">No. HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><small>{{ $user->created_at?->format('d/m/Y') ?? '-' }}</small></td>
                                <td><span class="fw-semibold">{{ $user->name }}</span></td>
                                <td class="hide-xs"><small>{{ $user->email }}</small></td>
                                <td class="hide-sm"><small>{{ $user->no_hp ?? '-' }}</small></td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Verifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#cekProfilModal-{{ $user->uuid }}" title="Lihat detail">
                                        <i class="bi bi-eye"></i> Lihat
                                    </button>
                                    @include('pages.dashboard.modal-detail-user', ['user' => $user])
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="card-footer bg-transparent d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
<!-- / Content -->
@endsection
