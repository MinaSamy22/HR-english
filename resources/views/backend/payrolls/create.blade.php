@extends('backend.layouts.app')
@section('content')
    {{-- mina payroll css --}}
    <link rel="stylesheet" href="{{ url('dist/css/payrollcreate.css') }}?v=4">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img//payroll3.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="card-header">
                        <h1 class="card-title">{{ __('dashboard.create_payroll') }}</h1>
                    </div>
                </div>
            </div>

            {{-- Display Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>{{ __('dashboard.error') }}!</strong>
                    <div style="white-space: pre-line; margin-top: 8px;">{!! session('error') !!}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Display Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>{{ __('dashboard.validation_errors') }}!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form class="form-horizontal" method="post" action="{{ url('admin/payroll/add') }}"
                enctype="multipart/form-data" id="addForm">
                {{ csrf_field() }}

                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label mb-0">{{ __('dashboard.payroll_type') }} <span style="color: red;">*</span></label>
                        <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="payroll_type" id="type_monthly"
                                    value="monthly" {{ old('payroll_type') == 'monthly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_monthly">{{ __('dashboard.monthly') }}</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="payroll_type" id="type_weekly"
                                    value="weekly" {{ old('payroll_type') == 'weekly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_weekly">{{ __('dashboard.weekly') }}</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="payroll_type" id="type_daily"
                                    value="daily" {{ old('payroll_type') == 'daily' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_daily">{{ __('dashboard.daily') }}</label>
                            </div>
                            <small id="type-error" class="text-danger d-block w-100 mt-1" style="display:none;"></small>
                        </div>
                    </div>
                    <hr><br>

                                        {{-- Branch Filter --}}
                    @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ __('h_employee.branch') }}</label>
                            <div class="col-sm-9">
                                <select name="filter_branch_id" id="filter_branch_id" class="form-control"
                                    style="background-color: rgba(255, 255, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.2);">
                                    <option value="">{{ __('h_employee.all') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('filter_branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{ __('dashboard.start_date') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{ __('dashboard.end_date') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{ __('dashboard.employee_name') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="checkbox-box">
                                <!-- Daily Employees -->
                                @if($getEmployees->where('salary_type', 3)->count() > 0)
                                    <div class="employee-category" id="daily-employees" style="display: none;">
                                        <h5 class="category-title">
                                            <i class="fas fa-calendar-day"></i> {{ __('dashboard.daily_wage_employees') }}
                                            <span class="employee-count" id="daily-count">(0/{{ $getEmployees->where('salary_type', 3)->count() }})</span>
                                        </h5>
                                        <div class="category-controls mb-2">
                                            <input type="checkbox" id="select-all-daily" class="select-all-category">
                                            <label for="select-all-daily"><strong>{{ __('dashboard.select_all_daily') }}</strong></label>
                                        </div>
                                        <div class="employee-list">
                                            @foreach ($getEmployees->where('salary_type', 3) as $employee)
                                                <div class="checkbox-item employee-item" data-branch-id="{{ $employee->branch_id }}">
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        id="employee-{{ $employee->id }}" class="employee-checkbox daily-employee"
                                                        {{ in_array($employee->id, old('employee_ids', [])) ? 'checked' : '' }}>
                                                    <label for="employee-{{ $employee->id }}">{{ $employee->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Weekly Employees -->
                                @if($getEmployees->where('salary_type', 2)->count() > 0)
                                    <div class="employee-category" id="weekly-employees" style="display: none;">
                                        <h5 class="category-title">
                                            <i class="fas fa-calendar-week"></i> {{ __('dashboard.weekly_wage_employees') }}
                                            <span class="employee-count" id="weekly-count">(0/{{ $getEmployees->where('salary_type', 2)->count() }})</span>
                                        </h5>
                                        <div class="category-controls mb-2">
                                            <input type="checkbox" id="select-all-weekly" class="select-all-category">
                                            <label for="select-all-weekly"><strong>{{ __('dashboard.select_all_weekly') }}</strong></label>
                                        </div>
                                        <div class="employee-list">
                                            @foreach ($getEmployees->where('salary_type', 2) as $employee)
                                                <div class="checkbox-item employee-item" data-branch-id="{{ $employee->branch_id }}">
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        id="employee-{{ $employee->id }}" class="employee-checkbox weekly-employee"
                                                        {{ in_array($employee->id, old('employee_ids', [])) ? 'checked' : '' }}>
                                                    <label for="employee-{{ $employee->id }}">{{ $employee->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Monthly Employees -->
                                @if($getEmployees->where('salary_type', 1)->count() > 0)
                                    <div class="employee-category" id="monthly-employees" style="display: none;">
                                        <h5 class="category-title">
                                            <i class="fas fa-calendar-alt"></i> {{ __('dashboard.monthly_wage_employees') }}
                                            <span class="employee-count" id="monthly-count">(0/{{ $getEmployees->where('salary_type', 1)->count() }})</span>
                                        </h5>
                                        <div class="category-controls mb-2">
                                            <input type="checkbox" id="select-all-monthly" class="select-all-category">
                                            <label for="select-all-monthly"><strong>{{ __('dashboard.select_all_monthly') }}</strong></label>
                                        </div>
                                        <div class="employee-list">
                                            @foreach ($getEmployees->where('salary_type', 1) as $employee)
                                                <div class="checkbox-item employee-item" data-branch-id="{{ $employee->branch_id }}">
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        id="employee-{{ $employee->id }}" class="employee-checkbox monthly-employee"
                                                        {{ in_array($employee->id, old('employee_ids', [])) ? 'checked' : '' }}>
                                                    <label for="employee-{{ $employee->id }}">{{ $employee->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <button type="submit" id="submitBtn" class="btn btn-white float-right" style="background-color: #ffc852;">{{ __('dashboard.calculate_payroll') }} </button>
                    </div>

                </form>
        </div>
    </div>

<!-- Link to the new JavaScript file -->
<!-- Pass translations to JavaScript -->
<script>
    window.payrollTranslations = {
        select_payroll_type: "{{ __('h_payroll.select_payroll_type') }}",
        daily_exceeds_limit: "{{ __('h_payroll.daily_exceeds_limit') }}",
        weekly_exceeds_limit: "{{ __('h_payroll.weekly_exceeds_limit') }}",
        monthly_minimum_days: "{{ __('h_payroll.monthly_minimum_days') }}",
        monthly_exceeds_limit: "{{ __('h_payroll.monthly_exceeds_limit') }}"
    };
</script>
<script src="{{ url('dist/js/payrollcreate.js')}}?v=3"></script>

<script>
// Branch filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const branchFilter = document.getElementById('filter_branch_id');

    if (branchFilter) {
        branchFilter.addEventListener('change', function() {
            const selectedBranchId = this.value;
            const employeeItems = document.querySelectorAll('.employee-item');

            employeeItems.forEach(function(item) {
                const checkbox = item.querySelector('.employee-checkbox');
                const employeeBranchId = item.getAttribute('data-branch-id');

                if (selectedBranchId === '' || employeeBranchId === selectedBranchId) {
                    // Show employee
                    item.style.display = 'block';
                    checkbox.disabled = false;
                } else {
                    // Hide employee and uncheck
                    item.style.display = 'none';
                    checkbox.checked = false;
                    checkbox.disabled = true;
                }
            });

            // Update counts after filtering
            updateEmployeeCounts();
        });
    }

    // Function to update employee counts
    function updateEmployeeCounts() {
        // Update daily count
        const dailyVisible = document.querySelectorAll('#daily-employees .employee-item[style="display: block;"], #daily-employees .employee-item:not([style*="display: none"])').length;
        const dailyChecked = document.querySelectorAll('#daily-employees .employee-checkbox:checked:not(:disabled)').length;
        const dailyCountEl = document.getElementById('daily-count');
        if (dailyCountEl) {
            dailyCountEl.textContent = `(${dailyChecked}/${dailyVisible})`;
        }

        // Update weekly count
        const weeklyVisible = document.querySelectorAll('#weekly-employees .employee-item[style="display: block;"], #weekly-employees .employee-item:not([style*="display: none"])').length;
        const weeklyChecked = document.querySelectorAll('#weekly-employees .employee-checkbox:checked:not(:disabled)').length;
        const weeklyCountEl = document.getElementById('weekly-count');
        if (weeklyCountEl) {
            weeklyCountEl.textContent = `(${weeklyChecked}/${weeklyVisible})`;
        }

        // Update monthly count
        const monthlyVisible = document.querySelectorAll('#monthly-employees .employee-item[style="display: block;"], #monthly-employees .employee-item:not([style*="display: none"])').length;
        const monthlyChecked = document.querySelectorAll('#monthly-employees .employee-checkbox:checked:not(:disabled)').length;
        const monthlyCountEl = document.getElementById('monthly-count');
        if (monthlyCountEl) {
            monthlyCountEl.textContent = `(${monthlyChecked}/${monthlyVisible})`;
        }
    }
});
</script>
