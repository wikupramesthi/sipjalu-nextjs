@extends('layouts.auth')
@section('title', '404 — Halaman Tidak Ditemukan')
@section('content')

<style>
    .e404-wrap { min-height: 78vh; display: flex; align-items: center; justify-content: center; padding: 32px 16px; position: relative; overflow: hidden; }
    .e404-shape { position: absolute; border-radius: 50%; filter: blur(70px); opacity: .45; pointer-events: none; }
    .e404-shape-a { width: 340px; height: 340px; background: #dbeafe; top: -90px; left: -90px; }
    .e404-shape-b { width: 300px; height: 300px; background: #dcfce7; bottom: -100px; right: -70px; }
    .e404-shape-c { width: 180px; height: 180px; background: #fee2e2; bottom: 12%; left: 8%; }
    .e404-card { position: relative; text-align: center; max-width: 560px; width: 100%; background: #fff; border: 1px solid #e6ebf2; border-radius: 24px; padding: 44px 36px 36px; box-shadow: 0 24px 60px rgba(15, 40, 80, .10); }
    .e404-logo img { height: 54px; width: auto; object-fit: contain; }
    .e404-illus { width: 190px; height: auto; margin: 6px auto 2px; display: block; animation: e404-float 5s ease-in-out infinite; }
    @keyframes e404-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .e404-code { font-size: 76px; font-weight: 800; letter-spacing: 2px; line-height: 1; background: linear-gradient(135deg, #0a4d8e, #139a8d); -webkit-background-clip: text; background-clip: text; color: transparent; }
    .e404-title { font-size: 1.35rem; font-weight: 700; color: #16233a; margin: 10px 0 6px; }
    .e404-copy { color: #64748b; font-size: .92rem; margin-bottom: 6px; }
    .e404-path { display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; background: #f1f5f9; border: 1px dashed #cbd5e1; color: #475569; font-size: .78rem; border-radius: 8px; padding: 5px 12px; margin: 8px 0 2px; }
    .e404-cta { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 20px; }
    .e404-btn { display: inline-flex; align-items: center; gap: 8px; border-radius: 12px; padding: 10px 22px; font-weight: 600; font-size: .88rem; text-decoration: none; border: 1px solid transparent; transition: all .15s ease; cursor: pointer; }
    .e404-btn-solid { background: #60a5fa; border-color: #60a5fa; color: #fff; box-shadow: 0 6px 16px rgba(96, 165, 250, .35); }
    .e404-btn-solid:hover { background: #3b82f6; border-color: #3b82f6; color: #fff; }
    .e404-btn-soft { background: #f1f5f9; border-color: #e2e8f0; color: #334155; }
    .e404-btn-soft:hover { background: #e2e8f0; color: #0f172a; }
    .e404-foot { margin-top: 24px; color: #94a3b8; font-size: .75rem; }
    .e404-foot a { color: #64748b; text-decoration: none; }
</style>

<div class="e404-wrap">
    <div class="e404-shape e404-shape-a" aria-hidden="true"></div>
    <div class="e404-shape e404-shape-b" aria-hidden="true"></div>
    <div class="e404-shape e404-shape-c" aria-hidden="true"></div>

    <div class="e404-card">
        <a href="{{ auth()->check() ? route('dashboard.index') : url('/') }}" class="e404-logo" aria-label="Beranda Pemerintah Kota Bekasi">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Pemerintah Kota Bekasi" decoding="async">
        </a>

        <svg class="e404-illus" viewBox="0 0 200 150" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <ellipse cx="100" cy="136" rx="62" ry="8" fill="#e6ebf2"/>
            <rect x="52" y="18" width="96" height="112" rx="10" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="3"/>
            <rect x="64" y="34" width="56" height="9" rx="4.5" fill="#bfdbfe"/>
            <rect x="64" y="50" width="72" height="7" rx="3.5" fill="#e2e8f0"/>
            <rect x="64" y="62" width="72" height="7" rx="3.5" fill="#e2e8f0"/>
            <rect x="64" y="74" width="48" height="7" rx="3.5" fill="#e2e8f0"/>
            <rect x="64" y="92" width="34" height="16" rx="8" fill="#dcfce7" stroke="#86efac" stroke-width="2"/>
            <circle cx="138" cy="102" r="26" fill="#dbeafe" stroke="#60a5fa" stroke-width="5"/>
            <line x1="157" y1="121" x2="172" y2="136" stroke="#60a5fa" stroke-width="9" stroke-linecap="round"/>
            <text x="100" y="112" text-anchor="middle" font-size="30" font-weight="800" fill="#3b82f6" font-family="sans-serif">?</text>
        </svg>

        <div class="e404-code">404</div>
        <h1 class="e404-title">Ups, halaman tidak ditemukan.</h1>
        <p class="e404-copy">Alamat yang Anda tuju sudah dipindah, dihapus, atau tidak pernah ada. Periksa kembali tautannya.</p>
        <div><span class="e404-path">{{ '/' . ltrim(request()->path(), '/') }}</span></div>

        <div class="e404-cta">
            <button type="button" onclick="history.back()" class="e404-btn e404-btn-soft">
                <i class="ti ti-arrow-left"></i><span>Kembali</span>
            </button>
            @auth
                <a href="{{ route('dashboard.index') }}" class="e404-btn e404-btn-solid">
                    <i class="ti ti-layout-dashboard"></i><span>Ke Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="e404-btn e404-btn-solid">
                    <i class="ti ti-login-2"></i><span>Ke Halaman Masuk</span>
                </a>
            @endauth
        </div>

        <div class="e404-foot">&copy; {{ date('Y') }} <a href="#">Pemerintah Kota Bekasi</a>. All Rights Reserved</div>
    </div>
</div>

@endsection
