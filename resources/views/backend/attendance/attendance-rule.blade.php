@extends('backend.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vacation-balance-route" content="{{ route('attendance.update-vacation-balance') }}">
    <meta name="late-deduction-route" content="{{ route('attendance-rules.update-late-deduction') }}">
    <meta name="half-day-route" content="{{ route('attendance-rules.update-half-day') }}">
    <meta name="work-hours-route" content="{{ route('attendance.update-work-hours') }}">
    <meta name="bonus-per-hour-route" content="{{ route('attendance.update-bonus-per-hour') }}">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-clock mr-2"></i>Company Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active">Company Settings</li>
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
                                    Attendance Rules
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
                                            Late Arrival Deduction (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="late_deduction_percentage"
                                                name="late_deduction_percentage"
                                                value="{{ old('late_deduction_percentage', $setting->late_deduction_percentage ?? 0) }}"
                                                min="0" max="100" onchange="updateLateDeduction(this.value)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">% of daily wage</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Percentage deducted from daily wage when
                                            employee arrives late</small>
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
                                            Half Day Absence Deduction (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="half_day_deduction_percentage"
                                                name="half_day_deduction_percentage"
                                                value="{{ old('half_day_deduction_percentage', $setting->half_day_deduction_percentage ?? 0) }}"
                                                min="0" max="100" onchange="updateHalfDayDeduction(this.value)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">% of daily wage</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Percentage deducted from daily wage for half-day
                                            absences</small>
                                        <div id="half_day_deduction_feedback" class="mt-1"></div>
                                    </div>

                                    <!-- Include the external JavaScript file -->
                                    <script>
                                        const halfDayUpdateRoute = '{{ route('attendance-rules.update-half-day') }}';
                                    </script>




                                    <!-- Bonus Per Hour -->
                                    <div class="form-group">
                                        <label for="bonus_per_hour">
                                            <i class="fas fa-dollar-sign text-success mr-1"></i>                                            Bonus Per Hour
                                        </label>
                                        <input type="number" step="0.01" class="form-control" id="bonus_per_hour"
                                            name="bonus_per_hour"
                                            value="{{ old('bonus_per_hour', $setting->bonus_per_hour ?? 0) }}"
                                            min="0">
                                        <small class="form-text text-muted">Amount of bonus money paid per extra
                                            hour</small>
                                        <div id="bonus_per_hour_feedback" class="mt-1"></div>
                                    </div>




                                    <!-- Work Hours -->
                                    <meta name="work-hours-route" content="{{ route('attendance.update-work-hours') }}">

                                    <div class="form-group">
                                        <label for="work_hours_per_day">
                                            <i class="fas fa-clock text-primary mr-1"></i>
                                            Work Hours per Day
                                        </label>
                                        <input type="number" step="0.5" class="form-control"
                                            id="work_hours_per_day" name="work_hours_per_day"
                                            value="{{ old('work_hours_per_day', $setting->work_hours_per_day ?? 0) }}"
                                            min="1" max="24">
                                        <small class="form-text text-muted">Define the expected working hours per
                                            day</small>
                                        <div id="work_hours_feedback" class="mt-1"></div>
                                    </div>


                                    <!-- Working Days -->
                                    <div class="form-group">
                                        <label>
                                            <i class="fas fa-calendar-week text-success mr-1"></i>
                                            Working Days in Week
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
                                        <small class="form-text text-muted">Select the working days in a typical
                                            week</small>
                                    </div>


                                    <!-- Meta tags for vacation balance update -->
                                    <meta name="csrf-token" content="{{ csrf_token() }}">

                                    <!-- Vacation Balance -->
                                    <div class="form-group">
                                        <label for="vacation_balance">
                                            <i class="fas fa-suitcase-rolling text-warning mr-1"></i>
                                            Vacation Balance
                                        </label>
                                        <input type="number" class="form-control" id="vacation_balance"
                                            name="vacation_balance"
                                            value="{{ old('vacation_balance', $setting->vacation_balance ?? 0) }}"
                                            min="0">
                                        <small class="form-text text-muted">Total paid vacation days per year</small>
                                        <div id="vacation_balance_feedback" class="mt-1"></div>
                                    </div>


                                    <!-- Official Holidays -->
                                    <div class="form-group">
                                        <label for="official_holidays">
                                            <i class="fas fa-umbrella-beach text-danger mr-1"></i>
                                            Official Holidays (No Salary Deduction)
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
                                            <i class="fas fa-plus"></i> Add More Holidays
                                        </button>
                                        <small class="form-text text-muted">Add multiple holidays with titles and
                                            dates</small>
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
                                                    <strong>Summary:</strong>
                                                    <span class="info-box-number">
                                                        Late Arrival: <span
                                                            id="late_deduction_preview">{{ $setting->late_deduction_percentage ?? 0 }}</span>%
                                                        deduction
                                                    </span>

                                                    <div class="progress">
                                                        <div class="progress-bar" id="late_progress" style="width: 100%">
                                                        </div>
                                                    </div>
                                                    <span class="info-box-number mt-1">
                                                        Half Day: <span
                                                            id="half_day_deduction_preview">{{ $setting->half_day_deduction_percentage ?? 0 }}</span>%
                                                        deduction
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>




                                                    <!-- New Summary Items -->
                                                    <hr class="mt-3 mb-2">
                                                    <span class="info-box-number">
                                                        Bonus Per Hour: <span
                                                            id="work_hours_preview">{{ $setting->bonus_per_hour ?? 0 }}</span>
                                                        hrs
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                    <span class="info-box-number">
                                                        Work Hours/Day: <span
                                                            id="work_hours_preview">{{ $setting->work_hours_per_day ?? 0 }}</span>
                                                        hrs
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                    <span class="info-box-number">
                                                        <strong>Working Days:</strong>
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
                                                        <strong>Vacation Balance:</strong>
                                                        <span
                                                            id="vacation_balance_preview">{{ $setting->vacation_balance ?? 0 }}</span>
                                                        days
                                                    </span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="half_day_progress"
                                                            style="width: 100%"></div>
                                                    </div>


                                                    <span class="info-box-number">
                                                        Official Holidays:
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
                                                    <i class="fas fa-undo"></i> Refresh
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Save Policy
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
                                    Help & Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="callout callout-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i> Important Notes:</h5>
                                    <p>Changes to attendance Rules will make effect in payroll calculation but you must
                                        click save policy at the end.</p>
                                </div>

                                <div class="text-muted mt-3">
                                    <p><strong>Late Arrival:</strong> Applied when an employee clocks in after the attend
                                        period.</p>
                                    <p><strong>Half Day:</strong> Applied for arrivals after the half-day time .</p>
                                    <p><strong>Full Absence:</strong> Applies when an employee doesn't report to work
                                        without prior approval.</p>
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
