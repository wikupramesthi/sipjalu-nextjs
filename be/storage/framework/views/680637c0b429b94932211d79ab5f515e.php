<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('auth-page-class', 'auth-login-fixed'); ?>
<?php $__env->startSection('body-class', 'auth-login-page'); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="/css/auth-login-page.css?v=<?php echo e(filemtime(public_path('css/auth-login-page.css'))); ?>">
<?php $__env->stopPush(); ?>

<div class="authentication-inner">
    <!-- Brand Panel -->
    <div class="auth-brand-panel">
        <svg class="auth-brand-cityscape" id="loginCityscape" viewBox="0 0 1200 660" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <defs>
                <linearGradient id="cityGlass" x1="0" y1="0" x2="1" y2="1">
                    <stop stop-color="var(--city-glass-light)" />
                    <stop offset="1" stop-color="var(--city-glass-dark)" />
                </linearGradient>
                <radialGradient id="cityMoonGlow">
                    <stop stop-color="#f8e9b5" stop-opacity=".3" />
                    <stop offset="1" stop-color="#f8e9b5" stop-opacity="0" />
                </radialGradient>
                <linearGradient id="cityNightTint" x1="0" y1="0" x2="0" y2="1">
                    <stop stop-color="#182747" />
                    <stop offset="1" stop-color="#365477" />
                </linearGradient>
                <pattern id="cityWindows" width="28" height="30" patternUnits="userSpaceOnUse">
                    <rect x="7" y="7" width="10" height="13" rx="1.5" fill="var(--city-window)" opacity=".58" />
                </pattern>
            </defs>
            <g class="city-night-sky" aria-hidden="true">
                <path class="city-night-wash" fill="url(#cityNightTint)" d="M0 0h1200v660H0z" />
                <g class="city-moon-rise">
                    <circle cx="1000" cy="142" r="104" fill="url(#cityMoonGlow)" />
                    <path class="city-moon" d="M1015 82a49 49 0 1 0 47 79 42 42 0 0 1-47-79Z" />
                </g>
                <g class="city-stars">
                    <circle cx="714" cy="102" r="2.2" />
                    <circle cx="793" cy="166" r="1.6" />
                    <circle cx="876" cy="89" r="2" />
                    <circle cx="935" cy="208" r="1.7" />
                    <circle cx="1087" cy="80" r="1.8" />
                    <circle cx="1132" cy="184" r="2.1" />
                    <path d="M824 114v10m-5-5h10m168 131v9m-4.5-4.5h9m-373-21v8m-4-4h8" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="8" fill="var(--city-far)" opacity=".72">
                <g class="cityscape-drift">
                    <path d="M0 454h68v-79h64v43h59v-97h91v54h45v-121h79v77h62v-58h94v112h56v-154h82v91h56v-71h92v107h48v-128h71v75h50v-44h82v119h62v-88h61v91h58v-65h66v72h50v206H0Z" />
                    <path d="M350 275h9v-42h5v42h9v3h-23zm439 25h8v-57h4v57h8v3h-20zm269 27h7v-36h4v36h7v3h-18z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="17">
                <g class="cityscape-drift">
                    <path fill="var(--city-mid)" d="M0 498h78V389h94v64h49V334h116v326H0zm329-58h89V312h35v-45h43v45h40v348H329zm198 37h102V343h48V222h21v-36h7v36h21v121h42v317H527zm236-27h88V327h34v-40h47v40h42v333H763zm221 13h75V374h33v-51h56v51h52v286H984z" />
                    <path fill="url(#cityWindows)" d="M18 414h55v216H18zm75-1h62v216H93zm141-54h81v272h-81zm109-27h24v294h-24zm138-6h69v300h-69zm79 0h63v300h-63zm191-78h18v378h-18zm38 0h18v378h-18zm137 102h67v276h-67zm166 65h85v211h-85z" />
                    <path fill="url(#cityGlass)" opacity=".46" d="M659 343h18V222h17v-36h8v36h17v121h17v317h-77z" />
                    <path fill="var(--city-edge)" opacity=".45" d="M78 389h18v271H78zm213-55h24v326h-24zm162-67h16v393h-16zm72 76h18v317h-18zm160-121h14v438h-14zm202 105h18v333h-18zm171 47h17v286h-17z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="29">
                <g class="cityscape-drift">
                    <path fill="var(--city-near)" d="M0 550h88V451h78v57h51V403h81v257H0zm264 30h76V440h25v-61h61v61h33v220H264zm202-23h76V388h36v-45h49v45h39v272H466zm206 37h80V460h44v-89h63v89h40v200H672zm228-16h82V433h45v-54h70v54h40v227H900z" />
                    <path fill="url(#cityWindows)" opacity=".8" d="M18 475h48v165H18zm91-2h39v165h-39zm177 9h43v173h-43zm95-74h35v242h-35zm113 2h45v242h-45zm184-1h38v242h-38zm139 11h34v231h-34zm105-4h39v235h-39z" />
                    <path fill="var(--city-edge)" opacity=".48" d="M88 451h15v209H88zm264-72h15v281h-15zm120 0h16v281h-16zm170-36h16v317h-16zm168 28h16v289h-16zm178 8h17v281h-17z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="41">
                <g class="cityscape-drift">
                    <path fill="var(--city-front)" d="M0 610h70v-72h92v39h57v-91h76v174H0zm249 50v-78h86v28h47v-79h73v129zm192 0V536h56v-48h72v48h48v124zm169 0v-72h84v30h40v-96h74v138zm174 0v-91h74v37h54v-67h89v121zm210-76h87v76h-87z" />
                    <path fill="var(--city-window)" opacity=".42" d="M22 558h16v20H22zm31 0h16v20H53zm230 50h17v18h-17zm33 0h17v18h-17zm197-98h17v18h-17zm34 0h17v18h-17zm216 76h17v19h-17zm33 0h17v19h-17zm211-32h18v19h-18zm36 0h18v19h-18z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="52" fill="var(--city-accent)">
                <g class="cityscape-drift">
                    <path opacity=".6" d="M0 646h1200v14H0zM125 580h5v80h-5zm938-21h5v101h-5z" />
                    <path opacity=".74" d="m587 660 20-70 4 1-13 69zm51 0-13-69 4-1 20 70z" />
                    <path d="M151 660c0-13 10-23 23-23s23 10 23 23zm809 0c0-14 11-25 25-25s25 11 25 25zm-755-25 7-15h-5l8-13 8 13h-5l7 15h-8v25h-4v-25zm811-17 8-17h-5l9-14 9 14h-5l8 17h-9v34h-6v-34z" />
                </g>
            </g>
        </svg>

        <div class="auth-brand-content">
            <a href="<?php echo e(route('home')); ?>" class="auth-brand-mark" aria-label="<?php echo e(__('login.organization')); ?>">
                <svg class="login-brand-symbol" viewBox="0 0 48 48" role="img" aria-label="<?php echo e(__('login.organization')); ?>" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 3.5 39.5 12.4v17.8L24 39.1 8.5 30.2V12.4L24 3.5Z" fill="none" stroke="var(--login-mark-stroke)" stroke-width="2.4" stroke-linejoin="round"/>
                    <path d="m15 17.1 9-5.2 9 5.2v10.3l-9 5.2-9-5.2V17.1Z" fill="#31a8d7" opacity=".92"/>
                    <circle cx="24" cy="22.2" r="3.1" fill="#f3fbff"/>
                    <path d="m24 25.2-5 6.1m5-6.1 5 6.1" stroke="#f3fbff" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="auth-brand-name">
                    <?php echo e(__('login.organization')); ?>

                    <strong><?php echo e(__('login.portal_name')); ?></strong>
                </span>
            </a>

            <h1 class="auth-brand-headline">
                <?php echo e(__('login.brand_welcome')); ?>

                <span class="auth-brand-headline-accent"><?php echo e(__('login.brand_portal')); ?></span>
            </h1>

            <p class="auth-brand-tagline">
                <?php echo e(__('login.brand_tagline')); ?>

            </p>

            <ul class="auth-brand-points list-unstyled">
                <li>
                    <i class="ti ti-chart-arrows-vertical"></i>
                    <span><?php echo e(__('login.brand_point_1')); ?></span>
                </li>
                <li>
                    <i class="ti ti-certificate"></i>
                    <span><?php echo e(__('login.brand_point_2')); ?></span>
                </li>
                <li>
                    <i class="ti ti-file-spreadsheet"></i>
                    <span><?php echo e(__('login.brand_point_3')); ?></span>
                </li>
                <li>
                    <i class="ti ti-shield-check"></i>
                    <span><?php echo e(__('login.brand_point_4')); ?></span>
                </li>
            </ul>

        </div>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel d-flex col-12 col-lg-5 col-xl-4 align-items-center">
        <div class="login-lang-switch-wrap--form">
            <div class="login-lang-switch" role="group" aria-label="<?php echo e(__('login.lang_switch')); ?>">
                <a href="<?php echo e(route('lang.switch', 'id')); ?>" class="<?php echo e(app()->getLocale() === 'id' ? 'active' : ''); ?>" lang="id" title="Bahasa Indonesia"><?php echo e(__('login.lang_id')); ?></a>
                <a href="<?php echo e(route('lang.switch', 'en')); ?>" class="<?php echo e(app()->getLocale() === 'en' ? 'active' : ''); ?>" lang="en" title="English"><?php echo e(__('login.lang_en')); ?></a>
            </div>
        </div>
        <button type="button" class="login-theme-toggle" id="loginThemeToggle" aria-label="<?php echo e(__('login.theme_toggle')); ?>" aria-pressed="false" title="<?php echo e(__('login.theme_toggle')); ?>">
            <i class="ti ti-moon-stars" aria-hidden="true"></i>
        </button>
        <span class="auth-form-shape auth-form-shape-1 d-lg-none" aria-hidden="true"></span>
        <span class="auth-form-shape auth-form-shape-2 d-lg-none" aria-hidden="true"></span>

        <div class="auth-form-inner">
            <div id="sec_masuk">
                <div class="login-panel-brand">
                    <svg class="login-brand-symbol" viewBox="0 0 48 48" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 3.5 39.5 12.4v17.8L24 39.1 8.5 30.2V12.4L24 3.5Z" fill="none" stroke="var(--login-mark-stroke)" stroke-width="2.4" stroke-linejoin="round"/>
                        <path d="m15 17.1 9-5.2 9 5.2v10.3l-9 5.2-9-5.2V17.1Z" fill="#31a8d7" opacity=".92"/>
                        <circle cx="24" cy="22.2" r="3.1" fill="#f3fbff"/>
                        <path d="m24 25.2-5 6.1m5-6.1 5 6.1" stroke="#f3fbff" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>
                        <?php echo e(__('login.organization')); ?>

                        <strong><?php echo e(__('login.portal_name')); ?></strong>
                    </span>
                </div>
                <h1 class="judul-login auth-form-heading"><?php echo e(__('login.heading')); ?></h1>
                <p class="login-intro"><?php echo e(__('login.panel_description')); ?></p>

                <form method="POST" id="loginForm" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>

                    <?php if($errors->any()): ?>
                    <div class="auth-alert auth-alert-danger login-form-alert" role="alert">
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
                            <div class="form-floating login-password-field">
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
                        <small class="text-danger login-field-error"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label login-captcha-label">
                            <span><?php echo e(__('login.security_code')); ?> <span class="text-danger">*</span></span>
                            <small class="login-captcha-refresh-hint"><?php echo e(__('login.click_to_refresh')); ?></small>
                        </label>
                        <div class="login-captcha-image-row">
                            <img id="captchaImage"
                                src="<?php echo e(url('/captcha-file/flat')); ?>"
                                alt="Captcha"
                                class="login-captcha-image"
                                title="<?php echo e(__('login.click_to_refresh')); ?>">
                            <button type="button" id="refreshCaptcha" class="btn btn-sm btn-outline-secondary login-captcha-refresh" title="<?php echo e(__('login.refresh')); ?> captcha">
                                <i class="ti ti-refresh"></i> <?php echo e(__('login.refresh')); ?>

                            </button>
                        </div>
                        <div class="form-floating login-captcha-input">
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
                        <small class="text-danger login-field-error"><i class="ti ti-alert-circle me-1"></i><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="login-captcha-hint"><?php echo e(__('login.captcha_hint')); ?></small>
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

                <div class="login-info-box">
                    <i class="ti ti-lock" aria-hidden="true"></i>
                    <span><?php echo e(__('login.access_note')); ?></span>
                </div>

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

                    <a href="<?php echo e(route('home')); ?>"><?php echo e(__('login.portal_name')); ?>.</a>
                    <?php echo e(__('login.footer_rights')); ?>

                </small>
            </div>
        </div>
    </div>
