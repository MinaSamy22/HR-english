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
                enctype="multipart/form-data">
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
                                                <div class="checkbox-item">
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
                                                <div class="checkbox-item">
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
                                                <div class="checkbox-item">
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
                        <button type="submit" class="btn btn-white float-right" style="background-color: #ffc852;">{{ __('dashboard.calculate_payroll') }} </button>
                    </div>

                </form>
        </div>
    </div>

<!-- Link to the new JavaScript file -->
<script src="{{ url('dist/js/payrollcreate.js')}}?v=3"></script>

@endsection
