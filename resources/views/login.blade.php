<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'au']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.sign_in') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">

    @if (in_array(app()->getLocale(), ['ar', 'au']))
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-secondary: #2563eb;
            --brand-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body.auth-body {
            margin: 0;
            min-height: 100vh;
            background: #ffffff;
            color: var(--brand-dark);
            position: relative;
            @if (in_array(app()->getLocale(), ['ar', 'au']))
                font-family: 'Cairo', sans-serif;
                line-height: 1.5;
            @else
                font-family: 'Inter', sans-serif;
            @endif
        }

        @if (in_array(app()->getLocale(), ['ar', 'au']))
            .login-header h1,
            .login-header p,
            .form-label,
            .auth-layout .btn-primary,
            .auth-layout .form-control {
                line-height: 1.4 !important;
            }
        @endif

        .auth-page-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            background: #ffffff;
        }

        .auth-page-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 0% 0%, rgba(37, 99, 235, 0.06), transparent 55%),
                radial-gradient(ellipse 60% 45% at 100% 100%, rgba(124, 58, 237, 0.05), transparent 50%);
        }

        .auth-page-bg .info-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: linear-gradient(to bottom, black 10%, black 85%, transparent 100%);
        }

        .auth-layout {
            display: flex !important;
            flex-direction: row !important;
            align-items: stretch !important;
            justify-content: flex-start !important;
            min-height: 100vh;
            height: auto !important;
            background: transparent !important;
            position: relative;
            z-index: 1;
        }

        /* English: info left, login right */
        .auth-layout-ltr {
            direction: ltr;
        }

        /* Arabic / Urdu: login left, info right */
        .auth-layout-rtl {
            direction: rtl;
        }

        /* ── Left info panel ── */
        .info-panel {
            position: relative;
            flex: 1.1;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 48px 56px 40px;
            background: transparent;
            color: var(--brand-dark);
        }

        .auth-page-bg .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .orb-1 {
            width: 280px;
            height: 280px;
            top: -80px;
            right: -60px;
            background: rgba(37, 99, 235, 0.08);
        }

        .orb-2 {
            width: 220px;
            height: 220px;
            bottom: 10%;
            left: -70px;
            background: rgba(124, 58, 237, 0.07);
            animation-delay: -3s;
        }

        .orb-3 {
            width: 140px;
            height: 140px;
            bottom: 35%;
            right: 15%;
            background: rgba(59, 130, 246, 0.06);
            animation-delay: -5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-18px) scale(1.04);
            }
        }

        .info-inner {
            position: relative;
            z-index: 2;
            max-width: 520px;
            text-align: start;
        }

        .info-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 48px;
        }

        .info-brand img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            background: transparent;
            padding: 0;
        }

        /* ── Language switcher ── */
        .language-switcher {
            position: fixed;
            top: 24px;
            inset-inline-end: 32px;
            z-index: 1000;
            width: auto;
        }

        .info-brand-text span {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
        }

        [dir="ltr"] .info-brand-text span {
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .info-brand-text strong {
            display: block;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--brand-dark);
        }

        .info-headline {
            font-size: clamp(28px, 3.2vw, 38px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin: 0 0 16px;
        }

        .info-headline em {
            font-style: normal;
            background: #2563eb;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-tagline {
            font-size: 16px;
            line-height: 1.7;
            color: var(--text-muted);
            margin: 0 0 36px;
            max-width: 440px;
        }

        .feature-carousel {
            display: grid;
            grid-template-columns: 1fr;
        }

        .feature-slide {
            grid-area: 1 / 1;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s ease;
            pointer-events: none;
        }

        .feature-slide.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .feature-card {
            display: flex;
            gap: 18px;
            align-items: flex-start;
            padding: 22px 24px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid var(--border);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }

        .feature-icon {
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            background: #2563eb;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }

        .feature-body h3 {
            margin: 0 0 6px;
            font-size: 17px;
            font-weight: 700;
            color: var(--brand-dark);
        }

        .feature-body p {
            margin: 0;
            font-size: 14px;
            line-height: 1.65;
            color: var(--text-muted);
        }

        .feature-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 28px;
        }

        .feature-dots {
            display: flex;
            gap: 8px;
        }

        .feature-dots button {
            width: 8px;
            height: 8px;
            padding: 0;
            border: none;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .feature-dots button.active {
            width: 28px;
            border-radius: 4px;
            background: var(--brand-primary);
        }

        /* ── Right login side ── */
        .login-side {
            flex: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 32px 48px;
            background: transparent;
        }

        .login-card-wrap {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--surface);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow:
                0 1px 3px rgba(15, 23, 42, 0.04),
                0 20px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 6px;
            letter-spacing: -0.02em;
            color: var(--brand-dark);
        }

        .login-header p {
            margin: 0;
            font-size: 15px;
            color: var(--text-muted);
        }

        .alert-success,
        .alert-danger {
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: start;
        }

        [dir="rtl"] .auth-layout .login-card form {
            direction: rtl;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            text-align: start;
        }

        .input-wrap {
            position: relative;
        }

        .auth-layout .form-control {
            width: 100%;
            border: 1.5px solid var(--border) !important;
            border-radius: 12px !important;
            padding-block: 13px !important;
            padding-inline-start: 16px !important;
            padding-inline-end: 46px !important;
            height: auto !important;
            font-size: 15px;
            font-family: inherit;
            color: var(--brand-dark);
            background: #fafbfc !important;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            text-align: start;
        }

        [dir="rtl"] .auth-layout input[type="email"] {
            direction: ltr;
            text-align: right;
        }

        [dir="rtl"] .auth-layout input[type="password"] {
            direction: rtl;
            text-align: right;
        }

        [dir="rtl"] .auth-layout .form-control {
            direction: rtl;
        }

        [dir="rtl"] .auth-layout input[type="email"].form-control {
            direction: ltr;
        }

        .auth-layout .form-control::placeholder {
            color: #94a3b8;
        }

        .auth-layout .form-control:focus {
            outline: none;
            border-color: var(--brand-primary) !important;
            background: #fff !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            inset-inline-end: 16px;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
        }

        .password-toggle {
            pointer-events: auto;
            cursor: pointer;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: var(--brand-primary);
        }

        .auth-layout .btn-primary {
            background: linear-gradient(135deg, #0069ff 0%, #2563eb 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 14px !important;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .auth-layout .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(0, 105, 255, 0.2);
        }

        .login-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: #94a3b8;
        }

        /* ── Language switcher ── */

        .lang-dropdown-wrapper {
            position: relative;
        }

        .lang-dropdown-btn {
            background: transparent;
            border: none;
            padding: 0;
            width: 100%;
            cursor: pointer;
            font-family: inherit;
        }

        .custom-badge {
            background: #ffffff;
            color: #475569;
            padding: 7px 13px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid var(--border);
            font-size: 13px;
            font-weight: 500;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            white-space: nowrap;
        }

        .lang-dropdown-btn:hover .custom-badge {
            border-color: #cbd5e1;
            background: #f8fafc;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
            color: var(--brand-primary);
        }

        .custom-badge .fa-chevron-down {
            margin-inline-start: 4px;
            font-size: 10px;
            color: #94a3b8;
        }

        .lang-dropdown-content {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            inset-inline-end: 0;
            min-width: 150px;
            width: max-content;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.12);
            border: 1px solid var(--border);
            z-index: 1001;
            overflow: hidden;
            animation: slideDown 0.2s ease;
        }

        .lang-dropdown-content.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lang-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            text-decoration: none;
            color: #334155;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s;
        }

        .lang-option:hover {
            background: #f8fafc;
            color: var(--brand-primary);
        }

        .lang-option.active {
            background: #eff6ff;
            color: var(--brand-primary);
            font-weight: 600;
        }

        .lang-option .fa-check {
            margin-inline-start: auto;
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {

            .auth-layout-ltr,
            .auth-layout-rtl {
                flex-direction: column !important;
            }

            .info-panel {
                min-height: auto;
                padding: 36px 28px 28px;
            }

            .login-side {
                padding: 28px 20px 40px;
            }

            .login-card {
                padding: 32px 24px;
            }
        }

        @media (max-width: 480px) {
            .info-headline {
                font-size: 26px;
            }
        }
    </style>
</head>

<body class="auth-body">
    <div class="auth-page-bg">
        <div class="info-grid"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <div class="language-switcher">
        <div class="lang-dropdown-wrapper">
            <button class="lang-dropdown-btn" onclick="toggleLangDropdown(event)" type="button"
                aria-label="Language">
                <div class="custom-badge">
                    @if (app()->getLocale() == 'ar')
                        <span class="flag-icon flag-icon-sa"></span>
                        <span>{{ __('auth.lang_arabic') }}</span>
                    @elseif(app()->getLocale() == 'au')
                        <span class="flag-icon flag-icon-pk"></span>
                        <span>{{ __('auth.lang_urdu') }}</span>
                    @else
                        <span class="flag-icon flag-icon-gb"></span>
                        <span>{{ __('auth.lang_english') }}</span>
                    @endif
                    <i class="fas fa-chevron-down"></i>
                </div>
            </button>
            <div class="lang-dropdown-content" id="langDropdown">
                <a href="{{ url('lang/en') }}"
                    class="lang-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-gb"></span>
                    <span>{{ __('auth.lang_english') }}</span>
                    @if (app()->getLocale() == 'en')
                        <i class="fas fa-check text-success"></i>
                    @endif
                </a>
                <a href="{{ url('lang/ar') }}"
                    class="lang-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-sa"></span>
                    <span>{{ __('auth.lang_arabic') }}</span>
                    @if (app()->getLocale() == 'ar')
                        <i class="fas fa-check text-success"></i>
                    @endif
                </a>
                <a href="{{ url('lang/au') }}"
                    class="lang-option {{ app()->getLocale() == 'au' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-pk"></span>
                    <span>{{ __('auth.lang_urdu') }}</span>
                    @if (app()->getLocale() == 'au')
                        <i class="fas fa-check text-success"></i>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <div class="auth-layout {{ in_array(app()->getLocale(), ['ar', 'au']) ? 'auth-layout-rtl' : 'auth-layout-ltr' }}">

        {{-- Left info panel --}}
        <aside class="info-panel">

            <div class="info-inner">
                <div class="info-brand">
                    <img src="{{ url('dist/img/hr_logo-.png') }}" alt="HR Logo">
                    <div class="info-brand-text">
                        <span>{{ __('auth.login_brand_subtitle') }}</span>
                        <strong>{{ __('auth.login_brand_title') }}</strong>
                    </div>
                </div>

                <h2 class="info-headline">
                    {{ __('auth.login_headline_before') }}
                    <em>{{ __('auth.login_headline_highlight') }}</em>@if (filled(__('auth.login_headline_after')))
                    {{ ' ' . __('auth.login_headline_after') }}@endif
                </h2>
                <p class="info-tagline">
                    {{ __('auth.login_tagline') }}
                </p>

                <div class="feature-carousel" id="featureCarousel">
                    <div class="feature-slide active">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-building"></i></div>
                            <div class="feature-body">
                                <h3>{{ __('auth.login_slide_1_title') }}</h3>
                                <p>{{ __('auth.login_slide_1_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slide">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
                            <div class="feature-body">
                                <h3>{{ __('auth.login_slide_2_title') }}</h3>
                                <p>{{ __('auth.login_slide_2_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slide">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="feature-body">
                                <h3>{{ __('auth.login_slide_3_title') }}</h3>
                                <p>{{ __('auth.login_slide_3_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slide">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-money-check-alt"></i></div>
                            <div class="feature-body">
                                <h3>{{ __('auth.login_slide_4_title') }}</h3>
                                <p>{{ __('auth.login_slide_4_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slide">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fas fa-bullhorn"></i></div>
                            <div class="feature-body">
                                <h3>{{ __('auth.login_slide_5_title') }}</h3>
                                <p>{{ __('auth.login_slide_5_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="feature-nav">
                    <div class="feature-dots" id="featureDots">
                        <button type="button" class="active" onclick="goToSlide(0)" aria-label="1"></button>
                        <button type="button" onclick="goToSlide(1)" aria-label="2"></button>
                        <button type="button" onclick="goToSlide(2)" aria-label="3"></button>
                        <button type="button" onclick="goToSlide(3)" aria-label="4"></button>
                        <button type="button" onclick="goToSlide(4)" aria-label="5"></button>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Right login form --}}
        <main class="login-side">
            <div class="login-card-wrap">
                <div class="login-card">
                    <div class="login-header">
                        <h1>{{ __('auth.sign_in') }}</h1>
                        <p>{{ __('auth.sign_in_message') }}</p>
                    </div>

                    @if (!empty(session('success')))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ url('login_post') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="email">{{ __('auth.email') }}</label>
                            <div class="input-wrap">
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="{{ __('auth.email') }}" required>
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">{{ __('auth.password') }}</label>
                            <div class="input-wrap">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="{{ __('auth.password') }}" required>
                                <span class="input-icon password-toggle"
                                    onclick="togglePassword('password', 'eyeIcon')">
                                    <i id="eyeIcon" class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">{{ __('auth.sign_in') }}</button>
                    </form>

                    <div class="login-footer">
                        &copy; {{ date('Y') }} {{ __('auth.login_footer') }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ url('dist/js/eye.js') }}"></script>

    <script>
        function toggleLangDropdown(event) {
            event.stopPropagation();
            document.getElementById('langDropdown').classList.toggle('show');
        }

        document.addEventListener('click', function (event) {
            const switcher = document.querySelector('.language-switcher');
            if (switcher && !switcher.contains(event.target)) {
                document.getElementById('langDropdown').classList.remove('show');
            }
        });

        document.getElementById('langDropdown').addEventListener('click', function (event) {
            event.stopPropagation();
        });

        const slides = document.querySelectorAll('.feature-slide');
        const dots = document.querySelectorAll('#featureDots button');
        let currentSlide = 0;
        let slideTimer = null;
        const SLIDE_DURATION = 6000;

        function goToSlide(index) {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            currentSlide = index;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
            resetAutoSlide();
        }

        function nextSlide() {
            goToSlide((currentSlide + 1) % slides.length);
        }

        function resetAutoSlide() {
            clearInterval(slideTimer);
            slideTimer = setInterval(nextSlide, SLIDE_DURATION);
        }

        resetAutoSlide();
    </script>
</body>

</html>