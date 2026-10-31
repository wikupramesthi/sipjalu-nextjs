@extends('layouts.app')
@section('title', 'Identitas Website')
@section('breadcrumb')
<x-breadcrumb title="Identitas Website" page="Pengaturan" active="Identitas Website" route="{{ route('website-identity.edit') }}" />
@endsection
@section('content')
<section class="section">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('website-identity.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tab pane: Identitas Website | Hero Section --}}
        <div class="card ml-toolbar mb-4">
            <div class="card-body">
                <ul class="nav nav-pills media-tabs" id="identityTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-identitas-btn" data-bs-toggle="pill" data-bs-target="#tab-identitas" type="button" role="tab" aria-controls="tab-identitas" aria-selected="true">
                            <i class="bi bi-globe me-1"></i> Identitas Website
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-hero-btn" data-bs-toggle="pill" data-bs-target="#tab-hero" type="button" role="tab" aria-controls="tab-hero" aria-selected="false">
                            <i class="bi bi-layout-text-window-reverse me-1"></i> Hero Section
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="identityTabContent">
            {{-- Pane 1: Identitas Website ( Existing ) --}}
            <div class="tab-pane fade show active" id="tab-identitas" role="tabpanel" aria-labelledby="tab-identitas-btn">
                <div class="row g-4">
                    {{-- Kolom Kiri --}}
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center gap-2">
                                <i class="bi bi-globe text-primary"></i>
                                <h5 class="mb-0">Informasi Umum</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Site Name <span class="text-danger">*</span></label>
                                        <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $identity->site_name) }}" placeholder="Pemerintah Kota Bekasi" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Site Title <span class="text-danger">*</span></label>
                                        <input type="text" name="site_title" class="form-control" value="{{ old('site_title', $identity->site_title) }}" placeholder="Portal Resmi Pemerintah Kota Bekasi" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tagline</label>
                                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $identity->tagline) }}" placeholder="Agenda Pemerintah Kota Bekasi...">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi Singkat</label>
                                        <textarea name="description" rows="3" class="form-control" placeholder="Deskripsi website...">{{ old('description', $identity->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center gap-2">
                                <i class="bi bi-share text-primary"></i>
                                <h5 class="mb-0">Kontak & Sosial Media</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $identity->email) }}" placeholder="info@bekasikota.go.id">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. Telepon / WA</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $identity->phone) }}" placeholder="021-xxxxxxx">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="address" rows="2" class="form-control" placeholder="Jl. ... Kota Bekasi">{{ old('address', $identity->address) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-facebook text-primary me-1"></i> Facebook URL</label>
                                        <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $identity->facebook_url) }}" placeholder="https://facebook.com/...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-instagram text-danger me-1"></i> Instagram URL</label>
                                        <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $identity->instagram_url) }}" placeholder="https://instagram.com/...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-youtube text-danger me-1"></i> Youtube URL</label>
                                        <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $identity->youtube_url) }}" placeholder="https://youtube.com/...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-tiktok me-1"></i> Tiktok URL</label>
                                        <input type="url" name="tiktok_url" class="form-control" value="{{ old('tiktok_url', $identity->tiktok_url) }}" placeholder="https://tiktok.com/@...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center gap-2">
                                <i class="bi bi-code-slash text-primary"></i>
                                <h5 class="mb-0">SEO & Meta</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $identity->meta_title) }}" placeholder="Pemerintah Kota Bekasi - Portal Resmi">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Meta Description</label>
                                        <textarea name="meta_description" rows="2" class="form-control" placeholder="Deskripsi untuk SEO (150-160 karakter)">{{ old('meta_description', $identity->meta_description) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Meta Keywords (pisahkan koma)</label>
                                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $identity->meta_keywords) }}" placeholder="bekasikota, bekasi, agenda">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Google Analytics ID</label>
                                        <input type="text" name="google_analytics_id" class="form-control" value="{{ old('google_analytics_id', $identity->google_analytics_id) }}" placeholder="G-XXXXXXXX">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Google Site Verification</label>
                                        <input type="text" name="google_site_verification" class="form-control" value="{{ old('google_site_verification', $identity->google_site_verification) }}" placeholder="xxxx">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-image me-1 text-primary"></i> Branding</h5>
                            </div>
                            <div class="card-body">
                                @foreach(['logo' => 'Logo (PNG transparan, max 2MB)', 'favicon' => 'Favicon (ICO/PNG 32x32, max 1MB)', 'og_image' => 'OG Image (1200x630, max 3MB)'] as $field => $label)
                                <div class="mb-4">
                                    <label class="form-label">{{ $label }}</label>
                                    @if($identity->$field)
                                        <div class="mb-2 p-2 border rounded d-flex align-items-center gap-2 bg-light">
                                            <img src="{{ asset('storage/'.$identity->$field) }}" alt="{{ $field }}" style="height:40px; width:auto; object-fit:contain; background:#fff; border-radius:6px; border:1px solid #eee; padding:4px;">
                                            <small class="text-muted text-truncate" style="max-width:160px;">{{ basename($identity->$field) }}</small>
                                            <label class="ms-auto small text-danger mb-0" style="cursor:pointer;">
                                                <input type="checkbox" name="remove_{{ $field }}" value="1" class="form-check-input me-1"> Hapus
                                            </label>
                                        </div>
                                    @endif
                                    <input type="file" name="{{ $field }}" class="form-control" accept="image/*">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Identitas</button>
                                    <a href="{{ route('dashboard.index') }}" class="btn btn-light">Batal</a>
                                </div>
                                <hr>
                                <div class="small text-muted">
                                    <div class="d-flex justify-content-between"><span>UUID</span><span class="text-truncate ms-2" style="max-width:160px;">{{ $identity->uuid }}</span></div>
                                    <div class="d-flex justify-content-between"><span>Diperbarui</span><span>{{ $identity->updated_at ? $identity->updated_at->format('d/m/Y H:i') : '-' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="identity-tip mt-4">
                            <div class="identity-tip__glow"></div>
                            <div class="identity-tip__body">
                                <div class="identity-tip__head">
                                    <span class="identity-tip__icon"><i class="bi bi-stars"></i></span>
                                    <div>
                                        <h6 class="mb-0">Informasi Penting</h6>
                                        <small>Optimalkan identitas website</small>
                                    </div>
                                </div>
                                <ul class="identity-tip__list">
                                    <li>
                                        <span class="tip-bullet"><i class="bi bi-palette"></i></span>
                                        <div><strong>Logo & Favicon</strong><span>Akan tampil di header & frontend. Pakai PNG transparan 512×512, max 2MB.</span></div>
                                    </li>
                                    <li>
                                        <span class="tip-bullet"><i class="bi bi-share"></i></span>
                                        <div><strong>OG Image</strong><span>Pratinjau saat share ke WhatsApp / Facebook. Ideal 1200×630, max 3MB.</span></div>
                                    </li>
                                    <li>
                                        <span class="tip-bullet"><i class="bi bi-graph-up-arrow"></i></span>
                                        <div><strong>GA4 ID</strong><span>Format <code>G-XXXXXXXXXX</code> — cek di Google Analytics &gt; Admin &gt; Data Streams.</span></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pane 2: Hero Section (bisa diupdate, tampil di beranda) --}}
            <div class="tab-pane fade" id="tab-hero" role="tabpanel" aria-labelledby="tab-hero-btn">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center gap-2">
                                <i class="bi bi-type-h1 text-primary"></i>
                                <h5 class="mb-0">Konten Utama</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Badge</label>
                                        <input type="text" name="hero_badge" class="form-control" value="{{ old('hero_badge', $identity->hero_badge) }}" placeholder="PORPROV XV • Jawa Barat 2026">
                                        <small class="text-muted">Kosongkan untuk pakai bawaan.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Judul Baris 1</label>
                                        <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $identity->hero_title) }}" placeholder="Atlet Bekasi">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Judul Baris 2 (gradien)</label>
                                        <input type="text" name="hero_title_highlight" class="form-control" value="{{ old('hero_title_highlight', $identity->hero_title_highlight) }}" placeholder="Menuju Podium">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi / Lead</label>
                                        <textarea name="hero_lead" rows="3" class="form-control" placeholder="Dukung kontingen Kota Bekasi di Pekan Olahraga Provinsi Jawa Barat — jadwal, venue, live score, dan kabar atlet dalam satu portal.">{{ old('hero_lead', $identity->hero_lead) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center gap-2">
                                <i class="bi bi-cursor text-primary"></i>
                                <h5 class="mb-0">CTA & Hitung Mundur</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Label Tombol CTA</label>
                                        <input type="text" name="hero_cta_label" class="form-control" value="{{ old('hero_cta_label', $identity->hero_cta_label) }}" placeholder="Lihat Jadwal & Venue">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">URL Tombol CTA</label>
                                        <input type="text" name="hero_cta_url" class="form-control" value="{{ old('hero_cta_url', $identity->hero_cta_url) }}" placeholder="https://porprovbekasikota2026.id/jadwal">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Label Countdown</label>
                                        <input type="text" name="hero_countdown_label" class="form-control" value="{{ old('hero_countdown_label', $identity->hero_countdown_label) }}" placeholder="Hitung Mundur • Opening Ceremony 7 November 2026">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Target Countdown</label>
                                        <input type="datetime-local" name="hero_countdown_target" class="form-control" value="{{ old('hero_countdown_target', $identity->hero_countdown_target ? $identity->hero_countdown_target->format('Y-m-d\TH:i') : '') }}">
                                        <small class="text-muted">Cth: 07/11/2026 19:00. Angka Hari/Jam/Menit/Detik dihitung otomatis.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-image me-1 text-primary"></i> Gambar Background Hero</h5>
                            </div>
                            <div class="card-body">
                                @if($identity->hero_background)
                                    <div class="mb-2 p-2 border rounded d-flex align-items-center gap-2 bg-light">
                                        <img src="{{ asset('storage/'.$identity->hero_background) }}" alt="hero_background" style="height:40px; width:auto; object-fit:contain; background:#fff; border-radius:6px; border:1px solid #eee; padding:4px;">
                                        <small class="text-muted text-truncate" style="max-width:160px;">{{ basename($identity->hero_background) }}</small>
                                        <label class="ms-auto small text-danger mb-0" style="cursor:pointer;">
                                            <input type="checkbox" name="remove_hero_background" value="1" class="form-check-input me-1"> Hapus
                                        </label>
                                    </div>
                                @endif
                                <input type="file" name="hero_background" class="form-control" accept="image/*">
                                <small class="text-muted">Ideal landscape 1920×1080, max 3MB. Kosongkan untuk pakai bawaan.</small>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Hero Section</button>
                                </div>
                                <hr>
                                <div class="small text-muted">Semua field opsional — yang kosong otomatis pakai teks bawaan di beranda. Kolom kanan hero dikosongkan untuk slider agenda featured.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@push('after-script')
<script>
    (function () {
        // Kalau validasi gagal di field hero_*, langsung buka tab Hero Section
        @php($heroError = $errors->any() && collect(['hero_badge','hero_title','hero_title_highlight','hero_lead','hero_cta_label','hero_cta_url','hero_countdown_label','hero_countdown_target'])->contains(fn($f) => $errors->has($f)))
        @if($heroError)
            var heroBtn = document.getElementById('tab-hero-btn');
            if (heroBtn && window.bootstrap) {
                var tab = new bootstrap.Tab(heroBtn);
                tab.show();
            }
        @endif
    })();
</script>
@endpush
