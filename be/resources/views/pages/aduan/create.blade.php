@extends('layouts.app')
@section('title', 'Tambah Aduan')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Tambah Aduan" page="Aduan" active="Tambah Aduan" route="{{ route('aduans.index') }}" />
@endsection

<section class="section">

    <div class="alert alert-info py-2">
        Nomor aduan dibuat otomatis (format ADU-YYYYMMDD-XXXX). Tanggal & jam pengaduan diisi otomatis waktu saat ini bila dikosongkan.
    </div>

    <form action="{{ route('aduans.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">Informasi Aduan</h5>
                        <p class="text-muted small mb-4">Masukkan detail aduan yang dilaporkan.</p>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="kategori" class="form-label fw-semibold">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select id="kategori" name="kategori"
                                    class="form-select @error('kategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach (\App\Models\Aduan::KATEGORI as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="judul" class="form-label fw-semibold">
                                    Judul <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="judul" name="judul"
                                    class="form-control @error('judul') is-invalid @enderror"
                                    value="{{ old('judul') }}" placeholder="Judul aduan" required>
                                @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="isi_aduan" class="form-label fw-semibold">
                                Isi Aduan <span class="text-danger">*</span>
                            </label>
                            <textarea id="isi_aduan" name="isi_aduan" rows="5"
                                class="form-control @error('isi_aduan') is-invalid @enderror"
                                placeholder="Jelaskan aduan..." required>{{ old('isi_aduan') }}</textarea>
                            @error('isi_aduan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="lokasi" class="form-label fw-semibold">Alamat / Lokasi</label>
                            <input type="text" id="lokasi" name="lokasi"
                                class="form-control @error('lokasi') is-invalid @enderror"
                                value="{{ old('lokasi') }}" placeholder="Terisi otomatis dari peta, bisa diubah manual">
                            @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="kecamatan_id" class="form-label fw-semibold">Kecamatan</label>
                                <select id="kecamatan_id" name="kecamatan_id"
                                    class="form-select @error('kecamatan_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatans as $kec)
                                    <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                        {{ $kec->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kecamatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="kelurahan_id" class="form-label fw-semibold">Kelurahan</label>
                                <select id="kelurahan_id" name="kelurahan_id"
                                    class="form-select @error('kelurahan_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kecamatan dulu --</option>
                                </select>
                                @error('kelurahan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="tanggal_kejadian" class="form-label fw-semibold">Tanggal Kejadian</label>
                                <input type="date" id="tanggal_kejadian" name="tanggal_kejadian"
                                    class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                    value="{{ old('tanggal_kejadian') }}">
                                @error('tanggal_kejadian')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="tanggal_pengaduan" class="form-label fw-semibold">Tanggal & Jam Pengaduan</label>
                                <input type="datetime-local" id="tanggal_pengaduan" name="tanggal_pengaduan"
                                    class="form-control @error('tanggal_pengaduan') is-invalid @enderror"
                                    value="{{ old('tanggal_pengaduan') }}">
                                <div class="form-text">Kosongkan untuk memakai waktu saat ini.</div>
                                @error('tanggal_pengaduan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-0">
                                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                                <input type="text" id="latitude" name="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude') }}" placeholder="-6.2...">
                                @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-0">
                                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                                <input type="text" id="longitude" name="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude') }}" placeholder="106.8...">
                                @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div id="duplikat-warning" class="alert alert-warning d-none mt-4">
                    <strong><i class="bi bi-exclamation-triangle me-1"></i> Ditemukan laporan serupa!</strong>
                    <span>Kemungkinan duplikat — periksa dulu sebelum menyimpan agar tidak dobel penanganan:</span>
                    <ul id="duplikat-list" class="mb-0 mt-2"></ul>
                </div>

                @include('pages.aduan.map-picker')

            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Privasi Pelapor</h5>
                        <p class="text-muted small mb-3">Pilih apakah nama Anda ditampilkan ke publik.</p>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="is_anonim" id="tampil_nama"
                                value="0" {{ old('is_anonim', '0') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tampil_nama">
                                <strong>Tampilkan nama</strong><br>
                                <small class="text-muted">Nama pelapor terlihat di daftar publik.</small>
                            </label>
                        </div>

                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="is_anonim" id="lapor_anonim"
                                value="1" {{ old('is_anonim') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="lapor_anonim">
                                <strong>Anonim</strong><br>
                                <small class="text-muted">Nama disembunyikan, tampil sebagai "Anonim".</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Foto Bukti</h5>
                        <p class="text-muted small mb-4">JPG, JPEG, PNG, WEBP. Maksimal 2 MB per foto.</p>

                        @foreach (['foto_1' => 'Foto 1', 'foto_2' => 'Foto 2', 'foto_3' => 'Foto 3'] as $field => $label)
                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label fw-semibold">{{ $label }}</label>
                            <input type="file" id="{{ $field }}" name="{{ $field }}"
                                class="form-control @error($field) is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                            @error($field)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Penanganan</h5>
                        <p class="text-muted small mb-4">Status, prioritas, dan sifat aduan.</p>

                      @role('super-admin|admin')
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror" required>
                                @foreach (['menunggu','diverifikasi','diproses','selesai','ditolak'] as $st)
                                <option value="{{ $st }}" {{ old('status', 'menunggu') == $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                                @endforeach
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                     @endrole

                        <div class="mb-3">
                            <label for="prioritas" class="form-label fw-semibold">Prioritas <span class="text-danger">*</span></label>
                            <select id="prioritas" name="prioritas"
                                class="form-select @error('prioritas') is-invalid @enderror" required>
                                @foreach (['rendah','sedang','tinggi','darurat'] as $pr)
                                <option value="{{ $pr }}" {{ old('prioritas', 'sedang') == $pr ? 'selected' : '' }}>
                                    {{ ucfirst($pr) }}
                                </option>
                                @endforeach
                            </select>
                            @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="sifat" class="form-label fw-semibold">Sifat <span class="text-danger">*</span></label>
                            <select id="sifat" name="sifat"
                                class="form-select @error('sifat') is-invalid @enderror" required>
                                @foreach (['biasa','penting','segera','rahasia'] as $sf)
                                <option value="{{ $sf }}" {{ old('sifat', 'biasa') == $sf ? 'selected' : '' }}>
                                    {{ ucfirst($sf) }}
                                </option>
                                @endforeach
                            </select>
                            @error('sifat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-lg me-1"></i> Simpan Aduan
                        </button>
                        <a href="{{ route('aduans.index') }}" class="btn btn-light w-100">Batal</a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-lightbulb me-1 text-warning"></i> Tips Pengaduan
                        </h5>
                        <p class="text-muted small mb-3">
                            Laporan yang lengkap akan lebih cepat ditangani petugas.
                        </p>
                        <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Gunakan judul yang jelas, mis. lokasi + masalahnya.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Jelaskan kronologi: kapan terjadi, seberapa parah, dan dampaknya.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Pilih kategori dan wilayah yang tepat agar diteruskan ke bidang yang benar.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Lampirkan foto bukti yang jelas (maksimal 3 foto).</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Tandai titik lokasi di peta seakurat mungkin.</span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Satu laporan untuk satu masalah, lalu simpan nomor aduan untuk melacak status.</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </form>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kecSelect = document.getElementById('kecamatan_id');
        const kelSelect = document.getElementById('kelurahan_id');

        kecSelect.addEventListener('change', function() {
            const id = this.value;
            kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            if (!id) return;
            fetch("{{ url('backend/aduans/kelurahan') }}/" + id)
                .then(r => r.json())
                .then(data => {
                    Object.entries(data).forEach(([val, label]) => {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = label;
                        kelSelect.appendChild(opt);
                    });
                });
        });
    });
</script>

<script>
    // Peringatan duplikat saat mengisi form
    document.addEventListener('DOMContentLoaded', function() {
        const box = document.getElementById('duplikat-warning');
        const list = document.getElementById('duplikat-list');
        if (!box || !list) return;

        let timer = null;
        const cekDuplikat = () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const params = new URLSearchParams({
                    kategori: document.getElementById('kategori')?.value || '',
                    latitude: document.getElementById('latitude')?.value || '',
                    longitude: document.getElementById('longitude')?.value || '',
                    kecamatan_id: document.getElementById('kecamatan_id')?.value || '',
                    tanggal_kejadian: document.getElementById('tanggal_kejadian')?.value || '',
                });
                fetch("{{ url('backend/aduans/cek-duplikat') }}?" + params.toString())
                    .then(r => r.json())
                    .then(res => {
                        const data = res.data || [];
                        if (data.length === 0) {
                            box.classList.add('d-none');
                            return;
                        }
                        list.innerHTML = '';
                        data.forEach(d => {
                            const li = document.createElement('li');
                            const a = document.createElement('a');
                            a.href = d.url;
                            a.target = '_blank';
                            a.textContent = d.nomor_aduan + ' — ' + d.judul + ' (' + d.status + ', ' + d.tanggal + ')';
                            li.appendChild(a);
                            list.appendChild(li);
                        });
                        box.classList.remove('d-none');
                    })
                    .catch(() => {});
            }, 600);
        };

        ['kategori', 'latitude', 'longitude', 'kecamatan_id', 'tanggal_kejadian'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', cekDuplikat);
        });
    });
</script>

@endsection
