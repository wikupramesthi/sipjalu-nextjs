@extends('layouts.app')

@section('title', 'Blokir Login')

@section('content')

@section('breadcrumb')
    <x-breadcrumb title="Blokir Login" page="Keamanan" active="Blokir Login"
        route="{{ route('security.login-lockout.index') }}" />
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

    <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-info-circle fs-5"></i>
        <div class="small text-white">
            IP atau email otomatis diblokir sementara setelah
            <strong>{{ config('security.brute_force.max_attempts', 5) }} percobaan login gagal</strong>
            dalam {{ config('security.brute_force.decay_minutes', 15) }} menit, selama
            {{ config('security.brute_force.lockout_minutes', 30) }} menit. Blokir akan terbuka sendiri atau dapat
            dibuka secara manual melalui halaman ini.
        </div>
    </div>

    <div class="row mb-4 g-3 ml-stats">
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-danger"><i class="bx bx-block"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['blocked']) }}</div>
                            <div class="ml-stat-label">Sedang Diblokir</div>
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
                            <div class="ml-stat-value">{{ number_format($stats['tracked']) }}</div>
                            <div class="ml-stat-label">Terpantau</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="ml-stat">
                        <div class="ml-stat-icon ic-primary"><i class="bx bx-time-five"></i></div>
                        <div>
                            <div class="ml-stat-value">{{ number_format($stats['attempts_today']) }}</div>
                            <div class="ml-stat-label">Percobaan Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card ml-toolbar security-toolbar mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('security.login-lockout.index') }}"
                class="security-filter-form d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label class="security-filter-label" for="filterSearch">Cari</label>
                    <input type="text" name="search" id="filterSearch" class="form-control"
                        placeholder="IP atau email..." value="{{ $search }}" style="min-width: 200px;">
                </div>
                <div>
                    <label class="security-filter-label" for="filterType">Tipe</label>
                    <select name="type" id="filterType" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="ip" @selected($type === 'ip')>IP Address</option>
                        <option value="email" @selected($type === 'email')>Email</option>
                    </select>
                </div>
                <div>
                    <label class="security-filter-label" for="filterStatus">Status</label>
                    <select name="status" id="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="blocked" @selected($status === 'blocked')>Diblokir</option>
                        <option value="tracked" @selected($status === 'tracked')>Dipantau</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('security.login-lockout.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Daftar Blokir</h5>
                <span class="badge bg-primary-subtle text-primary">{{ number_format($lockouts->total()) }}
                    entri</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <p class="text-muted small mb-0 d-none d-md-block">Pemantauan percobaan login gagal yang dihitung per IP dan email.</p>
                @can('login-lockout.destroy')
                <button type="button" id="bulkUnlockBtn" class="btn btn-sm btn-success d-none" onclick="bulkUnlock()">
                    <i class="bi bi-unlock"></i> Buka terpilih (<span id="bulkCountLockout">0</span>)
                </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <form id="bulk-unlock-form" action="{{ route('security.login-lockout.bulkDestroy') }}" method="POST" class="d-none">
                @csrf @method('DELETE')
                <div id="bulk-ids-lockout"></div>
            </form>
            <div class="table-responsive">
                <table class="table security-table">
                    <thead>
                        <tr>
                            @can('login-lockout.destroy')
                            <th style="width:36px;"><input type="checkbox" id="checkAllLockout" class="form-check-input"></th>
                            @endcan
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Percobaan</th>
                            <th>Status</th>
                            <th>Percobaan Terakhir</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($lockouts as $lockout)
                            <tr>
                                @can('login-lockout.destroy')
                                <td><input type="checkbox" class="form-check-input lockout-check" value="{{ $lockout->id }}"></td>
                                @endcan
                                <td><span class="badge bg-light text-dark">{{ $lockout->type_label }}</span></td>
                                <td><span class="font-monospace small">{{ $lockout->value }}</span></td>
                                <td>
                                    @if ($lockout->attempts >= config('security.brute_force.max_attempts', 5))
                                        <span class="badge bg-danger-subtle text-danger">{{ $lockout->attempts }}
                                            kali</span>
                                    @else
                                        <span class="log-muted">{{ $lockout->attempts }}
                                            / {{ config('security.brute_force.max_attempts', 5) }} </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($lockout->is_blocked)
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-lock-fill me-1"></i>Diblokir s/d
                                            {{ optional($lockout->blocked_until)->translatedFormat('d M Y H:i') }}
                                        </span>
                                        @if ($lockout->reason)
                                            <small class="log-muted d-block mt-1">{{ $lockout->reason }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="bi bi-eye me-1"></i>Dipantau
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ optional($lockout->last_attempt_at)->translatedFormat('d M Y H:i') ?? '-' }}</span>
                                    <small class="log-muted">{{ optional($lockout->last_attempt_at)?->diffForHumans() }}</small>
                                </td>
                                <td class="text-end">
                                    @can('login-lockout.destroy')
                                        <button type="button" class="btn btn-sm btn-outline-success" title="Buka blokir"
                                            onclick="unlockLoginLockout({{ $lockout->id }})">
                                            <i class="bi bi-unlock"></i>
                                        </button>
                                        <form id="unlock-login-lockout-{{ $lockout->id }}"
                                            action="{{ route('security.login-lockout.destroy', $lockout->id) }}"
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
                                    Tidak ada entri blokir yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($lockouts->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $lockouts->firstItem() }}</strong> -
                        <strong>{{ $lockouts->lastItem() }}</strong> dari
                        <strong>{{ $lockouts->total() }}</strong> entri
                    </div>
                    <div>{{ $lockouts->links('pagination::minimal') }}</div>
                </div>
            @endif
        </div>
    </div>
</section>

@push('after-script')
    <script>
        function unlockLoginLockout(id) {
            Swal.fire({
                title: 'Buka blokir?',
                text: 'Pengguna/IP ini akan diizinkan mencoba login kembali.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Buka!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#22c55e'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('unlock-login-lockout-' + id).submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAllLockout');
            const bulkBtn = document.getElementById('bulkUnlockBtn');
            const bulkCount = document.getElementById('bulkCountLockout');
            function updateBulk() {
                const checks = document.querySelectorAll('.lockout-check');
                const checked = document.querySelectorAll('.lockout-check:checked');
                const n = checked.length;
                if (bulkCount) bulkCount.textContent = n;
                if (bulkBtn) bulkBtn.classList.toggle('d-none', n === 0);
                if (checkAll) {
                    checkAll.checked = n > 0 && n === checks.length;
                    checkAll.indeterminate = n > 0 && n < checks.length;
                }
            }
            if (checkAll) checkAll.addEventListener('change', function() {
                document.querySelectorAll('.lockout-check').forEach(c => c.checked = this.checked);
                updateBulk();
            });
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('lockout-check')) updateBulk();
            });
        });
        function bulkUnlock() {
            const ids = Array.from(document.querySelectorAll('.lockout-check:checked')).map(c => c.value);
            if (ids.length === 0) return;
            Swal.fire({
                title: 'Buka ' + ids.length + ' blokir terpilih?',
                text: 'IP/Email yang dipilih akan diizinkan login kembali.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Buka!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#22c55e'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('bulk-unlock-form');
                    const container = document.getElementById('bulk-ids-lockout');
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
