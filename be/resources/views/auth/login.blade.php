@extends('layouts.auth')
@section('title', 'Login')
@section('auth-page-class', 'auth-login-fixed')
@section('body-class', 'auth-login-page')
@section('content')

@push('styles')
    <link rel="stylesheet" href="/css/auth-login-page.css?v={{ filemtime(public_path('css/auth-login-page.css')) }}">
@endpush

<div class="authentication-inner">
    <!-- Brand Panel -->
    <div class="auth-brand-panel">
        <div class="login-top-controls">
            <div class="login-lang-switch-wrap--form">
                <div class="login-lang-switch" role="group" aria-label="{{ __('login.lang_switch') }}">
                    <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'active' : '' }}" lang="id" title="Bahasa Indonesia">{{ __('login.lang_id') }}</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}" lang="en" title="English">{{ __('login.lang_en') }}</a>
                </div>
            </div>
            <button type="button" class="login-theme-toggle" id="loginThemeToggle" aria-label="{{ __('login.theme_toggle') }}" aria-pressed="false" title="{{ __('login.theme_toggle') }}">
                <i class="ti ti-moon-stars" aria-hidden="true"></i>
            </button>
        </div>
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
                <radialGradient id="citySunGlow">
                    <stop stop-color="#fff2c6" stop-opacity=".5" />
                    <stop offset=".45" stop-color="#ffe3a3" stop-opacity=".18" />
                    <stop offset="1" stop-color="#ffe3a3" stop-opacity="0" />
                </radialGradient>
                <radialGradient id="cityLampGlow">
                    <stop stop-color="#ffe7a0" stop-opacity=".34" />
                    <stop offset="1" stop-color="#ffe7a0" stop-opacity="0" />
                </radialGradient>
                <linearGradient id="cityNightTint" x1="0" y1="0" x2="0" y2="1">
                    <stop stop-color="#182747" stop-opacity="0" />
                    <stop offset=".28" stop-color="#182747" stop-opacity=".04" />
                    <stop offset=".68" stop-color="#243d5b" stop-opacity=".2" />
                    <stop offset="1" stop-color="#365477" stop-opacity=".42" />
                </linearGradient>
                <linearGradient id="cityDuskTint" x1="0" y1="0" x2="0" y2="1">
                    <stop stop-color="#e99f91" stop-opacity="0" />
                    <stop offset=".35" stop-color="#e99f91" stop-opacity=".04" />
                    <stop offset=".72" stop-color="#f0bd9b" stop-opacity=".14" />
                    <stop offset="1" stop-color="#f5d6a5" stop-opacity=".28" />
                </linearGradient>
                <pattern id="cityWindows" width="28" height="30" patternUnits="userSpaceOnUse">
                    <rect x="7" y="7" width="10" height="13" rx="1.5" fill="var(--city-window)" opacity=".58" />
                </pattern>
            </defs>
            <g class="city-day-sky" aria-hidden="true">
                <circle class="city-sun-glow" cx="1000" cy="142" r="155" fill="url(#citySunGlow)" />
                <circle class="city-sun" cx="1000" cy="142" r="34" />
                <g class="city-cloud city-cloud--far">
                    <path d="M850 248c0-11 9-20 20-20 4-13 16-22 30-22 17 0 30 12 33 28 12 0 21 9 21 20 0 12-10 21-22 21h-61c-12 0-21-9-21-21z" />
                </g>
                <g class="city-cloud city-cloud--near">
                    <path d="M1010 280c0-9 7-16 16-16 4-11 14-18 25-18 14 0 25 10 28 23 10 0 18 8 18 17 0 10-8 18-18 18h-51c-10 0-18-8-18-18z" />
                </g>
            </g>
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
            <path class="city-dusk-sky" fill="url(#cityDuskTint)" d="M0 0h1200v660H0z" aria-hidden="true" />
            <g class="city-birds" aria-hidden="true">
                <g transform="translate(0 185)">
                    <g class="city-bird-flight city-bird-flight--one">
                        <path d="M0 10q8-10 16 0 8-10 16 0" />
                    </g>
                </g>
                <g transform="translate(0 210)">
                    <g class="city-bird-flight city-bird-flight--two">
                        <path d="M0 10q6-8 12 0 6-8 12 0" />
                    </g>
                </g>
                <g transform="translate(0 194)">
                    <g class="city-bird-flight city-bird-flight--three">
                        <path d="M0 8q5-7 10 0 5-7 10 0" />
                    </g>
                </g>
            </g>
            <g class="cityscape-layer city-hills" data-parallax-depth="5" aria-hidden="true">
                <path class="city-hill city-hill--far" d="M0 558c105-52 168-57 274-8 97-76 169-94 280-20 93-58 183-67 292-11 118-73 214-61 354 2v139H0Z" />
                <path class="city-hill city-hill--near" d="M0 592c121-44 220-29 325 14 95-58 192-67 307-12 115-49 215-43 327 13 83-36 158-42 241-10v63H0Z" />
            </g>
            <g class="cityscape-layer" data-parallax-depth="8" fill="var(--city-far)" opacity=".72">
                <g class="cityscape-drift">
                    <path d="M0 454h68v-79h64v43h59v-97h91v54h45v-121h79v77h62v-58h94v112h56v-154h82v91h56v-71h92v107h48v-128h71v75h50v-44h82v119h62v-88h61v91h58v-65h66v72h50v206H0Z" />
                    <path d="M350 275h9v-42h5v42h9v3h-23zm439 25h8v-57h4v57h8v3h-20zm269 27h7v-36h4v36h7v3h-18z" />
                </g>
            </g>
            <g class="cityscape-layer city-landmark" data-parallax-depth="13" transform="translate(0 -95)" aria-hidden="true">
                <path class="city-landmark-shadow" d="M858 477V330h12v-24h6v-18l26-25 26 25v18h7v24h11v147z" />
                <path class="city-landmark-face" d="M866 477V330h12v-24h6v-18l18-18 18 18v18h7v24h11v147z" />
                <path class="city-landmark-roof" d="m880 306 22-25 22 25zm-6 7h56v8h-56z" />
                <circle class="city-landmark-clock" cx="902" cy="346" r="13" />
                <path class="city-landmark-clock-mark" d="M902 337v9l6 4" />
                <path class="city-landmark-column" d="M881 372v82m14-82v82m14-82v82m14-82v82" />
                <path class="city-landmark-base" d="M862 459h80v8h-80zm-7 11h94v7h-94z" />
            </g>
            <g class="cityscape-layer" data-parallax-depth="17">
                <g class="cityscape-drift">
                    <path fill="var(--city-mid)" d="M0 498h78V389h94v64h49V334h116v326H0zm329-58h89V312h35v-45h43v45h40v348H329zm198 37h102V343h48V222h21v-36h7v36h21v121h42v317H527zm236-27h88V327h34v-40h47v40h42v333H763zm221 13h75V374h33v-51h56v51h52v286H984z" />
                    <path class="city-window-band city-window-band--mid" fill="url(#cityWindows)" d="M18 414h55v216H18zm75-1h62v216H93zm141-54h81v272h-81zm109-27h24v294h-24zm138-6h69v300h-69zm79 0h63v300h-63zm191-78h18v378h-18zm38 0h18v378h-18zm137 102h67v276h-67zm166 65h85v211h-85z" />
                    <path fill="url(#cityGlass)" opacity=".46" d="M659 343h18V222h17v-36h8v36h17v121h17v317h-77z" />
                    <path fill="var(--city-edge)" opacity=".45" d="M78 389h18v271H78zm213-55h24v326h-24zm162-67h16v393h-16zm72 76h18v317h-18zm160-121h14v438h-14zm202 105h18v333h-18zm171 47h17v286h-17z" />
                    <path class="city-rooftop-details" d="M111 389v-13h4v-9h16v9h4v13zm276-77v-14h5v-10h4v10h5v14zm318-126v-11h4v-9h4v9h4v11m148 151v-12h4v-8h14v8h4v12z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="29">
                <g class="cityscape-drift">
                    <path fill="var(--city-near)" d="M0 550h88V451h78v57h51V403h81v257H0zm264 30h76V440h25v-61h61v61h33v220H264zm202-23h76V388h36v-45h49v45h39v272H466zm206 37h80V460h44v-89h63v89h40v200H672zm228-16h82V433h45v-54h70v54h40v227H900z" />
                    <path class="city-window-band city-window-band--near" fill="url(#cityWindows)" opacity=".8" d="M18 475h48v165H18zm91-2h39v165h-39zm177 9h43v173h-43zm95-74h35v242h-35zm113 2h45v242h-45zm184-1h38v242h-38zm139 11h34v231h-34zm105-4h39v235h-39z" />
                    <path fill="var(--city-edge)" opacity=".48" d="M88 451h15v209H88zm264-72h15v281h-15zm120 0h16v281h-16zm170-36h16v317h-16zm168 28h16v289h-16zm178 8h17v281h-17z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="41">
                <g class="cityscape-drift">
                    <path fill="var(--city-front)" d="M0 610h70v-72h92v39h57v-91h76v174H0zm249 50v-78h86v28h47v-79h73v129zm192 0V536h56v-48h72v48h48v124zm169 0v-72h84v30h40v-96h74v138zm174 0v-91h74v37h54v-67h89v121zm210-76h87v76h-87z" />
                    <path fill="var(--city-window)" opacity=".42" d="M22 558h16v20H22zm31 0h16v20H53zm230 50h17v18h-17zm33 0h17v18h-17zm197-98h17v18h-17zm34 0h17v18h-17zm216 76h17v19h-17zm33 0h17v19h-17zm211-32h18v19h-18zm36 0h18v19h-18z" />
                </g>
            </g>
            <g class="cityscape-layer city-trees" data-parallax-depth="46" aria-hidden="true">
                <g class="city-tree city-tree--left" transform="translate(178 0)">
                    <path class="city-tree-trunk" d="M0 646v-48" />
                    <path class="city-tree-crown city-tree-crown--back" d="M0 566c-18 0-28 15-24 29-13 12-4 31 12 31 4 15 26 18 34 5 17 2 25-18 13-30 4-17-12-31-27-27-2-5-5-8-8-8z" />
                    <path class="city-tree-crown city-tree-crown--front" d="M-1 581c-14-2-22 12-16 23-8 9-2 23 10 24 6 13 24 11 27-2 14-4 14-20 4-26 1-13-12-22-25-19z" />
                </g>
                <g class="city-tree city-tree--slim" transform="translate(344 0)">
                    <path class="city-tree-trunk" d="M0 646v-45" />
                    <path class="city-tree-crown city-tree-crown--back" d="M0 553c-12 0-19 12-16 22-10 10-4 24 8 26 2 13 18 17 27 7 12 1 19-13 12-22 5-12-6-25-18-22-2-6-7-11-13-11z" />
                    <path class="city-tree-crown city-tree-crown--front" d="M1 568c-10-1-16 9-12 17-7 7-3 18 7 20 4 11 17 10 22 1 10-3 11-15 4-21 2-9-8-18-17-15z" />
                </g>
                <g class="city-tree city-tree--wide" transform="translate(812 0)">
                    <path class="city-tree-trunk" d="M0 646v-49" />
                    <path class="city-tree-crown city-tree-crown--back" d="M0 565c-16-5-29 9-25 23-14 8-12 28 3 33 2 14 20 20 31 10 14 8 31-4 28-19 11-12 2-31-13-31-3-10-14-16-24-11z" />
                    <path class="city-tree-crown city-tree-crown--front" d="M0 579c-12-4-22 8-18 19-10 6-8 21 3 25 4 12 18 14 27 5 11 5 23-4 20-16 8-9 1-23-10-23-4-8-14-13-22-10z" />
                </g>
                <g class="city-tree city-tree--right" transform="translate(1040 0)">
                    <path class="city-tree-trunk" d="M0 646v-54" />
                    <path class="city-tree-crown city-tree-crown--back" d="M0 559c-18 0-28 15-24 29-13 12-4 31 12 31 4 15 26 18 34 5 17 2 25-18 13-30 4-17-12-31-27-27-2-5-5-8-8-8z" />
                    <path class="city-tree-crown city-tree-crown--front" d="M-1 575c-14-2-22 12-16 23-8 9-2 23 10 24 6 13 24 11 27-2 14-4 14-20 4-26 1-13-12-22-25-19z" />
                </g>
            </g>
            <g class="cityscape-layer" data-parallax-depth="52" fill="var(--city-accent)">
                <g class="cityscape-drift">
                    <path opacity=".6" d="M0 646h1200v14H0zM125 580h5v80h-5zm938-21h5v101h-5z" />
                    <path opacity=".74" d="m587 660 20-70 4 1-13 69zm51 0-13-69 4-1 20 70z" />
                    <path d="M151 660c0-13 10-23 23-23s23 10 23 23zm809 0c0-14 11-25 25-25s25 11 25 25zm-755-25 7-15h-5l8-13 8 13h-5l7 15h-8v25h-4v-25zm811-17 8-17h-5l9-14 9 14h-5l8 17h-9v34h-6v-34z" />
                </g>
            </g>
            <g class="city-street" aria-hidden="true">
                <path class="city-road-surface" d="M0 621h1200v39H0z" />
                <path class="city-sidewalk" d="M0 605h1200v16H0z" />
                <path class="city-curb" d="M0 619h1200v3H0z" />
                <path class="city-sidewalk-joints" d="M90 607v11m110-11v11m110-11v11m110-11v11m110-11v11m110-11v11m110-11v11m110-11v11m110-11v11m110-11v11" />
                <path class="city-road-markings" d="M44 642h72m90 0h72m90 0h72m90 0h72m90 0h72m90 0h72m90 0h72m90 0h72" />
                <path class="city-road-edge" d="M0 657h1200" />
            </g>
            <g class="city-street-lights" aria-hidden="true">
                <g class="city-street-lamp" transform="translate(95 0)">
                    <path class="city-street-lamp-pole" d="M0 650V574q0-9 9-9h19" />
                    <path class="city-street-lamp-fixture" d="M24 561h13q4 0 4 4v4H24z" />
                    <ellipse class="city-street-lamp-glow" cx="30" cy="568" rx="18" ry="15" />
                    <path class="city-street-lamp-beam" d="m25 569-48 77h105l-48-77z" />
                </g>
                <g class="city-street-lamp" transform="translate(420 0)">
                    <path class="city-street-lamp-pole" d="M0 650V574q0-9 9-9h19" />
                    <path class="city-street-lamp-fixture" d="M24 561h13q4 0 4 4v4H24z" />
                    <ellipse class="city-street-lamp-glow" cx="30" cy="568" rx="18" ry="15" />
                    <path class="city-street-lamp-beam" d="m25 569-48 77h105l-48-77z" />
                </g>
                <g class="city-street-lamp" transform="translate(790 0)">
                    <path class="city-street-lamp-pole" d="M0 650V574q0-9 9-9h19" />
                    <path class="city-street-lamp-fixture" d="M24 561h13q4 0 4 4v4H24z" />
                    <ellipse class="city-street-lamp-glow" cx="30" cy="568" rx="18" ry="15" />
                    <path class="city-street-lamp-beam" d="m25 569-48 77h105l-48-77z" />
                </g>
                <g class="city-street-lamp" transform="translate(1110 0)">
                    <path class="city-street-lamp-pole" d="M0 650V574q0-9 9-9h19" />
                    <path class="city-street-lamp-fixture" d="M24 561h13q4 0 4 4v4H24z" />
                    <ellipse class="city-street-lamp-glow" cx="30" cy="568" rx="18" ry="15" />
                    <path class="city-street-lamp-beam" d="m25 569-48 77h105l-48-77z" />
                </g>
            </g>
            <g class="city-traffic" aria-hidden="true">
                <g class="city-car-track" transform="translate(0 614)">
                    <g class="city-car city-car--forward">
                        <path class="city-car-trail" d="M7 22h-42" />
                        <path class="city-car-body" d="M5 18h7l7-9c1-2 3-3 5-3h21c3 0 5 1 7 4l6 8h5c3 0 5 2 5 5v4H4v-4c0-3 0-5 1-5Z" />
                        <path class="city-car-window" d="m22 9-6 9h15V9zm12 0v9h16l-6-7c-1-1-2-2-4-2z" />
                        <path class="city-car-light" d="M6 20h5v3H6zm56 0h4v3h-4z" />
                        <circle class="city-car-wheel" cx="17" cy="27" r="5" />
                        <circle class="city-car-wheel" cx="51" cy="27" r="5" />
                    </g>
                </g>
                <g class="city-car-track" transform="translate(0 614)">
                    <g class="city-car city-car--reverse">
                        <g transform="translate(72 0) scale(-1 1)">
                            <path class="city-car-trail" d="M7 22h-42" />
                            <path class="city-car-body" d="M5 18h7l7-9c1-2 3-3 5-3h21c3 0 5 1 7 4l6 8h5c3 0 5 2 5 5v4H4v-4c0-3 0-5 1-5Z" />
                            <path class="city-car-window" d="m22 9-6 9h15V9zm12 0v9h16l-6-7c-1-1-2-2-4-2z" />
                            <path class="city-car-light" d="M6 20h5v3H6zm56 0h4v3h-4z" />
                            <circle class="city-car-wheel" cx="17" cy="27" r="5" />
                            <circle class="city-car-wheel" cx="51" cy="27" r="5" />
                        </g>
                    </g>
                </g>
                <g class="city-car-track" transform="translate(0 614)">
                    <g class="city-car city-car--late">
                        <path class="city-car-trail" d="M7 22h-42" />
                        <path class="city-car-body" d="M5 18h7l7-9c1-2 3-3 5-3h21c3 0 5 1 7 4l6 8h5c3 0 5 2 5 5v4H4v-4c0-3 0-5 1-5Z" />
                        <path class="city-car-window" d="m22 9-6 9h15V9zm12 0v9h16l-6-7c-1-1-2-2-4-2z" />
                        <path class="city-car-light" d="M6 20h5v3H6zm56 0h4v3h-4z" />
                        <circle class="city-car-wheel" cx="17" cy="27" r="5" />
                        <circle class="city-car-wheel" cx="51" cy="27" r="5" />
                    </g>
                </g>
            </g>
        </svg>

        <div class="auth-brand-content">
            <a href="{{ route('home') }}" class="auth-brand-mark" aria-label="{{ __('login.organization') }}">
                <svg class="login-brand-symbol" viewBox="0 0 48 48" role="img" aria-label="{{ __('login.organization') }}" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 3.5 39.5 12.4v17.8L24 39.1 8.5 30.2V12.4L24 3.5Z" fill="none" stroke="var(--login-mark-stroke)" stroke-width="2.4" stroke-linejoin="round"/>
                    <path d="m15 17.1 9-5.2 9 5.2v10.3l-9 5.2-9-5.2V17.1Z" fill="#31a8d7" opacity=".92"/>
                    <circle cx="24" cy="22.2" r="3.1" fill="#f3fbff"/>
                    <path d="m24 25.2-5 6.1m5-6.1 5 6.1" stroke="#f3fbff" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="auth-brand-name">
                    {{ __('login.organization') }}
                    <strong>{{ __('login.portal_name') }}</strong>
                </span>
            </a>

            <h1 class="auth-brand-headline">
                {{ __('login.brand_welcome') }}
                <span class="auth-brand-headline-accent">{{ __('login.brand_portal') }}</span>
            </h1>

            <p class="auth-brand-tagline">
                {{ __('login.brand_tagline') }}
            </p>

            <ul class="auth-brand-points list-unstyled">
                <li>
                    <i class="ti ti-chart-arrows-vertical"></i>
                    <span>{{ __('login.brand_point_1') }}</span>
                </li>
                <li>
                    <i class="ti ti-certificate"></i>
                    <span>{{ __('login.brand_point_2') }}</span>
                </li>
                <li>
                    <i class="ti ti-file-spreadsheet"></i>
                    <span>{{ __('login.brand_point_3') }}</span>
                </li>
                <li>
                    <i class="ti ti-shield-check"></i>
                    <span>{{ __('login.brand_point_4') }}</span>
                </li>
            </ul>

        </div>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel d-flex col-12 col-lg-5 col-xl-4 align-items-center">
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
                        {{ __('login.organization') }}
                        <strong>{{ __('login.portal_name') }}</strong>
                    </span>
                </div>
                <h1 class="judul-login auth-form-heading">{{ __('login.heading') }}</h1>
                <p class="login-intro">{{ __('login.panel_description') }}</p>

                <form method="POST" id="loginForm" action="{{ route('login') }}">
                    @csrf

                    @if ($errors->any())
                    <div class="auth-alert auth-alert-danger login-form-alert" role="alert" tabindex="-1">
                        <i class="ti ti-alert-triangle me-1"></i>
                        @if ($errors->has('captcha'))
                        {{ $errors->first('captcha') }}
                        @elseif ($errors->has('session_expired'))
                        {{ $errors->first('session_expired') }}
                        @else
                        {{ $errors->first() }}
                        @endif
                    </div>
                    @endif

                    <div class="form-group">
                        <div class="form-floating">
                            <input name="email" id="email" type="email" autocomplete="username" required
                                value="{{ old('email') }}"
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                @error('email') aria-describedby="email-error" @enderror
                                class="@error('email') is-invalid @enderror" />
                            <label for="email">{{ __('login.email') }}</label>
                        </div>
                        @error('email')
                        <small class="text-danger login-field-error" id="email-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="form-floating login-password-field">
                                <input name="password" id="password" type="password"
                                    autocomplete="current-password" required
                                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                    @error('password') aria-describedby="password-error" @enderror
                                    class="@error('password') is-invalid @enderror">
                                <label for="password">{{ __('login.password') }}</label>
                            </div>
                            <span class="input-group-text" id="togglePassword" tabindex="0"
                                title="{{ __('login.password_toggle_show') }} / {{ __('login.password_toggle_hide') }}" role="button" aria-label="Toggle password" aria-pressed="false">
                                <i class="ti ti-eye-closed"></i>
                            </span>
                        </div>
                        @error('password')
                        <small class="text-danger login-field-error" id="password-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label login-captcha-label">
                            <span id="captcha-label">{{ __('login.security_code') }} <span class="text-danger">*</span></span>
                            <small class="login-captcha-refresh-hint">{{ __('login.click_to_refresh') }}</small>
                        </label>
                        <div class="login-captcha-image-row">
                            <img id="captchaImage"
                                src="{{ url('/captcha-file/flat') }}"
                                alt="Captcha"
                                class="login-captcha-image"
                                title="{{ __('login.click_to_refresh') }}">
                            <button type="button" id="refreshCaptcha" class="btn btn-sm btn-outline-secondary login-captcha-refresh" title="{{ __('login.refresh') }} captcha">
                                <i class="ti ti-refresh"></i> {{ __('login.refresh') }}
                            </button>
                        </div>
                        <div class="form-floating login-captcha-input">
                            <input name="captcha" id="captcha" type="text"
                                autocomplete="off" required placeholder="{{ __('login.captcha_placeholder') }}"
                                aria-labelledby="captcha-label"
                                aria-invalid="{{ $errors->has('captcha') ? 'true' : 'false' }}"
                                @error('captcha') aria-describedby="captcha-error" @enderror
                                class="@error('captcha') is-invalid @enderror" maxlength="6">
                            <label for="captcha">{{ __('login.captcha_label') }} *</label>
                        </div>
                        @error('captcha')
                        <small class="text-danger login-field-error" id="captcha-error"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
                        @enderror
                        <small class="login-captcha-hint">{{ __('login.captcha_hint') }}</small>
                    </div>

                    <div class="form-row-between">
                        <label class="form-check" for="remember">
                            <input type="checkbox" name="remember" id="remember" value="1" />
                            <span class="form-check-label">{{ __('login.remember') }}</span>
                        </label>
                        <a id="forgot" href="javascript:void(0);" class="forgot-link">{{ __('login.forgot') }}</a>
                    </div>

                    <button type="submit" class="btn-primary" id="btnLogin">
                        <i class="ti ti-login-2"></i><span>{{ __('login.login_now') }}</span>
                    </button>
                    <span class="login-sr-only" id="loginSubmitStatus" role="status" aria-live="polite"></span>

                </form>

                <div class="login-info-box">
                    <i class="ti ti-lock" aria-hidden="true"></i>
                    <span>{{ __('login.access_note') }}</span>
                </div>

            </div>

            <!-- Reset Password -->
            <div id="sec_resetpass" style="display: none;">
                <h1 class="judul-login auth-form-heading">{{ __('login.heading_reset') }}</h1>
                <div class="auth-alert auth-alert-info" role="alert">
                    <i class="ti ti-info-square-rounded"></i>
                    <span>{{ __('login.reset_info') }}</span>
                </div>
                <form id="formResetpass" novalidate action="#" method="POST">
                    <div class="form-group">
                        <div class="form-floating">
                            <input type="text" id="nipReset" name="nip"
                                placeholder="{{ __('login.email_placeholder') }}" required />
                            <label for="nipReset">{{ __('login.email_registered') }}</label>
                        </div>
                    </div>
                    <span class="btn-primary">
                        <i class="ti ti-send"></i><span>{{ __('login.send_to_email') }}</span>
                    </span>
                </form>
                <div class="auth-back-link">
                    <a id="ahaa" href="javascript:void(0);"
                        class="d-flex align-items-center justify-content-center fw-bold">
                        {{ __('login.already_remember') }} <i class="ti ti-arrow-narrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="auth-footer">
                <small>
                    © {{ date('Y') }}
                    <a href="{{ route('home') }}">Sipjalu.</a>
                    {{ __('login.footer_rights') }}
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
        var transitionTimeout;

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
                window.clearTimeout(transitionTimeout);
                root.removeAttribute('data-login-theme-transition');
                if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    root.setAttribute('data-login-theme-transition', theme === 'light' ? 'to-light' : 'to-dark');
                    transitionTimeout = window.setTimeout(function () {
                        root.removeAttribute('data-login-theme-transition');
                    }, 2400);
                }
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
                    + (-position.x * depth * 1.15) + 'px, '
                    + (-position.y * depth * 0.6) + 'px, 0)';
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

        const captchaUrl = "{{ url('/captcha-file/flat') }}";

        function refreshCaptcha(shouldFocus) {
            if (!captchaImage) return;

            const timestamp = Date.now();

            captchaImage.src = captchaUrl + '?_=' + timestamp + Math.random();

            if (captchaInput) {
                captchaInput.value = '';
                if (shouldFocus !== false) captchaInput.focus();
            }
        }

        // Refresh CAPTCHA jika validasi email atau CAPTCHA gagal
        @if ($errors->has('captcha') || $errors->has('email'))
            refreshCaptcha(false);
        @endif

        @if ($errors->any())
            const firstInvalid = loginForm && loginForm.querySelector('[aria-invalid="true"]');
            const errorSummary = loginForm && loginForm.querySelector('.login-form-alert');
            if (firstInvalid) {
                firstInvalid.focus();
            } else if (errorSummary) {
                errorSummary.focus();
            }
        @endif

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
            loginForm.addEventListener('invalid', function (event) {
                event.target.setAttribute('aria-invalid', 'true');
            }, true);

            loginForm.addEventListener('input', function (event) {
                const field = event.target;
                if (!field.matches('input[aria-invalid="true"]') || !field.checkValidity()) return;

                field.setAttribute('aria-invalid', 'false');
                field.classList.remove('is-invalid');
                const errorId = field.getAttribute('aria-describedby');
                const error = errorId && document.getElementById(errorId);
                if (error) error.hidden = true;
            });

            loginForm.addEventListener('submit', function () {
                if (btnLogin && !btnLogin.disabled) {
                    btnLogin.disabled = true;
                    loginForm.setAttribute('aria-busy', 'true');
                    const submitStatus = document.getElementById('loginSubmitStatus');
                    if (submitStatus) submitStatus.textContent = "{{ __('login.verifying') }}";

                    btnLogin.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2"
                              role="status"
                              aria-hidden="true"></span>
                        <span>{{ __('login.verifying') }}</span>
                    `;
                }
            });
        }
    });
</script>

@endsection