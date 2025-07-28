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
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item d-flex align-items-center">
            <a href="https://hrar.prosofteg.com/admin/dashboard" class="nav-link px-2" role="button"
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
        <img src="{{ url('/dist/img/hr_logo-.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Human Resource</span>
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
                        <p>Dashboard</p>
                    </a>
                </li>



                <li class="nav-header">Main Information</li>

                <!-- Employees -->
                <li class="nav-item">
                    <a href="{{ url('admin/employees') }}"
                        class="nav-link @if (Request::segment(2) == 'employees') active @endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>Employees</p>
                    </a>
                </li>



                <!-- Managers -->
                <li class="nav-item">
                    <a href="{{ url('admin/manager') }}"
                        class="nav-link @if (Request::segment(2) == 'manager') active @endif">
                        <i class="nav-icon fa fa-user"></i>
                        <p>Managers</p>
                    </a>
                </li>



                <!-- Administration -->
                <li class="nav-item">
                    <a href="{{ url('admin/administration') }}"
                        class="nav-link @if (Request::segment(2) == 'administration') active @endif">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>Administrations</p>
                    </a>
                </li>



                <!-- Departments -->
                <li class="nav-item">
                    <a href="{{ url('admin/department') }}"
                        class="nav-link @if (Request::segment(2) == 'department') active @endif">
                        <i class="nav-icon fa fa-building"></i>
                        <p>Departments</p>
                    </a>
                </li>



                <!-- Jobs -->
                <li class="nav-item has-treeview @if (Request::segment(2) == 'jobs' || Request::segment(2) == 'job_history') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'jobs' || Request::segment(2) == 'job_history') active @endif">
                        <i class="nav-icon fa fa-briefcase"></i>
                        <p>Jobs<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/jobs') }}"
                                class="nav-link @if (Request::segment(2) == 'jobs') active @endif">
                                <i class="fa fa-list nav-icon"></i>
                                <p>Current Jobs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/job_history') }}"
                                class="nav-link @if (Request::segment(2) == 'job_history') active @endif">
                                <i class="fa fa-history nav-icon"></i>
                                <p>Job History</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- News -->
                <li class="nav-item">
                    <a href="{{ url('admin/news') }}"
                        class="nav-link @if (Request::segment(2) == 'news') active @endif">
<i class="nav-icon fa fa-bullhorn"></i>
                        <p>Company News</p>
                    </a>
                </li>



                <li class="nav-header">Attendance & Payroll</li>



                <!-- Attendance -->
                <li class="nav-item has-treeview @if (in_array(Request::segment(2), ['attendance', 'reports', 'biometer-excel'])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Request::segment(2), ['attendance', 'reports', 'biometer-excel'])) active @endif">
                        <i class="nav-icon fa fa-calendar-check"></i>
                        <p>Attendance<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ url('admin/attendance') }}"
                                class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                                <i class="nav-icon fa fa-calendar-alt"></i>
                                <p>Attendance Manually</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/reports') }}"
                                class="nav-link @if (Request::segment(2) == 'reports') active @endif">
                                <i class="nav-icon fa fa-file-alt"></i>
                                <p>Attendance Reports</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/biometer-excel') }}"
                                class="nav-link @if (Request::segment(2) == 'biometer-excel') active @endif">
                                <i class="fas fa-file-excel nav-icon"></i>
                                <p>Biometer Excel</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <!-- Taxs & insurance  -->
                <li class="nav-item has-treeview @if (Request::segment(2) == 'taxes' || Request::segment(2) == 'insurance') menu-open @endif">
                    <a href="#" class="nav-link @if (Request::segment(2) == 'taxes' || Request::segment(2) == 'insurance') active @endif">
                        <i class="nav-icon fa fa-file-invoice-dollar"></i>
                        <p>Taxs & insurance<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/taxes') }}"
                                class="nav-link @if (Request::segment(2) == 'taxes') active @endif">
                                <i class="fa fa-calculator nav-icon"></i>
                                <p>Taxes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/insurance') }}"
                                class="nav-link @if (Request::segment(2) == 'insurance') active @endif">
                                <i class="fa fa-medkit nav-icon"></i>
                                <p>Insurance</p>
                            </a>
                        </li>
                    </ul>
                </li>





                <!-- deductions -->
                <li class="nav-item">
                    <a href="{{ url('admin/deductions') }}"
                        class="nav-link @if (Request::segment(2) == 'deductions') active @endif">
                        <i class="nav-icon fa fa-exclamation-circle	"></i>
                        <p>Deductions</p>
                    </a>
                </li>


                <!-- Vacations -->
                <li class="nav-item">
                    <a href="{{ url('admin/vacations') }}"
                        class="nav-link @if (Request::segment(2) == 'vacations') active @endif">
                        <i class="nav-icon fa fa-umbrella-beach"></i>
                        <p> Vacations </p>
                    </a>
                </li>


                <!-- Bounas -->
                <li class="nav-item">
                    <a href="{{ url('admin/bounas') }}"
                        class="nav-link @if (Request::segment(2) == 'bounas') active @endif">
                        <i class="nav-icon fa fa-dollar-sign"></i>
                        <p> OverTime </p>
                    </a>
                </li>


                <!-- Payroll -->
                <li class="nav-item has-treeview @if (in_array(Request::segment(2), ['attendance-rule', 'payroll', 'payslip'])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Request::segment(2), ['attendance-rule', 'payroll', 'payslip'])) active @endif">
                        <i class="nav-icon fa fa-coins"></i>
                        <p>Payroll<i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (session('branch_id') === null)

                        <li class="nav-item">
                            <a href="{{ url('admin/attendance-rule') }}"
                                class="nav-link @if (Request::segment(2) == 'attendance-rule') active @endif">
                                <i class="fa fa-cogs nav-icon"></i>
                                <p>Company Policy</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ url('admin/payroll') }}"
                                class="nav-link @if (Request::segment(2) == 'payroll') active @endif">
                                <i class="fa fa-calculator nav-icon"></i>
                                <p>Calculate Payroll</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/payslip') }}"
                                class="nav-link @if (Request::segment(2) == 'payslip') active @endif">
                                <i class="fa fa-receipt nav-icon"></i>
                                <p>Payslip Report</p>
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

                <li class="nav-header">Settings</li>

                <!-- Branches -->
@if (session('branch_id') === null)
    <li class="nav-item">
        <a href="{{ url('admin/branches') }}"
            class="nav-link @if (Request::segment(2) == 'branches') active @endif">
            <i class="nav-icon fas fa-code-branch text-white"></i>
            <p>Branches</p>
        </a>
    </li>
@endif


                <!-- Company information -->
                @if (session('branch_id') === null)

                <li class="nav-item">
                    <a href="{{ url('admin/company-info') }}"
                        class="nav-link @if (Request::segment(2) == 'company-info') active @endif">
                        <i class="nav-icon fas fa-info-circle "></i>
                        <p>Company information</p>
                    </a>
                </li>
                @endif


                <!-- My Account -->
                <li class="nav-item">
                    <a href="{{ url('admin/my_account') }}"
                        class="nav-link @if (Request::segment(2) == 'my_account') active @endif">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>My Account</p>
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
