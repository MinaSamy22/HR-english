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
                                @php
                                    $branchLabel =
                                        $branchName === 'Main'
                                            ? __('h_dashboard.main_branchs')
                                            : __('h_dashboard.branch') . ' ' . $branchName;
                                @endphp

                                <h6
                                    class="d-none d-md-inline-flex align-items-center mb-0
               text-dark bg-light px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-code-branch me-2 text-primary"></i>
                                    {{ $branchLabel }}
                                </h6>

                                <!-- Mobile view -->
                                <small
                                    class="d-inline-flex d-md-none align-items-center mb-0
                  text-dark bg-light px-2 py-1 rounded-pill shadow-sm">
                                    <i class="fas fa-code-branch me-1 text-primary"></i>
                                    {{ $branchLabel }}
                                </small>
                            </div>


                            <div class="me-3">
                                <h5 class="text-muted d-none d-md-block">{{ __('h_dashboard.welcome') }},
                                    {{ Auth::user()->name }} 👋</h5>
                                <small class="text-muted d-block d-md-none">{{ __('h_dashboard.welcome') }},
                                    {{ Auth::user()->name }} 👋</small>
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
                                        <div class="stat-label">{{ __('h_dashboard.total_employees') }}</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-arrow-up"></i>
                                            <span>{{ __('h_dashboard.active_workforce') }}</span>
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
                                        <div class="stat-label">{{ __('h_dashboard.present_today') }}</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ __('h_dashboard.on_time_arrival') }}</span>
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
                                        <div class="stat-label">{{ __('h_dashboard.late_arrivals') }}</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ __('h_dashboard.delayed_checkin') }}</span>
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
                                        <div class="stat-label">{{ __('h_dashboard.absent_today') }}</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>{{ __('h_dashboard.not_present') }}</span>
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
                            style="border-radius: 10px; border: none; border-inline-start: 4px solid #007bff;">
                            <div class="card-header"
                                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #007bff;">
                                    <i
                                        class="fas fa-chart-bar mr-2"></i>{{ __('h_dashboard.monthly_performance_analytics') }}
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
                            style="border-radius: 10px; border: none; border-inline-start: 4px solid #007bff;">
                            <div class="card-header"
                                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #007bff;">
                                    <i class="fas fa-bolt mr-2"></i>{{ __('h_dashboard.quick_access') }}
                                </h3>
                            </div>
                            <div class="card-body" style="background: white; padding: 1.5rem;">
                                <!-- Reports Section -->
                                <div class="main-actions-section">
                                    <h5 class="mb-3"
                                        style="color: #6c757d; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-tasks mr-2"></i>{{ __('h_dashboard.main_actions') }}
                                    </h5>
                                                                @if (hr_can('attendance_reports'))
                                    <!-- Add Attendance Manually -->
                                    <div class="quick-access-item mb-3">
                                        <a href="{{ route('attendance.index') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 12px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="fas fa-user-check mr-3" style="color: #007bff;"></i>
                                            <span
                                                style="font-weight: 500; color: #333;">{{ __('h_dashboard.add_attendance_manually') }}</span>
                                        </a>
                                    </div>
                                    @endif


                                    <!-- Add Payroll -->
                                    @if (hr_can('payroll'))
                                    <div class="quick-access-item mb-3">
                                        <a href="{{ route('payroll') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 12px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="nav-icon fa fa-coins mr-3" style="color: #007bff;"></i>
                                            <span
                                                style="font-weight: 500; color: #333;">{{ __('h_dashboard.add_payroll') }}</span>
                                        </a>
                                    </div>
                                    @endif

                                </div>
                                <!-- Reports Section -->
                                <div class="reports-section">
                                    <h5 class="mb-3"
                                        style="color: #6c757d; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-file-alt mr-2"></i>{{ __('h_dashboard.reports') }}
                                    </h5>

                                    <!-- Payslip Report -->
                                    @if (hr_can('payslip'))
                                    <div class="quick-access-item mb-2">
                                        <a href="{{ route('payslip') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 10px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="fas fa-receipt mr-3" style="color: #007bff;"></i>
                                            <span
                                                style="font-weight: 500; color: #333;">{{ __('h_dashboard.payslip_report') }}</span>
                                        </a>
                                    </div>
                                    @endif

                                    <!-- Attendance Report -->
                                    @if (hr_can('attendance_reports'))
                                    <div class="quick-access-item mb-2">
                                        <a href="{{ route('report') }}"
                                            class="btn btn-light btn-block text-left quick-btn"
                                            style="border-radius: 8px; padding: 10px 16px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                                            <i class="fas fa-calendar-check mr-3" style="color: #007bff;"></i>
                                            <span
                                                style="font-weight: 500; color: #333;">{{ __('h_dashboard.attendance_report') }}</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.row -->