</div>


<script>
    (function () {
        var themeToggle = document.getElementById('loginThemeToggle');
        var root = document.documentElement;
        var theme = localStorage.getItem('login-theme') === 'dark' ? 'dark' : 'light';

        function applyTheme(nextTheme) {
            root.setAttribute('data-login-theme', nextTheme);
            if (themeToggle) {
                themeToggle.setAttribute('aria-pressed', nextTheme === 'dark' ? 'true' : 'false');
                themeToggle.innerHTML = nextTheme === 'dark'
                    ? '<i class="ti ti-sun" aria-hidden="true"></i>'
                    : '<i class="ti ti-moon-stars" aria-hidden="true"></i>';
            }
        }

        applyTheme(theme);
        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                theme = theme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('login-theme', theme);
                applyTheme(theme);
            });
        }
    })();

    (function () {
        var brandPanel = document.querySelector('.auth-brand-panel');
        var cityscape = document.getElementById('loginCityscape');
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!brandPanel || !cityscape || reducedMotion) {
            return;
        }

        var layers = Array.prototype.slice.call(cityscape.querySelectorAll('[data-parallax-depth]'));
        var target = { x: 0, y: 0 };
        var position = { x: 0, y: 0 };
        var frame = null;

        function updateLayers() {
            frame = null;
            position.x += (target.x - position.x) * 0.12;
            position.y += (target.y - position.y) * 0.12;

            layers.forEach(function (layer) {
                var depth = Number(layer.getAttribute('data-parallax-depth'));
                layer.style.transform = 'translate3d('
                    + (-position.x * depth) + 'px, '
                    + (-position.y * depth * 0.45) + 'px, 0)';
            });

            if (Math.abs(target.x - position.x) > 0.001 || Math.abs(target.y - position.y) > 0.001) {
                frame = window.requestAnimationFrame(updateLayers);
            } else {
                position.x = target.x;
                position.y = target.y;
            }
        }

        function queueUpdate() {
            if (frame === null) {
                frame = window.requestAnimationFrame(updateLayers);
            }
        }

        brandPanel.addEventListener('pointermove', function (event) {
            var bounds = brandPanel.getBoundingClientRect();
            target.x = Math.max(-1, Math.min(1, ((event.clientX - bounds.left) / bounds.width) * 2 - 1));
            target.y = Math.max(-1, Math.min(1, ((event.clientY - bounds.top) / bounds.height) * 2 - 1));
            queueUpdate();
        });

        brandPanel.addEventListener('pointerleave', function () {
            target.x = 0;
            target.y = 0;
            queueUpdate();
        });
    })();

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
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\auth\login.blade.php ENDPATH**/ ?>