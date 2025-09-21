<!-- Preloader -->

<!-- CSS Link (add inside <head>) -->
<link rel="stylesheet" href="{{ asset('dist/css/darkmode.css') }}">
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

                <div class="badge badge-secondary d-flex align-items-center">
                    <i class="fas fa-globe mr-1"></i>
                    <span class="font-weight-bold">
                        {{ app()->getLocale() == 'ar' ? 'عربي' : 'EN' }}
                    </span>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="languageDropdown">



                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                    href="{{ url('lang/en') }}">
                    <span>English</span>
                    @if (app()->getLocale() == 'en')
                        <i class="fas fa-check ml-auto text-success"></i>
                    @endif
                </a>

                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'ar' ? 'active' : '' }}"
                    href="{{ url('lang/ar') }}">
                    <span>العربية</span>
                    @if (app()->getLocale() == 'ar')
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
                <li class="nav-item">
                    <a href="{{ url('admin/employees') }}"
                        class="nav-link @if (Request::segment(2) == 'employees') active @endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>{{ __('dashboard.employees') }}</p>
                    </a>
                </li>



                <!-- Managers -->
                <li class="nav-item">
                    <a href="{{ url('admin/manager') }}"
                        class="nav-link @if (Request::segment(2) == 'manager') active @endif">
                        <i class="nav-icon fa fa-user"></i>
                        <p>{{ __('dashboard.managers') }}</p>
                    </a>
                </li>

                <!-- Administration -->
                <li class="nav-item">
                    <a href="{{ url('admin/administration') }}"
                        class="nav-link @if (Request::segment(2) == 'administration') active @endif">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>{{ __('dashboard.administrations') }}</p>
                    </a>
                </li>

                <!-- Departments -->
                <li class="nav-item">
                    <a href="{{ url('admin/department') }}"
                        class="nav-link @if (Request::segment(2) == 'department') active @endif">
                        <i class="nav-icon fa fa-building"></i>
                        <p>{{ __('dashboard.departments') }}</p>
                    </a>
                </li>

                <!-- Jobs -->
                <li class="nav-item has-treeview @if (Request::segment(2) == 'jobs' || Request::segment(2) == 'job_history') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'jobs' || Request::segment(2) == 'job_history') active @endif">
                        <i class="nav-icon fa fa-briefcase"></i>
                        <p>{{ __('dashboard.jobs') }}<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/jobs') }}"
                                class="nav-link @if (Request::segment(2) == 'jobs') active @endif">
                                <i class="fa fa-list nav-icon"></i>
                                <p>{{ __('dashboard.current_jobs') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/job_history') }}"
                                class="nav-link @if (Request::segment(2) == 'job_history') active @endif">
                                <i class="fa fa-history nav-icon"></i>
                                <p>{{ __('dashboard.job_history') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- News -->
                <li class="nav-item">
                    <a href="{{ url('admin/news') }}"
                        class="nav-link @if (Request::segment(2) == 'news') active @endif">
                        <i class="nav-icon fa fa-bullhorn"></i>
                        <p>{{ __('dashboard.company_news') }}</p>
                    </a>
                </li>

                <!-- Employee Requests -->
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

                <!-- Performance -->
                <li class="nav-item has-treeview
    @if (Request::segment(2) == 'performance' || Request::segment(2) == 'performance-criteria') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'performance' || Request::segment(2) == 'performance-criteria') active @endif">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            {{ __('dashboard.performance') }}
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ url('admin/performance-criteria') }}"
                                class="nav-link @if (Request::segment(2) == 'performance-criteria') active @endif">
                                <i class="fa fa-cogs nav-icon"></i>
                                {{ __('dashboard.criteria') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/performance') }}"
                                class="nav-link @if (Request::segment(2) == 'performance') active @endif">
                                <i class="fa fa-list nav-icon"></i>
                                {{ __('dashboard.performance') }}
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Messages -->
                <li class="nav-item">
                    <a href="{{ url('admin/messages') }}"
                        class="nav-link @if (Request::segment(2) == 'messages') active @endif">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>{{ __('h_message.messages') }}</p>
                    </a>
                </li>




                <li class="nav-header">{{ __('dashboard.attendance_payroll') }}</li>



                <!-- Attendance -->
                <li class="nav-item has-treeview @if (in_array(Request::segment(2), ['attendance', 'reports', 'biometer-excel'])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Request::segment(2), ['attendance', 'reports', 'biometer-excel'])) active @endif">
                        <i class="nav-icon fa fa-calendar-check"></i>
                        <p>{{ __('dashboard.attendance') }}<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ url('admin/attendance') }}"
                                class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                                <i class="nav-icon fa fa-calendar-alt"></i>
                                <p>{{ __('dashboard.attendance_manually') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/reports') }}"
                                class="nav-link @if (Request::segment(2) == 'reports') active @endif">
                                <i class="nav-icon fa fa-file-alt"></i>
                                <p>{{ __('dashboard.attendance_reports') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/biometer-excel') }}"
                                class="nav-link @if (Request::segment(2) == 'biometer-excel') active @endif">
                                <i class="fas fa-file-excel nav-icon"></i>
                                <p>{{ __('dashboard.biometer_excel') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <!-- Taxs & insurance  -->
                <li class="nav-item has-treeview @if (Request::segment(2) == 'taxes' || Request::segment(2) == 'insurance') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'taxes' || Request::segment(2) == 'insurance') active @endif">
                        <i class="nav-icon fa fa-file-invoice-dollar"></i>
                        <p>{{ __('dashboard.tax_insurance') }}<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/taxes') }}"
                                class="nav-link @if (Request::segment(2) == 'taxes') active @endif">
                                <i class="fa fa-calculator nav-icon"></i>
                                <p>{{ __('dashboard.taxes') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/insurance') }}"
                                class="nav-link @if (Request::segment(2) == 'insurance') active @endif">
                                <i class="fa fa-medkit nav-icon"></i>
                                <p>{{ __('dashboard.insurance') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>





                <!-- deductions -->
                <li class="nav-item">
                    <a href="{{ url('admin/deductions') }}"
                        class="nav-link @if (Request::segment(2) == 'deductions') active @endif">
                        <i class="nav-icon fa fa-exclamation-circle	"></i>
                        <p>{{ __('dashboard.deductions') }}</p>
                    </a>
                </li>


                <!-- Vacations -->
                <li class="nav-item">
                    <a href="{{ url('admin/vacations') }}"
                        class="nav-link @if (Request::segment(2) == 'vacations') active @endif">
                        <i class="nav-icon fa fa-umbrella-beach"></i>
                        <p> {{ __('dashboard.vacations') }} </p>
                    </a>
                </li>


                <!-- Bounas -->
                <li class="nav-item">
                    <a href="{{ url('admin/bounas') }}"
                        class="nav-link @if (Request::segment(2) == 'bounas') active @endif">
                        <i class="nav-icon fa fa-dollar-sign"></i>
                        <p> {{ __('dashboard.overtime') }} </p>
                    </a>
                </li>


                <!-- Payroll -->
                <li class="nav-item has-treeview @if (in_array(Request::segment(2), ['attendance-rule', 'payroll', 'payslip'])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Request::segment(2), ['attendance-rule', 'payroll', 'payslip'])) active @endif">
                        <i class="nav-icon fa fa-coins"></i>
                        <p>{{ __('dashboard.payroll') }}<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                            <li class="nav-item">
                                <a href="{{ url('admin/attendance-rule') }}"
                                    class="nav-link @if (Request::segment(2) == 'attendance-rule') active @endif">
                                    <i class="fa fa-cogs nav-icon"></i>
                                    <p>{{ __('dashboard.company_policy') }}</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ url('admin/payroll') }}"
                                class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                                <i class="fa fa-calculator nav-icon"></i>
                                <p>{{ __('dashboard.calculate_payroll') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/payslip') }}"
                                class="nav-link @if (Request::segment(2) == 'payslip') active @endif">
                                <i class="fa fa-receipt nav-icon"></i>
                                <p>{{ __('dashboard.payslip_report') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>




                {{-- <li class="nav-item">
                    <a href="{{ url('admin/payroll') }}" class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                        <i class="nav-icon fa fa-coins"></i>
                        <p>Payroll</p>
                    </a>
                </li> --}}

                <li class="nav-header">{{ __('dashboard.settings') }}</li>

                <!-- Branches -->
                @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                    <li class="nav-item">
                        <a href="{{ url('admin/branches') }}"
                            class="nav-link @if (Request::segment(2) == 'branches') active @endif">
                            <i class="nav-icon fas fa-code-branch text-white"></i>
                            <p>{{ __('dashboard.branches') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Company information -->
                <li class="nav-item">
                    <a href="{{ route('locations.index') }}"
                        class="nav-link @if (Request::segment(2) == 'locations') active @endif">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>{{ __('dashboard.employee-locations') }}</p>
                    </a>
                </li>


                <!-- Company information -->
                @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                    <li class="nav-item">
                        <a href="{{ url('admin/company-info') }}"
                            class="nav-link @if (Request::segment(2) == 'company-info') active @endif">
                            <i class="nav-icon fas fa-info-circle "></i>
                            <p>{{ __('dashboard.company_info') }}</p>
                        </a>
                    </li>
                @endif


                <!-- My Account -->
                <li class="nav-item">
                    <a href="{{ url('admin/my_account') }}"
                        class="nav-link @if (Request::segment(2) == 'my_account') active @endif">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>{{ __('dashboard.my_account') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>




<!-- JavaScript Link (add before closing </body> tag) -->
<script src="{{ asset('dist/js/darkmode.js') }}"></script>
