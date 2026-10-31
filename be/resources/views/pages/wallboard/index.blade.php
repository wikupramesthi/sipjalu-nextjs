<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60">
    <title>Wallboard Pengaduan — Pemerintah Kota Bekasi</title>
    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #0b1220;
            color: #e2e8f0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            min-height: 100vh;
        }

        .wb-header {
            background: linear-gradient(135deg, #1B3A5F 0%, #274b73 100%);
            border-bottom: 3px solid #C9A227;
        }

        .wb-kpi {
            background: #16213a;
            border: 1px solid #263451;
            border-radius: 1rem;
        }

        .wb-kpi .angka {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }

        .wb-card {
            background: #16213a;
            border: 1px solid #263451;
            border-radius: 1rem;
        }

        .wb-bar-track {
            background: #263451;
            border-radius: 6px;
            height: 16px;
            overflow: hidden;
        }

        .wb-bar-fill {
            height: 100%;
            border-radius: 6px;
        }

        .wb-item {
            border-bottom: 1px solid #263451;
            padding: 10px 4px;
        }

        .wb-item:last-child {
            border-bottom: none;
        }

        .st {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .st-menunggu { background: #475569; color: #fff; }
        .st-diverifikasi { background: #0e7490; color: #fff; }
        .st-diproses { background: #b45309; color: #fff; }
        .st-selesai { background: #15803d; color: #fff; }
        .st-ditolak { background: #b91c1c; color: #fff; }
    </style>
</head>
<body>

<header class="wb-header py-3 mb-4">
    <div class="container-fluid px-4 d-flex align-items-center gap-3 flex-wrap">
        <img src="{{ asset('img/logo-white.png') }}" alt="Pemerintah Kota Bekasi" height="52">
        <div>
            <h2 class="fw-bold mb-0">PUSAT PANTAU PENGADUAN</h2>
            <div class="opacity-75">Pemerintah Kota Bekasi</div>
        </div>
        <div class="ms-auto text-end">
            <div id="wb-clock" class="fw-bold" style="font-size: 2rem; line-height: 1;">--:--:--</div>
            <div id="wb-date" class="opacity-75"></div>
        </div>
    </div>
</header>

<main class="container-fluid px-4 pb-4">
    <div class="row g-3 mb-4 text-center">
        <div class="col-6 col-lg-3">
            <div class="wb-kpi p-3">
                <div class="text-uppercase small opacity-75">Total Aduan Aktif</div>
                <div class="angka text-white">{{ number_format($total) }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="wb-kpi p-3" style="border-color: #15803d;">
                <div class="text-uppercase small opacity-75">Tingkat Penyelesaian</div>
                <div class="angka text-success">{{ $persen }}%</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="wb-kpi p-3" style="border-color: #b45309;">
                <div class="text-uppercase small opacity-75">Menunggu</div>
                <div class="angka text-warning">{{ number_format($menunggu) }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="wb-kpi p-3" style="border-color: #0e7490;">
                <div class="text-uppercase small opacity-75">Diproses</div>
                <div class="angka text-info">{{ number_format($diproses) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="wb-card p-3 h-100">
                <h5 class="fw-bold mb-2"><i class="bi bi-clock-history me-1"></i> Aduan Terbaru</h5>
                @forelse ($terbaru as $a)
                <div class="wb-item d-flex align-items-center gap-3">
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-truncate">{{ $a->judul }}</div>
                        <div class="small opacity-75"><code class="text-info">{{ $a->nomor_aduan }}</code> &bull; {{ $a->kategori }} &bull; {{ $a->tanggal_pengaduan?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <span class="st st-{{ $a->status }}">{{ ucfirst($a->status) }}</span>
                </div>
                @empty
                <p class="opacity-75 mb-0">Belum ada aduan aktif.</p>
                @endforelse
            </div>
        </div>
        <div class="col-lg-5">
            <div class="wb-card p-3 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart me-1"></i> Per Kategori</h5>
                @foreach ($perKategori as $i => $rk)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $rk['nama'] }}</span>
                        <strong>{{ number_format($rk['total']) }}</strong>
                    </div>
                    <div class="wb-bar-track">
                        <div class="wb-bar-fill" style="width: {{ $rk['total'] > 0 ? max(4, round($rk['total'] / $maks * 100)) : 0 }}%; background: {{ ['#38bdf8', '#fb923c', '#a78bfa', '#f87171', '#34d399', '#facc15', '#22d3ee', '#f472b6', '#a3e635'][$i % 9] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="text-center mt-3 small opacity-50">
        <i class="bi bi-arrow-clockwise me-1"></i> Diperbarui otomatis setiap 60 detik &bull; {{ now()->format('d M Y H:i:s') }}
    </div>
</main>

<script>
    function tick() {
        const now = new Date();
        const pad = (n) => String(n).padStart(2, '0');
        document.getElementById('wb-clock').textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        document.getElementById('wb-date').textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }
    tick();
    setInterval(tick, 1000);
</script>

</body>
</html>
