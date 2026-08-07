@extends('backend.layouts.app')

@section('content')
<!-- Simple CSS for Charts Blade -->
<link rel="stylesheet" href="{{ url('dist/css/charts.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh; padding-bottom: 2rem;">

    <!-- Content Header (Page header) -->
    <div class="content-header pt-4 pb-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h1 class="m-0 simple-header-title">
                    <i class="fas fa-chart-line text-primary me-2"></i> {{ __('h_charts.reports') }}
                </h1>
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}" class="text-secondary text-decoration-none">
                            {{ __('Calender.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary font-weight-normal">{{ __('h_charts.charts') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Section Title -->
            <div class="simple-section-title">
                {{ __('h_charts.basic_information') }}
            </div>

            <!-- Top Stat Cards Grid -->
            <div class="row mb-4">
                <!-- Employees Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="simple-stat-card">
                        <div>
                            <div class="simple-stat-label">{{ __('h_charts.employees') }}</div>
                            <div class="simple-stat-number">{{ $getEmployeeCount }}</div>
                        </div>
                        <div class="simple-stat-icon icon-blue">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <!-- Managers Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="simple-stat-card">
                        <div>
                            <div class="simple-stat-label">{{ __('h_charts.managers') }}</div>
                            <div class="simple-stat-number">{{ $getManagersCount }}</div>
                        </div>
                        <div class="simple-stat-icon icon-green">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>

                <!-- Jobs Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="simple-stat-card">
                        <div>
                            <div class="simple-stat-label">{{ __('h_charts.jobs') }}</div>
                            <div class="simple-stat-number">{{ $getJobsCount }}</div>
                        </div>
                        <div class="simple-stat-icon icon-amber">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                </div>

                <!-- Departments Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="simple-stat-card">
                        <div>
                            <div class="simple-stat-label">{{ __('h_charts.departments') }}</div>
                            <div class="simple-stat-number">{{ $getDepartmentCount }}</div>
                        </div>
                        <div class="simple-stat-icon icon-purple">
                            <i class="far fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Title: Overview & Overtime -->
            <div class="simple-section-title">
                Department & Overtime Analytics
            </div>

            <!-- Row 1: Departments Overview & Top Overtime -->
            <div class="row mb-2">
                <!-- Doughnut Chart Section -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="simple-chart-card">
                        <div class="simple-chart-header">
                            <h3 class="simple-chart-title">
                                <i class="fas fa-chart-pie text-primary me-2"></i>{{ __('h_charts.departments_overview') }}
                            </h3>
                        </div>
                        <div class="simple-chart-body">
                            <div class="simple-canvas-container">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart Section -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="simple-chart-card">
                        <div class="simple-chart-header">
                            <h3 class="simple-chart-title">
                                <i class="fas fa-chart-bar text-primary me-2"></i>{{ __('h_charts.top_employees_month') }}
                            </h3>
                        </div>
                        <div class="simple-chart-body">
                            <div class="simple-canvas-container">
                                <canvas id="barChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Title: Attendance & Trends -->
            <div class="simple-section-title">
                Attendance Trends & Staffing Analysis
            </div>

            <!-- Row 2: Annual Attendance Trends (Line Chart) -->
            <div class="row mb-2">
                <div class="col-12 mb-4">
                    <div class="simple-chart-card">
                        <div class="simple-chart-header">
                            <h3 class="simple-chart-title">
                                <i class="fas fa-chart-line text-primary me-2"></i>Annual Attendance & Absence Trends
                            </h3>
                        </div>
                        <div class="simple-chart-body">
                            <div class="simple-canvas-container" style="height: 320px;">
                                <canvas id="annualTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Today's Attendance & Job Roles Breakdown -->
            <div class="row mb-4">
                <!-- Today's Attendance Breakdown -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="simple-chart-card">
                        <div class="simple-chart-header">
                            <h3 class="simple-chart-title">
                                <i class="fas fa-user-clock text-primary me-2"></i>Today's Attendance Breakdown
                            </h3>
                        </div>
                        <div class="simple-chart-body">
                            <div class="simple-canvas-container">
                                <canvas id="todayAttendanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Roles Headcount -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="simple-chart-card">
                        <div class="simple-chart-header">
                            <h3 class="simple-chart-title">
                                <i class="fas fa-sitemap text-primary me-2"></i>Job Roles Distribution
                            </h3>
                        </div>
                        <div class="simple-chart-body">
                            <div class="simple-canvas-container">
                                <canvas id="jobRolesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Pass data from Blade to JavaScript -->
<script>
    var departmentNames = @json($departmentNames);
    var employeeCounts = @json($employeeCounts);
    var employeeNames = @json($employeeNames);
    var overtimeHours = @json($overtimeHours);

    // New analytical data
    var jobTitles = @json($jobTitles ?? []);
    var jobUserCounts = @json($jobUserCounts ?? []);
    var todayPresent = @json($todayPresent ?? 0);
    var todayLate = @json($todayLate ?? 0);
    var todayAbsent = @json($todayAbsent ?? 0);
    var todayHalfday = @json($todayHalfday ?? 0);
    var monthlyPresent = @json($monthlyPresent ?? []);
    var monthlyAbsences = @json($monthlyAbsences ?? []);
    var monthlyVacations = @json($monthlyVacations ?? []);

    // Pass translations to JavaScript
    var translations = {
        departments_overview: @json(__('h_charts.departments_overview')),
        number_of_employees: @json(__('h_charts.number_of_employees')),
        overtime_hours: @json(__('h_charts.overtime_hours')),
        employees: @json(__('h_charts.employees')),
        top_employees_per_month: @json(__('h_charts.top_employees_per_month'))
    };
</script>

<!-- Include external JavaScript file -->
<script src="{{ asset('dist/js/charts.js') }}"></script>

@endsection
