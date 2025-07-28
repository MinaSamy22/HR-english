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




                <!-- Company News Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm"
                            style="border-radius: 10px; border: none; border-left: 4px solid #28a745;">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #28a745;">
                                    <i class="fas fa-newspaper mr-2"></i>Latest Company News
                                </h3>

                            </div>
                            <div class="card-body" style="background: white; padding: 1.5rem;">
                                @if(isset($recentNews) && $recentNews->count() > 0)
                                    <div class="row">
                                        @foreach($recentNews as $newsItem)
                                            <div class="col-lg-6 col-md-12 mb-4">
                                                <div class="news-item border rounded p-3 h-100"
                                                     style="border-left: 3px solid #28a745 !important; transition: all 0.3s ease; background: #f8f9fa;">
                                                    <div class="row">
                                                        @if($newsItem->hasImage())
                                                            <div class="col-4">
                                                                <img src="{{ $newsItem->imageUrl }}"
                                                                     alt="{{ $newsItem->title }}"
                                                                     class="img-fluid rounded"
                                                                     style="height: 80px; width: 100%; object-fit: cover;">
                                                            </div>
                                                            <div class="col-8">
                                                        @else
                                                            <div class="col-12">
                                                        @endif
                                                            <h6 class="news-title mb-2" style="color: #333; font-weight: 600; line-height: 1.4;">
                                                                <a href="{{ route('news.show', $newsItem) }}"
                                                                   class="text-decoration-none"
                                                                   style="color: inherit;">
                                                                    {{ Str::limit($newsItem->title, 50) }}
                                                                </a>
                                                            </h6>
                                                            <p class="news-excerpt mb-2 text-muted small">
                                                                {{ Str::limit($newsItem->description, 80) }}
                                                            </p>
                                                            <div class="news-meta d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                                    {{ $newsItem->formattedDate }}
                                                                </small>
                                                                @if($newsItem->isRecent())
                                                                    <span class="badge badge-success badge-sm">New</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                                        </div>
                                        <h5 class="text-muted">No Recent News</h5>
                                        <p class="text-muted mb-3">There are no recent news items to display.</p>
                                        <a href="{{ route('news.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus mr-2"></i>Add First News
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- CSS for News Items Hover Effect -->
    <style>
        .news-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
            background: white !important;
        }

        .news-title a:hover {
            color: #28a745 !important;
        }

        .quick-btn:hover {
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-color: #007bff !important;
        }
    </style>



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
