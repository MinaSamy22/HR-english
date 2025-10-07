<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.sign_in') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    @if(app()->getLocale() == 'ar' || app()->getLocale() == 'au')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/mina.css') }}">

    <style>
        body {
            font-family: {{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? "'Cairo', sans-serif" : "'Source Sans Pro', sans-serif" }};
        }

        .login-logo {
            margin-bottom: 10px;
        }

        .login-logo img {
            max-width: 120px;
            height: auto;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .login-card-body {
            padding: 35px;
        }

        .login-box-msg {
            font-size: 16px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 30px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 45px 12px 15px;
            height: auto;
            font-size: 15px;
            transition: all 0.3s;
        }

        [dir="rtl"] .form-control {
            padding: 12px 15px 12px 45px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 16px;
        }

        [dir="rtl"] .input-icon {
            right: auto;
            left: 15px;
        }

        .password-toggle {
            cursor: pointer;
            z-index: 10;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0084ff 0%, #0091ff 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .language-switcher {
            position: relative;
            display: flex;
            justify-content: center;
            z-index: 9999;
            margin-bottom: 20px;
        }

        .lang-dropdown-wrapper {
            position: relative;
            display: inline-block;
            width: auto;
            min-width: 150px;
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

        [dir="rtl"] .input-group-append {
            margin-left: 0;
            margin-right: -1px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ url('dist/img/hr_logo-.png') }}" alt="HR Logo">
        </div>

        <div class="card">
            @include('_message')

            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('auth.sign_in_message') }}</p>

                <div class="language-switcher">
                    <div class="lang-dropdown-wrapper">
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
                                    <i class="fas fa-check text-success" style="margin-left: auto;"></i>
                                @endif
                            </a>
                            <a href="{{ url('lang/ar') }}" class="lang-option {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                <span class="flag-icon flag-icon-sa"></span>
                                <span>العربية</span>
                                @if(app()->getLocale() == 'ar')
                                    <i class="fas fa-check text-success" style="margin-left: auto;"></i>
                                @endif
                            </a>
                            <a href="{{ url('lang/au') }}" class="lang-option {{ app()->getLocale() == 'au' ? 'active' : '' }}">
                                <span class="flag-icon flag-icon-pk"></span>
                                <span>اردو</span>
                                @if(app()->getLocale() == 'au')
                                    <i class="fas fa-check text-success" style="margin-left: auto;"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ url('login_post') }}" method="post">
                    @csrf

                    <div class="form-group position-relative mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('auth.email') }}" required>
                        <span class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>

                    <div class="form-group position-relative mb-4">
                        <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('auth.password') }}" required>
                        <span class="input-icon password-toggle" onclick="togglePassword('password', 'eyeIcon')">
                            <i id="eyeIcon" class="fa fa-eye"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">{{ __('auth.sign_in') }}</button>
                </form>
            </div>
        </div>
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
            const dropdown = document.getElementById('langDropdown');
            const switcher = document.querySelector('.language-switcher');
            if (!switcher.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        document.getElementById('langDropdown').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>
</body>

</html>
