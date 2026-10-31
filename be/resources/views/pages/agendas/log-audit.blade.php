@extends('layouts.app')
@section('title', 'Log Audit Agenda')
@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Log Audit Agenda" page="Agenda" active="Log Audit" route="{{ route('agendas.logAudit') }}" />
@endsection

<section class="section">
    <div class="card shadow-sm">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0"><i class="bi bi-clock-history me-1 text-primary"></i>Log Audit Approval Agenda</h6>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($logs->total()) }} riwayat</span>
            </div>
            <a href="{{ route('agendas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>

        <div class="card-body border-bottom">
            <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
                <div>
                    <label class="form-label small mb-0" for="filterAdmin">Admin</label>
                    <select name="admin" id="filterAdmin" class="form-select form-select-sm" style="min-width:180px;">
                        <option value="">Semua Admin</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->uuid }}" @selected(request('admin') === $admin->uuid)>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-0" for="filterAksi">Aksi</label>
                    <select name="aksi" id="filterAksi" class="form-select form-select-sm" style="min-width:180px;">
                        <option value="">Semua Aksi</option>
                        @foreach($aksiLabel as $key => $label)
                            <option value="{{ $key }}" @selected(request('aksi') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('agendas.logAudit') }}" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 agenda-table" style="font-size:0.88rem;">
                    <thead>
                        <tr>
                            <th style="min-width:130px;">Waktu</th>
                            <th>Agenda</th>
                            <th style="min-width:150px;">Aksi</th>
                            <th style="min-width:150px;">Admin</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap">
                                    <small class="text-muted">{{ $log->created_at->format('d M Y') }}</small><br>
                                    <span class="small">{{ $log->created_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold d-block">{{ Str::limit($log->agenda?->title ?? '(agenda dihapus)', 50) }}</span>
                                    <small class="text-muted">{{ $log->agenda?->user?->name ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $aksiColor[$log->aksi] ?? 'secondary' }}-subtle text-{{ $aksiColor[$log->aksi] ?? 'secondary' }} border border-{{ $aksiColor[$log->aksi] ?? 'secondary' }}-subtle">
                                        {{ $aksiLabel[$log->aksi] ?? $log->aksi }}
                                    </span>
                                </td>
                                <td>
                                    <span class="agenda-meta-inline"><i class="bi bi-person"></i>{{ $log->user?->name ?? 'Sistem' }}</span>
                                </td>
                                <td>
                                    @if($log->catatan)
                                        <small class="text-muted" title="{{ $log->catatan }}">{{ Str::limit($log->catatan, 60) }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada riwayat approval.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }}</small>
                    <div>{{ $logs->links('pagination::minimal') }}</div>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
