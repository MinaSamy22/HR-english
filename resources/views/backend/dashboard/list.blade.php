@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ url('dist/css/dashboard.css') }}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper dashboard"
        style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">

        <!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <!-- Full width container with space between -->
                <div class="d-flex align-items-center justify-content-between">
                    <div class="company-logo-section d-flex align-items-center" style="margin-left: 15px">
                        @if (Auth::user()->company && Auth::user()->company->logo)
                            <div class="company-logo-wrapper d-flex align-items-center mr-0">
                                <img src="{{ Auth::user()->company->logo_url }}"
                                    alt="{{ Auth::user()->company->name }} Logo" class="company-logo mr-3"
                                    style="max-height: 60px; max-width: 120px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 50px rgba(0,0,0,0.1);">
                            </div>
                        @endif
                        <h6 class="mb-0 text-black bg-white px-2 py-6 rounded d-none d-md-block">{{ $branchName }} Branch</h6>
                        <small class="mb-0 text-black bg-white px-2 py-1 rounded d-block d-md-none">{{ $branchName }} Branch</small>
                    </div>
                    <div class="me-3">
                        <h5 class="text-muted d-none d-md-block">Welcome, {{ Auth::user()->name }} 👋</h5>
                        <small class="text-muted d-block d-md-none">Welcome, {{ Auth::user()->name }} 👋</small>
                    </div>
                </div>
            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.content-header -->


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Stat boxes (Responsive) -->
                <div class="container-fluid">
                    <!-- Modern Stat Cards -->
                    <div class="row g-4 mb-4">
                        <!-- Employees Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card employees-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">{{ $getEmployeeCount ?? '0' }}</div>
                                        <div class="stat-label">Total Employees</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-arrow-up"></i>
                                            <span>Active workforce</span>
                                        </div>
                                    </div>
                                    <div class="stat-icon">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Present Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card present-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">{{ $presentCount }}</div>
                                        <div class="stat-label">Present Today</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-check-circle"></i>
                                            <span>On time arrival</span>
                                        </div>
                                    </div>
                                    <div class="stat-icon">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Late Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card late-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">{{ $lateCount + $halfdayCount }}</div>
                                        <div class="stat-label">Late Arrivals</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-clock"></i>
                                            <span>Delayed check-in</span>
                                        </div>
                                    </div>
                                    <div class="stat-icon">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-user-clock"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Absent Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card absent-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">{{ $absentCount }}</div>
                                        <div class="stat-label">Absent Today</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>Not present</span>
                                        </div>
                                    </div>
                                    <div class="stat-icon">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-user-times"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!-- /.row -->

                </div><!-- /.container-fluid -->

                <!-- Bar Chart and Quick Access Section -->
                <div class="row mt-4">
                    <!-- Bar Chart Section (Responsive) -->
                    <div class="col-lg-8 col-md-12">
                        <div class="card shadow-sm"
                            style="border-radius: 10px; border: none; border-left: 4px solid #007bff;">
                            <div class="card-header"
                                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #007bff;">
                                    <i class="fas fa-chart-bar mr-2"></i>Monthly Performance Analytics
                                </h3>
                            </div>
                            <div class="card-body" style="background: white; padding: 1rem;">
                                <div class="chart-container">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Access Section -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card shadow-sm"
                            style="border-radius: 10px; border: none; border-left: 4px solid #007bff;">
                            <div class="card-header"
                                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #007bff;">
                                    <i class="fas fa-bolt mr-2"></i>Quick Access
                                </h3>
                            </div>
                            <div class="card-body" style="background: white; padding: 1.5rem;">
                                <!-- Reports Section -->
                                <div class="main-actions-section">
                                    <h5 class="mb-3"
                                        style="color: #6c757d; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-tasks mr-2"></i>Main Actions
                                    </h5>
                                    <!-- Add Attendance Manually -->
                                    <div class="quick-access-item mb-3">
                                        <a href="{{ route('attendance.index') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 12px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="fas fa-user-check mr-3" style="color: #007bff;"></i>
                                            <span style="font-weight: 500; color: #333;">Add Attendance Manually</span>
                                        </a>
                                    </div>


                                    <!-- Add Payroll -->
                                    <div class="quick-access-item mb-3">
                                        <a href="{{ route('payroll') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 12px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="nav-icon fa fa-coins mr-3" style="color: #007bff;"></i>
                                            <span style="font-weight: 500; color: #333;">Add Payroll</span>
                                        </a>
                                    </div>

                                </div>
                                <!-- Reports Section -->
                                <div class="reports-section">
                                    <h5 class="mb-3"
                                        style="color: #6c757d; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-file-alt mr-2"></i>Reports
                                    </h5>

                                    <!-- Payslip Report -->
                                    <div class="quick-access-item mb-2">
                                        <a href="{{ route('payslip') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 10px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="fas fa-receipt mr-3" style="color: #007bff;"></i>
                                            <span style="font-weight: 500; color: #333;">Payslip Report</span>
                                        </a>
                                    </div>

                                    <!-- Attendance Report -->
                                    <div class="quick-access-item mb-2">
                                        <a href="{{ route('report') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 10px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="fas fa-calendar-check mr-3" style="color: #007bff;"></i>
                                            <span style="font-weight: 500; color: #333;">Attendance Report</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->



    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Configuration Script -->
    <script>
        // Pass data from Laravel to JavaScript
        var chartDataPresent = @json($Present ?? []);
        var chartDataAbsent = @json($absences ?? []);
        var chartDataVacations = @json($vacations ?? []);
    </script>
    <script src="{{ asset('dist/js/dashboardlist.js') }}"></script>
@endsection
