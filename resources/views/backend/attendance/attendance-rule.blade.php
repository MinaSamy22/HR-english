@extends('backend.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vacation-balance-route" content="{{ route('attendance.update-vacation-balance') }}">
    <meta name="late-deduction-route" content="{{ route('attendance-rules.update-late-deduction') }}">
    <meta name="half-day-route" content="{{ route('attendance-rules.update-half-day') }}">
    <meta name="work-hours-route" content="{{ route('attendance.update-work-hours') }}">
    <meta name="bonus-per-hour-route" content="{{ route('attendance.update-bonus-per-hour') }}">
    <meta name="employees-work-hours-route" content="{{ route('attendance.update-employee-work-hours') }}">
    {{-- Translation meta tags --}}
    <meta name="msg-select-employee" content="{{ __('rules.no_employees_selected') }}">
    <meta name="msg-invalid-hours" content="{{ __('rules.invalid_hours') }}">
    <meta name="msg-updating" content="{{ __('Updating...') }}">
    <meta name="msg-assign-hours" content="{{ __('rules.assign_hours_to_selected_employees') }}">
    <meta name="msg-update-failed" content="{{ __('rules.error_message') }}">
    <meta name="msg-hrs" content="{{ __('rules.hrs') }}">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-clock mr-2"></i>{{ __('dashboard.company_settings') }}</h1>
                    </div>
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('dashboard.settings') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.company_settings') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Card -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-cog mr-1"></i>
                                    {{ __('dashboard.attendance_rules') }}
                                </h3>
                            </div>

                            <!-- Form Start -->
                            <form id="attendancePolicyForm" method="POST" action="{{ route('attendance-rule.save') }}">
                                @csrf

                                <div class="card-body">

                                    <!-- Late Deduction -->
                                    <div class="form-group">
                                        <label for="late_deduction_percentage">
                                            <i class="fas fa-hourglass-half text-warning mr-1"></i>
                                            {{ __('dashboard.late_arrival_deduction') }} (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="late_deduction_percentage"
                                                name="late_deduction_percentage"
                                                value="{{ old('late_deduction_percentage', $setting->late_deduction_percentage ?? 0) }}"
                                                min="0" max="100" onchange="updateLateDeduction(this.value)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">% {{ __('dashboard.of_daily_wage') }}</span>
                                            </div>
                                        </div>
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.percentage_deducted_from_daily_wage_when_employee_arrives_late') }}</small>
                                        <div id="lateDeductionFeedback" class="mt-2"></div>
                                    </div>
                                    <script>
                                        const updateLateDeductionUrl = '{{ route('attendance-rules.update-late-deduction') }}';
                                        const csrfToken = '{{ csrf_token() }}';
                                    </script>

                                    <!-- Half Day Deduction -->
                                    <div class="form-group">
                                        <label for="half_day_deduction_percentage">
                                            <i class="fas fa-calendar-day text-info mr-1"></i>
                                            {{ __('dashboard.half_day_absence_deduction') }} (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="half_day_deduction_percentage"
                                                name="half_day_deduction_percentage"
                                                value="{{ old('half_day_deduction_percentage', $setting->half_day_deduction_percentage ?? 0) }}"
                                                min="0" max="100" onchange="updateHalfDayDeduction(this.value)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">% {{ __('dashboard.of_daily_wage') }}</span>
                                            </div>
                                        </div>
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.percentage_deducted_from_daily_wage_for_half_day_absences') }}</small>
                                        <div id="half_day_deduction_feedback" class="mt-1"></div>
                                    </div>

                                    <!-- Include the external JavaScript file -->
                                    <script>
                                        const halfDayUpdateRoute = '{{ route('attendance-rules.update-half-day') }}';
                                    </script>

                                    <!-- Bonus Per Hour -->
                                    <div class="form-group">
                                        <label for="bonus_per_hour">
                                            <i class="fas fa-dollar-sign text-success mr-1"></i>
                                            {{ __('dashboard.bonus_per_hour') }}
                                        </label>
                                        <input type="number" step="0.01" class="form-control" id="bonus_per_hour"
                                            name="bonus_per_hour"
                                            value="{{ old('bonus_per_hour', $setting->bonus_per_hour ?? 0) }}"
                                            min="0">
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.amount_of_bonus_money_paid_per_extra_hour') }}</small>
                                        <div id="bonus_per_hour_feedback" class="mt-1"></div>
                                    </div>

                                    <!-- Work Hours and Working Days Assignment -->
                                    <div class="row">
                                        <!-- Employee Work Hours -->
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5><i
                                                            class="fas fa-users text-primary mr-2"></i>{{ __('dashboard.assign_work_hours') }}
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.select_work_hours') }}</label>
                                                        <input type="number" step="0.5" min="1"
                                                            max="24" class="form-control" id="assign_hours">
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-block"
                                                        id="assign_hours_btn">
                                                        <i class="fas fa-check mr-1"></i>
                                                        {{ __('dashboard.assign_hours_to_selected_employees') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Employee Working Days -->
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5><i
                                                            class="fas fa-calendar-week text-success mr-2"></i>{{ __('dashboard.assign_working_days') }}
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.select_working_days') }}</label>
                                                        <div class="row" id="working_days_selection">
                                                            @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                                                <div class="col-md-6">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox"
                                                                            class="custom-control-input working-day-checkbox"
                                                                            id="assign_day_{{ $day }}"
                                                                            value="{{ $day }}">
                                                                        <label class="custom-control-label"
                                                                            for="assign_day_{{ $day }}">
                                                                            {{ __('dashboard.' . strtolower($day)) }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-block"
                                                        id="assign_working_days_btn">
                                                        <i class="fas fa-calendar-check mr-1"></i>
                                                        {{ __('dashboard.assign_working_days_to_selected_employees') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alert Area -->
                                    <div id="alert_area"></div>

                                    <!-- Single Consolidated Employees List -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">{{ __('dashboard.employees') }}</h5>
                                                <small
                                                    class="text-muted">{{ __('dashboard.select_employees_to_update') }}</small>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="50">
                                                                <input type="checkbox" id="select_all">
                                                            </th>
                                                            <th>{{ __('dashboard.employee_name') }}</th>
                                                            <th>{{ __('dashboard.email') }}</th>
                                                            <th width="120">{{ __('dashboard.current_hours') }}</th>
                                                            <th width="300">{{ __('dashboard.working_days') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="employees_table">
                                                        @foreach ($employees as $employee)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" class="employee_check"
                                                                        value="{{ $employee->id }}">
                                                                </td>
                                                                <td>
                                                                    <strong>{{ $employee->name }}</strong>
                                                                </td>
                                                                <td class="text-muted">{{ $employee->email }}</td>
                                                                <td>
                                                                    <span class="badge badge-info employee-hours"
                                                                        data-id="{{ $employee->id }}">
                                                                        {{ $employee->work_hours_per_day ?? ($setting->work_hours_per_day ?? 8) }}
                                                                        {{ __('dashboard.hrs') }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $employeeWorkingDays = isset(
                                                                            $employee->working_days,
                                                                        )
                                                                            ? json_decode($employee->working_days, true)
                                                                            : (isset($setting->working_days)
                                                                                ? json_decode(
                                                                                    $setting->working_days,
                                                                                    true,
                                                                                )
                                                                                : [
                                                                                    'Monday',
                                                                                    'Tuesday',
                                                                                    'Wednesday',
                                                                                    'Thursday',
                                                                                    'Friday',
                                                                                ]);

                                                                        // Convert full day names to abbreviations for display
                                                                        $dayAbbreviations = [
                                                                            'Sunday' => __('dashboard.sun'),
                                                                            'Monday' => __('dashboard.mon'),
                                                                            'Tuesday' => __('dashboard.tue'),
                                                                            'Wednesday' => __('dashboard.wed'),
                                                                            'Thursday' => __('dashboard.thu'),
                                                                            'Friday' => __('dashboard.fri'),
                                                                            'Saturday' => __('dashboard.sat'),
                                                                        ];
                                                                    @endphp
                                                                    <div class="employee-working-days"
                                                                        data-id="{{ $employee->id }}">
                                                                        @if (is_array($employeeWorkingDays) && count($employeeWorkingDays) > 0)
                                                                            @foreach ($employeeWorkingDays as $day)
                                                                                <span class="badge badge-secondary mr-1">
                                                                                    {{ $dayAbbreviations[$day] ?? $day }}
                                                                                </span>
                                                                            @endforeach
                                                                        @else
                                                                            <span
                                                                                class="badge badge-warning">{{ __('dashboard.not_set') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Meta tags for vacation balance update -->
                                    <meta name="csrf-token" content="{{ csrf_token() }}">

                                    <!-- Vacation Balance -->
                                    <div class="form-group">
                                        <label for="vacation_balance">
                                            <i class="fas fa-suitcase-rolling text-warning mr-1"></i>
                                            {{ __('dashboard.vacation_balance') }}
                                        </label>
                                        <input type="number" class="form-control" id="vacation_balance"
                                            name="vacation_balance"
                                            value="{{ old('vacation_balance', $setting->vacation_balance ?? 0) }}"
                                            min="0">
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.total_paid_vacation_days_per_year') }}</small>
                                        <div id="vacation_balance_feedback" class="mt-1"></div>
                                    </div>

                                    <!-- Official Holidays -->
                                    <div class="form-group">
                                        <label for="official_holidays">
                                            <i class="fas fa-umbrella-beach text-danger mr-1"></i>
                                            {{ __('dashboard.official_holidays_no_salary_deduction') }}
                                        </label>
                                        <div id="holiday-container">
                                            @php
                                                $holidays = isset($setting->official_holidays)
                                                    ? json_decode($setting->official_holidays, true)
                                                    : [];
                                            @endphp

                                            @if (count($holidays) > 0)
                                                @foreach ($holidays as $index => $holiday)
                                                    <div class="holiday-entry mb-2 row">
                                                        <div class="col-md-4">
                                                            <input type="date" class="form-control holiday-date"
                                                                name="holiday_dates[]"
                                                                value="{{ $holiday['date'] ?? '' }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control holiday-title"
                                                                name="holiday_titles[]"
                                                                value="{{ $holiday['title'] ?? '' }}"
                                                                placeholder="Holiday title" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button"
                                                                class="btn btn-danger remove-holiday-btn">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="holiday-entry mb-2 row">
                                                    <div class="col-md-4">
                                                        <input type="date" class="form-control holiday-date"
                                                            name="holiday_dates[]" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control holiday-title"
                                                            name="holiday_titles[]" placeholder="Holiday title" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger remove-holiday-btn">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-success mt-2" id="add-holiday-btn">
                                            <i class="fas fa-plus"></i> {{ __('dashboard.add_more_holidays') }}
                                        </button>
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.add_multiple_holidays_with_titles_and_dates') }}</small>
                                    </div>


                                    <!-- Timezone -->
                                    <div class="form-group">
                                        <label for="timezone">
                                            <i class="fas fa-globe text-success mr-1"></i>
                                            {{ __('dashboard.timezone') }}
                                        </label>
                                        <select class="form-control select2" id="timezone" name="timezone"
                                            style="width: 100%;">
                                            @php
                                                $selectedTimezone = old(
                                                    'timezone',
                                                    $setting->timezone ?? config('app.timezone'),
                                                );
                                                $timezones = \DateTimeZone::listIdentifiers();
                                            @endphp
                                            @foreach ($timezones as $tz)
                                                <option value="{{ $tz }}"
                                                    {{ $tz == $selectedTimezone ? 'selected' : '' }}>
                                                    {{ $tz }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.select_your_company_timezone') }}</small>
                                        <div id="timezone_feedback" class="mt-1"></div>
                                    </div>


                                </div>
                                <!-- /Form End -->

                                {{-- Include the separated summary partial --}}
                                @include('backend.attendance.attendance-summary', compact('setting'))
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-md-4">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ __('dashboard.help_information') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="callout callout-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i> {{ __('dashboard.important_notes') }}:
                                    </h5>
                                    <p>{{ __('dashboard.changes_to_attendance_rules_will_make_effect_in_payroll_calculation_but_you_must_click_save_policy_at_the_end') }}
                                    </p>
                                </div>

                                <div class="text-muted mt-3">
                                    <p><strong>{{ __('dashboard.late-arrival') }}</strong>
                                        {{ __('dashboard.applied_when_an_employee_clocks_in_after_the_attend_period') }}.
                                    </p>
                                    <p><strong>{{ __('dashboard.half_day') }}:</strong>
                                        {{ __('dashboard.applied_for_arrivals_after_the_half_day_time') }} .</p>
                                    <p><strong>{{ __('dashboard.full_absence') }}:</strong>
                                        {{ __('dashboard.applies_when_an_employee_doesnt_report_to_work_without_prior_approval') }}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ url('dist/js/attendance settings/workhours-days.js?v=2') }}"></script>
    <script src="{{ url('dist\js\attendance settings\holidays.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\lateDeduction.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\half-day.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\vacation-balance.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\bounas.js') }}"></script>

@endsection
