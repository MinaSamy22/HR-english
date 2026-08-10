<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.register_new_admin') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">

    @if(app()->getLocale() == 'ar' || app()->getLocale() == 'au')
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
                    @if (app()->getLocale() == 'en')<i class="fas fa-check text-success"></i>@endif
                </a>
                <a href="{{ url('lang/ar') }}" class="lang-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-sa"></span>
                    <span>{{ __('auth.lang_arabic') }}</span>
                    @if (app()->getLocale() == 'ar')<i class="fas fa-check text-success"></i>@endif
                </a>
                <a href="{{ url('lang/au') }}" class="lang-option {{ app()->getLocale() == 'au' ? 'active' : '' }}">
                    <span class="flag-icon flag-icon-pk"></span>
                    <span>{{ __('auth.lang_urdu') }}</span>
                    @if (app()->getLocale() == 'au')<i class="fas fa-check text-success"></i>@endif
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
                    {{ __('auth.register_admin_headline_before') }}
                    <em>{{ __('auth.register_admin_headline_highlight') }}</em>@if (filled(__('auth.register_admin_headline_after')))
                    {{ ' ' . __('auth.register_admin_headline_after') }}@endif
                </h2>
                <p class="info-tagline">
                    {{ __('auth.register_admin_tagline') }}
                </p>

            </div>
        </aside>

        {{-- Right register form --}}
        <main class="login-side">
            <div class="login-card-wrap">
                <div class="login-card">
                    <div class="login-header">
                        <h1>{{ __('auth.register_new_admin') }}</h1>
                        <p>{{ __('auth.register') }}</p>
                    </div>

                    @include('_message')

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('admin.register.post') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">{{ __('auth.name') }}</label>
                            <div class="input-wrap">
                                <input type="text" name="name" class="form-control"
                                    placeholder="{{ __('auth.name') }}" required value="{{ old('name') }}">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                            </div>
                            @if($errors->has('name'))<span class="error-msg" style="color:#dc3545;font-size:13px;margin-top:5px;display:block;">{{ $errors->first('name') }}</span>@endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ __('auth.email') }}</label>
                            <div class="input-wrap">
                                <input type="email" name="email" class="form-control"
                                    placeholder="{{ __('auth.email') }}" required value="{{ old('email') }}">
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            </div>
                            @if($errors->has('email'))<span class="error-msg" style="color:#dc3545;font-size:13px;margin-top:5px;display:block;">{{ $errors->first('email') }}</span>@endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ __('auth.password') }}</label>
                            <div class="input-wrap">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="{{ __('auth.password') }}" required>
                                <span class="input-icon password-toggle" onclick="togglePassword('password', 'eyeIcon1')">
                                    <i id="eyeIcon1" class="fa fa-eye"></i>
                                </span>
                            </div>
                            @if($errors->has('password'))<span class="error-msg" style="color:#dc3545;font-size:13px;margin-top:5px;display:block;">{{ $errors->first('password') }}</span>@endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ __('auth.confirm_password') }}</label>
                            <div class="input-wrap">
                                <input type="password" id="confirm_password" name="password_confirmation" class="form-control"
                                    placeholder="{{ __('auth.confirm_password') }}" required>
                                <span class="input-icon password-toggle" onclick="togglePassword('confirm_password', 'eyeIcon2')">
                                    <i id="eyeIcon2" class="fa fa-eye"></i>
                                </span>
                            </div>
                            @if($errors->has('password_confirmation'))<span class="error-msg" style="color:#dc3545;font-size:13px;margin-top:5px;display:block;">{{ $errors->first('password_confirmation') }}</span>@endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">{{ __('auth.register') }}</button>
                    </form>

                    <div class="login-footer">
                        <a href="{{ url('/') }}" style="color:#2563eb;">{{ __('auth.sign_in') }}</a>
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
        document.addEventListener('click', function(event) {
            const switcher = document.querySelector('.language-switcher');
            if (switcher && !switcher.contains(event.target)) {
                document.getElementById('langDropdown').classList.remove('show');
            }
        });
        document.getElementById('langDropdown').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>
</body>

</html>
