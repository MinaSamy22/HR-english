@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0">{{ __('h_employee.employees') }}</h1>

                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "><a
                                href="{{ url('admin/employees') }}">{{ __('h_employee.employees_breadcrumb') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_employee.edit_breadcrumb') }}</li>
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
                                <h3 class="card-title">{{ __('h_employee.edit_employee') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post"
                                action="{{ route('employees_update', $getRecord->id) }}" enctype="multipart/form-data">
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
                                            <input type="text" value="{{ $getRecord->name }}" name="name"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_first_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.email') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="email" value="{{ $getRecord->email }}" name="email"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_email') }}">
                                            <span style="color:red">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.password') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-5 d-flex align-items-center">
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="{{ __('h_employee.enter_new_password') }}" />
                                            <i id="togglePassword" class="fa fa-eye ml-2"
                                                style="cursor: pointer; margin-left: 10px;"></i>
                                        </div>
                                        <div class="col-sm-5">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.phone_number') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->phone_number }}"
                                                name="phone_number" class="form-control"
                                                placeholder="{{ __('h_employee.enter_phone_number') }}">
                                            <span style="color:red">{{ $errors->first('phone_number') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.mobile_mac_address') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->macaddress }}" name="macaddress"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_mac_address') }}">
                                            <span style="color:red">{{ $errors->first('macaddress') }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.birth_date') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ $getRecord->birth_date }}" name="birth_date"
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
                                            <input type="date" value="{{ $getRecord->hire_date }}" name="hire_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.job_title') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="job_id" required>
                                                @foreach ($getJobs as $value_job)
                                                    <option {{ $value_job->id == $getRecord->job_id ? 'selected' : '' }}
                                                        value="{{ $value_job->id }}">{{ $value_job->job_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.manager_name') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="manager_id" required>
                                                @foreach ($getManagers as $value_manager)
                                                    <option
                                                        {{ $value_manager->id == $getRecord->manager_id ? 'selected' : '' }}
                                                        value="{{ $value_manager->id }}">{{ $value_manager->name }}
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
                                                @foreach ($getDepartments as $value_department)
                                                    <option
                                                        {{ $value_department->id == $getRecord->department_id ? 'selected' : '' }}
                                                        value="{{ $value_department->id }}">
                                                        {{ $value_department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ROLE SELECTION -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_employee.role') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="is_role" id="roleSelect" required>
                                                <option value="0" {{ $getRecord->is_role == 0 ? 'selected' : '' }}>
                                                    {{ __('h_employee.employee') }}
                                                </option>
                                                <option value="1" {{ $getRecord->is_role == 1 ? 'selected' : '' }}>
                                                    {{ __('h_employee.hrs') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- HR PERMISSIONS SECTION -->
                                    <div id="permissionsSection" class="form-group row mt-4" style="display:none;">

                                        @php
                                            // Load saved permissions from DB
                                            $hrPermissions = \App\Models\HrPermission::where('user_id', $getRecord->id)
                                                ->where('company_id', session('company_id'))
                                                ->first();

                                            $savedPermissions = [];
                                            if ($hrPermissions) {
                                                if (is_array($hrPermissions->permissions)) {
                                                    $savedPermissions = $hrPermissions->permissions;
                                                } elseif (is_string($hrPermissions->permissions)) {
                                                    $savedPermissions =
                                                        json_decode($hrPermissions->permissions, true) ?? [];
                                                }
                                            }

                                            // Labels list
                                            $permissionLabels = [
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

                                        <!-- LEFT LABEL -->
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('dashboard.hr_permissions') }}
                                        </label>

                                        <!-- RIGHT CONTENT -->
                                        <div class="col-sm-10">

                                            <!-- Title + Select All -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">{{ __('dashboard.hr_permissions') }}</h5>

                                                <button type="button" id="selectAllBtn" class="btn btn-primary btn-sm">
                                                    {{ __('dashboard.select_all') }}
                                                </button>
                                            </div>

                                            <!-- Permissions Card -->
                                            <div class="card shadow-sm">
                                                <div class="card-body">

                                                    <div class="row">
                                                        @foreach ($permissionLabels as $key => $label)
                                                            <div class="col-md-4 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox"
                                                                        type="checkbox" name="permissions[]"
                                                                        value="{{ $key }}"
                                                                        id="perm_{{ $key }}"
                                                                        {{ in_array($key, $savedPermissions) ? 'checked' : '' }}>
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
                                    </div>


                                    <!-- SCRIPT -->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {

                                            let roleSelect = document.getElementById('roleSelect');
                                            let permissionsSection = document.getElementById('permissionsSection');
                                            let selectAllBtn = document.getElementById('selectAllBtn');

                                            /* SHOW / HIDE PERMISSIONS */
                                            function togglePermissions() {
                                                if (roleSelect.value == "1") {
                                                    permissionsSection.style.display = 'flex';
                                                } else {
                                                    permissionsSection.style.display = 'none';
                                                    document.querySelectorAll('.permission-checkbox').forEach(ch => ch.checked = false);
                                                }
                                            }

                                            /* SELECT ALL BUTTON */
                                            function updateSelectAllText() {
                                                let checkboxes = document.querySelectorAll('.permission-checkbox');
                                                let allChecked = [...checkboxes].every(ch => ch.checked);

                                                if (selectAllBtn) {
                                                    selectAllBtn.innerText = allChecked ?
                                                        "{{ __('dashboard.unselect_all') }}" :
                                                        "{{ __('dashboard.select_all') }}";
                                                }
                                            }

                                            if (selectAllBtn) {
                                                selectAllBtn.addEventListener('click', function() {
                                                    let checkboxes = document.querySelectorAll('.permission-checkbox');
                                                    let allChecked = [...checkboxes].every(ch => ch.checked);

                                                    checkboxes.forEach(ch => ch.checked = !allChecked);

                                                    updateSelectAllText();
                                                });
                                            }

                                            /* INIT ON PAGE LOAD */
                                            togglePermissions();
                                            updateSelectAllText();

                                            /* WHEN ROLE CHANGES */
                                            roleSelect.addEventListener('change', togglePermissions);
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
                                            <select class="form-control" name="salary_type" required>
                                                <option value="1"
                                                    {{ $getRecord->salary_type == 1 ? 'selected' : '' }}>
                                                    {{ __('h_employee.monthly_salary') }}</option>
                                                <option value="2"
                                                    {{ $getRecord->salary_type == 2 ? 'selected' : '' }}>
                                                    {{ __('h_employee.weekly_wage') }}</option>
                                                <option value="3"
                                                    {{ $getRecord->salary_type == 3 ? 'selected' : '' }}>
                                                    {{ __('h_employee.daily_wage') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.salary') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->salary }}" name="salary"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_salary') }}">
                                            <span style="color:red">{{ $errors->first('salary') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.housing_allowance') }} </label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->housing_allowance }}" name="housing_allowance"
                                                class="form-control" 
                                                placeholder="{{ __('h_employee.enter_housing') }}">
                                            <span style="color:red">{{ $errors->first('housing_allowance') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.transportation_allowance') }} </label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->transportation_allowance }}" name="transportation_allowance"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_transportation') }}">
                                            <span style="color:red">{{ $errors->first('transportation_allowance') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.other_allowances') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->other_allowances }}" name="other_allowances"
                                                class="form-control"
                                                placeholder="{{ __('h_employee.enter_other_allowances') }}">
                                            <span style="color:red">{{ $errors->first('other_allowances') }}</span>
                                        </div>
                                    </div>





                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i class="fas fa-globe mr-2"></i>{{ __('h_employee.nationality_residency') ?? 'Nationality & Residency' }}
                                    </h5>

                                    <!-- Nationality -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('dashboard.nationality') }} <span style="color:red">*</span>
                                        </label>

                                        <div class="col-sm-10">
                                            <select name="nationality" id="nationality" class="form-control" required>
                                                <option value="local"
                                                    {{ old('nationality', $getRecord->nationality) == 'local' ? 'selected' : '' }}>
                                                    {{ __('dashboard.nationality_local') }}
                                                </option>

                                                <option value="foreign"
                                                    {{ old('nationality', $getRecord->nationality) == 'foreign' ? 'selected' : '' }}>
                                                    {{ __('dashboard.nationality_foreign') }}
                                                </option>
                                            </select>

                                            <span style="color:red">{{ $errors->first('nationality') }}</span>
                                        </div>
                                    </div>

                                    <!-- FOREIGN FIELDS -->
                                    <div id="foreign_fields"
                                        style="{{ old('nationality', $getRecord->nationality) == 'foreign' ? '' : 'display:none;' }}">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.country_code') }} <span style="color:red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" name="country_code" class="form-control"
                                                    value="{{ old('country_code', $getRecord->country_code) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.residency_expiry') }} <span style="color:red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="date" name="residency_expiry" class="form-control"
                                                    value="{{ old('residency_expiry', $getRecord->residency_expiry) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.passport_number') }}</label>

                                            <div class="col-sm-10">
                                                <input type="text" name="passport_number" class="form-control"
                                                    value="{{ old('passport_number', $getRecord->passport_number) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.passport_expiry') }}</label>

                                            <div class="col-sm-10">
                                                <input type="date" name="passport_expiry" class="form-control"
                                                    value="{{ old('passport_expiry', $getRecord->passport_expiry) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.residency_number') }} <span style="color:red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" name="residency_number" class="form-control"
                                                    value="{{ old('residency_number', $getRecord->residency_number) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('dashboard.iban') }} <span style="color:red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" name="iban" class="form-control"
                                                    value="{{ old('iban', $getRecord->iban) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.residency_job') }}</label>

                                            <div class="col-sm-10">
                                                <input type="text" name="residency_job" class="form-control"
                                                    value="{{ old('residency_job', $getRecord->residency_job) }}">
                                            </div>
                                        </div>

                                    </div>


                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const nationality = document.getElementById('nationality');
                                            const foreignFields = document.getElementById('foreign_fields');
                                            const requiredInputs = foreignFields.querySelectorAll('input');

                                            function toggleForeign() {
                                                if (nationality.value === 'foreign') {
                                                    foreignFields.style.display = 'block';
                                                    requiredInputs.forEach(input => {
                                                        input.setAttribute('required', true);
                                                    });
                                                } else {
                                                    foreignFields.style.display = 'none';
                                                    requiredInputs.forEach(input => {
                                                        input.removeAttribute('required');
                                                    });
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
                                            <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="time"
                                                value="{{ $getRecord->work_start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_start_time)->format('H:i') : '' }}"
                                                name="work_start_time" class="form-control" required
                                                placeholder="{{ __('h_employee.enter_start_time') }}">
                                            <span style="color:red">{{ $errors->first('work_start_time') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.work_end_time') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="time"
                                                value="{{ $getRecord->work_end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_end_time)->format('H:i') : '' }}"
                                                name="work_end_time" class="form-control" required
                                                placeholder="{{ __('h_employee.enter_end_time') }}">
                                            <span style="color:red">{{ $errors->first('work_end_time') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.early_minutes') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->checkin_early_minutes }}"
                                                name="checkin_early_minutes" class="form-control" required
                                                placeholder="{{ __('h_employee.early_minutes') }}">
                                            <span style="color:red">{{ $errors->first('checkin_early_minutes') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.shift_count') }}</label>
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
                                            <span style="color:red">{{ $errors->first('shift_count') }}</span>
                                        </div>
                                    </div>

                                    <div id="second-shift-fields"
                                        style="display: {{ old('shift_count', $getRecord->shift_count ?? 1) == 2 ? 'block' : 'none' }}">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('h_employee.second_work_start_time') }}</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="second_work_start_time" class="form-control"
                                                    value="{{ $getRecord->second_work_start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->second_work_start_time)->format('H:i') : '' }}">
                                                <span
                                                    style="color:red">{{ $errors->first('second_work_start_time') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('h_employee.second_work_end_time') }}</label>
                                            <div class="col-sm-10">
                                                <input type="time" name="second_work_end_time" class="form-control"
                                                    value="{{ $getRecord->second_work_end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->second_work_end_time)->format('H:i') : '' }}">
                                                <span
                                                    style="color:red">{{ $errors->first('second_work_end_time') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        document.getElementById('shift_count').addEventListener('change', function() {
                                            const secondShiftFields = document.getElementById('second-shift-fields');
                                            if (this.value == '2') {
                                                secondShiftFields.style.display = 'block';
                                            } else {
                                                secondShiftFields.style.display = 'none';
                                                // Clear the values when hiding
                                                document.querySelector('input[name="second_work_start_time"]').value = '';
                                                document.querySelector('input[name="second_work_end_time"]').value = '';
                                            }
                                        });
                                    </script>


                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-fingerprint mr-2"></i>{{ __('h_employee.attendance_settings') ?? 'Attendance Settings' }}
                                    </h5>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.free_biometric') }}
                                            <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_biometric"
                                                    id="biometric_yes" value="1"
                                                    {{ old('is_biometric', $getRecord->is_biometric) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="biometric_yes">{{ __('h_employee.yes') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_biometric"
                                                    id="biometric_no" value="0"
                                                    {{ old('is_biometric', $getRecord->is_biometric) === 0 ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="biometric_no">{{ __('h_employee.no') }}</label>
                                            </div>
                                            <br>
                                            <span style="color:red">{{ $errors->first('is_biometric') }}</span>
                                        </div>
                                    </div>


                                    <h5 class="border-bottom pt-2 pb-3 mb-4">
                                        <i
                                            class="fas fa-paperclip mr-2"></i>{{ __('h_employee.attachments') ?? 'Attachments & Documents' }}
                                    </h5>

                                    <!-- Add file input field -->
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-label">{{ __('h_employee.attachment_pdf') }}</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="attachment" class="form-control" accept=".pdf">
                                            @if ($getRecord->attachment)
                                                <small class="form-text text-muted">
                                                    {{ __('h_employee.current_file') }}: {{ $getRecord->attachment }}
                                                    <a href="{{ route('view.attachment', $getRecord->attachment) }}"
                                                        target="_blank">{{ __('h_employee.view_file') }}</a>
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                </div>



                                <div class="card-footer">
                                    <a href="{{ url('admin/employees') }}"
                                        class="btn btn-default float-left">{{ __('h_employee.back') }}</a>
                                    <button type="submit"
                                        class="btn btn-primary float-right">{{ __('h_employee.update') }}</button>
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
