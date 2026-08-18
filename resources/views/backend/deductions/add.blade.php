@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/deduction.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_deduction.add_deduction') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('h_deduction.add') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_deduction.deductions') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_deduction.add_deduction') }}</h3>
                            </div>

                            <form class="form-horizontal" method="post" action="{{ url('admin/deductions/add') }}"
                                enctype="multipart/form-data" id="addForm">
                                {{ csrf_field() }}

                                <div class="card-body">

                                    {{-- Employee Selection --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_deduction.employee_name') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="employee_id" id="employeeSelect" required>
                                                <option value="">{{ __('h_deduction.select_employee') }}</option>
                                                @foreach ($getUsers as $value_users)
                                                    <option value="{{ $value_users->id }}">{{ $value_users->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>




                                    {{-- Deduction Reason --}}
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-lable">{{ __('h_deduction.deduction_reason') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span> </label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('deduction_type') }}" name="deduction_type"
                                                class="form-control" placeholder="{{ __('h_deduction.enter_reason') }}">
                                        </div>
                                    </div>
                                    {{-- Days to Deduct --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_deduction.days_to_deduct') }}</label>
                                        <div class="col-sm-10">
                                            <input type="decimal" id="deduct_days" name="deduction_days"
                                                class="form-control" placeholder="{{ __('h_deduction.enter_number_of_days') }}">

                                        </div>
                                    </div>

                                    {{-- Deduction Amount --}}
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-lable">{{ __('h_deduction.money_deduction') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="decimal" value="{{ old('money_deduction') }}"
                                                name="money_deduction" id="money_deduction" class="form-control"
                                                placeholder="{{ __('h_deduction.enter_money_deduction') }}">
                                        </div>
                                    </div>

                                    {{-- Date --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_deduction.date') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="datetime-local" value="{{ old('created_at') }}" name="created_at"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/deductions') }}"
                                        class="btn btn-default float-left">{{ __('h_deduction.back') }}</a>
                                    <button type="submit" id="submitBtn"
                                        class="btn btn-primary float-right">{{ __('h_deduction.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

        {{-- Hidden employees data for calculation --}}
    <script>
        let employees = @json($getUsers->map(function($u){
            return [
                'id' => $u->id,
                'salary' => $u->salary,
                'working_days' => is_array($u->working_days) ? count($u->working_days) : 30
            ];
        }));
    </script>

    {{-- Auto-calculation JS --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let employeeSelect = document.getElementById("employeeSelect");
            let daysInput = document.getElementById("deduct_days");
            let moneyInput = document.getElementById("money_deduction");

            function calculateDeduction() {
                let empId = employeeSelect.value;
                let days = parseFloat(daysInput.value);

                if (!empId || !days) return;

                let emp = employees.find(e => e.id == empId);
                if (!emp) return;

                let salary = parseFloat(emp.salary);
                let workingDays = parseInt(emp.working_days);
                if (workingDays <= 0) workingDays = 30;

                let dailyWage = salary / workingDays;
                let deductionValue = dailyWage * days;

                moneyInput.value = deductionValue.toFixed(2);
            }

            employeeSelect.addEventListener('change', calculateDeduction);
            daysInput.addEventListener('input', calculateDeduction);
        });

        // Prevent double submit on add form
        const addForm = document.getElementById('addForm');
        const submitBtn = document.getElementById('submitBtn');
        let isSubmitting = false;

        addForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('h_deduction.submit') }}...';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
        });

        // Re-enable button if user goes back
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __('h_deduction.submit') }}';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        });
    </script>
@endsection
