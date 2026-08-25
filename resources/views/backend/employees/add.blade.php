@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_employee.employees') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "><a
                                href="{{ url('admin/employees') }}">{{ __('h_employee.employees_breadcrumb') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_employee.add_breadcrumb') }}</li>
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
                            <form class="form-horizontal" method="post" action="{{ url('admin/employees/add') }}"
                                enctype="multipart/form-data" id="addForm">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ __('h_employee.basic_information') ?? 'Basic Information' }}
                                    </h5>


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

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.birth_date') }}<span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('birth_date') }}" name="birth_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-briefcase mr-2"></i>{{ __('h_employee.employment_details') ?? 'Employment Details' }}
                                    </h5>

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

                                    <!-- HR Permissions Section -->
                                    <div id="permissionsSection" class="mt-4" style="display:none;">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4>{{ __('dashboard.hr_permissions') }}</h4>

                                            <!-- Select All Button -->
                                            <button type="button" id="selectAllBtn" class="btn btn-primary btn-sm">
                                                {{ __('dashboard.select_all') }}
                                            </button>
                                        </div>

                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <div class="row">

                                                    @php
                                                        $permissions = [
                                                            'employees' => __('dashboard.employees'),
                                                            'managers' => __('dashboard.managers'),
                                                            'administrations' => __('dashboard.administrations'),
                                                            'departments' => __('dashboard.departments'),
                                                            'jobs' => __('dashboard.jobs'),
                                                            'job_history' => __('dashboard.job_history'),
                                                            'news' => __('dashboard.news'),
                                                            'requests' => __('dashboard.requests'),
                                                            'messages' => __('h_message.messages'),
                                                            'performance' => __('dashboard.performance'),
                                                            'attendance' => __('dashboard.attendance'),
                                                            'attendance_reports' => __('dashboard.attendance_reports'),
                                                            'biometer_excel' => __('dashboard.biometer_excel'),
                                                            'taxes' => __('dashboard.taxes'),
                                                            'insurance' => __('dashboard.insurance'),
                                                            'deductions' => __('dashboard.deductions'),
                                                            'vacations' => __('dashboard.vacations'),
                                                            'bounas' => __('dashboard.overtime'),
                                                            'payroll' => __('dashboard.payroll'),
                                                            'attendance_rule' => __('dashboard.company_policy'),
                                                            'payslip' => __('dashboard.payslip_report'),
                                                            'branches' => __('dashboard.branches'),
                                                            'locations' => __('dashboard.locations'),
                                                            'company_info' => __('dashboard.company_info'),
                                                            'my_account' => __('dashboard.my_account'),
                                                        ];
                                                    @endphp

                                                    @foreach ($permissions as $key => $label)
                                                        <div class="col-md-4 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input permission-checkbox"
                                                                    type="checkbox" name="permissions[]"
                                                                    value="{{ $key }}"
                                                                    id="perm_{{ $key }}">

                                                                <label class="form-check-label"
                                                                    for="perm_{{ $key }}">
                                                                    {{ $label }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        // Show/Hide Permission Section Based on Role
                                        document.getElementById('roleSelect').addEventListener('change', function() {
                                            let permissions = document.getElementById('permissionsSection');
                                            this.value == "1" ? permissions.style.display = 'block' : permissions.style.display = 'none';
                                        });

                                        // Select All Permissions
                                        document.getElementById('selectAllBtn').addEventListener('click', function() {
                                            let checkboxes = document.querySelectorAll('.permission-checkbox');
                                            let allChecked = [...checkboxes].every(ch => ch.checked);

                                            checkboxes.forEach(ch => ch.checked = !allChecked);

                                            // Button text changes based on state
                                            this.innerText = allChecked ?
                                                "{{ __('dashboard.select_all') }}" :
                                                "{{ __('dashboard.unselect_all') }}";
                                        });
                                    </script>

                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-dollar-sign mr-2"></i>{{ __('h_employee.salary_information') ?? 'Salary Information' }}
                                    </h5>

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

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.housing_allowance') }} </label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('housing_allowance') }}" name="housing_allowance"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_housing') }}">
                                            <span style="color:red">{{ $errors->first('housing_allowance') }}</span>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.transportation_allowance') }} </label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('transportation_allowance') }}" name="transportation_allowance"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_transportation') }}">
                                            <span style="color:red">{{ $errors->first('transportation_allowance') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.other_allowances') }} </label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('other_allowances') }}" name="other_allowances"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_other_allowances') }}">
                                            <span style="color:red">{{ $errors->first('other_allowances') }}</span>
                                        </div>
                                    </div>




                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-globe mr-2"></i>{{ __('h_employee.nationality_residency') ?? 'Nationality & Residency' }}
                                    </h5>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('dashboard.nationality') }} <span style="color:red">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="nationality" id="nationality" class="form-control" required>
                                                <option value="local">{{ __('dashboard.nationality_local') }}</option>
                                                <option value="foreign">{{ __('dashboard.nationality_foreign') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="foreign_fields" style="display:none;">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.country_code') }} <span style="color:red">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="country_code" class="form-control foreign-required">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.residency_expiry') }} <span style="color:red">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="date" name="residency_expiry" class="form-control foreign-required">
                                            </div>
                                        </div>

                                        <div class="form-group row"> 
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.passport_number') }} <span style="color:red">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="passport_number" class="form-control foreign-required">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.passport_expiry') }} <span style="color:red">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="date" name="passport_expiry" class="form-control foreign-required">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.residency_number') }} <span style="color:red">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="residency_number" class="form-control foreign-required">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{ __('dashboard.iban') }}</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="iban" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.residency_job') }}</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="residency_job" class="form-control">
                                            </div>
                                        </div>


                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const nationality = document.getElementById('nationality');
                                            const foreignFields = document.getElementById('foreign_fields');
                                            const requiredInputs = foreignFields.querySelectorAll('.foreign-required');

                                            function toggleForeign() {
                                                if (nationality.value === 'foreign') {
                                                    foreignFields.style.display = 'block';
                                                    requiredInputs.forEach(input => input.setAttribute('required', 'required'));
                                                } else {
                                                    foreignFields.style.display = 'none';
                                                    requiredInputs.forEach(input => input.removeAttribute('required'));
                                                }
                                            }

                                            nationality.addEventListener('change', toggleForeign);
                                            toggleForeign();
                                        });
                                    </script>

                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-clock mr-2"></i>{{ __('h_employee.work_schedule') ?? 'Work Schedule' }}
                                    </h5>



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
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.early_minutes') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('checkin_early_minutes') }}"
                                                name="checkin_early_minutes" class="form-control" required>
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





                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-fingerprint mr-2"></i>{{ __('h_employee.attendance_settings') ?? 'Attendance Settings' }}
                                    </h5>


                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.free_biometric') }}
                                            <span
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



                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-paperclip mr-2"></i>{{ __('h_employee.attachments') ?? 'Attachments & Documents' }}
                                    </h5>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"
                                            for="attachment">{{ __('h_employee.attachment') }}:</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="attachment" class="form-control"
                                                accept="application/pdf">
                                        </div>
                                    </div>




                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/employees') }}"
                                        class="btn btn-default float-left">{{ __('h_employee.back') }}</a>
                                    <button type="submit" id="submitBtn"
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
