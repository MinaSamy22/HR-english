@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('h_employee.employees') }}</h1>
                    </div><!-- /.col -->
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_employee.edit_breadcrumb') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_employee.employees_breadcrumb') }}</li>
                        </ol>
                    </div><!-- /.col -->
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
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.birth_date') }} <span
                                                style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ $getRecord->birth_date }}" name="birth_date"
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

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.mobile_mac_address') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->macaddress }}" name="macaddress"
                                                class="form-control" required
                                                placeholder="{{ __('h_employee.enter_mac_address') }}">
                                            <span style="color:red">{{ $errors->first('macaddress') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.work_start_time') }}
                                            <span style="color: red;">{{ __('h_employee.required_field') }}</span></label>
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

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-lable">{{ __('h_employee.work_hours_per_day') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" name="work_hours_per_day" class="form-control"
                                                value="{{ old('work_hours_per_day', $getRecord->work_hours_per_day) }}"
                                                placeholder="{{ __('h_employee.enter_work_hours') }}">
                                            <span style="color:red">{{ $errors->first('work_hours_per_day') }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.free_biometric') }} <span
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
