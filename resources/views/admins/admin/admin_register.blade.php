<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.page_title') }}</title>
    {{-- for the tiny logo in top bar --}}
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    @if(app()->getLocale() == 'ar' || app()->getLocale() == 'au')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

    <!-- for Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <!-- mina css -->
    <link rel="stylesheet" href="{{ url('dist/css/mina.css') }}">

    <style>
        body {
            font-family: {{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? "'Cairo', sans-serif" : "'Source Sans Pro', sans-serif" }};
        }

        /* Language Switcher Styles */
        .language-switcher {
            position: relative;
            display: inline-block;
            z-index: 9999;
            margin-bottom: 20px;
        }

        .lang-dropdown-btn {
            background: white;
            color: #4a5568;
            padding: 0;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            white-space: nowrap;
            width: 100%;
        }

        .custom-badge {
            background: #f8f9fa;
            color: #4a5568;
            padding: 10px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            width: 100%;
        }

        .lang-dropdown-btn:hover .custom-badge {
            border-color: #cbd5e0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            background: #e9ecef;
        }

        [dir="ltr"] .custom-badge .flag-icon {
            margin-right: 4px;
        }

        [dir="rtl"] .custom-badge .flag-icon {
            margin-left: 4px;
        }

        .lang-dropdown-content {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            z-index: 10000;
            overflow: hidden;
            animation: slideDown 0.3s ease;
            border: 1px solid rgba(0, 0, 0, .15);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lang-dropdown-content.show {
            display: block;
        }

        .lang-option {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            text-decoration: none;
            color: #2d3748;
            transition: all 0.2s ease;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            width: 100%;
            background: white;
            cursor: pointer;
        }

        .lang-option:hover {
            background-color: #f8f9fa;
            color: #667eea;
        }

        .lang-option.active {
            background-color: #e3f2fd;
            color: #1976d2;
            font-weight: 600;
        }

        .lang-option .flag-icon {
            font-size: 20px;
            width: 24px;
        }

        .lang-option .fa-check {
            margin-left: auto;
            font-size: 14px;
        }

        [dir="rtl"] .lang-option .fa-check {
            margin-right: auto;
            margin-left: 0;
        }

        /* RTL Support for form elements */
        [dir="rtl"] .input-group-append {
            margin-left: 0;
            margin-right: -1px;
        }

        [dir="rtl"] .input-group-text {
            border-radius: 0.25rem 0 0 0.25rem;
        }

        [dir="rtl"] .form-control {
            border-radius: 0 0.25rem 0.25rem 0;
        }

        [dir="rtl"] .position-absolute {
            right: auto !important;
            left: 15px !important;
        }

        .login-box-msg {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>{{ __('auth.human') }}</b>{{ __('auth.resource') }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            @include('_message')

            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('auth.register_new_admin') }}</p>

                <!-- Language Switcher -->
                <div class="language-switcher">
                    <button class="lang-dropdown-btn" onclick="toggleLangDropdown(event)" type="button">
                        <div class="custom-badge">
                            @if(app()->getLocale() == 'ar')
                                <span class="flag-icon flag-icon-sa"></span>
                                <span class="font-weight-bold">عربي</span>
                            @elseif(app()->getLocale() == 'au')
                                <span class="flag-icon flag-icon-pk"></span>
                                <span class="font-weight-bold">اردو</span>
                            @else
                                <span class="flag-icon flag-icon-gb"></span>
                                <span class="font-weight-bold">English</span>
                            @endif
                            <i class="fas fa-chevron-down" style="font-size: 12px; margin-left: auto;"></i>
                        </div>
                    </button>
                    <div class="lang-dropdown-content" id="langDropdown">
                        <a href="{{ url('lang/en') }}" class="lang-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                            <span class="flag-icon flag-icon-gb"></span>
                            <span>English</span>
                            @if(app()->getLocale() == 'en')
                                <i class="fas fa-check text-success"></i>
                            @endif
                        </a>
                        <a href="{{ url('lang/ar') }}" class="lang-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                            <span class="flag-icon flag-icon-sa"></span>
                            <span>العربية</span>
                            @if(app()->getLocale() == 'ar')
                                <i class="fas fa-check text-success"></i>
                            @endif
                        </a>
                        <a href="{{ url('lang/au') }}" class="lang-option {{ app()->getLocale() == 'au' ? 'active' : '' }}">
                            <span class="flag-icon flag-icon-pk"></span>
                            <span>اردو</span>
                            @if(app()->getLocale() == 'au')
                                <i class="fas fa-check text-success"></i>
                            @endif
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.register.post') }}" method="post">
                    {{ csrf_field() }}

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="{{ __('auth.name') }}" name="name" required value="{{ old('name') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <span style="color:red;">{{ $errors->first('name') }}</span>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="{{ __('auth.email') }}" name="email" required value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <span style="color:red;">{{ $errors->first('email') }}</span>

                    <div class="form-group position-relative">
                        <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('auth.password') }}" required>
                        <span class="position-absolute" onclick="togglePassword('password', 'eyeIcon1')" style="top: 10px; right: 15px; cursor: pointer;">
                            <i id="eyeIcon1" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <span style="color:red;">{{ $errors->first('password') }}</span>

                    <div class="form-group position-relative">
                        <input type="password" id="confirm_password" name="password_confirmation" class="form-control" placeholder="{{ __('auth.confirm_password') }}" required>
                        <span class="position-absolute" onclick="togglePassword('confirm_password', 'eyeIcon2')" style="top: 10px; right: 15px; cursor: pointer;">
                            <i id="eyeIcon2" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <span style="color:red;">{{ $errors->first('password_confirmation') }}</span>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary"></div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('auth.register') }}</button>
                        </div>
                    </div>
                </form>

                <!-- for going to  -->
                <p class="row">
                    <a href="{{ url('/') }}" class="text-center">{{ __('auth.sign_in') }}</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.min.js') }}"></script>
    <!-- for eye toggle -->
    <script src="{{ url('dist/js/eye.js') }}"></script>

    <script>
        // Language Dropdown Toggle
        function toggleLangDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('langDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('langDropdown');
            const switcher = document.querySelector('.language-switcher');

            if (!switcher.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Prevent dropdown from closing when clicking inside it
        document.getElementById('langDropdown').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>
</body>

</html>
