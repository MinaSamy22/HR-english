@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_employee.employees') }}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_employee.add_breadcrumb') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_employee.employees_breadcrumb') }}</li>
                        </ol>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_employee.add_employee') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" accept="{{ url('admin/employees/add') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.name') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') }}" name="name"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.phone_number') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('phone_number') }}" name="phone_number"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_phone_number') }}">
                                            <span style="color:red">{{ $errors->first('phone_number') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-lable">{{ __('h_employee.mobile_mac_address') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('macaddress') }}" name="macaddress"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_mac_address_optional') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.email') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="email" value="{{ old('email') }}" name="email"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_email') }}">
                                            <span style="color:red">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.birth_date') }}<span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('birth_date') }}" name="birth_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.hire_date') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('hire_date') }}" name="hire_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.job_title') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="job_id" required>
                                                <option value="">{{ __('h_employee.select_job_title') }}</option>
                                                @foreach ($getJobs as $value_job)
                                                    <option value="{{ $value_job->id }}">{{ $value_job->job_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.salary_type') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select name="salary_type" class="form-control" required>
                                                <option value="">{{ __('h_employee.select_salary_type') }}</option>
                                                <option value="1">{{ __('h_employee.monthly_salary') }}</option>
                                                <option value="2">{{ __('h_employee.weekly_wage') }}</option>
                                                <option value="3">{{ __('h_employee.daily_wage') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.salary') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('salary') }}" name="salary"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_salary') }}">
                                            <span style="color:red">{{ $errors->first('salary') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_employee.main_salary') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="main_salary"
                                                    id="main_salary_yes" value="1"
                                                    {{ old('main_salary') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="main_salary_yes">
                                                    {{ __('h_employee.yes') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="main_salary"
                                                    id="main_salary_no" value="0"
                                                    {{ old('main_salary') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="main_salary_no">
                                                    {{ __('h_employee.no') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Salary field -->
                                    <div class="form-group row align-items-center" id="additional_salary_field"
                                        style="display:none;">
                                        <label class="col-sm-2 col-form-label">
                                          {{ __('dashboard.additional_salary') }}
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="number" name="additional_salary" class="form-control"
                                               placeholder="{{ __('dashboard.enter_additional_salary') }}" step="0.01">
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const yesRadio = document.getElementById('main_salary_yes');
                                            const noRadio = document.getElementById('main_salary_no');
                                            const additionalSalaryField = document.getElementById('additional_salary_field');

                                            function toggleAdditionalField() {
                                                if (noRadio.checked) {
                                                    additionalSalaryField.style.display = 'flex';
                                                } else {
                                                    additionalSalaryField.style.display = 'none';
                                                }
                                            }

                                            yesRadio.addEventListener('change', toggleAdditionalField);
                                            noRadio.addEventListener('change', toggleAdditionalField);
                                            toggleAdditionalField(); // run on page load
                                        });
                                    </script>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"
                                            for="attachment">{{ __('h_employee.attachment') }}:</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="attachment" class="form-control"
                                                accept="application/pdf">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.work_start_time') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="time" value="{{ old('work_start_time') }}"
                                                name="work_start_time" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.work_end_time') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="time" value="{{ old('work_end_time') }}"
                                                name="work_end_time" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_employee.shift_count') }} <span style="color: red;">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="shift_count" id="shift_count" class="form-control" required>
                                                <option value="1"
                                                    {{ old('shift_count', $getRecord->shift_count ?? 1) == 1 ? 'selected' : '' }}>
                                                    {{ __('h_employee.one_shift') }}
                                                </option>
                                                <option value="2"
                                                    {{ old('shift_count', $getRecord->shift_count ?? 1) == 2 ? 'selected' : '' }}>
                                                    {{ __('h_employee.two_shifts') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="secondShiftFields" style="display: none;">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('h_employee.second_work_start_time') }}</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="second_work_start_time"
                                                    value="{{ old('second_work_start_time', $getRecord->second_work_start_time ?? '') }}"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('h_employee.second_work_end_time') }}</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="second_work_end_time"
                                                    value="{{ old('second_work_end_time', $getRecord->second_work_end_time ?? '') }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label">{{ __('h_employee.work_hours_per_day') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="work_hours_per_day"
                                                value="{{ old('work_hours_per_day', $getRecord->work_hours_per_day ?? '') }}"
                                                class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.free_biometric') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_biometric"
                                                    id="biometric_yes" value="1"
                                                    {{ old('is_biometric') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="biometric_yes">{{ __('h_employee.yes') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_biometric"
                                                    id="biometric_no" value="0"
                                                    {{ old('is_biometric') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="biometric_no">{{ __('h_employee.no') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.manager_name') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="manager_id" required>
                                                <option value="">{{ __('h_employee.select_manager_name') }}</option>
                                                @foreach ($getManagers as $value_manager)
                                                    <option value="{{ $value_manager->id }}">{{ $value_manager->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.department_name') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="department_id" required>
                                                <option value="">{{ __('h_employee.select_department') }}</option>
                                                @foreach ($getDepartments as $value_department)
                                                    <option value="{{ $value_department->id }}">
                                                        {{ $value_department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.role') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="is_role" id="roleSelect" required>
                                                <option value="">{{ __('h_employee.select_role') }}</option>
                                                <option value="0">{{ __('h_employee.employee') }}</option>
                                                <option value="1">{{ __('h_employee.hrs') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="passwordField">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.password') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-5 d-flex align-items-center">
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="{{ __('h_employee.enter_password') }}" />
                                            <i id="togglePassword" class="fa fa-eye ml-2"
                                                style="cursor: pointer; margin-left: 10px;"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/employees') }}"
                                        class="btn btn-default float-left">{{ __('h_employee.back') }}</a>
                                    <button type="submit"
                                        class="btn btn-primary float-right">{{ __('h_employee.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <script src="{{ url('dist/js/employee.js?v=2') }}"></script>
@endsection
