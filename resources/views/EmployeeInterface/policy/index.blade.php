@extends('EmployeeInterface.layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            <i class="fas fa-clipboard-list text-primary mr-2"></i>
                            {{ __('policy.company_attendance_policy') }}
                        </h1>
                        <p class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ __('policy.review_company_attendance_rules_and_policies') }}
                        </p>
                    </div>
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home" class="text">{{ __('Calender.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('policy.company_policy') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Key Policy Stats -->
                <div class="row mb-4">

                    <div class="col-md-3">
                        <div class="card text-center border-primary rounded">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                @php
                                    $emp = auth()->guard('employee')->user() ?? auth()->user();
                                    $workHours = $emp->work_hours_per_day ?? ($setting->work_hours_per_day ?? 8);
                                @endphp

                                <h3 class="text-primary">{{ $workHours }}</h3>
                                <p class="text-muted mb-0">{{ __('policy.daily_work_hours') }}</p>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <div class="card text-center border-success rounded">
                            <div class="card-body">
                                <i class="fas fa-coins fa-2x text-success mb-2"></i>
                                <h3 class="text-success">
                                    {{ number_format($setting->bonus_per_hour, 2) }}
                                </h3>
                                <p class="text-muted mb-0">{{ __('policy.overtime_bonus') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-center border-warning rounded">
                            <div class="card-body">
                                <i class="fas fa-umbrella-beach fa-2x text-warning mb-2"></i>
                                <h3 class="text-warning">
                                    {{ number_format($setting->vacation_balance, 2) }}
                                </h3>
                                <p class="text-muted mb-0">{{ __('policy.vacation_days_year') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-center border-danger rounded">
                            <div class="card-body">
                                <i class="fas fa-calendar-alt fa-2x text-danger mb-2"></i>
                                @php
                                    $holidayCount = count(json_decode($setting->official_holidays ?? '[]', true));
                                @endphp
                                <h3 class="text-danger">{{ $holidayCount }}</h3>
                                <p class="text-muted mb-0">{{ __('policy.official_holidays') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Main Policy Information -->
                    <div class="col-md-8">
                        <!-- Deduction Policies -->
                        <div class="card mb-4 shadow-sm rounded">

                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-minus-circle text-danger mr-2"></i>
                                    {{ __('policy.deduction_policies') }}
                                </h5>
                            </div>
                            <div class="card-body">

                                @php
                                    // Get threshold values from setting or use defaults
                                    $lateThreshold = $setting->late_threshold_minutes ?? 15;
                                    $halfDayThreshold = $setting->half_day_threshold_minutes ?? 240;
                                    $absentThreshold = $setting->absent_threshold_minutes ?? 480;
                                @endphp

                                <!-- Attendance Policy Visual -->
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">{{ __('dashboard.attendance_policy_visual') }}</h6>

                                    <!-- Simple colored bar -->
                                    <div style="display: flex; height: 50px; border-radius: 5px; overflow: hidden;">
                                        <div id="green"
                                            style="background: #28a745; flex: 1; display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: 500;">
                                            <span id="greenText">0-{{ $lateThreshold }}
                                                {{ __('dashboard.minutes') }}</span>
                                        </div>
                                        <div id="yellow"
                                            style="background: #ffc107; flex: 1; display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: 500;">
                                            <span id="yellowText">{{ $lateThreshold + 1 }}-{{ $halfDayThreshold }}
                                                {{ __('dashboard.minutes') }}</span>
                                        </div>
                                        <div id="orange"
                                            style="background: #fd7e14; flex: 1; display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: 500;">
                                            <span id="orangeText">{{ $halfDayThreshold + 1 }}-{{ $absentThreshold }}
                                                {{ __('dashboard.minutes') }}</span>
                                        </div>
                                        <div id="red"
                                            style="background: #dc3545; flex: 1; display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: 500;">
                                            <span id="redText">{{ $absentThreshold }}+
                                                {{ __('dashboard.minutes') }}</span>
                                        </div>
                                    </div>

                                    <!-- Labels below -->
                                    <div
                                        style="display: flex; justify-content: space-around; margin-top: 8px; font-size: 12px; color: #495057;">
                                        <span>{{ __('dashboard.present') }}</span>
                                        <span>{{ __('dashboard.late') }}</span>
                                        <span>{{ __('dashboard.half_day') }}</span>
                                        <span>{{ __('dashboard.absent') }}</span>
                                    </div>
                                </div>
                            </div>
                            <hr>



                            @push('scripts')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Function to update the visual bar labels dynamically
                                        function updateAttendanceVisual() {
                                            // Get the actual input elements
                                            const lateInput = document.querySelector('input[name="late_threshold_minutes"]');
                                            const halfDayInput = document.querySelector('input[name="half_day_threshold_minutes"]');
                                            const absentInput = document.querySelector('input[name="absent_threshold_minutes"]');

                                            // Check if inputs exist before reading values
                                            if (!lateInput || !halfDayInput || !absentInput) {
                                                console.warn('Attendance threshold inputs not found - visual will not update dynamically');
                                                return;
                                            }

                                            // Read actual values from inputs
                                            const lateThreshold = parseInt(lateInput.value) || {{ $lateThreshold }};
                                            const halfDayThreshold = parseInt(halfDayInput.value) || {{ $halfDayThreshold }};
                                            const absentThreshold = parseInt(absentInput.value) || {{ $absentThreshold }};

                                            const minText = "{{ __('dashboard.minutes') }}";

                                            // Update the visual bar with actual threshold values
                                            const greenText = document.getElementById('greenText');
                                            const yellowText = document.getElementById('yellowText');
                                            const orangeText = document.getElementById('orangeText');
                                            const redText = document.getElementById('redText');

                                            if (greenText) greenText.textContent = `0-${lateThreshold} ${minText}`;
                                            if (yellowText) yellowText.textContent = `${lateThreshold + 1}-${halfDayThreshold} ${minText}`;
                                            if (orangeText) orangeText.textContent = `${halfDayThreshold + 1}-${absentThreshold} ${minText}`;
                                            if (redText) redText.textContent = `${absentThreshold}+ ${minText}`;
                                        }

                                        // Initial update on page load
                                        updateAttendanceVisual();

                                        // Listen for changes on the threshold input fields to update dynamically
                                        const lateInput = document.querySelector('input[name="late_threshold_minutes"]');
                                        const halfDayInput = document.querySelector('input[name="half_day_threshold_minutes"]');
                                        const absentInput = document.querySelector('input[name="absent_threshold_minutes"]');

                                        if (lateInput) {
                                            lateInput.addEventListener('input', updateAttendanceVisual);
                                            lateInput.addEventListener('change', updateAttendanceVisual);
                                            lateInput.addEventListener('blur', updateAttendanceVisual);
                                        }

                                        if (halfDayInput) {
                                            halfDayInput.addEventListener('input', updateAttendanceVisual);
                                            halfDayInput.addEventListener('change', updateAttendanceVisual);
                                            halfDayInput.addEventListener('blur', updateAttendanceVisual);
                                        }

                                        if (absentInput) {
                                            absentInput.addEventListener('input', updateAttendanceVisual);
                                            absentInput.addEventListener('change', updateAttendanceVisual);
                                            absentInput.addEventListener('blur', updateAttendanceVisual);
                                        }
                                    });
                                </script>
                            @endpush
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="border-left border-warning pl-3 mb-3">
                                            <h6>
                                                <i class="fas fa-clock text-warning mr-2"></i>
                                                {{ __('policy.late_arrival') }}
                                            </h6>
                                            <p class="mb-0">
                                                <i class="fas fa text-muted mr-1"></i>
                                                <strong>{{ $setting->late_deduction_percentage ?? 0 }}%</strong>
                                                {{ __('policy.deduction_from_daily_wage') }}
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa text-muted mr-1"></i>
                                                <strong> {{ __('dashboard.late_arrival_threshold') }}</strong>
                                                {{ $setting->late_threshold_minutes }}
                                                {{ __('dashboard.minutes_after_start_time') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border-left border-info pl-3 mb-3">
                                            <h6>
                                                <i class="fas fa-user-clock text-info mr-2"></i>
                                                {{ __('policy.half_day_absence') }}
                                            </h6>
                                            <p class="mb-0">
                                                <i class="fas fa text-muted mr-1"></i>
                                                <strong>{{ $setting->half_day_deduction_percentage ?? 0 }}%</strong>
                                                {{ __('policy.deduction_from_daily_wage') }}
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa text-muted mr-1"></i>
                                                <strong> {{ __('dashboard.half_day_threshold') }}</strong>
                                                {{ $setting->half_day_threshold_minutes }}
                                                {{ __('dashboard.minutes_of_absence') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- the new --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="border-left border-danger pl-3 mb-3">
                                            <h6>
                                                <i class="fas fa-user-times text-danger mr-2"></i>
                                                {{ __('policy.absent_calculation') }}
                                            </h6>
                                            <p class="mb-0">
                                                <i class="fas fa text-muted mr-1"></i>
                                                <strong>100%</strong>
                                                {{ __('policy.deduction_from_daily_wage') }}
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa text-muted mr-1"></i>
                                                <strong> {{ __('dashboard.late_arrival_threshold') }}</strong>
                                                {{ $setting->absent_threshold_minutes }}
                                                {{ __('dashboard.minutes_after_start_time') }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>



                        </div>

                        <!-- Working Schedule -->
                        <div class="card mb-4 shadow-sm rounded">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-week text-primary mr-2"></i>
                                    {{ __('policy.working_schedule') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $emp = auth()->guard('employee')->user() ?? auth()->user();

                                    // Retrieve working days with fallback logic
                                    $workingDaysJson = $emp->working_days ?? ($setting->working_days ?? null);
                                    $workingDays = $workingDaysJson
                                        ? json_decode($workingDaysJson, true)
                                        : ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

                                    $allDays = [
                                        'Sunday',
                                        'Monday',
                                        'Tuesday',
                                        'Wednesday',
                                        'Thursday',
                                        'Friday',
                                        'Saturday',
                                    ];
                                    $dayIcons = [
                                        'Sunday' => 'fas fa-briefcase',
                                        'Monday' => 'fas fa-briefcase',
                                        'Tuesday' => 'fas fa-briefcase',
                                        'Wednesday' => 'fas fa-briefcase',
                                        'Thursday' => 'fas fa-briefcase',
                                        'Friday' => 'fas fa-briefcase',
                                        'Saturday' => 'fas fa-briefcase',
                                    ];
                                @endphp
                                <div class="row">
                                    @foreach ($allDays as $day)
                                        <div class="col-md-3 col-6 mb-2">
                                            @if (in_array($day, $workingDays))
                                                <div class="badge badge-success w-100 p-2 rounded">
                                                    <i class="{{ $dayIcons[$day] }} mr-1"></i>
                                                    {{ __('policy.' . strtolower($day)) }}
                                                </div>
                                            @else
                                                <div class="badge badge-light w-100 p-2 text-muted rounded">
                                                    <i class="{{ $dayIcons[$day] }} mr-1"></i>
                                                    {{ __('policy.' . strtolower($day)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ __('policy.green_badges_indicate_working_days') }}
                                </small>
                            </div>
                        </div>

                        <!-- Important Reminders -->
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                    {{ __('policy.important_reminders') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3 p-2 border-left border-info">
                                        <i class="fas fa-clock text-info mr-2"></i>
                                        <strong>{{ __('policy.arrive_on_time_to_avoid_deductions') }}</strong>
                                    </li>
                                    <li class="mb-3 p-2 border-left border-success">
                                        <i class="fas fa-trophy text-success mr-2"></i>
                                        <strong>{{ __('policy.earn_bonus_for_extra_hours_worked') }}</strong>
                                    </li>
                                    <li class="mb-0 p-2 border-left border-warning">
                                        <i class="fas fa-fingerprint text-warning mr-2"></i>
                                        <strong>{{ __('policy.always_clock_in_and_out_properly') }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Official Holidays -->
                        <div class="card mb-4 shadow-sm rounded">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-star text-danger mr-2"></i>
                                    {{ __('policy.official_holidays') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $holidays = isset($setting->official_holidays)
                                        ? json_decode($setting->official_holidays, true)
                                        : [];
                                @endphp

                                @if (count($holidays) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($holidays as $holiday)
                                            <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            <i class="fas fa-gift text-danger mr-2"></i>
                                                            {{ $holiday['title'] }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            {{ \Carbon\Carbon::parse($holiday['date'])->format('F j, Y') }}
                                                        </small>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="badge badge-danger rounded">
                                                            <i class="fas fa-calendar-day mr-1"></i>
                                                            {{ \Carbon\Carbon::parse($holiday['date'])->format('M d') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">{{ __('policy.no_official_holidays_configured') }}</p>
                                    </div>
                                @endif
                            </div>

                        </div>

                        <!-- ✅ Company Policy Section (Grey) -->
                        <div class="card mb-4 shadow-sm rounded">
                            <div class="card shadow-sm"
                                style="border-radius: 10px; border: none; border-inline-start: 4px solid #6c757d;">
                                <div class="card-header d-flex justify-content-between align-items-center"
                                    style="background: white; color: #333; border: none; border-bottom: 1px solid #e9ecef;">
                                    <h3 class="card-title" style="font-weight: 600; font-size: 1.1rem; color: #6c757d;">
                                        <i class="fas fa-file-pdf mr-2"></i> {{ __('h_dashboard.company_policy') }}
                                    </h3>
                                </div>

                                <div class="card-body" style="background: white; padding: 1.5rem;">

                                    @if (isset($setting) && isset($setting->company_policy_pdf) && !empty($setting->company_policy_pdf))
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
                                                <p class="mb-1 font-weight-bold"
                                                    style="font-size: 0.95rem; color: #495057;">
                                                    {{ __('h_dashboard.company_policy_document') }}
                                                </p>
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                                    {{ __('h_dashboard.company_policy_uploaded') }}
                                                </p>
                                            </div>

                                            <a href="{{ route('company-policy.view', $setting->company_policy_pdf) }}"
                                                target="_blank" class="btn btn-secondary btn-sm"
                                                style="font-size: 0.85rem; padding: 0.5rem 1rem;">
                                                <i class="fas fa-eye mr-1"></i> {{ __('h_dashboard.view') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-file-pdf text-muted mb-3"
                                                style="font-size: 3rem; opacity: 0.3;"></i>
                                            <p class="text-muted mb-0" style="font-size: 0.95rem;">
                                                {{ __('h_dashboard.no_company_policy') }}
                                            </p>
                                            <small
                                                class="text-muted">{{ __('h_dashboard.policy_will_appear_here') }}</small>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
