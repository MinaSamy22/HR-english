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
                        <h1 class="card-title">Create Payroll</h1>
                    </div>
                </div>
            </div>

            <form class="form-horizontal" method="post" action="{{ url('admin/payroll/add') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}

                    <div class="form-group row align-items-center">
                        <label class="col-sm-3 col-form-label mb-0">Payroll Type <span style="color: red;">*</span></label>
                        <div class="col-sm-9 d-flex align-items-center gap-3 flex-wrap">
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="payroll_type" id="type_monthly"
                                    value="monthly">
                                <label class="form-check-label" for="type_monthly">Monthly</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="payroll_type" id="type_weekly"
                                    value="weekly">
                                <label class="form-check-label" for="type_weekly">Weekly</label>
                            </div>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="radio" name="payroll_type" id="type_daily"
                                    value="daily">
                                <label class="form-check-label" for="type_daily">Daily</label>
                            </div>
                            <small id="type-error" class="text-danger d-block w-100 mt-1" style="display:none;"></small>
                        </div>
                    </div>
                    <hr><br>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Start Date<span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">End Date<span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Employee Name<span style="color: red;">*</span></label>
                        <div class="col-sm-9">
                            <div class="checkbox-box">
                                <!-- Daily Employees -->
                                @if($getEmployees->where('salary_type', 3)->count() > 0)
                                    <div class="employee-category" id="daily-employees" style="display: none;">
                                        <h5 class="category-title">
                                            <i class="fas fa-calendar-day"></i> Daily Wage Employees
                                            <span class="employee-count" id="daily-count">(0/{{ $getEmployees->where('salary_type', 3)->count() }})</span>
                                        </h5>
                                        <div class="category-controls mb-2">
                                            <input type="checkbox" id="select-all-daily" class="select-all-category">
                                            <label for="select-all-daily"><strong>Select All Daily</strong></label>
                                        </div>
                                        <div class="employee-list">
                                            @foreach ($getEmployees->where('salary_type', 3) as $employee)
                                                <div class="checkbox-item">
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        id="employee-{{ $employee->id }}" class="employee-checkbox daily-employee">
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
                                            <i class="fas fa-calendar-week"></i> Weekly Wage Employees
                                            <span class="employee-count" id="weekly-count">(0/{{ $getEmployees->where('salary_type', 2)->count() }})</span>
                                        </h5>
                                        <div class="category-controls mb-2">
                                            <input type="checkbox" id="select-all-weekly" class="select-all-category">
                                            <label for="select-all-weekly"><strong>Select All Weekly</strong></label>
                                        </div>
                                        <div class="employee-list">
                                            @foreach ($getEmployees->where('salary_type', 2) as $employee)
                                                <div class="checkbox-item">
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        id="employee-{{ $employee->id }}" class="employee-checkbox weekly-employee">
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
                                            <i class="fas fa-calendar-alt"></i> Monthly Wage Employees
                                            <span class="employee-count" id="monthly-count">(0/{{ $getEmployees->where('salary_type', 1)->count() }})</span>
                                        </h5>
                                        <div class="category-controls mb-2">
                                            <input type="checkbox" id="select-all-monthly" class="select-all-category">
                                            <label for="select-all-monthly"><strong>Select All Monthly</strong></label>
                                        </div>
                                        <div class="employee-list">
                                            @foreach ($getEmployees->where('salary_type', 1) as $employee)
                                                <div class="checkbox-item">
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        id="employee-{{ $employee->id }}" class="employee-checkbox monthly-employee">
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
                        <button type="submit" class="btn btn-white float-right" style="background-color: #ffc852;">Calculate Payroll </button>
                    </div>

                </form>
        </div>
    </div>



<!-- Link to the new JavaScript file -->
<script src="{{ url('dist/js/payrollcreate.js')}}?v=3"></script>


@endsection

