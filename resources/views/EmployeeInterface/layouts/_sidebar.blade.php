<!-- Preloader -->

<!-- CSS Link (add inside <head>) -->
<link rel="stylesheet" href="{{ asset('dist/css/darkmode.css') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">



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

        {{-- <li class="nav-item d-flex align-items-center">
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
        </li> --}}

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



        <!-- Moon Icon for Dark Mode Toggle -->
        {{-- <li class="nav-item">
            <a class="nav-link dark-mode-toggle" role="button">
                <i class="nav-icon fa fa-moon" style="color: #908a8a;"></i>
            </a>
        </li> --}}



        @php
            $processedRequestsCount = getProcessedRequestsCount();
            $processedNotifications = getProcessedNotifications(10); // Get latest 10 processed notifications
        @endphp

        @php
            // الطلبات المعالجة
            $processedRequestsCount = getProcessedRequestsCount();
            $processedNotifications = getProcessedNotifications(10);

            // الرسائل الغير مقروءة
            $unreadMessagesCount = getUnreadMessagesCount();
            $unreadMessages = getUnreadMessages(10);
        @endphp

        {{-- إشعارات الطلبات --}}
        <li class="nav-item dropdown">
            <a class="nav-link position-relative p-2" href="#" id="processedNotifDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-lg text-secondary"></i>

                @if ($processedRequestsCount > 0)
                    <span class="badge badge-danger position-absolute"
                        style="top: 2px; right: 2px; font-size: 0.65rem; padding: 0.25em 0.5em; border-radius: 8px;">
                        {{ $processedRequestsCount > 99 ? '99+' : $processedRequestsCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu shadow notif-dropdown" aria-labelledby="processedNotifDropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <h6 class="mb-0 font-weight-bold">{{ __('dashboard.notifications') }}</h6>
                    @if ($processedRequestsCount > 0)
                        <span class="badge badge-light text-muted">
                            {{ $processedRequestsCount }} {{ __('dashboard.new') }}
                        </span>
                    @endif
                </div>

                <div style="max-height: 350px; overflow-y: auto;">
                    @forelse ($processedNotifications as $notification)
                        <a class="dropdown-item py-2 border-bottom small notification-item"
                            href="{{ $notification['url'] ?? '#' }}" style="white-space: normal;">
                            <div class="d-flex align-items-start notif-item">
                                <div class="icon-wrapper flex-shrink-0">
                                    @php
                                        $iconColor = 'text-secondary';
                                        if (isset($notification['type'])) {
                                            switch ($notification['type']) {
                                                case 'vacation':
                                                    $iconColor = 'text-primary';
                                                    break;
                                                case 'extra_time':
                                                    $iconColor = 'text-warning';
                                                    break;
                                                case 'resignation':
                                                    $iconColor = 'text-danger';
                                                    break;
                                                case 'late_removal':
                                                    $iconColor = 'text-secondary';
                                                    break;
                                                case 'early_leave':
                                                    $iconColor = 'text-warning';
                                                    break;
                                            }
                                        }
                                    @endphp
                                    <i class="{{ $notification['icon'] }} {{ $iconColor }}"
                                        style="font-size: 1rem;"></i>
                                </div>
                                <div class="flex-grow-1 notif-text">
                                    <div class="font-weight-normal" style="font-size: 0.9rem;">
                                        {!! $notification['message'] !!}
                                        @if ($notification['status'] == 'accepted')
                                            <span
                                                class="badge badge-success badge-sm ml-1">{{ __('dashboard.accepted') }}</span>
                                        @else
                                            <span
                                                class="badge badge-danger badge-sm ml-1">{{ __('dashboard.rejected') }}</span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $notification['date']->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="dropdown-item text-muted small text-center py-3">
                            <i class="fas fa-bell-slash mb-2 d-block text-secondary"></i>
                            {{ __('dashboard.no_processed_notifications') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </li>

        {{-- إشعارات الرسائل --}}
        {{-- إشعارات الرسائل --}}
        <li class="nav-item dropdown">
            <a class="nav-link position-relative p-2" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-lg text-secondary"></i>

                @if ($unreadMessagesCount > 0)
                    <span class="badge badge-danger position-absolute"
                        style="top: 2px; right: 2px; font-size: 0.65rem; padding: 0.25em 0.5em; border-radius: 8px;">
                        {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu shadow notif-dropdown" aria-labelledby="messagesDropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <h6 class="mb-0 font-weight-bold">{{ __('E_message.messages') }}</h6>
                    @if ($unreadMessagesCount > 0)
                        <span class="badge badge-light text-muted">
                            {{ $unreadMessagesCount }} {{ __('E_message.new') }}
                        </span>
                    @endif
                </div>

                <div style="max-height: 350px; overflow-y: auto;">
                    @forelse ($unreadMessages as $message)
                        <a class="dropdown-item py-2 border-bottom small notification-item"
                            href="{{ $message['url'] }}" style="white-space: normal;">
                            <div class="d-flex align-items-start notif-item">
                                <div class="icon-wrapper flex-shrink-0">
                                    <i class="{{ $message['icon'] }} text-primary" style="font-size: 1rem;"></i>
                                </div>
                                <div class="flex-grow-1 notif-text">
                                    <div class="font-weight-normal" style="font-size: 0.9rem;">
                                        {{ $message['message'] }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $message['date']->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="dropdown-item text-muted small text-center py-3">
                            <i class="fas fa-envelope-open-text mb-2 d-block text-secondary"></i>
                            {{ __('E_message.no_unread_messages') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </li>



        <script>
            function markAllProcessedAsSeen() {
                fetch('{{ route('requests.mark-all-processed-seen') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide the badge
                            document.querySelector('#processedNotifDropdown .badge').style.display = 'none';

                            // Update the header
                            const header = document.querySelector('#processedNotifDropdown').nextElementSibling
                                .querySelector('.dropdown-header');
                            header.innerHTML =
                                '<h6 class="mb-0 font-weight-bold">{{ __('dashboard.processed_notifications') }}</h6>';

                            // Remove all notification items or reload the page
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notifications as seen:', error);
                    });
            }

            // Mark individual notification as seen when clicked
            document.addEventListener('click', function(e) {
                const notificationItem = e.target.closest('.notification-item');
                if (notificationItem) {
                    const type = notificationItem.getAttribute('data-type');
                    const id = notificationItem.getAttribute('data-id');

                    // The URL will handle marking as seen via the showProcessedRequest method
                }
            });
        </script>
        <style>
            /* Add spacing between notification icons in LTR */
            [dir="ltr"] .nav-item.dropdown {
                margin-left: 0.5rem;
            }

            [dir="ltr"] .nav-item.dropdown:first-child {
                margin-left: 0;
            }

            /* Keep RTL spacing as is (it's working well) */
            [dir="rtl"] .nav-item.dropdown {
                margin-right: 0.5rem;
            }

            [dir="rtl"] .nav-item.dropdown:first-child {
                margin-right: 0;
            }

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
        <img src="{{ url('/dist/img/hr_logo-.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
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



                <!-- payroll -->
                <li class="nav-item">
                    <a href="{{ url('employee/payroll') }}"
                        class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                        <i class="nav-icon fa fa-coins"></i>
                        <p>{{ __('E_dashboard.my_payroll') }}</p>
                    </a>
                </li>

                <!-- Attendance -->
                <li class="nav-item">
                    <a href="{{ url('employee/attendance') }}"
                        class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                        <i class="nav-icon fa fa-calendar-check"></i>
                        <p>{{ __('E_dashboard.my_attendance') }}</p>
                    </a>
                </li>

                <!-- My Performance -->
                <li class="nav-item">
                    <a href="{{ url('employee/performance') }}"
                        class="nav-link @if (Request::segment(2) == 'performance') active @endif">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>{{ __('E_dashboard.performance') }} </p>
                    </a>
                </li>

                <!-- Company Policy -->
                <li class="nav-item">
                    <a href="{{ url('employee/policys') }}"
                        class="nav-link @if (Request::segment(2) == 'policys') active @endif">
                        <i class="fa fa-cogs nav-icon"></i>
                        <p>{{ __('dashboard.company_policy') }}</p>
                    </a>
                </li>

                <!-- messages -->
                <li class="nav-item">
                    <a href="{{ url('employee/messages') }}"
                        class="nav-link @if (Request::segment(2) == 'messages') active @endif">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            {{ __('h_message.messages') }}
                            @php $messageCount = getUnreadMessagesCount(); @endphp
                            @if ($messageCount > 0)
                                <span class="badge badge-danger right">{{ $messageCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- Requests -->
                <li class="nav-header">{{ __('E_dashboard.my_requests') }}</li>

                <!--Late Requests -->
                <li class="nav-item">
                    <a href="{{ url('employee/late') }}"
                        class="nav-link @if (Request::segment(2) == 'late') active @endif">
                        <i class="nav-icon fa fa-clock"></i>
                        <p>{{ __('E_dashboard.attendance_request') }}</p>
                    </a>
                </li>

                <!--vacation Requests -->
                <li class="nav-item">
                    <a href="{{ url('employee/vacation') }}"
                        class="nav-link @if (Request::segment(2) == 'vacation') active @endif">
                        <i class="nav-icon fa fa-umbrella-beach"></i>
                        <p>{{ __('E_dashboard.vacation_request') }}</p>
                    </a>
                </li>

                <!--extra time Requests -->
                <li class="nav-item">
                    <a href="{{ url('employee/extra') }}"
                        class="nav-link @if (Request::segment(2) == 'extra') active @endif">
                        <i class="nav-icon fa fa-dollar-sign"></i>
                        <p>{{ __('E_dashboard.extra_time_request') }}</p>
                    </a>
                </li>

                <!-- Early Leave Requests -->
                <li class="nav-item">
                    <a href="{{ url('employee/early-leave') }}"
                        class="nav-link @if (Request::segment(2) == 'early-leave') active @endif">
                        <i class="nav-icon fa fa-running"></i>
                        <p>{{ __('E_dashboard.early_leave_requests') }}</p>
                    </a>
                </li>

                <!--resignation Requests -->
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



    <style>
        /* ===== TABLET & MOBILE RESPONSIVE FIXES ===== */

        /* Tablet devices (768px - 1024px) */
        @media (max-width: 1024px) and (min-width: 768px) {
            /* Make sidebar collapsible by default on tablets */
            .main-sidebar {
                margin-left: -250px;
            }

            body:not(.sidebar-collapse) .main-sidebar {
                margin-left: 0;
            }

            /* Adjust main content when sidebar is hidden */
            .content-wrapper,
            .main-header,
            .main-footer {
                margin-left: 0 !important;
            }

            body:not(.sidebar-collapse) .content-wrapper,
            body:not(.sidebar-collapse) .main-header,
            body:not(.sidebar-collapse) .main-footer {
                margin-left: 250px !important;
            }

            /* Add overlay when sidebar is open on tablet */
            body:not(.sidebar-collapse)::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1037;
            }

            /* Ensure sidebar appears above overlay */
            .main-sidebar {
                position: fixed;
                z-index: 1038;
                height: 100vh;
            }
        }

        /* Mobile devices (max-width: 767px) */
        @media (max-width: 767px) {
            /* Sidebar hidden by default */
            .main-sidebar {
                position: fixed;
                transform: translateX(-250px);
                transition: transform 0.3s ease-in-out;
                z-index: 1038;
                height: 100vh;
            }

            /* Show sidebar when menu is open */
            body:not(.sidebar-collapse) .main-sidebar {
                transform: translateX(0);
            }

            /* RTL support for mobile */
            [dir="rtl"] .main-sidebar {
                transform: translateX(250px);
            }

            [dir="rtl"] body:not(.sidebar-collapse) .main-sidebar {
                transform: translateX(0);
            }

            /* Content takes full width on mobile */
            .content-wrapper,
            .main-header,
            .main-footer {
                margin-left: 0 !important;
            }

            /* Dark overlay when sidebar is open */
            body:not(.sidebar-collapse)::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1037;
                animation: fadeIn 0.3s;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            /* Reduce sidebar width slightly on very small screens */
            @media (max-width: 576px) {
                .main-sidebar {
                    width: 230px;
                }

                .main-sidebar {
                    transform: translateX(-230px);
                }

                [dir="rtl"] .main-sidebar {
                    transform: translateX(230px);
                }
            }
        }

        /* Smooth transitions for sidebar */
        .main-sidebar {
            transition: all 0.3s ease-in-out;
        }

        /* Ensure pushmenu button is visible and functional */
        .nav-link[data-widget="pushmenu"] {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Better touch targets for mobile */
        @media (max-width: 767px) {
            .nav-link {
                padding: 0.75rem 1rem;
                min-height: 44px;
            }

            .nav-sidebar .nav-link {
                padding: 0.75rem 1rem;
            }

            .nav-sidebar .nav-link p {
                font-size: 0.95rem;
            }
        }

        /* Fix brand logo on mobile */
        @media (max-width: 767px) {
            .brand-link {
                padding: 0.8125rem 0.5rem;
            }

            .brand-text {
                font-size: 1rem;
            }
        }

        /* Adjust user panel for smaller screens */
        @media (max-width: 767px) {
            .user-panel {
                padding: 0.75rem 0.5rem !important;
            }

            .user-panel .info a {
                font-size: 0.9rem;
            }
        }
    </style>
<!-- JavaScript Link (add before closing </body> tag) -->
<script src="{{ asset('dist/js/darkmode.js') }}"></script>
