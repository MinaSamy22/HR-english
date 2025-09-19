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




                                    <!-- Work Hours Assignment -->
<meta name="employees-work-hours-route"
    content="{{ route('attendance.update-employee-work-hours') }}">

<div class="row">
    <!-- Employee Work Hours -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-users text-primary mr-2"></i>{{ __('rules.assign_work_hours') }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>{{ __('rules.select_work_hours') }}</label>
                    <input type="number" step="0.5" min="1" max="24"
                        class="form-control" id="assign_hours" >
                </div>
                <button type="button" class="btn btn-success btn-block"
                    id="assign_hours_btn">
                    <i class="fas fa-check mr-1"></i>
                    {{ __('rules.assign_hours_to_selected_employees') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Employees List -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">{{ __('rules.employees') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="select_all">
                        </th>
                        <th>{{ __('rules.employee_name') }}</th>
                        <th>{{ __('rules.email') }}</th>
                        <th width="150">{{ __('rules.current_hours') }}</th>
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
                                    {{ __('rules.hrs') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Alert Area -->
<div id="alert_area"></div>

                                    <!-- Working Days -->
                                    <div class="form-group">
                                        <label>
                                            <i class="fas fa-calendar-week text-success mr-1"></i>
                                            {{ __('dashboard.working_days_in_week') }}
                                        </label>
                                        @php
                                            $workingDays = isset($setting->working_days)
                                                ? json_decode($setting->working_days, true)
                                                : ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                        @endphp
                                        <div class="row">
                                            @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                                <div class="col-md-4">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="work_day_{{ $day }}" name="working_days[]"
                                                            value="{{ $day }}"
                                                            {{ in_array($day, $workingDays) ? 'checked' : '' }}
                                                            onchange="updatePreview()">
                                                        <label class="custom-control-label"
                                                            for="work_day_{{ $day }}">{{ $day }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small
                                            class="form-text text-muted">{{ __('dashboard.select_the_working_days_in_a_typical_week') }}</small>
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


                                </div>
                                <!-- /Form End -->

                                <!-- Preview Section -->
                                <div class="card-footer bg-light">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="info-box bg-gradient-info">
                                                <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                                <div class="info-box-content">
                                                    <strong>{{ __('dashboard.summary') }}:</strong>
                                                    <span class="info-box-number">
                                                        {{ __('dashboard.late_arrival') }}: <span
                                                            id="late_deduction_preview">{{ $setting->late_deduction_percentage ?? 0 }}</span>%
                                                        {{ __('dashboard.deduction') }}
                                                    </span>

                                                    <div class="progress">
                                                        <div class="progress-bar" id="late_progress" style="width: 100%">
                                                        </div>
                                                    </div>
                                                    <span class="info-box-number mt-1">
                                                        {{ __('dashboard.half_day') }}: <span
                                                            id="half_day_deduction_preview">{{ $setting->half_day_deduction_percentage ?? 0 }}</span>%
                                                        {{ __('dashboard.deduction') }}
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>




                                                    <!-- New Summary Items -->
                                                    <hr class="mt-3 mb-2">
                                                    <span class="info-box-number">
                                                        {{ __('dashboard.bonus_per_hour') }}: <span
                                                            id="work_hours_preview">{{ $setting->bonus_per_hour ?? 0 }}</span>
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                    {{-- <span class="info-box-number">
                                                        {{ __('dashboard.work_hours_per_day') }}: <span
                                                            id="work_hours_preview">{{ $setting->work_hours_per_day ?? 0 }}</span>
                                                        {{ __('dashboard.hrs') }}
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div> --}}


                                                    <span class="info-box-number">
                                                        <strong>{{ __('dashboard.working_days') }}:</strong>
                                                        <span id="working_days_preview">
                                                            {{ $setting && isset($setting->working_days) && $setting->working_days
                                                                ? implode(', ', json_decode($setting->working_days))
                                                                : 'Not Set' }}
                                                        </span>
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                    <span class="info-box-number">
                                                        <strong>{{ __('dashboard.vacation_balance') }}:</strong>
                                                        <span
                                                            id="vacation_balance_preview">{{ $setting->vacation_balance ?? 0 }}</span>
                                                        {{ __('dashboard.days') }}
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                    <span class="info-box-number">
                                                        {{ __('dashboard.official_holidays') }}:
                                                        <ul id="official_holidays_preview" class="pl-3 mb-0">
                                                            @foreach (json_decode($setting->official_holidays ?? '[]') as $holiday)
                                                                <li>{{ $holiday->title }} -
                                                                    {{ \Carbon\Carbon::parse($holiday->date)->format('F j, Y') }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-right mt-4">
                                                <button type="button" class="btn btn-default mr-2"
                                                    onclick="location.reload()">
                                                    <i class="fas fa-undo"></i> {{ __('dashboard.refresh') }}
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> {{ __('dashboard.save_policy') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Preview Section -->
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

    <script src="{{ url('dist\js\attendance settings\working-days.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\work-hours.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\holidays.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\lateDeduction.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\half-day.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\vacation-balance.js') }}"></script>
    <script src="{{ url('dist\js\attendance settings\bounas.js') }}"></script>

@endsection
