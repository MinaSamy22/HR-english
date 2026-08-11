<!-- Preloader -->
<!-- CSS Link (add inside <head>) -->
<link rel="stylesheet" href="{{ asset('dist/css/darkmode.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
<!-- Inter Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- FontAwesome 5 (if not already loaded) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    .dropdown-item.active,
    .dropdown-item:active {
        background-color: #e3f2fd !important;
        color: #1976d2 !important;
    }

    .dropdown-item:hover {
        background-color: #f5f5f5 !important;
    }

    /* Desktop positioning - Always align dropdown to screen edge depending on direction */
    [dir="ltr"] .notif-dropdown {
        right: 0 !important;
        left: auto !important;
    }

    [dir="rtl"] .notif-dropdown {
        left: 0 !important;
        right: auto !important;
        text-align: right;
    }

    .notif-dropdown {
        width: 340px;
        border-radius: 0.75rem;
        padding: 0;
        border: 1px solid rgba(0, 0, 0, .15);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175);
    }

    .notif-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .notif-dropdown .icon-wrapper {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* --- Icon alignment for LTR/RTL --- */
    [dir="ltr"] .notif-item .icon-wrapper {
        margin-right: 0.75rem;
        margin-left: 0;
    }

    [dir="ltr"] .notif-item .notif-text {
        text-align: left;
    }

    [dir="rtl"] .notif-item .icon-wrapper {
        margin-left: 0.75rem;
        margin-right: 0;
    }

    [dir="rtl"] .notif-item .notif-text {
        text-align: right;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .notif-dropdown {
            /* Center the dropdown on mobile */
            position: fixed !important;
            top: 60px !important;
            /* Adjust based on your navbar height */
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            width: calc(100vw - 20px) !important;
            /* Full width minus padding */
            max-width: 350px !important;
            z-index: 1050;
            margin: 0 10px;
        }

        /* Override both LTR and RTL positioning for mobile centering */
        [dir="ltr"] .notif-dropdown {
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
        }

        [dir="rtl"] .notif-dropdown {
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
        }

        /* Adjust notification content for mobile */
        .notif-dropdown .dropdown-header {
            padding: 0.75rem 1rem;
        }

        .notif-dropdown .dropdown-item {
            padding: 0.75rem 1rem;
        }

        .notif-item .icon-wrapper {
            width: 24px;
            height: 24px;
        }

        .notif-item .notif-text {
            font-size: 0.875rem;
        }

        /* Reduce margins on mobile */
        [dir="ltr"] .notif-item .icon-wrapper {
            margin-right: 0.5rem;
        }

        [dir="rtl"] .notif-item .icon-wrapper {
            margin-left: 0.5rem;
        }
    }

    /* Extra small screens */
    @media (max-width: 576px) {
        .notif-dropdown {
            width: calc(100vw - 10px) !important;
            margin: 0 5px;
            border-radius: 0.5rem;
        }

        .notif-dropdown .dropdown-header h6 {
            font-size: 0.9rem;
        }

        .notif-item .notif-text div {
            font-size: 0.8rem !important;
        }

        .notif-item .notif-text .text-muted {
            font-size: 0.7rem !important;
        }
    }

    /* Ensure dropdown arrow positioning on mobile */
    @media (max-width: 768px) {
        .dropdown-menu::before {
            display: none !important;
        }
    }

    /* Optional: Add backdrop for mobile */
    @media (max-width: 768px) {
        .dropdown-menu.show {
            backdrop-filter: blur(2px);
        }
    }
</style>

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
        {{-- https://hrar.prosofteg.com/admin/dashboard --}}


        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="languageDropdown"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <div class="badge d-flex align-items-center custom-badge">
                    @if (app()->getLocale() == 'ar')
                        <span class="flag-icon flag-icon-sa mr-1"></span>
                        <span class="font-weight-bold">عربي</span>
                    @elseif(app()->getLocale() == 'au')
                        <span class="flag-icon flag-icon-pk mr-1"></span>
                        <span class="font-weight-bold">اردو</span>
                    @else
                        <span class="flag-icon flag-icon-gb mr-1"></span>
                        <span class="font-weight-bold">EN</span>
                    @endif
                </div>


            </a>

            <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="languageDropdown">

                <!-- English -->
                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                    href="{{ url('lang/en') }}">
                    <span class="flag-icon flag-icon-gb mr-2"></span> English
                    @if (app()->getLocale() == 'en')
                        <i class="fas fa-check ml-auto text-success"></i>
                    @endif
                </a>

                <!-- Arabic -->
                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'ar' ? 'active' : '' }}"
                    href="{{ url('lang/ar') }}">
                    <span class="flag-icon flag-icon-sa mr-2"></span> العربية
                    @if (app()->getLocale() == 'ar')
                        <i class="fas fa-check ml-auto text-success"></i>
                    @endif
                </a>

                <!-- Urdu -->
                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'ur' ? 'active' : '' }}"
                    href="{{ url('lang/au') }}">
                    <span class="flag-icon flag-icon-pk mr-2"></span> اردو
                    @if (app()->getLocale() == 'au')
                        <i class="fas fa-check ml-auto text-success"></i>
                    @endif
                </a>
            </div>

        </li>


        @php
            $pendingRequestsCount = getPendingRequestsCount();
            $notifications = getPendingNotifications(10); // Get latest 10 notifications
        @endphp

        <li class="nav-item dropdown">
            <a class="nav-link position-relative p-2" href="#" id="notifDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-lg text-secondary"></i>

                @if ($pendingRequestsCount > 0)
                    <span class="badge badge-danger position-absolute"
                        style="
                    top: 2px;
                    right: 2px;
                    font-size: 0.65rem;
                    padding: 0.25em 0.5em;
                    border-radius: 8px;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.15);
                ">
                        {{ $pendingRequestsCount > 99 ? '99+' : $pendingRequestsCount }}
                    </span>
                @endif
            </a>

            <!-- Dropdown menu -->
            <div class="dropdown-menu shadow notif-dropdown" aria-labelledby="notifDropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <h6 class="mb-0 font-weight-bold">{{ __('dashboard.notifications') }}</h6>
                    @if ($pendingRequestsCount > 0)
                        <span class="badge badge-light text-muted">
                            {{ $pendingRequestsCount }} {{ __('dashboard.pending') }}
                        </span>
                    @endif
                </div>

                <div style="max-height: 350px; overflow-y: auto;">
                    @forelse ($notifications as $notification)
                        <a class="dropdown-item py-2 border-bottom small" href="{{ $notification['url'] ?? '#' }}"
                            style="white-space: normal;">
                            <div class="d-flex align-items-start notif-item">
                                <div class="icon-wrapper flex-shrink-0">
                                    <i class="{{ $notification['icon'] }} {{ $notification['color'] }}"
                                        style="font-size: 1rem;"></i>
                                </div>
                                <div class="flex-grow-1 notif-text">
                                    <div class="font-weight-normal" style="font-size: 0.9rem;">{!! $notification['message'] !!}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $notification['date']->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="dropdown-item text-muted small text-center py-3">
                            <i class="fas fa-bell-slash mb-2 d-block"></i>
                            {{ __('dashboard.no_pending_notifications') }}
                        </div>
                    @endforelse
                </div>

                {{-- <div class="dropdown-footer text-center border-top">
            <a href="{{ url('admin/Requests') }}" class="dropdown-item small text-primary py-2">
                {{ __('dashboard.view_all') }}
            </a>
        </div> --}}
            </div>
        </li>






        <!-- Moon Icon for Dark Mode Toggle -->
        {{-- <li class="nav-item">
            <a class="nav-link dark-mode-toggle" role="button">
                <i class="nav-icon fa fa-moon" style="color: #908a8a;"></i>
            </a>
        </li> --}}

        <li class="nav-item">
            <a href="{{ url('admin/do') }}" class="nav-link" role="button">
                <i class="nav-icon fa fa-tasks"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/charts') }}" class="nav-link" role="button">
                <i class="nav-icon fa fa-chart-bar"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/calendar') }}" class="nav-link" role="button">
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
        <img src="{{ url('/dist/img/hr_logo-.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ __('dashboard.human_resource') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('/dist/img/admin.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">



                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}"
                        class="nav-link @if (Request::segment(2) == 'dashboard' ||
                                Request::segment(2) == 'calendar' ||
                                Request::segment(2) == 'charts' ||
                                Request::segment(2) == 'do') active @endif">
                        <i class="nav-icon fa fa-tachometer-alt"></i>
                        <p>{{ __('dashboard.dashboard') }}</p>
                    </a>
                </li>



                <li class="nav-header">{{ __('dashboard.main_info') }}</li>


                <!-- Employees -->
                @if (hr_can('employees'))
                    <li class="nav-item">
                        <a href="{{ url('admin/employees') }}"
                            class="nav-link @if (Request::segment(2) == 'employees') active @endif">
                            <i class="nav-icon fa fa-users"></i>
                            <p>{{ __('dashboard.employees') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Managers -->
                @if (hr_can('managers'))
                    <li class="nav-item">
                        <a href="{{ url('admin/manager') }}"
                            class="nav-link @if (Request::segment(2) == 'manager') active @endif">
                            <i class="nav-icon fa fa-user"></i>
                            <p>{{ __('dashboard.managers') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Administration -->
                @if (hr_can('administrations'))
                    <li class="nav-item">
                        <a href="{{ url('admin/administration') }}"
                            class="nav-link @if (Request::segment(2) == 'administration') active @endif">
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>{{ __('dashboard.administrations') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Departments -->
                @if (hr_can('departments'))
                    <li class="nav-item">
                        <a href="{{ url('admin/department') }}"
                            class="nav-link @if (Request::segment(2) == 'department') active @endif">
                            <i class="nav-icon fa fa-building"></i>
                            <p>{{ __('dashboard.departments') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Branches -->
                @if (hr_can('branches'))
                    @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                        <li class="nav-item">
                            <a href="{{ url('admin/branches') }}"
                                class="nav-link @if (Request::segment(2) == 'branches') active @endif">
                                <i class="nav-icon fas fa-code-branch text-white"></i>
                                <p>{{ __('dashboard.branches') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                <!-- Jobs -->
                @if (hr_can('jobs'))
                    <li class="nav-item">
                        <a href="{{ url('admin/jobs') }}"
                            class="nav-link @if (Request::segment(2) == 'jobs') active @endif">
                            <i class="nav-icon fa fa-briefcase"></i>
                            <p>{{ __('dashboard.jobs') }}</p>
                        </a>
                    </li>
                @endif


                <li class="nav-header">{{ __('dashboard.communication') }}</li>
                <!-- News -->
                @if (hr_can('news'))
                    <li class="nav-item">
                        <a href="{{ url('admin/news') }}"
                            class="nav-link @if (Request::segment(2) == 'news') active @endif">
                            <i class="nav-icon fa fa-bullhorn"></i>
                            <p>{{ __('dashboard.company_news') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Employee Requests -->
                @if (hr_can('requests'))
                    @php
                        $pendingRequestsCount = getPendingRequestsCount();
                    @endphp

                    <li class="nav-item">
                        <a href="{{ url('admin/Requests') }}"
                            class="nav-link @if (Request::segment(2) == 'Requests') active @endif">
                            <i class="nav-icon fa fa-envelope"></i>
                            <p>
                                {{ __('dashboard.requests') }}
                                @if ($pendingRequestsCount > 0)
                                    <span class="badge badge-danger right">{{ $pendingRequestsCount }}</span>
                                @endif
                            </p>
                        </a>
                    </li>
                @endif

                <!-- Messages -->
                @if (hr_can('messages'))
                    <li class="nav-item">
                        <a href="{{ url('admin/messages') }}"
                            class="nav-link @if (Request::segment(2) == 'messages') active @endif">
                            <i class="nav-icon fas fa-paper-plane"></i>
                            <p>{{ __('h_message.messages') }}</p>
                        </a>
                    </li>
                @endif


                {{-- Performance --}}
                @if (hr_can('performance') || hr_can('performance_criteria'))
                    <li class="nav-item has-treeview
    @if (Request::segment(2) == 'performance' || Request::segment(2) == 'performance-criteria') menu-open @endif">

                        <a href="#" class="nav-link
        @if (Request::segment(2) == 'performance' || Request::segment(2) == 'performance-criteria') active @endif">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                {{ __('dashboard.performance') }}
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @if (hr_can('performance_criteria'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/performance-criteria') }}"
                                        class="nav-link @if (Request::segment(2) == 'performance-criteria') active @endif">
                                        <i class="fa fa-cogs nav-icon"></i>
                                        {{ __('dashboard.criteria') }}
                                    </a>
                                </li>
                            @endif

                            @if (hr_can('performance'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/performance') }}"
                                        class="nav-link @if (Request::segment(2) == 'performance') active @endif">
                                        <i class="fa fa-list nav-icon"></i>
                                        {{ __('dashboard.performance') }}
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif




                <li class="nav-header">{{ __('dashboard.attendance_payroll') }}</li>



                {{-- Attendance --}}
                @if (hr_can('attendance') || hr_can('attendance_reports') || hr_can('biometer_excel'))
                    <li class="nav-item has-treeview
    @if (in_array(Request::segment(2), ['attendance', 'reports', 'biometer-excel'])) menu-open @endif">

                        <a href="#" class="nav-link
        @if (in_array(Request::segment(2), ['attendance', 'reports', 'biometer-excel'])) active @endif">
                            <i class="nav-icon fa fa-calendar-check"></i>
                            <p>{{ __('dashboard.attendance') }}<i class="right fa fa-angle-left"></i></p>
                        </a>

                        <ul class="nav nav-treeview">

                            @if (hr_can('attendance'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/attendance') }}"
                                        class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                                        <i class="nav-icon fa fa-calendar-alt"></i>
                                        <p>{{ __('dashboard.attendance_manually') }}</p>
                                    </a>
                                </li>
                            @endif

                            @if (hr_can('attendance_reports'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/reports') }}"
                                        class="nav-link @if (Request::segment(2) == 'reports') active @endif">
                                        <i class="nav-icon fa fa-file-alt"></i>
                                        <p>{{ __('dashboard.attendance_reports') }}</p>
                                    </a>
                                </li>
                            @endif

                            @if (hr_can('biometer_excel'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/biometer-excel') }}"
                                        class="nav-link @if (Request::segment(2) == 'biometer-excel') active @endif">
                                        <i class="fas fa-file-excel nav-icon"></i>
                                        <p>{{ __('dashboard.biometer_excel') }}</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                {{-- Taxes & Insurance --}}
                @if (hr_can('taxes') || hr_can('insurance'))
                    <li class="nav-item has-treeview
    @if (Request::segment(2) == 'taxes' || Request::segment(2) == 'insurance') menu-open @endif">

                        <a href="#" class="nav-link
        @if (Request::segment(2) == 'taxes' || Request::segment(2) == 'insurance') active @endif">
                            <i class="nav-icon fa fa-file-invoice-dollar"></i>
                            <p>{{ __('dashboard.tax_insurance') }}<i class="right fa fa-angle-left"></i></p>
                        </a>

                        <ul class="nav nav-treeview">

                            @if (hr_can('taxes'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/taxes') }}"
                                        class="nav-link @if (Request::segment(2) == 'taxes') active @endif">
                                        <i class="fa fa-calculator nav-icon"></i>
                                        <p>{{ __('dashboard.taxes') }}</p>
                                    </a>
                                </li>
                            @endif

                            @if (hr_can('insurance'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/insurance') }}"
                                        class="nav-link @if (Request::segment(2) == 'insurance') active @endif">
                                        <i class="fa fa-medkit nav-icon"></i>
                                        <p>{{ __('dashboard.insurance') }}</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif




                <!-- deductions -->
                @if (hr_can('deductions'))
                    <li class="nav-item">
                        <a href="{{ url('admin/deductions') }}"
                            class="nav-link @if (Request::segment(2) == 'deductions') active @endif">
                            <i class="nav-icon fa fa-exclamation-circle	"></i>
                            <p>{{ __('dashboard.deductions') }}</p>
                        </a>
                    </li>
                @endif


                <!-- Vacations -->
                @if (hr_can('vacations'))
                    <li class="nav-item">
                        <a href="{{ url('admin/vacations') }}"
                            class="nav-link @if (Request::segment(2) == 'vacations') active @endif">
                            <i class="nav-icon fa fa-umbrella-beach"></i>
                            <p> {{ __('dashboard.vacations') }} </p>
                        </a>
                    </li>
                @endif

                @if (hr_can('bounas'))
                    <!-- Bounas -->
                    <li class="nav-item">
                        <a href="{{ url('admin/bounas') }}"
                            class="nav-link @if (Request::segment(2) == 'bounas') active @endif">
                            <i class="nav-icon fa fa-dollar-sign"></i>
                            <p> {{ __('dashboard.overtime') }} </p>
                        </a>
                    </li>
                @endif


                {{-- Payroll --}}
                @if (hr_can('payroll') || hr_can('payslip') || hr_can('salary_payment'))
                    <li class="nav-item has-treeview
        @if (in_array(Request::segment(2), ['payroll', 'payslip', 'salary-payment'])) menu-open @endif">

                        <a href="#"
                            class="nav-link
            @if (in_array(Request::segment(2), ['payroll', 'payslip', 'salary-payment'])) active @endif">
                            <i class="nav-icon fa fa-coins"></i>
                            <p>
                                {{ __('dashboard.payroll') }}
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            {{-- Calculate Payroll --}}
                            @if (hr_can('payroll'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/payroll') }}"
                                        class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                                        <i class="fa fa-calculator nav-icon"></i>
                                        <p>{{ __('dashboard.calculate_payroll') }}</p>
                                    </a>
                                </li>
                            @endif

                            {{-- Payslip --}}
                            @if (hr_can('payslip'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/payslip') }}"
                                        class="nav-link @if (Request::segment(2) == 'payslip') active @endif">
                                        <i class="fa fa-receipt nav-icon"></i>
                                        <p>{{ __('dashboard.payslip_report') }}</p>
                                    </a>
                                </li>
                            @endif

                            {{-- Salary Payment --}}
                            @if (hr_can('salary_payment'))
                                <li class="nav-item">
                                    <a href="{{ url('admin/salary-payment') }}"
                                        class="nav-link @if (Request::segment(2) == 'salary-payment') active @endif">
                                        <i class="fa fa-money-bill-wave nav-icon"></i>
                                        <p>{{ __('dashboard.salary_payment') }}</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif




                {{-- <li class="nav-item">
                    <a href="{{ url('admin/payroll') }}" class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                        <i class="nav-icon fa fa-coins"></i>
                        <p>Payroll</p>
                    </a>
                </li> --}}

                <li class="nav-header">{{ __('dashboard.settings') }}</li>

                <!-- payroll report -->
                {{-- <li class="nav-item">
                        <a href="{{ url('admin/financial-analysis') }}"
                            class="nav-link @if (Request::segment(2) == 'financial-analysis') active @endif">
                            <i class="nav-icon fas fa-chart-area text-white"></i>
                            <p>{{ __('dashboard.branches') }}</p>
                        </a>
                    </li> --}}
                {{-- Company Policy (Attendance Rule) — Only visible for main branch AND permission --}}
                @if (hr_can('attendance_rule') &&
                        (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1))
                    <li class="nav-item">
                        <a href="{{ url('admin/attendance-rule') }}"
                            class="nav-link @if (Request::segment(2) == 'attendance-rule') active @endif">
                            <i class="fa fa-cogs nav-icon"></i>
                            <p>{{ __('dashboard.company_policy') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Company information -->
                @if (hr_can('company_info'))
                    @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                        <li class="nav-item">
                            <a href="{{ url('admin/company-info') }}"
                                class="nav-link @if (Request::segment(2) == 'company-info') active @endif">
                                <i class="nav-icon fas fa-info-circle "></i>
                                <p>{{ __('dashboard.company_info') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                <!-- locations -->
                @if (hr_can('locations'))
                    <li class="nav-item">
                        <a href="{{ route('locations.index') }}"
                            class="nav-link @if (Request::segment(2) == 'locations') active @endif">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>{{ __('dashboard.employee-locations') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Employee Archeve -->
                @if (hr_can('job_history'))
                    <li class="nav-item">
                        <a href="{{ url('admin/job_history') }}"
                            class="nav-link @if (Request::segment(2) == 'job_history') active @endif">
                            <i class="fa fa-history nav-icon"></i>
                            <p>{{ __('h_job_history.job_history') }}</p>
                        </a>
                    </li>
                @endif



                <!-- My Account -->
                @if (hr_can('my_account'))
                    <li class="nav-item">
                        <a href="{{ url('admin/my_account') }}"
                            class="nav-link @if (Request::segment(2) == 'my_account') active @endif">
                            <i class="nav-icon fa fa-cog"></i>
                            <p>{{ __('dashboard.my_account') }}</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>




<!-- JavaScript Link (add before closing </body> tag) -->
<script src="{{ asset('dist/js/darkmode.js') }}"></script>
