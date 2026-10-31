@extends('layouts.app')
@section('title', 'Tambah Item Menu: ' . $websiteMenu->name)
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Tambah Item Menu" page="Pengaturan" active="Item Menu: {{ $websiteMenu->name }}" route="{{ route('website-menu.items.index', $websiteMenu) }}" />
@endsection

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('website-menu.items.index', $websiteMenu) }}" class="btn btn-light">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="fw-normal mb-0 text-body">Tambah Item Menu</h4>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('website-menu.items.store', $websiteMenu) }}" method="POST">
            @csrf
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Contoh: Beranda, Tentang Kami" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">URL</label>
                <div class="col-sm-10">
                    <input type="url" class="form-control" name="url" placeholder="https://example.com/halaman">
                    <div class="form-text">Isi URL atau Route, tidak perlu keduanya</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Route</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="route" placeholder="home.index">
                    <div class="form-text">Nama route Laravel, kosongkan jika pakai URL</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Route Params (JSON)</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="route_params" rows="2" placeholder='{"id": 1, "slug": "tentang-kami"}'></textarea>
                    <div class="form-text">Parameter untuk route, format JSON</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Icon</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="icon" placeholder="bx bx-home">
                    <div class="form-text">Class icon Boxicons, contoh: bx bx-home, bx bx-user</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Parent</label>
                <div class="col-sm-10">
                    <select class="form-select" name="parent_id">
                        <option value="">-- Menu Utama (Top Level) --</option>
                        @foreach ($parentItems as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Pilih parent untuk membuat sub-menu (dropdown)</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Buka di Tab Baru</label>
                <div class="col-sm-10">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="target_blank" id="target_blank">
                        <label class="form-check-label" for="target_blank">Ya, buka di tab baru (_blank)</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                        <label class="form-check-label" for="status">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Posisi</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="position" value="0" min="0">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Deskripsi</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="description" rows="2" placeholder="Deskripsi item (opsional)"></textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('website-menu.items.index', $websiteMenu) }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection