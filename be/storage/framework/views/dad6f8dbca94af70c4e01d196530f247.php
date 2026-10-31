<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('content'); ?>

<style>
    .login-lang-switch {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px;
        border-radius: 999px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
    }

    .login-lang-switch-wrap--brand {
        position: absolute;
        top: 18px;
        right: 24px;
        z-index: 10;
    }

    .login-lang-switch a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
        color: #64748b;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .login-lang-switch a.active {
        background: #273049;
        color: #fff;
        box-shadow: 0 2px 8px rgba(39, 48, 73, 0.25);
    }

    .login-lang-switch a:not(.active):hover {
        background: #fff;
        color: #273049;
    }

    @media (max-width: 991.98px) {
        .login-lang-switch-wrap--brand {
            /* Brand panel hidden on mobile, pindah toggle ke dalam form panel via JS? 
           fallback: tampilkan di atas form juga — sembunyikan yang brand dan tampilkan yang form */
            display: none;
        }
    }

    .login-lang-switch-wrap--form {
        display: none !important;
    }
</style>

<div class="authentication-inner">
    <!-- Brand Panel -->
    <div class="auth-brand-panel">
        <div class="auth-brand-shape auth-brand-shape-1" aria-hidden="true"></div>
        <div class="auth-brand-shape auth-brand-shape-2" aria-hidden="true"></div>
        <div class="auth-brand-shape auth-brand-shape-3" aria-hidden="true"></div>

        <!-- Toggle bahasa di atas logo kiri (desktop) -->
        <div class="login-lang-switch-wrap--brand">
            <div class="login-lang-switch" role="group" aria-label="<?php echo e(__('login.lang_switch')); ?>">
                <a href="<?php echo e(route('lang.switch', 'id')); ?>" class="<?php echo e(app()->getLocale() === 'id' ? 'active' : ''); ?>" title="Bahasa Indonesia"><?php echo e(__('login.lang_id')); ?></a>
                <a href="<?php echo e(route('lang.switch', 'en')); ?>" class="<?php echo e(app()->getLocale() === 'en' ? 'active' : ''); ?>" title="English"><?php echo e(__('login.lang_en')); ?></a>
            </div>
        </div>

        <img src="<?php echo e(asset('img/auth-login.png')); ?>" class="auth-brand-illust" alt="Ilustrasi Halaman Login" decoding="async" aria-hidden="true">

        <div class="auth-brand-content">
            <a href="#" class="auth-brand-mark" aria-label="Beranda Pemerintah Kota Bekasi">
                <img src="<?php echo e(asset('img/logo.png')); ?>" class="auth-brand-logo-wrap" alt="Logo Pemerintah Kota Bekasi" decoding="async">
            </a>

            <h1 class="auth-brand-headline">
                <span class="auth-brand-headline-accent"><?php echo e(__('login.brand_welcome')); ?></span> <span class="auth-brand-headline-accent auth-brand-headline-accent--delay"><?php echo e(__('login.brand_portal')); ?></span>
            </h1>

            <p class="auth-brand-tagline">
                <?php echo e(__('login.brand_tagline')); ?>

            </p>

            <ul class="auth-brand-points list-unstyled">
                <li>
                    <i class="ti ti-building"></i>
                    <span><?php echo e(__('login.brand_point_1')); ?></span>
                </li>
                <li>
                    <i class="ti ti-database"></i>
                    <span><?php echo e(__('login.brand_point_2')); ?></span>
                </li>
                <li>
                    <i class="ti ti-apps"></i>
                    <span><?php echo e(__('login.brand_point_3')); ?></span>
                </li>
            </ul>

        </div>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel d-flex col-12 col-lg-5 col-xl-4 align-items-center">
        <span class="auth-form-shape auth-form-shape-1 d-lg-none" aria-hidden="true"></span>
        <span class="auth-form-shape auth-form-shape-2 d-lg-none" aria-hidden="true"></span>

        <div class="auth-form-inner" style="position:relative;">
            <!-- Toggle khusus mobile (brand hidden) — di atas logo/form -->
            <div class="login-lang-switch-wrap--form">
                <div class="login-lang-switch" role="group" aria-label="<?php echo e(__('login.lang_switch')); ?>">
                    <a href="<?php echo e(route('lang.switch', 'id')); ?>" class="<?php echo e(app()->getLocale() === 'id' ? 'active' : ''); ?>" title="Bahasa Indonesia">🇮🇩 <?php echo e(__('login.lang_id')); ?></a>
                    <a href="<?php echo e(route('lang.switch', 'en')); ?>" class="<?php echo e(app()->getLocale() === 'en' ? 'active' : ''); ?>" title="English">🇬🇧 <?php echo e(__('login.lang_en')); ?></a>
                </div>
            </div>

            <!-- Hero mobile -->
            <div class="auth-mobile-hero d-none" id="mobileHero" aria-hidden="true">
                <svg viewBox="0 0 640 300" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="320" cy="150" r="70" fill="#273049" opacity="0.15" />
                    <rect x="352" y="92" width="150" height="104" rx="16" fill="#ffffff"
                        stroke="#273049" stroke-opacity="0.18" />
                    <rect x="368" y="108" width="60" height="9" rx="4.5" fill="#273049"
                        opacity="0.5" />
                    <rect x="368" y="126" width="95" height="7" rx="3.5" fill="#273049"
                        opacity="0.18" />
                    <rect x="368" y="142" width="78" height="7" rx="3.5" fill="#FCAC0A"
                        opacity="0.28" />
                    <circle cx="200" cy="140" r="42" fill="#273049" opacity="0.8" />
                    <circle cx="188" cy="128" r="22" fill="#273049" opacity="0.9" />
                    <rect x="160" y="196" width="80" height="90" rx="30" fill="#273049"
                        opacity="0.8" />
                    <circle cx="456" cy="190" r="10" fill="#FCAC0A" opacity="0.85" />
                    <path d="M200 182 L310 200 M320 220 L368 200" stroke="#273049" stroke-opacity="0.3"
                        stroke-width="2" stroke-dasharray="4 6" fill="none" />
                </svg>
            </div>

            <div class="deco-orbit" aria-hidden="true">
                <svg viewBox="0 0 240 140" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="120" cy="70" r="56" class="deco-halo" />
                    <ellipse cx="120" cy="70" rx="58" ry="14"
                        class="deco-ring deco-ring-a" />
                    <ellipse cx="120" cy="70" rx="48" ry="11"
                        class="deco-ring deco-ring-b" />
                    <circle cx="120" cy="70" r="22" class="deco-core" />
                    <circle cx="111" cy="62" r="6" class="deco-core-glint" />
                    <circle r="4.2" fill="#FCAC0A" class="deco-moon">
                        <animateMotion dur="12s" repeatCount="indefinite"
                            path="M62,70 a58,14 0 1,1 116,0 a58,14 0 1,1 -116,0" />
                        <animate attributeName="opacity" values="0.45;0.95;0.45" dur="12s"
                            repeatCount="indefinite" />
                    </circle>
                </svg>
            </div>

            <div id="sec_masuk">
                <h1 class="judul-login auth-form-heading"><?php echo e(__('login.heading')); ?></h1>

                <form method="POST" id="loginForm" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>

                    <?php if($errors->any()): ?>
                    <div class="auth-alert auth-alert-danger mb-3" role="alert" style="background:#fde8e8;border:1px solid #f5c2c7;color:#842029;border-radius:8px;padding:10px 14px;font-size:13px;">
                        <i class="ti ti-alert-triangle me-1"></i>
                        <?php if($errors->has('captcha')): ?>
                        <?php echo e($errors->first('captcha')); ?>

                        <?php elseif($errors->has('session_expired')): ?>
                        <?php echo e($errors->first('session_expired')); ?>

                        <?php else: ?>
                        <?php echo e($errors->first()); ?>

                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <div class="form-floating">
                            <input name="email" id="email" type="email" autocomplete="username" required
                                value="<?php echo e(old('email')); ?>"
                                class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                            <label for="email"><?php echo e(__('login.email')); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="form-floating" style="flex:1;">
                                <input name="password" id="password" type="password"
                                    autocomplete="current-password" required
                                    class="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <label for="password"><?php echo e(__('login.password')); ?></label>
                            </div>
                            <span class="input-group-text" id="togglePassword"
                                title="<?php echo e(__('login.password_toggle_show')); ?> / <?php echo e(__('login.password_toggle_hide')); ?>" role="button" aria-label="Toggle password">
                                <i class="ti ti-eye-closed"></i>
                            </span>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger d-block mt-1" style="font-size:12px;"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label mb-1 d-flex align-items-center justify-content-between" style="font-size:12px;font-weight:600;color:#273049;">
                            <span><?php echo e(__('login.security_code')); ?> <span class="text-danger">*</span></span>
                            <small class="text-muted fw-normal" style="font-size:11px;"><?php echo e(__('login.click_to_refresh')); ?></small>
                        </label>
                        <div class="d-flex align-items-center gap-2 p-2" style="background:#f8f9fa;border:1px solid #e9ecef;border-radius:8px;">
                            <img id="captchaImage"
                                src="<?php echo e(url('/captcha-file/flat')); ?>"
                                alt="Captcha"
                                style="height: 44px; cursor: pointer; border-radius:6px; border:1px solid #dee2e6; flex-shrink:0;"
                                title="<?php echo e(__('login.click_to_refresh')); ?>">
                            <button type="button" id="refreshCaptcha" class="btn btn-sm btn-outline-secondary" title="<?php echo e(__('login.refresh')); ?> captcha" style="white-space:nowrap;">
                                <i class="ti ti-refresh"></i> <?php echo e(__('login.refresh')); ?>

                            </button>
                        </div>
                        <div class="form-floating mt-2">
                            <input name="captcha" id="captcha" type="text"
                                autocomplete="off" required placeholder="<?php echo e(__('login.captcha_placeholder')); ?>"
                                class="<?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="6">
                            <label for="captcha"><?php echo e(__('login.captcha_label')); ?> *</label>
                        </div>
                        <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger d-block mt-1" style="font-size:12px;"><i class="ti ti-alert-circle me-1"></i><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted d-block mt-1" style="font-size:11px;"><?php echo e(__('login.captcha_hint')); ?></small>
                    </div>

                    <div class="form-row-between">
                        <label class="form-check" for="remember">
                            <input type="checkbox" name="remember" id="remember" value="1" />
                            <span class="form-check-label"><?php echo e(__('login.remember')); ?></span>
                        </label>
                        <a id="forgot" href="javascript:void(0);" class="forgot-link"><?php echo e(__('login.forgot')); ?></a>
                    </div>

                    <button type="submit" class="btn-primary" id="btnLogin">
                        <i class="ti ti-login-2"></i><span><?php echo e(__('login.login_now')); ?></span>
                    </button>

                </form>

                


            </div>

            <!-- Reset Password -->
            <div id="sec_resetpass" style="display: none;">
                <h1 class="judul-login auth-form-heading"><?php echo e(__('login.heading_reset')); ?></h1>
                <div class="auth-alert auth-alert-info" role="alert">
                    <i class="ti ti-info-square-rounded"></i>
                    <span><?php echo e(__('login.reset_info')); ?></span>
                </div>
                <form id="formResetpass" novalidate action="#" method="POST">
                    <div class="form-group">
                        <div class="form-floating">
                            <input type="text" id="nipReset" name="nip"
                                placeholder="<?php echo e(__('login.email_placeholder')); ?>" required />
                            <label for="nipReset"><?php echo e(__('login.email_registered')); ?></label>
                        </div>
                    </div>
                    <span class="btn-primary">
                        <i class="ti ti-send"></i><span><?php echo e(__('login.send_to_email')); ?></span>
                    </span>
                </form>
                <div class="auth-back-link">
                    <a id="ahaa" href="javascript:void(0);"
                        class="d-flex align-items-center justify-content-center fw-bold">
                        <?php echo e(__('login.already_remember')); ?> <i class="ti ti-arrow-narrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="auth-footer">
                <small>
                    © <?php echo e(date('Y')); ?>

                    <a href="#">Pemerintah Kota Bekasi.</a>
                    <?php echo e(__('login.footer_rights')); ?>

                </small>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const captchaImage = document.getElementById('captchaImage');
        const refreshBtn = document.getElementById('refreshCaptcha');
        const captchaInput = document.getElementById('captcha');
        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.getElementById('btnLogin');

        const captchaUrl = "<?php echo e(url('/captcha-file/flat')); ?>";

        function refreshCaptcha() {
            if (!captchaImage) return;

            const timestamp = Date.now();

            captchaImage.src = captchaUrl + '?_=' + timestamp + Math.random();

            if (captchaInput) {
                captchaInput.value = '';
                captchaInput.focus();
            }
        }

        // Refresh CAPTCHA jika validasi email atau CAPTCHA gagal
        <?php if($errors->has('captcha') || $errors->has('email')): ?>
            refreshCaptcha();
        <?php endif; ?>

        // Klik gambar CAPTCHA untuk refresh
        if (captchaImage) {
            captchaImage.addEventListener('click', refreshCaptcha);

            captchaImage.addEventListener('error', function () {
                setTimeout(refreshCaptcha, 800);
            });
        }

        // Tombol refresh CAPTCHA
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function (e) {
                e.preventDefault();
                refreshCaptcha();
            });
        }

        // Submit login: kunci tombol sekali submit agar tidak double-POST
        // (double-POST membuat counter brute-force terhitung 2x).
        // Tidak perlu fallback re-enable: submit yang valid selalu pindah/
        // reload halaman (sukses -> dashboard, gagal -> redirect back).
        // Validasi bawaan browser (required) menggagalkan submit sebelum
        // handler ini jalan, jadi tombol tetap aktif jika form belum valid.
        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                if (btnLogin && !btnLogin.disabled) {
                    btnLogin.disabled = true;

                    btnLogin.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2"
                              role="status"
                              aria-hidden="true"></span>
                        <span><?php echo e(__('login.verifying')); ?></span>
                    `;
                }
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/auth/login.blade.php ENDPATH**/ ?>