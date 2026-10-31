<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-locale="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="title" content="Agenda Pemerintah Kota Bekasi">
    <meta name="description"
        content="Portal resmi Agenda Pemerintah Kota Bekasi yang menyediakan informasi agenda, jadwal, kegiatan, dan aktivitas Pemerintah Kota Bekasi secara mudah dan terintegrasi.">
    <meta name="keywords"
        content="Agenda Pemerintah Kota Bekasi, agenda Pemkot Bekasi, jadwal kegiatan Bekasi, kegiatan Pemerintah Kota Bekasi, agenda pemerintahan Bekasi, kalender kegiatan Bekasi, agenda Wali Kota Bekasi">
    <meta name="author" content="Diskominfostandi">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="revisit-after" content="7 days">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="Pemerintah Kota Bekasi">
    <meta property="og:description"
        content="Website resmi Pemerintah Kota Bekasi yang menyediakan informasi pembangunan dan pemeliharaan jalan, jembatan, drainase, serta pengelolaan sumber daya air.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Pemerintah Kota Bekasi">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="{{ asset('img/seamless-pattern3.png') }}">
    <meta property="og:image:alt" content="Pemerintah Kota Bekasi">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Pemerintah Kota Bekasi">
    <meta name="twitter:description"
        content="Portal resmi Agenda Pemerintah Kota Bekasi yang menyediakan informasi agenda, jadwal, kegiatan, dan aktivitas Pemerintah Kota Bekasi secara mudah dan terintegrasi.">
    <meta name="twitter:image" content="{{ asset('img/seamless-pattern3.png') }}">

    <!-- Theme & Mobile -->
    <meta name="theme-color" content="#0d6efd">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Pemerintah Kota Bekasi">


    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}" type="image/png">
    <link rel="alternate icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet"
        href="/dist/assets/compiled/css/auth.css?v={{ file_exists(public_path('dist/assets/compiled/css/auth.css')) ? filemtime(public_path('dist/assets/compiled/css/auth.css')) : '1' }}">
    <link rel="stylesheet"
        href="/css/btn-soft.css?v={{ file_exists(public_path('css/btn-soft.css')) ? filemtime(public_path('css/btn-soft.css')) : '1' }}">

    <meta name="theme-color" content="#273049">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.46.0/dist/tabler-icons.min.css" />

</head>

