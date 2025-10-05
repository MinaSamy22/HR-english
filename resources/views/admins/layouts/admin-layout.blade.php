<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin dashboard for company management">
    <title>@yield('title', __('Admin-Interface.page_title'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if(app()->getLocale() == 'ar' || app()->getLocale() == 'au')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    {{-- my css --}}
    <link rel="stylesheet" href="{{ url('../dist/css/admin-home.css') }}">

    <style>
        /* RTL/LTR Support */
        body {
            font-family: {{ app()->getLocale() == 'ar' || app()->getLocale() == 'au' ? "'Cairo', sans-serif" : "'Inter', sans-serif" }};
        }

        [dir="rtl"] .header h1 i,
        [dir="rtl"] .quick-actions h2 i,
        [dir="rtl"] .chart-container h2 i,
        [dir="rtl"] .action-group h3 i {
            margin-right: 0;
            margin-left: 8px;
        }

        /* Language Switcher Styles */
        .language-switcher {
            position: relative;
            display: inline-block;
            z-index: 9999;
        }

        .lang-dropdown-btn {
            background: transparent;
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
        }

        .custom-badge {
            background: white;
            color: #4a5568;
            padding: 8px 14px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .lang-dropdown-btn:hover .custom-badge {
            border-color: #cbd5e0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
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
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            z-index: 10000;
            overflow: hidden;
            animation: slideDown 0.3s ease;
            border: 1px solid rgba(0, 0, 0, .15);
        }

        [dir="ltr"] .lang-dropdown-content {
            left: 0;
        }

        [dir="rtl"] .lang-dropdown-content {
            right: 0;
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
        }

        /* Prevent layout shift */
        .header {
            position: relative;
            z-index: 1;
        }

        .stats-grid {
            position: relative;
            z-index: 0;
        }

        @yield('styles')
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-tachometer-alt" style="margin-right: 8px; color: #667eea;"></i>
                @yield('page_title', __('Admin-Interface.control_panel'))
            </h1>
            <div class="user-info">
                <!-- 1. Welcome Message -->
                <span style="color: #4a5568; font-weight: 500; font-size: 14px;">
                    {{ __('Admin-Interface.welcome') }}, {{ Auth::guard('admin')->user()->name ?? __('admin.administrator') }}
                </span>

                <!-- 2. Language Switcher -->
                <div class="language-switcher">
                    <button class="lang-dropdown-btn" onclick="toggleLangDropdown(event)">
                        <div class="custom-badge">
                            @if(app()->getLocale() == 'ar')
                                <span class="flag-icon flag-icon-sa"></span>
                                <span class="font-weight-bold">عربي</span>
                            @elseif(app()->getLocale() == 'au')
                                <span class="flag-icon flag-icon-pk"></span>
                                <span class="font-weight-bold">اردو</span>
                            @else
                                <span class="flag-icon flag-icon-gb"></span>
                                <span class="font-weight-bold">EN</span>
                            @endif
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

                <!-- 3. Logout Button -->
                <a href="{{ route('logout') }}" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    {{ __('Admin-Interface.logout') }}
                </a>
            </div>
        </div>

        <!-- Page Content -->
        @yield('content')
    </div>

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

    @yield('scripts')
</body>

</html>
