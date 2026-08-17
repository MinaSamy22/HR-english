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
    <link rel="stylesheet" href="{{ url('dist/css/login.css') }}">
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

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
            <button class="lang-dropdown-btn" onclick="toggleLangDropdown(event)" type="button" aria-label="Language">
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
                <a href="{{ url('lang/en') }}" class="lang-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-gb"></span>
                    <span>{{ __('auth.lang_english') }}</span>
                    @if (app()->getLocale() == 'en')
                        <i class="fas fa-check text-success"></i>
                    @endif
                </a>
                <a href="{{ url('lang/ar') }}" class="lang-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-sa"></span>
                    <span>{{ __('auth.lang_arabic') }}</span>
                    @if (app()->getLocale() == 'ar')
                        <i class="fas fa-check text-success"></i>
                    @endif
                </a>
                <a href="{{ url('lang/au') }}" class="lang-option {{ app()->getLocale() == 'au' ? 'active' : '' }}">
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

                    <form action="{{ url('login_post') }}" method="post" id="loginForm">
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

                        <button type="submit" class="btn btn-primary btn-block" id="submitBtn">{{ __('auth.sign_in') }}</button>
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

        // Prevent double submit on login form
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        let isSubmitting = false;

        loginForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("auth.sign_in") }}...';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
        });

        // Re-enable button if user goes back
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __("auth.sign_in") }}';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        });
    </script>
</body>

</html>