<div class="row mt-4">



    <!--  Company News Section (Green) -->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow-sm"
            style="border-radius: 10px; border: none; border-inline-start: 4px solid #28a745;">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #28a745;">
                    <i class="fas fa-newspaper mr-2"></i>{{ __('h_dashboard.latest_company_news') }}
                </h3>
            </div>

            <div class="card-body"
                style="background: white; padding: 1.5rem; max-height: 500px; overflow-y: auto;">

                @if (isset($recentNews) && $recentNews->count() > 0)
                    <div class="row">
                        @foreach ($recentNews as $newsItem)
                            <div class="col-lg-12 col-md-12 mb-3">
                                <div class="news-item border rounded p-3 h-100"
                                    style="border-inline-start: 3px solid #28a745 !important; background: #f8f9fa; transition: 0.3s;">

                                    <div class="row">
                                        @if ($newsItem->hasImage())
                                            <div class="col-4">
                                                <div class="news-image-container"
                                                    style="height: 80px; width: 100%; overflow: hidden; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center;">
                                                    <img src="{{ $newsItem->imageUrl }}"
                                                         alt="{{ $newsItem->title }}"
                                                         class="img-fluid"
                                                         style="max-height: 100%; object-fit: contain;">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                        @else
                                            <div class="col-12">
                                        @endif

                                            <h6 class="news-title mb-2"
                                                style="color: #333; font-weight: 600; line-height: 1.4;">
                                                <a href="{{ route('news.show', $newsItem) }}"
                                                   class="text-decoration-none" style="color: inherit;">
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

                                                <a href="{{ route('news.show', $newsItem) }}"
                                                   class="btn btn-success btn-sm view-btn"
                                                   style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                    <i class="fas fa-eye mr-1"></i>{{ __('h_dashboard.view') }}
                                                </a>
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
                        <h5 class="text-muted">{{ __('h_dashboard.no_recent_news') }}</h5>
                        <p class="text-muted mb-3">{{ __('h_dashboard.no_recent_news_desc') }}</p>
                        <a href="{{ route('news.create') }}" class="btn btn-success">
                            <i class="fas fa-plus mr-2"></i>{{ __('h_dashboard.add_first_news') }}
                        </a>
                    </div>

                @endif

            </div>
        </div>
    </div>

<!-- ✅ Company Policy Section (Grey) -->
<div class="col-lg-6 col-md-12">
    <div class="card shadow-sm"
        style="border-radius: 10px; border: none; border-inline-start: 4px solid #6c757d;">
        <div class="card-header d-flex justify-content-between align-items-center"
            style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
            <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #6c757d;">
                <i class="fas fa-file-pdf mr-2"></i> {{ __('h_dashboard.company_policy') }}
            </h3>
        </div>

        <div class="card-body" style="background: white; padding: 1.5rem;">

            @if(isset($setting) && isset($setting->company_policy_pdf) && !empty($setting->company_policy_pdf))

                <div class="mb-3 p-3 border rounded"
                     style="border-inline-start: 3px solid #6c757d !important; background: #f8f9fa;">
                    <p class="mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                        <i class="fas fa-info-circle text-secondary mr-1"></i>
                        {{ __('h_dashboard.policy_note_text', ['default' => 'Review the company policy document to stay informed about workplace guidelines, attendance rules, and important procedures.']) }}
                    </p>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border rounded"
                     style="border-inline-start: 3px solid #6c757d !important; background: #f8f9fa;">
                    <div>
                        <p class="mb-1 font-weight-bold" style="font-size: 0.95rem; color: #495057;">
                            {{ __('h_dashboard.company_policy_document') }}
                        </p>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                            {{ __('h_dashboard.company_policy_uploaded') }}
                        </p>
                    </div>

                    <a href="{{ route('company-policy.view', $setting->company_policy_pdf) }}"
                       target="_blank"
                       class="btn btn-secondary btn-sm"
                       style="font-size: 0.85rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-eye mr-1"></i> {{ __('h_dashboard.view') }}
                    </a>
                </div>

            @else

                <div class="text-center py-4">
                    <i class="fas fa-file-pdf text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">
                        {{ __('h_dashboard.no_company_policy') }}
                    </p>
                    <small class="text-muted">{{ __('h_dashboard.policy_will_appear_here') }}</small>
                </div>

            @endif

        </div>
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            background: white !important;
        }

        .news-title a:hover {
            color: #28a745 !important;
        }

        .quick-btn:hover {
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-color: #007bff !important;
        }

        .view-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        /* Custom scrollbar for news section */
        .card-body::-webkit-scrollbar {
            width: 6px;
        }

        .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background: #28a745;
            border-radius: 3px;
        }

        .card-body::-webkit-scrollbar-thumb:hover {
            background: #1e7e34;
        }

        .news-image-container:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
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
    <script src="{{ asset('dist/js/dashboardlist.js?v=4') }}"></script>
@endsection
