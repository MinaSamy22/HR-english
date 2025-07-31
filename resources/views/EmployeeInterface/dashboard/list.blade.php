@extends('EmployeeInterface.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-2">Welcome back, <span
                                            id="employee-name">{{ $user->name ?? 'Employee' }}</span></h4>
                                    <p class="text-muted mb-0">Have a great day at work. Here's your overview for you.</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">


                <!-- Quick Stats Row -->
                <div class="container-fluid">
                    <!-- Modern Stat Cards -->
                    <div class="row g-4 mb-4">
                        <!-- Present Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card employees-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">23</div>
                                        <div class="stat-label">Present days</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Attendance this month</span>
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

                        <!--Late Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card present-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">2</div>
                                        <div class="stat-label">Late days</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-clock"></i>
                                            <span>Late this month</span>
                                        </div>
                                    </div>
                                    <div class="stat-icon">
                                        <div class="icon-wrapper">
                                            <i class="fa fa-exclamation-circle	"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- absent Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card late-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">4</div>
                                        <div class="stat-label">Absent Days</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-user-times"></i>
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

                        <!-- vacation Card -->
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="modern-stat-card absent-card">
                                <div class="card-gradient"></div>
                                <div class="card-content">
                                    <div class="stat-info">
                                        <div class="stat-number">14</div>
                                        <div class="stat-label">Vacation Balance</div>
                                        <div class="stat-trend">
                                            <i class="fas fa-umbrella-beach"></i>
                                            <span>Is rest from Your balance</span>
                                        </div>
                                    </div>
                                    <div class="stat-icon">
                                        <div class="icon-wrapper">
                                            <i class="fas fa-umbrella-beach"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!-- /.row -->

                </div><!-- /.container-fluid -->

                <!-- Quick Actions & Recent Activity Row -->
                <div class="row mt-4">
                    <!-- Latest Company News -->
                    <div class="col-md-6">
                        <div class="card shadow-sm"
                            style="border-radius: 10px; border: none; border-left: 4px solid #28a745;">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #28a745;">
                                    <i class="fas fa-newspaper mr-2"></i>Latest Company News
                                </h3>
                            </div>
                            <div class="card-body"
                                style="background: white; padding: 1.5rem; max-height: 500px; overflow-y: auto;">
                               @if(isset($recentNews) && $recentNews->count() > 0)
    @foreach($recentNews as $newsItem)
        <div class="mb-4">
            <div class="news-item border rounded p-3 h-100"
                style="border-left: 3px solid #28a745 !important; background: #f8f9fa;">
                <div class="row">
                    @if($newsItem->hasImage())
                        <div class="col-4">
                            <div class="news-image-container"
                                style="height: 80px; overflow: hidden; border-radius: 0.375rem; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $newsItem->imageUrl }}"
                                     alt="{{ $newsItem->title }}"
                                     class="img-fluid"
                                     style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>
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
                            <a href="{{ route('news.show', $newsItem) }}"
                               class="btn btn-success btn-sm view-btn"
                               style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else

                                    <div class="text-center py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                                        </div>
                                        <h5 class="text-muted">No Recent News</h5>
                                <p class="text-muted mb-3">There are no recent news items to display.</p>

                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-1"></i>
                                Recent Activity
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <span class="badge badge-success">Leave</span>
                                        </div>
                                        <div class="flex-grow-1 ml-3">
                                            <p class="mb-1">Leave request approved</p>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <span class="badge badge-info">Payroll</span>
                                        </div>
                                        <div class="flex-grow-1 ml-3">
                                            <p class="mb-1">Payslip generated for January</p>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <span class="badge badge-warning">Training</span>
                                        </div>
                                        <div class="flex-grow-1 ml-3">
                                            <p class="mb-1">New training course assigned</p>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <style>
        /* Modern Card Styles */
        .modern-stat-card {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            min-height: 140px;
        }


        .modern-stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }



        .card-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transition: height 0.3s ease;
        }

        .modern-stat-card:hover .card-gradient {
            height: 8px;
        }

        .employees-card .card-gradient {
            background: #28a745;
        }

        .present-card .card-gradient {
            background: #fdcb6e;
        }

        .late-card .card-gradient {
            background: #d63031;
        }

        .absent-card .card-gradient {
            background: #17a2b8;
        }

        .card-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px;
            height: 120px;
            margin-top: 8px;
        }

        .stat-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 6px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }



        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }



        .stat-trend {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 500;
            margin-top: auto;
            /* This pushes the trend to the bottom of the stat-info container */
        }



        .stat-trend i {
            font-size: 0.8rem;
        }

        .stat-icon {
            position: relative;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .employees-card .icon-wrapper {
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.1), rgba(56, 239, 125, 0.1));
            color: #28a745;
        }

        .present-card .icon-wrapper {
            background: linear-gradient(135deg, rgba(255, 234, 167, 0.2), rgba(253, 203, 110, 0.2));
            color: #fdcb6e;
        }

        .late-card .icon-wrapper {
            background: linear-gradient(135deg, rgba(225, 130, 107, 0.2), rgba(218, 99, 99, 0.2));
            color: #d63031;
        }

        .absent-card .icon-wrapper {
            background: linear-gradient(135deg, rgba(141, 174, 237, 0.2), rgba(116, 155, 207, 0.2));
            color: #17a2b8;
        }

        .icon-wrapper i {
            font-size: 1.6rem;
            transition: all 0.3s ease;
        }

        .modern-stat-card:hover .icon-wrapper {
            transform: scale(1.1);
        }

        /* Modern Chart Card */
        .modern-chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .modern-chart-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .modern-chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            padding: 20px 25px;
            border-radius: 20px 20px 0 0;
        }

        .modern-chart-card .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
        }

        .modern-chart-card .card-body {
            padding: 25px;
        }

        /* Responsive Design */
        @media (max-width: 1199.98px) {
            .stat-number {
                font-size: 2rem;
            }

            .icon-wrapper {
                width: 50px;
                height: 50px;
            }

            .icon-wrapper i {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 991.98px) {
            .card-content {
                padding: 18px;
                height: 90px;
            }

            .stat-number {
                font-size: 1.9rem;
            }

            .icon-wrapper {
                width: 50px;
                height: 50px;
            }

            .icon-wrapper i {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 767.98px) {
            .modern-stat-card {
                margin-bottom: 20px;
                min-height: 100px;
            }

            .card-content {
                padding: 16px;
                height: auto;
                min-height: 75px;
                align-items: center;
            }

            .stat-number {
                font-size: 1.7rem;
                margin-bottom: 4px;
            }

            .stat-label {
                font-size: 0.82rem;
                margin-bottom: 4px;
            }

            .icon-wrapper {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                flex-shrink: 0;
            }

            .icon-wrapper i {
                font-size: 1.1rem;
            }

            .stat-trend {
                font-size: 0.78rem;
            }

            .stat-info {
                min-width: 0;
            }
        }

        @media (max-width: 575.98px) {
            .card-content {
                flex-direction: row;
                text-align: left;
                height: auto;
                padding: 14px 16px;
                align-items: center;
            }

            .stat-info {
                margin-bottom: 0;
                margin-right: 14px;
            }

            .stat-number {
                font-size: 1.7rem;
                margin-bottom: 4px;
            }

            .stat-label {
                font-size: 0.82rem;
                margin-bottom: 4px;
            }

            .icon-wrapper {
                width: 40px;
                height: 40px;
                border-radius: 11px;
            }

            .icon-wrapper i {
                font-size: 1.15rem;
            }

            .modern-stat-card {
                min-height: 110px;
            }

            .stat-trend {
                font-size: 0.78rem;
            }

            .stat-icon {
                margin-left: 14px;
            }
        }

        /* Animation for cards loading */
        @keyframes slideInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modern-stat-card {
            animation: slideInUp 0.6s ease-out;
        }

        .modern-stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .modern-stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .modern-stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .modern-stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }



        /* Dark Mode Styles */
        .dark-mode .modern-stat-card {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .dark-mode .modern-stat-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .dark-mode .stat-number {
            color: #f1f5f9;
        }

        .dark-mode .stat-label {
            color: #cbd5e1;
        }

        .dark-mode .stat-trend {
            color: #94a3b8;
        }

        /* news section */
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


    </div>

@endsection
