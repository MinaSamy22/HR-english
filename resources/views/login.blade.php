<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
    dir="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.sign_in') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #007bff;
            --primary-dark: #0069d9;
            --primary-light: #3b82f6;
            --accent: #06b6d4;
            --text-dark: #1e293b;
            --text-mid: #475569;
            --text-light: #94a3b8;
            --border: #e2e8f0;
            --bg-card: #ffffff;
            --radius: 16px;
            --font:
                {{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? "'Cairo', sans-serif" : "'Inter', sans-serif" }}
            ;
        }

        html,
        body {
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: var(--font);
            background: #ffffff;
        }

        @media (min-width: 768px) {
            html,
            body {
                height: 100vh;
                width: 100vw;
                overflow: hidden;
            }
        }

        /* ── Page Layout ── */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            background: #ffffff;
        }

        @media (max-width: 767px) {
            .login-wrapper {
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 24px 16px;
                background: #f8fafc;
                overflow-y: auto;
            }
        }

        @media (min-width: 768px) {
            .login-wrapper {
                height: 100vh;
                width: 100vw;
                overflow: hidden;
            }
        }

        /* ── Left Panel / Scroller Section ── */
        .login-panel {
            display: flex;
            position: relative;
            background: #fafbfc;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 32px 20px 24px;
            color: #1e293b;
            width: 100%;
        }

        @media (max-width: 767px) {
            .login-panel {
                border-bottom: 1px solid #e2e8f0;
                margin-bottom: 20px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
                border: 1px solid #e2e8f0;
                max-width: 420px;
            }

            .panel-logo {
                width: 70px;
                height: 70px;
                margin-bottom: 20px;
                border-radius: 18px;
            }

            .panel-logo img {
                width: 44px;
            }

            .slide-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
                margin-bottom: 14px;
                border-radius: 16px;
            }

            .panel-title {
                font-size: 20px;
                margin-bottom: 8px;
            }

            .panel-subtitle {
                font-size: 13px;
                line-height: 1.6;
            }

            .panel-dots {
                margin-top: 20px;
            }
        }

        @media (min-width: 768px) {
            .login-panel {
                flex: 1;
                border-inline-end: 1px solid #e2e8f0;
                padding: 40px 30px;
                overflow: hidden;
            }
        }

        /* Animated blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            opacity: 0.3;
            animation: float 8s ease-in-out infinite;
        }

        .blob-1 {
            width: 380px;
            height: 380px;
            background: #f1f5f9;
            top: -120px;
            left: -100px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 280px;
            height: 280px;
            background: #f8fafc;
            bottom: -80px;
            right: -80px;
            animation-delay: 3s;
        }

        .blob-3 {
            width: 180px;
            height: 180px;
            background: #f1f5f9;
            bottom: 120px;
            left: 40px;
            animation-delay: 5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-30px) scale(1.05);
            }
        }

        .panel-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 380px;
            margin: auto 0;
        }

        /* Pure logo wrapper without blue background */
        .panel-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
        }

        .panel-logo-wrap img {
            height: 72px;
            width: auto;
            object-fit: contain;
            background: transparent;
        }

        @media (max-width: 767px) {
            .panel-logo-wrap img {
                height: 56px;
            }
        }

        .panel-title {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 14px;
            line-height: 1.3;
            color: #0f172a;
        }

        .panel-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #64748b;
            line-height: 1.75;
            max-width: 320px;
        }

        /* ── Slider ── */
        .panel-slides {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .panel-slide {
            display: none;
            flex-direction: column;
            align-items: center;
            text-align: center;
            animation: slideFadeIn 0.3s ease-out;
        }

        .panel-slide.active {
            display: flex;
        }

        @keyframes slideFadeIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ── Dots ── */
        .panel-dots {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 36px;
        }

        .panel-dots .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            border: none;
            padding: 0;
            transition: all 0.25s ease;
        }

        .panel-dots .dot.active {
            background: #4f46e5;
            width: 28px;
            border-radius: 4px;
        }

        /* ── Right Form Side ── */
        .login-form-side {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 24px 20px;
            background: #ffffff;
        }

        @media (max-width: 767px) {
            .login-form-side {
                max-width: 420px;
                height: auto;
                padding: 32px 24px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
                border: 1px solid #e2e8f0;
                margin: auto;
            }
        }

        @media (min-width: 768px) {
            .login-form-side {
                width: 460px;
                height: 100vh;
                flex-shrink: 0;
                box-shadow: -4px 0 40px rgba(79, 70, 229, 0.08);
                overflow: hidden;
            }
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            margin: auto 0;
        }

        /* Logo (mobile only) */
        .mobile-logo {
            display: none;
        }

        /* Heading */
        .login-heading {
            margin-bottom: 8px;
        }

        .login-heading h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.4px;
        }

        .login-heading p {
            font-size: 14px;
            color: var(--text-light);
            margin-top: 6px;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 22px 0;
        }

        /* Alert */
        .alert-success {
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Language switcher */
        .lang-section {
            margin-bottom: 24px;
        }

        .lang-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }

        .lang-dropdown-wrapper {
            position: relative;
        }

        .lang-dropdown-btn {
            width: 100%;
            background: var(--bg-card);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: var(--font);
        }

        .lang-dropdown-btn:hover,
        .lang-dropdown-btn:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
            outline: none;
        }

        .lang-dropdown-btn .chevron {
            margin-inline-start: auto;
            color: var(--text-light);
            font-size: 12px;
            transition: transform 0.25s;
        }

        .lang-dropdown-btn.open .chevron {
            transform: rotate(180deg);
        }

        .lang-dropdown-content {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            z-index: 9999;
            animation: fadeSlide 0.2s ease;
        }

        .lang-dropdown-content.show {
            display: block;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(-6px);
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
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            background: #fff;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: background 0.15s;
            font-family: var(--font);
        }

        .lang-option:hover {
            background: #f8faff;
        }

        .lang-option.active {
            color: var(--primary);
            background: #eef2ff;
            font-weight: 600;
        }

        .lang-option .check {
            margin-inline-start: auto;
            color: var(--primary);
            font-size: 13px;
        }

        /* Form fields */
        .field-group {
            margin-bottom: 16px;
        }

        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 7px;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap .field-icon {
            position: absolute;
            top: 50%;
            inset-inline-end: 14px;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 15px;
            pointer-events: none;
        }

        .field-wrap .field-icon.clickable {
            pointer-events: all;
            cursor: pointer;
            transition: color 0.2s;
        }

        .field-wrap .field-icon.clickable:hover {
            color: var(--primary);
        }

        .form-input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 12px 42px 12px 14px;
            font-size: 16px;
            color: var(--text-dark);
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: var(--font);
            min-height: 48px;
        }

        [dir="rtl"] .form-input {
            padding: 12px 14px 12px 42px;
        }

        .form-input::placeholder {
            color: var(--text-light);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 13px;
            min-height: 48px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
            font-family: var(--font);
            letter-spacing: 0.02em;
        }

        .btn-login:hover {
            background: #0069d9;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer note */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: var(--text-light);
        }
    </style>