<body>

    <div class="authentication-wrapper authentication-cover auth-2026">
        <!-- Logo (mobile) -->
        <a href="#" class="auth-cover-brand auth-cover-brand-top auth-logo d-lg-none">
            <img src="{{ asset('img/logo.png') }}" class="auth-brand-logo-wrap" alt="Logo Pemerintah Kota Bekasi"
                decoding="async">
        </a>

        @yield('content')

    </div>


    <!-- FAB Bantuan -->
    <button type="button" class="ui-help-fab" id="helpFab" aria-label="Bantuan" aria-expanded="false"
        aria-controls="helpPopup">
        <i class="ti ti-lifebuoy" aria-hidden="true"></i>
    </button>
    <div class="ui-help-backdrop" id="helpBackdrop" hidden></div>

    <div class="ui-help-popup" id="helpPopup" role="dialog" aria-modal="false" aria-label="Bantuan login" hidden>

        <div class="ui-help-head">
            <span class="ui-help-head-title">
                <span>
                    <i class="ti ti-message-circle-help" aria-hidden="true"></i>
                    {{ __('login.help_title') }}
                </span>
                <small>{{ __('login.help_subtitle') }}</small>
            </span>

            <button type="button" class="ui-help-close" id="helpClose" aria-label="Tutup">
                <i class="ti ti-x" aria-hidden="true"></i>
            </button>
        </div>

        <div class="ui-help-body">

            <div class="ui-help-item">
                <span class="ui-help-icon">
                    <i class="ti ti-login" aria-hidden="true"></i>
                </span>
                <div>
                    <strong>{{ __('login.help_how_to') }}</strong>
                    <p>
                        {!! __('login.help_how_to_desc') !!}
                    </p>
                </div>
            </div>

            <div class="ui-help-item">
                <span class="ui-help-icon">
                    <i class="ti ti-key" aria-hidden="true"></i>
                </span>
                <div>
                    <strong>{{ __('login.help_forgot_title') }}</strong>
                    <p>
                        {!! __('login.help_forgot_desc') !!}
                    </p>
                </div>
            </div>

            <div class="ui-help-item">
                <span class="ui-help-icon">
                    <i class="ti ti-headset" aria-hidden="true"></i>
                </span>
                <div>
                    <strong>{{ __('login.help_trouble') }}</strong>
                    <p>
                        {{ __('login.help_trouble_desc') }}
                    </p>
                </div>
            </div>

            <div class="ui-help-foot">
                <i class="ti ti-info-circle" aria-hidden="true"></i>
                <span>
                    {{ __('login.help_footer') }}
                </span>
            </div>

        </div>
    </div>

    <script>
        (function() {
            // Popup bantuan
            var helpFab = document.getElementById('helpFab');
            var helpPopup = document.getElementById('helpPopup');
            var helpBackdrop = document.getElementById('helpBackdrop');
            var helpClose = document.getElementById('helpClose');

            function openHelp() {
                if (!helpPopup) return;
                helpPopup.hidden = false;
                if (helpBackdrop) helpBackdrop.hidden = false;
                requestAnimationFrame(function() {
                    helpPopup.classList.add('is-open');
                    if (helpBackdrop) helpBackdrop.classList.add('is-open');
                    if (helpFab) helpFab.setAttribute('aria-expanded', 'true');
                });
            }

            function closeHelp() {
                if (!helpPopup) return;
                helpPopup.classList.remove('is-open');
                if (helpBackdrop) helpBackdrop.classList.remove('is-open');
                if (helpFab) helpFab.setAttribute('aria-expanded', 'false');
                setTimeout(function() {
                    helpPopup.hidden = true;
                    if (helpBackdrop) helpBackdrop.hidden = true;
                }, 220);
            }

            if (helpFab) {
                helpFab.addEventListener('click', function() {
                    var open = !helpPopup.hidden && helpPopup.classList.contains('is-open');
                    open ? closeHelp() : openHelp();
                });
            }
            if (helpClose) helpClose.addEventListener('click', closeHelp);
            if (helpBackdrop) helpBackdrop.addEventListener('click', closeHelp);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeHelp();
            });

            var toggle = document.getElementById('togglePassword');
            var pass = document.getElementById('password');
            if (toggle && pass) {
                var eye = toggle.querySelector('i');
                toggle.addEventListener('click', function() {
                    var show = pass.type === 'password';
                    pass.type = show ? 'text' : 'password';
                    eye.className = show ? 'ti ti-eye' : 'ti ti-eye-closed';
                });
            }

            // Toggle reset password
            var linkForgot = document.getElementById('forgot');
            var secMasuk = document.getElementById('sec_masuk');
            var secReset = document.getElementById('sec_resetpass');
            var linkBack = document.getElementById('ahaa');

            var showCallback = function() {
                if (window.matchMedia && window.matchMedia('(max-width: 991.98px)').matches) {
                    var hero = document.getElementById('mobileHero');
                    if (hero) hero.classList.remove('d-none');
                }
            };

            if (linkForgot && secReset) {
                linkForgot.addEventListener('click', function() {
                    secMasuk.style.display = 'none';
                    secReset.style.display = 'block';
                });
            }
            if (linkBack && secReset) {
                linkBack.addEventListener('click', function() {
                    secReset.style.display = 'none';
                    secMasuk.style.display = 'block';
                });
            }

            // Kirim tombol login (contoh)
            var form = document.getElementById('formAuthentication');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = document.getElementById('btnLogin');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="ti ti-loader ti-spin"></i><span>Memeriksa...</span>';
                    setTimeout(function() {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ti ti-login-2"></i><span>Masuk Sekarang</span>';
                        alert('Contoh halaman login — silakan hubungkan ke backend Anda.');
                    }, 900);
                });
            }
        })();
    </script>

</body>

</html>
