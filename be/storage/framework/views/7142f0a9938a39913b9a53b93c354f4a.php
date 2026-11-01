<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-locale="<?php echo e(app()->getLocale()); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?></title>
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
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="Pemerintah Kota Bekasi">
    <meta property="og:description"
        content="Website resmi Pemerintah Kota Bekasi yang menyediakan informasi pembangunan dan pemeliharaan jalan, jembatan, drainase, serta pengelolaan sumber daya air.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Pemerintah Kota Bekasi">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="<?php echo e(asset('img/seamless-pattern3.png')); ?>">
    <meta property="og:image:alt" content="Pemerintah Kota Bekasi">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Pemerintah Kota Bekasi">
    <meta name="twitter:description"
        content="Portal resmi Agenda Pemerintah Kota Bekasi yang menyediakan informasi agenda, jadwal, kegiatan, dan aktivitas Pemerintah Kota Bekasi secara mudah dan terintegrasi.">
    <meta name="twitter:image" content="<?php echo e(asset('img/seamless-pattern3.png')); ?>">

    <!-- Theme & Mobile -->
    <meta name="theme-color" content="#0d6efd">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Pemerintah Kota Bekasi">


    <link rel="shortcut icon" href="<?php echo e(asset('img/fav.png')); ?>" type="image/png">
    <link rel="alternate icon" href="<?php echo e(asset('img/favicon.ico')); ?>" type="image/x-icon">
    <link rel="stylesheet"
        href="/dist/assets/compiled/css/auth.css?v=<?php echo e(file_exists(public_path('dist/assets/compiled/css/auth.css')) ? filemtime(public_path('dist/assets/compiled/css/auth.css')) : '1'); ?>">
    <link rel="stylesheet"
        href="/css/btn-soft.css?v=<?php echo e(file_exists(public_path('css/btn-soft.css')) ? filemtime(public_path('css/btn-soft.css')) : '1'); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>

    <meta name="theme-color" content="#273049">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.46.0/dist/tabler-icons.min.css" />

</head>

<body class="<?php echo $__env->yieldContent('body-class'); ?>">

    <div class="authentication-wrapper authentication-cover auth-2026 <?php echo $__env->yieldContent('auth-page-class'); ?>">
        <?php echo $__env->yieldContent('content'); ?>

    </div>


    <script>
        (function() {
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
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views/layouts/auth.blade.php ENDPATH**/ ?>