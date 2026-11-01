<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo $__env->yieldContent('title'); ?></title>

    <link rel="shortcut icon" href="<?php echo e(asset('img/fav.png')); ?>" type="image/png">
    <link rel="alternate icon" href="<?php echo e(asset('img/favicon.ico')); ?>" type="image/x-icon">

    <!-- Style -->
    <?php echo $__env->yieldPushContent('before-style'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(asset('dist/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')); ?>">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="<?php echo e(asset('dist/assets/compiled/css/table-datatable-jquery.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/assets/compiled/css/app.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('dist/assets/compiled/css/app-dark.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('dist/assets/compiled/css/iconly.css')); ?>" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-modern.css')); ?>?v=<?php echo e(\Illuminate\Support\Facades\File::exists(public_path('css/admin-modern.css')) ? filemtime(public_path('css/admin-modern.css')) : '1'); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/btn-soft.css')); ?>?v=<?php echo e(\Illuminate\Support\Facades\File::exists(public_path('css/btn-soft.css')) ? filemtime(public_path('css/btn-soft.css')) : '1'); ?>" />

    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet" />



    <?php echo $__env->yieldPushContent('after-style'); ?>
    <!-- /Style -->

</head>

<body>
    <div class="min-height-300 position-absolute w-100" style="background: linear-gradient(90deg, #0a4d8e 0%, #139a8d 100%); top: 0; left: 0;"></div>
    <div class="app-loader" id="app-loader">
        <img src="<?php echo e(asset('img/fav.png')); ?>" alt="Pemerintah Kota Bekasi" class="app-loader__logo app-loader__logo--light">
        <img src="<?php echo e(asset('img/fav.png')); ?>" alt="Pemerintah Kota Bekasi" class="app-loader__logo app-loader__logo--dark">
        <div class="app-loader__spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="app-loader__text">Memuat halaman&hellip;</span>
    </div>
    <div id="app">
        <?php if (isset($component)) { $__componentOriginaldadc8f6aa159c05f9151bf94d661e8e3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldadc8f6aa159c05f9151bf94d661e8e3 = $attributes; } ?>
<?php $component = App\View\Components\Menu::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Menu::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldadc8f6aa159c05f9151bf94d661e8e3)): ?>
<?php $attributes = $__attributesOriginaldadc8f6aa159c05f9151bf94d661e8e3; ?>
<?php unset($__attributesOriginaldadc8f6aa159c05f9151bf94d661e8e3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldadc8f6aa159c05f9151bf94d661e8e3)): ?>
<?php $component = $__componentOriginaldadc8f6aa159c05f9151bf94d661e8e3; ?>
<?php unset($__componentOriginaldadc8f6aa159c05f9151bf94d661e8e3); ?>
<?php endif; ?>

        <div id="main" class='layout-navbar navbar-fixed'>
            <?php if (isset($component)) { $__componentOriginal3c0808d0ccb9dfd11d29783234199f91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c0808d0ccb9dfd11d29783234199f91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.web.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('web.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c0808d0ccb9dfd11d29783234199f91)): ?>
<?php $attributes = $__attributesOriginal3c0808d0ccb9dfd11d29783234199f91; ?>
<?php unset($__attributesOriginal3c0808d0ccb9dfd11d29783234199f91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c0808d0ccb9dfd11d29783234199f91)): ?>
<?php $component = $__componentOriginal3c0808d0ccb9dfd11d29783234199f91; ?>
<?php unset($__componentOriginal3c0808d0ccb9dfd11d29783234199f91); ?>
<?php endif; ?>

            <div id="main-content">
                <?php if (isset($component)) { $__componentOriginalb24df6adf99a77ed35057e476f61e153 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb24df6adf99a77ed35057e476f61e153 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.validation-errors','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb24df6adf99a77ed35057e476f61e153)): ?>