</head>

<body>
    <div class="login-wrapper">

        {{-- ── Left decorative panel ── --}}
        <div class="login-panel">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>

            <div class="panel-content">

                {{-- Fixed Logo --}}
                <div class="panel-logo-wrap">
                    <img src="{{ url('dist/img/hr_logo-.png') }}" alt="HR Logo">
                </div>

                {{-- Slides --}}
                <div class="panel-slides" id="panelSlider">

                    {{-- Slide 1 --}}
                    <div class="panel-slide active">
                        <div class="panel-title">Company Policy<br>& Deductions</div>
                        <p class="panel-subtitle">Set your company policy, deductions system, and working days for your employees.</p>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="panel-slide">
                        <div class="panel-title">Manage Employee<br>Requests</div>
                        <p class="panel-subtitle">Review and manage your employees' requests quickly and efficiently.</p>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="panel-slide">
                        <div class="panel-title">Attendance &<br>Performance</div>
                        <p class="panel-subtitle">Track the attendance and set your employees performance with ease.</p>
                    </div>

                    {{-- Slide 4 --}}
                    <div class="panel-slide">
                        <div class="panel-title">Create<br>Payroll</div>
                        <p class="panel-subtitle">Generate accurate payrolls and payslips for all your employees in just a few clicks.</p>
                    </div>

                </div>

                {{-- Dots --}}
                <div class="panel-dots" id="panelDots">
                    <button type="button" class="dot active" onclick="goToSlide(0)"></button>
                    <button type="button" class="dot" onclick="goToSlide(1)"></button>
                    <button type="button" class="dot" onclick="goToSlide(2)"></button>
                    <button type="button" class="dot" onclick="goToSlide(3)"></button>
                </div>

            </div>
        </div>

        {{-- ── Right form side ── --}}
        <div class="login-form-side">
            <div class="login-card">

                {{-- Mobile logo --}}
                <div class="mobile-logo">
                    <img src="{{ url('dist/img/hr_logo-.png') }}" alt="HR Logo">
                </div>

                {{-- Heading --}}
                <div class="login-heading">
                    <h1>{{ __('auth.sign_in') }}</h1>
                    <p>{{ __('auth.sign_in_message') }}</p>
                </div>

                <div class="divider"></div>

                {{-- Success message --}}
                @if (!empty(session('success')))
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Language switcher --}}
                <div class="lang-section">
                    <div class="lang-dropdown-wrapper">
                        <button class="lang-dropdown-btn" id="langBtn" onclick="toggleLangDropdown(event)"
                            type="button">
                            @if (app()->getLocale() == 'ar')
                                <span class="flag-icon flag-icon-sa"></span>
                                <span>عربي</span>
                            @elseif(app()->getLocale() == 'au')
                                <span class="flag-icon flag-icon-pk"></span>
                                <span>اردو</span>
                            @else
                                <span class="flag-icon flag-icon-gb"></span>
                                <span>English</span>
                            @endif
                            <i class="fas fa-chevron-down chevron"></i>
                        </button>

                        <div class="lang-dropdown-content" id="langDropdown">
                            <a href="{{ url('lang/en') }}"
                                class="lang-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                <span class="flag-icon flag-icon-gb"></span>
                                <span>English</span>
                                @if (app()->getLocale() == 'en') <i class="fas fa-check check"></i> @endif
                            </a>
                            <a href="{{ url('lang/ar') }}"
                                class="lang-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                <span class="flag-icon flag-icon-sa"></span>
                                <span>العربية</span>
                                @if (app()->getLocale() == 'ar') <i class="fas fa-check check"></i> @endif
                            </a>
                            <a href="{{ url('lang/au') }}"
                                class="lang-option {{ app()->getLocale() == 'au' ? 'active' : '' }}">
                                <span class="flag-icon flag-icon-pk"></span>
                                <span>اردو</span>
                                @if (app()->getLocale() == 'au') <i class="fas fa-check check"></i> @endif
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Login form --}}
                <form action="{{ url('login_post') }}" method="post" autocomplete="off">
                    @csrf

                    <div class="field-group">
                        <label class="field-label" for="email">{{ __('auth.email') }}</label>
                        <div class="field-wrap">
                            <input id="email" type="email" name="email" class="form-input"
                                placeholder="{{ __('auth.email') }}" required autocomplete="email">
                            <span class="field-icon"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="password">{{ __('auth.password') }}</label>
                        <div class="field-wrap">
                            <input id="password" type="password" name="password" class="form-input"
                                placeholder="{{ __('auth.password') }}" required autocomplete="current-password">
                            <span class="field-icon clickable" onclick="togglePassword('password', 'eyeIcon')">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">{{ __('auth.sign_in') }}</button>
                </form>

                <div class="login-footer">
                    &copy; {{ date('Y') }} HR Management System
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('dist/js/eye.js') }}"></script>
    <script>
        /* ── Language dropdown ── */
        function toggleLangDropdown(event) {
            event.stopPropagation();
            const btn = document.getElementById('langBtn');
            const dropdown = document.getElementById('langDropdown');
            const isOpen = dropdown.classList.toggle('show');
            btn.classList.toggle('open', isOpen);
        }

        document.addEventListener('click', function () {
            document.getElementById('langDropdown').classList.remove('show');
            document.getElementById('langBtn').classList.remove('open');
        });

        document.getElementById('langDropdown').addEventListener('click', function (e) {
            e.stopPropagation();
        });

        /* ── Auto-sliding panel ── */
        const slides = document.querySelectorAll('.panel-slide');
        const dots = document.querySelectorAll('#panelDots .dot');
        let current = 0;
        let timer;

        function goToSlide(index) {
            if (!slides.length) return;
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (index + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function nextSlide() {
            goToSlide(current + 1);
        }

        function startAuto() {
            clearInterval(timer);
            timer = setInterval(nextSlide, 2500);
        }

        dots.forEach((dot, i) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                goToSlide(i);
                startAuto();
            });
        });

        startAuto();
    </script>
</body>

</html>