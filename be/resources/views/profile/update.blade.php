@extends('layouts.app')
@section('title', 'Profil')
@section('breadcrumb')

<x-breadcrumb
    title="Profil"
    page="Pengaturan Akun"
    active="Profil"
    route="{{ route('profile.edit') }}" />

@endsection

@section('content')

{{-- Notifikasi Berhasil --}}
@if (session('success'))

<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    <span class="text-white">
        {{ session('success') }}
    </span>
    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Tutup">
    </button>
</div>

@endif


{{-- Kesalahan Kata Sandi --}}
@if ($errors->updatePassword->any())

@foreach ($errors->updatePassword->all() as $error)

<div class="alert alert-danger alert-dismissible fade show mb-4"
    role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <span class="text-white">
        {{ $error }}
    </span>
    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Tutup">
    </button>
</div>

@endforeach
@endif


{{-- Header Profil --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">

            {{-- Avatar --}}
            <div class="col-auto">
                <div class="position-relative">
                    <img src="{{ Auth::user()->avatar
                            ? (Str::startsWith(Auth::user()->avatar, 'http')
                                ? Auth::user()->avatar
                                : asset('storage/' . Auth::user()->avatar))
                            : asset('dist/assets/images/avatar.jpg') }}"
                        alt="{{ auth()->user()->name }}"
                        class="rounded-circle border border-4 border-white shadow"
                        style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            </div>


            {{-- Informasi Pengguna --}}
            <div class="col">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h3 class="fw-semibold mb-0">
                        {{ auth()->user()->name }}
                    </h3>

                    @if (auth()->user()->getRoleNames()->isNotEmpty())
                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                        {{ ucfirst(auth()->user()->getRoleNames()[0]) }}
                    </span>
                    @endif
                </div>

                <div class="text-muted mb-3">
                    <i class="bi bi-envelope me-1"></i>
                    {{ auth()->user()->email }}
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Akun Aktif
                    </span>

                    <span class="badge rounded-pill bg-light text-muted border px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        Bergabung {{ auth()->user()->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>


            {{-- Tombol Aksi --}}
            <div class="col-auto">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button"
                        class="btn btn-light border px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-form-edit-password-{{ auth()->user()->getAuthIdentifier() }}">
                        <i class="bi bi-lock me-1"></i>
                        Ubah Kata Sandi
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- Form Utama --}}
<form action="{{ route('account.update', $user->uuid) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="row g-4">


        {{-- Foto Profil --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="fw-semibold mb-1">
                                Foto Profil
                            </h5>

                            <small class="text-muted">
                                Foto profil akun Anda
                            </small>
                        </div>

                        <div class="bg-primary-subtle rounded-3 p-2">
                            <i class="bi bi-camera text-primary fs-5"></i>
                        </div>
                    </div>


                    {{-- Avatar Saat Ini --}}
                    <div class="text-center mb-4">
                        <img src="{{ Auth::user()->avatar
                                ? (Str::startsWith(Auth::user()->avatar, 'http')
                                    ? Auth::user()->avatar
                                    : asset('storage/' . Auth::user()->avatar))
                                : asset('dist/assets/images/avatar.jpg') }}"
                            alt="{{ auth()->user()->name }}"
                            class="rounded-circle border border-4 border-light shadow-sm"
                            style="width: 170px; height: 170px; object-fit: cover;">
                    </div>


                    <div class="bg-primary-subtle rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-image text-primary fs-4"></i>

                            <div>
                                <div class="fw-semibold">
                                    Unggah Foto Baru
                                </div>

                                <small class="text-muted">
                                    JPG atau PNG, maksimal 1 MB
                                </small>
                            </div>
                        </div>
                    </div>


                    <input
                        class="form-control @error('avatar') is-invalid @enderror"
                        id="avatar"
                        type="file"
                        name="avatar"
                        accept="image/png,image/jpeg">

                    @error('avatar')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>
            </div>
        </div>


        {{-- Informasi Pribadi --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="fw-semibold mb-1">
                                Informasi Pribadi
                            </h5>

                            <small class="text-muted">
                                Pastikan informasi pribadi Anda selalu benar dan terbaru.
                            </small>
                        </div>

                        <div class="bg-success-subtle rounded-3 p-2">
                            <i class="bi bi-person-vcard text-success fs-5"></i>
                        </div>
                    </div>


                    {{-- Nama Lengkap --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-medium">
                            Nama Lengkap
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap Anda"
                            required>

                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    {{-- Email & Nomor Telepon --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium">
                                Alamat Email
                            </label>

                            <input
                                class="form-control bg-light"
                                id="email"
                                type="email"
                                value="{{ $user->email }}"
                                disabled>

                            <small class="text-muted">
                                Alamat email tidak dapat diubah.
                            </small>
                        </div>


                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-medium">
                                Nomor WhatsApp
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                class="form-control @error('no_hp') is-invalid @enderror"
                                id="no_hp"
                                type="text"
                                name="no_hp"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                placeholder="Contoh: 08123456789"
                                required>

                            @error('no_hp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>


                    {{-- Informasi Kelahiran --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label for="tempat_lahir" class="form-label fw-medium">
                                Tempat Lahir
                            </label>

                            <input
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                id="tempat_lahir"
                                type="text"
                                name="tempat_lahir"
                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                placeholder="Masukkan tempat lahir Anda">

                            @error('tempat_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label fw-medium">
                                Tanggal Lahir
                            </label>

                            <input
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir"
                                type="date"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">

                            @error('tanggal_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>


                    {{-- Jenis Kelamin & Agama --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label fw-medium">
                                Jenis Kelamin
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                required>

                                <option value="">
                                    Pilih Jenis Kelamin
                                </option>

                                <option value="L"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>

                            </select>

                            @error('jenis_kelamin')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label for="agama" class="form-label fw-medium">
                                Agama
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="agama"
                                id="agama"
                                class="form-select @error('agama') is-invalid @enderror"
                                required>

                                <option value="">
                                    Pilih Agama
                                </option>

                                @foreach ([
                                'Islam',
                                'Kristen',
                                'Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu',
                                'Lainnya'
                                ] as $agama)

                                <option value="{{ $agama }}"
                                    {{ old('agama', $user->agama) == $agama ? 'selected' : '' }}>
                                    {{ $agama }}
                                </option>

                                @endforeach

                            </select>

                            @error('agama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="kecamatan" class="small mb-1">Kecamatan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="kecamatan" name="kecamatan_id" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}"
                                    {{ old('kecamatan_id', $user->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                    {{ $kecamatan->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="kelurahan" class="small mb-1">Kelurahan <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="kelurahan" name="kelurahan_id" required>
                                <option value="">-- Pilih Kelurahan --</option>
                                @if (!empty($user->kecamatan_id))
                                @php
                                $kelurahans = \App\Models\Kelurahan::where(
                                'kecamatan_id',
                                $user->kecamatan_id,
                                )->get();
                                @endphp
                                @foreach ($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}"
                                    {{ old('kelurahan_id', $user->kelurahan_id) == $kelurahan->id ? 'selected' : '' }}>
                                    {{ $kelurahan->nama }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>


                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label for="alamat" class="form-label fw-medium">
                            Alamat
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            class="form-control @error('alamat') is-invalid @enderror"
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat tempat tinggal Anda"
                            required>{{ old('alamat', $user->alamat) }}</textarea>

                        @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    {{-- Informasi Profesional --}}
                    @role('user')

                    {{-- Disembunyikan untuk pengguna biasa --}}

                    @else

                    <div class="border-top pt-4 mt-4">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="fw-semibold mb-1">
                                    Informasi Profesional
                                </h5>

                                <small class="text-muted">
                                    Status keaktifan akun Anda.
                                </small>
                            </div>

                            <div class="bg-warning-subtle rounded-3 p-2">
                                <i class="bi bi-award text-warning fs-5"></i>
                            </div>
                        </div>


                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium d-block">
                                    Status Akun
                                </label>

                                <input type="hidden" name="is_active" value="inactive">

                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input @error('is_active') is-invalid @enderror"
                                        type="checkbox"
                                        role="switch"
                                        id="is_active"
                                        name="is_active"
                                        value="active"
                                        {{ old('is_active', $user->is_active ?? 'active') === 'active' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Akun Aktif
                                    </label>
                                </div>

                                <small class="text-muted">
                                    Nonaktifkan untuk menonaktifkan akun ini.
                                </small>

                                @error('is_active')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>


                    </div>

                    @endrole

                    {{-- Tombol Simpan --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">

                        <button
                            type="button"
                            class="btn btn-light border px-4"
                            onclick="window.history.back()">
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-1"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </div>
            </div>
        </div>

    </div>

</form>


@include('profile.modals-file-preview', ['user' => $user])
@include('profile.partials.change-password')

@push('after-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kecSelect = document.getElementById('kecamatan');
        const kelSelect = document.getElementById('kelurahan');
        if (!kecSelect || !kelSelect) return;

        const selectedKel = "{{ old('kelurahan_id', $user->kelurahan_id) }}";
        const baseUrl = "{{ url('backend/get-kelurahan') }}";

        kecSelect.addEventListener('change', function() {
            const id = this.value;
            kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            if (!id) return;
            kelSelect.innerHTML = '<option value="">Memuat...</option>';
            fetch(baseUrl + '/' + id, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                    Object.entries(data).forEach(function([val, label]) {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = label;
                        if (String(val) === String(selectedKel)) opt.selected = true;
                        kelSelect.appendChild(opt);
                    });
                })
                .catch(function() {
                    kelSelect.innerHTML = '<option value="">Gagal memuat kelurahan</option>';
                });
        });
    });
</script>
@endpush
@endsection