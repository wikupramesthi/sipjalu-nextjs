@extends('layouts.app')
@section('title', 'Log Audit Berita')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Log Audit Berita" page="Berita" active="Log Audit" route="{{ route('beritas.index') }}" />
@endsection

<section class="section">
    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0"><i class="bi bi-clock-history me-1 text-primary"></i>Riwayat Berita</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($logs->total()) }} riwayat</span>
            </div>
            <a href="{{ route('beritas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>

        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('beritas.logAudit') }}" class="d-flex flex-wrap align-items-end gap-2">
                <div>
                    <label class="form-label small mb-0" for="auditEvent">Aksi</label>
                    <select name="event" id="auditEvent" class="form-select form-select-sm">
                        <option value="">Semua Aksi</option>
                        @foreach ($events as $key => $label)
                            <option value="{{ $key }}" @selected(request('event') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="auditUser">Pengguna</label>
                    <select name="user_uuid" id="auditUser" class="form-select form-select-sm">
                        <option value="">Semua Pengguna</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->user_uuid }}" @selected(request('user_uuid') === $user->user_uuid)>{{ $user->user_name ?? 'Sistem' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="auditStart">Dari tanggal</label>
                    <input type="date" name="start_date" id="auditStart" class="form-control form-control-sm" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="form-label small mb-0" for="auditEnd">Sampai tanggal</label>
                    <input type="date" name="end_date" id="auditEnd" class="form-control form-control-sm" value="{{ request('end_date') }}">
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('beritas.logAudit') }}" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Waktu</th><th>Berita</th><th>Aksi</th><th>Pengguna</th><th>Alamat IP</th></tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="text-nowrap">
                                <small class="text-muted">{{ $log->created_at?->format('d M Y') }}</small><br>
                                <span class="small">{{ $log->created_at?->format('H:i:s') }}</span>
                            </td>
                            <td><span class="fw-semibold">{{ $log->auditable_label ?? '(berita)' }}</span></td>
                            <td><span class="badge {{ $log->event_badge }}">{{ $log->event_label }}</span></td>
                            <td>{{ $log->user_name ?? 'Sistem' }}</td>
                            <td><small class="text-muted">{{ $log->ip_address ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada riwayat berita.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }}</small>
            {{ $logs->links('pagination::minimal') }}
        </div>
    </div>
</section>
@endsection
