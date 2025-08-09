@extends('EmployeeInterface.layouts.app')

@section('content')

  <link rel="stylesheet" href="{{ url('dist/css/employeeinterface/dashboard.css') }}">

    <!-- Content Header (Page header) -->
<div class="content-wrapper dashboard"
        style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
        <div class="content-header">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-2">{{ __('E_dashboard.welcome') }}, <span id="employee-name">{{ $user->name ?? __('E_dashboard.employee') }}👋</span></h4>
                                    <p class="text-muted mb-0">{{ __('E_dashboard.welcome_message') }}</p>
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
                        <div class="stat-number">{{ $presentDays }}</div>
                        <div class="stat-label">{{ __('E_dashboard.present_days') }}</div>
                        <div class="stat-trend">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ __('E_dashboard.attendance_this_month') }}</span>
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
            <div class="modern-stat-card present-card">
                <div class="card-gradient"></div>
                <div class="card-content">
                    <div class="stat-info">
                        <div class="stat-number">{{ $lateDays }}</div>
                        <div class="stat-label">{{ __('E_dashboard.late_days') }}</div>
                        <div class="stat-trend">
                            <i class="fas fa-clock"></i>
                            <span>{{ __('E_dashboard.late_this_month') }}</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <div class="icon-wrapper">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absent Card -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="modern-stat-card late-card">
                <div class="card-gradient"></div>
                <div class="card-content">
                    <div class="stat-info">
                        <div class="stat-number">{{ $absentDays }}</div>
                        <div class="stat-label">{{ __('E_dashboard.absent_days') }}</div>
                        <div class="stat-trend">
                            <i class="fas fa-user-times"></i>
                            <span>{{ __('E_dashboard.not_present') }}</span>
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

        <!-- Vacation Card -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="modern-stat-card absent-card">
                <div class="card-gradient"></div>
                <div class="card-content">
                    <div class="stat-info">
                        <div class="stat-number">{{ $vacationBalance }}</div>
                        <div class="stat-label">{{ __('E_dashboard.vacation_balance') }}</div>
                        <div class="stat-trend">
                            <i class="fas fa-umbrella-beach"></i>
                            <span>{{ __('E_dashboard.days_left_balance') }}</span>
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
                                    <i class="fas fa-newspaper mr-2"></i>{{ __('E_dashboard.latest_company_news') }}
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
                            <a href="{{ route('Employeenews.show', $newsItem) }}"
                               class="btn btn-success btn-sm view-btn"
                               style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                <i class="fas fa-eye mr-1"></i>{{ __('E_dashboard.view') }}
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
                                        <h5 class="text-muted">{{ __('E_dashboard.no_recent_news') }}</h5>
                                        <p class="text-muted mb-3">{{ __('E_dashboard.no_recent_news_message') }}</p>
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
                {{ __('E_dashboard.recent_activity') }}
            </h3>
        </div>
        <div class="card-body p-0">
            @if($recentActivities->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                    <p class="text-muted">{{ __('E_dashboard.no_recent_activity') }}</p>
                </div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($recentActivities as $activity)
                        @php
                            $statusBadgeClass = match(strtolower($activity->status)) {
                                'approved' => 'badge-success',
                                'pending' => 'badge-warning',
                                'rejected', 'denied' => 'badge-danger',
                                'completed' => 'badge-info',
                                default => 'badge-secondary'
                            };

                            $timeAgo = \Carbon\Carbon::parse($activity->updated_at)->diffForHumans();
                        @endphp

                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <span class="badge {{ $activity->badge_class }}">
                                        {{ $activity->activity_type }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ml-3">
                                    <p class="mb-1">
                                        <i class="{{ $activity->icon }} mr-1"></i>
                                        {{ $activity->activity_type }} {{ __('E_dashboard.request') }}
                                        <span class="badge {{ $statusBadgeClass }} ml-1">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </p>
                                    @if(!empty($activity->reason))
                                        <small class="text-muted d-block">
                                            {{ Str::limit($activity->reason, 50) }}
                                        </small>
                                    @endif
                                    <small class="text-muted">
                                        {{ $timeAgo }}
                                    </small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
            </div>
    </div>

    </div>

@endsection
