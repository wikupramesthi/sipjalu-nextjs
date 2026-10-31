@extends('layouts.app')

@section('title', 'Failed Login')

@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Failed Login" page="Keamanan" active="Failed Login"
        route="{{ route('security.failed-login.index') }}" />
@endsection

<section class="section">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible mb-3 fade show" role="alert">
            <span class="alert-text text-white">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bx bx-shield-x"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['total']) }}</div>
                            <div class="ml-stat-label">Total Gagal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-time-five"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['last24']) }}</div>
                            <div class="ml-stat-label">24 Jam Terakhir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-globe"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['unique_ip']) }}</div>
                            <div class="ml-stat-label">IP Unik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-violet"><i class="bx bx-envelope"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['unique_email']) }}</div>
                            <div class="ml-stat-label">Email Unik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-warning"><i class="bx bx-alarm-exclamation"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['suspicious_ip']) }}</div>
                            <div class="ml-stat-label">IP Berisiko</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card ml-toolbar security-toolbar mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
                <form method="GET" action="{{ route('security.failed-login.index') }}"
                    class="security-filter-form d-flex flex-wrap align-items-end gap-3">
                    <div>
                        <label class="security-filter-label" for="filterSearch">Cari</label>
                        <input type="text" name="search" id="filterSearch" class="form-control"
                            placeholder="Email atau IP..." value="{{ $search }}" style="min-width: 200px;">
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterStart">Dari Tanggal</label>
                        <input type="date" name="start_date" id="filterStart" class="form-control"
                            value="{{ $startDate }}">
                    </div>
                    <div>
                        <label class="security-filter-label" for="filterEnd">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="filterEnd" class="form-control"
                            value="{{ $endDate }}">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('security.failed-login.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>

                @can('failed-login.destroy')
                    @if ($stats['total'] > 0)
                        <button type="button" class="btn btn-outline-danger" onclick="clearFailedLogins()">
                            <i class="bi bi-eraser"></i> Bersihkan Semua
                        </button>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Login Gagal</h5>
                <span class="badge bg-danger-subtle text-danger">{{ number_format($attempts->total()) }}
                    percobaan</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <p class="text-muted small mb-0 d-none d-md-block">Percobaan login yang gagal beserta alamat IP-nya.</p>
                @can('failed-login.destroy')
                <button type="button" id="bulkDeleteFailedBtn" class="btn btn-sm btn-danger d-none" onclick="bulkDeleteFailed()">
                    <i class="bi bi-trash"></i> Hapus terpilih (<span id="bulkCountFailed">0</span>)
                </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <form id="bulk-delete-failed-form" action="{{ route('security.failed-login.bulkDestroy') }}" method="POST" class="d-none">
                @csrf @method('DELETE')
                <div id="bulk-ids-failed"></div>
            </form>
            <div class="table-responsive">
                <table class="table security-table">
                    <thead>
                        <tr>
                            @can('failed-login.destroy')
                            <th style="width:36px;"><input type="checkbox" id="checkAllFailed" class="form-check-input"></th>
                            @endcan
                            <th>Email</th>
                            <th>Perangkat</th>
                            <th>IP Address</th>
                            <th>Tanda</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($attempts as $attempt)
                            @php $flags = $detector->failedFlags($attempt, $suspiciousIps); @endphp
                            <tr>
                                @can('failed-login.destroy')
                                <td><input type="checkbox" class="form-check-input failed-check" value="{{ $attempt->id }}"></td>
                                @endcan
                                <td>
                                    <span class="d-block">{{ $attempt->email ?? 'Tidak diketahui' }}</span>
                                    @if ($attempt->user)
                                        <small class="log-muted"><i class="bi bi-person-check"></i>
                                            {{ $attempt->user->name }}</small>
                                    @else
                                        <small class="log-muted">Akun tidak terdaftar</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ $attempt->browser() }}</span>
                                    <small class="log-muted">
                                        {{ $attempt->platform() }}
                                        <span class="badge bg-light text-dark ms-1">{{ $attempt->deviceType() }}</span>
                                    </small>
                                </td>
                                <td><span class="font-monospace small">{{ $attempt->ip_address ?? '-' }}</span></td>
                                <td>
                                    @if ($flags)
                                        @foreach ($flags as $flag)
                                            <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1 mb-1">
                                                <i class="bi bi-exclamation-triangle"></i>{{ $flag }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="log-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ optional($attempt->attempted_at)->translatedFormat('d M Y H:i') ?? '-' }}</span>
                                    <small class="log-muted">{{ optional($attempt->attempted_at)?->diffForHumans() }}</small>
                                </td>
                                <td class="text-end">
                                    @can('failed-login.destroy')
                                        <button type="button" class="btn btn-sm btn-danger text-white" title="Hapus"
                                            onclick="deleteFailedLogin({{ $attempt->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-failed-login-{{ $attempt->id }}"
                                            action="{{ route('security.failed-login.destroy', $attempt->id) }}"
                                            method="POST" class="d-none">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-shield-check fs-3 d-block mb-2"></i>
                                    Tidak ada percobaan login gagal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attempts->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $attempts->firstItem() }}</strong> -
                        <strong>{{ $attempts->lastItem() }}</strong> dari
                        <strong>{{ $attempts->total() }}</strong> percobaan
                    </div>
                    <div>{{ $attempts->links('pagination::minimal') }}</div>
                </div>
            @endif
        </div>
    </div>
</section>

<form id="clear-failed-login-form" action="{{ route('security.failed-login.clear') }}" method="POST" class="d-none">
    @method('DELETE')
    @csrf
</form>

@push('after-script')
    <script>
        function deleteFailedLogin(id) {
            Swal.fire({
                title: 'Hapus catatan ini?',
                text: 'Catatan percobaan login ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-failed-login-' + id).submit();
                }
            });
        }

        function clearFailedLogins() {
            Swal.fire({
                title: 'Bersihkan semua catatan?',
                text: 'Seluruh riwayat login gagal akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bersihkan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clear-failed-login-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAllFailed');
            const bulkBtn = document.getElementById('bulkDeleteFailedBtn');
            const bulkCount = document.getElementById('bulkCountFailed');
            function updateBulk() {
                const checks = document.querySelectorAll('.failed-check');
                const checked = document.querySelectorAll('.failed-check:checked');
                const n = checked.length;
                if (bulkCount) bulkCount.textContent = n;
                if (bulkBtn) bulkBtn.classList.toggle('d-none', n === 0);
                if (checkAll) {
                    checkAll.checked = n > 0 && n === checks.length;
                    checkAll.indeterminate = n > 0 && n < checks.length;
                }
            }
            if (checkAll) checkAll.addEventListener('change', function() {
                document.querySelectorAll('.failed-check').forEach(c => c.checked = this.checked);
                updateBulk();
            });
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('failed-check')) updateBulk();
            });
        });
        function bulkDeleteFailed() {
            const ids = Array.from(document.querySelectorAll('.failed-check:checked')).map(c => c.value);
            if (ids.length === 0) return;
            Swal.fire({
                title: 'Hapus ' + ids.length + ' catatan terpilih?',
                text: 'Data yang dipilih akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('bulk-delete-failed-form');
                    const container = document.getElementById('bulk-ids-failed');
                    container.innerHTML = '';
                    ids.forEach(id => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = id;
                        container.appendChild(inp);
                    });
                    form.submit();
                }
            });
        }
    </script>
@endpush
@endsection