<?php $attributes = $__attributesOriginalb24df6adf99a77ed35057e476f61e153; ?>
<?php unset($__attributesOriginalb24df6adf99a77ed35057e476f61e153); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb24df6adf99a77ed35057e476f61e153)): ?>
<?php $component = $__componentOriginalb24df6adf99a77ed35057e476f61e153; ?>
<?php unset($__componentOriginalb24df6adf99a77ed35057e476f61e153); ?>
<?php endif; ?>
                <div class="page-heading">
                    <div class="page-title">
                        <?php echo $__env->yieldContent('breadcrumb'); ?>
                    </div>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            <!-- Footer -->
            <footer>
                <div class="footer clearfix mb-0">
                    <div class="argon-footer d-flex flex-wrap justify-content-center align-items-center gap-2 text-center">
                        <span>&copy; <script>
                                document.write(new Date().getFullYear())
                            </script> Pemerintah Kota Bekasi</span>
                        <span class="argon-footer-dot" aria-hidden="true"></span>
                        <span>All rights reserved</span>
                    </div>
                </div>
            </footer>
            <!--/Footer -->

        </div>
    </div>

    <?php echo $__env->make('components.global-search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Script -->
    <?php echo $__env->yieldPushContent('before-script'); ?>
    <script src="<?php echo e(asset('dist/assets/static/js/components/dark.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/compiled/js/app.js')); ?>"></script>

    <script src="<?php echo e(asset('dist/assets/extensions/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/extensions/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('dist/assets/static/js/pages/datatables.js')); ?>"></script>

    <script src="<?php echo e(asset('dist/assets/extensions/sweetalert2/sweetalert2.min.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('after-script'); ?>
    <!-- /Script -->

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize summernote on textareas with class .summernote
            function initSummernote($container) {
                $container.find('.summernote').each(function() {
                    if (!$(this).hasClass('summernote-initialized')) {
                        $(this).addClass('summernote-initialized').summernote({
                            height: 200,
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture', 'video']],
                                ['view', ['fullscreen', 'codeview', 'help']]
                            ]
                        });
                    }
                });
            }

            // Initialize on page load
            initSummernote($(document));

            // Initialize when modal is shown (for modals with summernote)
            $(document).on('shown.bs.modal', '.modal', function() {
                initSummernote($(this));
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 generik untuk dropdown multi-pilih.
            // Halaman yang memakai select2 menginisialisasinya sendiri via <?php $__env->startPush('after-script'); ?>.
            $('select[data-select2]').select2({
                width: '100%',
                closeOnSelect: false
            });
        });
    </script>

    <script>
        (function() {
            var loader = document.getElementById('app-loader');

            function hideLoader() {
                if (!loader || loader.classList.contains('is-hidden')) return;
                loader.classList.add('is-hidden');

                window.setTimeout(function() {
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 400);
            }

            if (document.readyState === 'complete') {
                hideLoader();
            } else {
                window.addEventListener('load', hideLoader);
            }

            /* Jaring pengaman: jangan biarkan loader menutup halaman selamanya. */
            window.setTimeout(hideLoader, 8000);
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <script>
        $(document).on("click", ".mark-as-read", function(e) {
            e.preventDefault();

            let id = $(this).data("id");
            let $item = $(this).closest(".notification-item");
            let target = $(this).attr("href");

            $.ajax({
                url: "<?php echo e(url('/notifications')); ?>/" + id + "/read",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(res) {
                    if (res.success) {
                        // hapus highlight bg-light
                        $item.removeClass("bg-light");

                        // update badge count
                        let count = parseInt($("#notif-count").text()) - 1;
                        if (count > 0) {
                            $("#notif-count").text(count);
                        } else {
                            $("#notif-count").remove();
                        }

                        // lanjut ke halaman tujuan bila ada
                        if (target && !target.startsWith("javascript")) {
                            window.location.href = target;
                        }
                    }
                }
            });
        });

        $(document).on("click", ".mark-all-read", function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo e(url('/notifications/read-all')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(res) {
                    if (res.success) {
                        location.reload();
                    }
                }
            });
        });
    </script>

    <?php if(auth()->guard()->check()): ?>
    <script>
        (function() {
            // Konfigurasi: ambil dari Laravel config (30 menit), fallback 30 jika Null
            const INACTIVE_TIMEOUT_MIN = <?php echo e((int) config('session.inactive_timeout', 30)); ?>;
            const WARNING_BEFORE_MIN = 2; // tampil warning 2 menit sebelum logout
            const INACTIVE_MS = INACTIVE_TIMEOUT_MIN * 60 * 1000;
            const WARNING_MS = WARNING_BEFORE_MIN * 60 * 1000;
            const TIMEOUT_MS = INACTIVE_MS - WARNING_MS;
            // Jika TIMEOUT_MS <=0 (misal timeout 1 menit), set langsung 5000ms
            const idleLimit = TIMEOUT_MS > 5000 ? TIMEOUT_MS : 5000;

            let idleTimer = null;
            let warningShown = false;
            let countdownInterval = null;

            function getCsrf() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            function doLogout(reason) {
                const token = getCsrf();
                // buat form POST ke route logout
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "<?php echo e(route('logout')); ?>";
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = token;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }

            function keepAlive() {
                const token = getCsrf();
                return fetch("<?php echo e(route('keep-alive')); ?>", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                }).catch(() => {});
            }

            function showWarning() {
                if (warningShown) return;
                warningShown = true;
                let remaining = WARNING_BEFORE_MIN * 60; // detik

                const updateText = () => {
                    const m = Math.floor(remaining / 60);
                    const s = String(remaining % 60).padStart(2, '0');
                    const html = `Sesi akan berakhir dalam <b>${m}:${s}</b> karena tidak ada aktivitas.<br>Klik <b>Tetap Login</b> untuk melanjutkan.`;
                    const container = document.querySelector('.swal2-html-container');
                    if (container) container.innerHTML = html;
                };

                // pakai SweetAlert2 jika ada, fallback confirm
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Sesi hampir berakhir',
                        html: `Sesi akan berakhir dalam <b>${WARNING_BEFORE_MIN}:00</b> karena tidak ada aktivitas.<br>Klik <b>Tetap Login</b> untuk melanjutkan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Tetap Login',
                        cancelButtonText: 'Logout',
                        confirmButtonColor: '#273049',
                        cancelButtonColor: '#d33',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            countdownInterval = setInterval(() => {
                                remaining--;
                                if (remaining <= 0) {
                                    clearInterval(countdownInterval);
                                    Swal.close();
                                    doLogout();
                                } else {
                                    updateText();
                                }
                            }, 1000);
                        },
                        willClose: () => {
                            if (countdownInterval) clearInterval(countdownInterval);
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            warningShown = false;
                            keepAlive().then(() => resetTimer());
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            doLogout();
                        } else {
                            // timeout Swal tertutup -> logout
                            if (remaining <= 0) doLogout();
                            else {
                                warningShown = false;
                                resetTimer();
                            }
                        }
                    });
                } else {
                    // fallback tanpa Swal
                    const ok = confirm(`Sesi akan berakhir dalam ${WARNING_BEFORE_MIN} menit karena tidak aktif. Klik OK untuk tetap login.`);
                    if (ok) {
                        warningShown = false;
                        keepAlive().then(() => resetTimer());
                    } else {
                        doLogout();
                    }
                }
            }

            function resetTimer() {
                if (idleTimer) clearTimeout(idleTimer);
                if (countdownInterval) clearInterval(countdownInterval);
                warningShown = false;
                idleTimer = setTimeout(showWarning, idleLimit);
            }

            // throttled reset agar tidak spam (max 1x per detik)
            let throttle = false;
            function onActivity() {
                if (warningShown) return; // jangan reset saat warning tampil
                if (throttle) return;
                throttle = true;
                setTimeout(() => throttle = false, 1000);
                resetTimer();
            }

            ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(evt => {
                document.addEventListener(evt, onActivity, { passive: true });
            });

            // start
            resetTimer();

            // optional: deteksi tab hidden -> tetap hitung, tidak pause
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible' && warningShown === false) {
                    // cek ke server apakah session masih hidup via keep-alive ping ringan?
                    // tidak perlu, middleware server akan logout saat request berikutnya
                }
            });
        })();
    </script>
    <?php endif; ?>



</body>

</html>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\layouts\app.blade.php ENDPATH**/ ?>