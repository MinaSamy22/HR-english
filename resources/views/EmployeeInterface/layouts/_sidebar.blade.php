<!-- Preloader -->

<!-- CSS Link (add inside <head>) -->
<link rel="stylesheet" href="{{ asset('dist/css/darkmode.css') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">


<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ url('/dist/img/hr_logo-.png') }}" alt="AdminLTE Logo" height="60"
        width="60">
</div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

         <li class="nav-item d-flex align-items-center">
            <a href="{{ url('lang/' . (app()->getLocale() == 'ar' ? 'en' : 'ar')) }}" class="nav-link px-2" role="button"
                title="Switch to Arabic">
                <span class="d-inline-flex align-items-center">
                    <span class="fw-bold d-none d-sm-inline">EN</span> <!-- Hidden on xs -->
                    <span class="mx-1 d-none d-sm-inline">/</span> <!-- Hidden on xs -->
                    <span class="fw-bold">عربي</span> <!-- Always visible -->

                    <!-- Mobile-only icon (globe) -->
                    <i class="fas fa-globe d-sm-none ms-1"></i>
                </span>
            </a>
        </li>



        <!-- Moon Icon for Dark Mode Toggle -->
        <li class="nav-item">
            <a class="nav-link dark-mode-toggle" role="button">
                <i class="nav-icon fa fa-moon" style="color: #908a8a;"></i>
            </a>
        </li>


        <li class="nav-item">
            <a href="{{ url('employee/calendar') }}" class="nav-link" role="button">
                <i class="nav-icon fa fa-calendar-alt"></i>
            </a>
        </li>


        <!-- Logout link -->

        <li class="nav-item">
            <a class="nav-link" href="{{ url('logout') }}">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>


    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <img src="{{ url('/dist/img/hr_logo-.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ __('E_dashboard.employee_panel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('/dist/img/admin.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                @if (Auth::guard('admin')->check())
                    <a class="d-block">{{ Auth::guard('admin')->user()->name }} </a>
                @elseif(Auth::guard('web')->check())
                    <a class="d-block">{{ Auth::guard('web')->user()->name }} </a>
                @elseif(Auth::guard('employee')->check())
                    <a class="d-block">{{ Auth::guard('employee')->user()->name }} </a>
                @elseif(Auth::check())
                    <a class="d-block">{{ Auth::user()->name }} </a>
                @else
                    <a class="d-block">{{ __('E_dashboard.guest_user') }}</a>
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('employee/home') }}"
                        class="nav-link @if (in_array(Request::segment(2), ['home', 'calendar', 'news'])) active @endif">
                        <i class="nav-icon fa fa-home"></i>
                        <p>{{ __('E_dashboard.home') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('E_dashboard.main_information') }}</li>

                <li class="nav-item">
                    <a href="{{ url('employee/payroll') }}"
                        class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                        <i class="nav-icon fa fa-coins"></i>
                        <p>{{ __('E_dashboard.my_payroll') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('employee/attendance') }}"
                        class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                        <i class="nav-icon fa fa-calendar-check"></i>
                        <p>{{ __('E_dashboard.my_attendance') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('E_dashboard.my_requests') }}</li>

                <li class="nav-item">
                    <a href="{{ url('employee/late') }}"
                        class="nav-link @if (Request::segment(2) == 'late') active @endif">
                        <i class="nav-icon fa fa-clock"></i>
                        <p>{{ __('E_dashboard.attendance_request') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('employee/vacation') }}"
                        class="nav-link @if (Request::segment(2) == 'vacation') active @endif">
                        <i class="nav-icon fa fa-umbrella-beach"></i>
                        <p>{{ __('E_dashboard.vacation_request') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('employee/extra') }}"
                        class="nav-link @if (Request::segment(2) == 'extra') active @endif">
                        <i class="nav-icon fa fa-dollar-sign"></i>
                        <p>{{ __('E_dashboard.extra_time_request') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('employee/resignation') }}"
                        class="nav-link @if (Request::segment(2) == 'resignation') active @endif">
                        <i class="nav-icon fa fa-file-signature"></i>
                        <p>{{ __('E_dashboard.resignation_request') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ __('E_dashboard.settings') }}</li>

                <!-- My Account -->
                <li class="nav-item">
                    <a href="{{ url('employee/my_account') }}"
                        class="nav-link @if (Request::segment(2) == 'my_account') active @endif">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>{{ __('E_dashboard.my_account') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>




<!-- JavaScript Link (add before closing </body> tag) -->
<script src="{{ asset('dist/js/darkmode.js') }}"></script>
