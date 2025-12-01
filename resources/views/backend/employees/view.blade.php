@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_employee.View Employees') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "><a
                                href="{{ url('admin/employees') }}">{{ __('h_employee.employees_breadcrumb') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_employee.View') }} </li>
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
                                <h3 class="card-title">{{ __('h_employee.View Employees') }} </h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="card-body">



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.id') }} <span
                                                style="color: red;">
                                                *</span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->id }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.name') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->name }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.email') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->email }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.phone_number') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->phone_number }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> {{ __('h_employee.birth_date') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->birth_date ? date('d-m-Y', strtotime($getRecord->birth_date)) : 'Not Set' }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> {{ __('h_employee.hire_date') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->hire_date ? date('d-m-Y', strtotime($getRecord->hire_date)) : 'Not Set' }}
                                        </div>
                                    </div>

                                    <!-- Nationality -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('dashboard.nationality') }}</label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->nationality == 'foreign')
                                                {{ __('dashboard.nationality_foreign') }}
                                            @else
                                                {{ __('dashboard.nationality_local') }}
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Foreign Fields Wrapper -->
                                    <div id="foreign_fields"
                                        style="{{ $getRecord->nationality == 'foreign' ? '' : 'display:none;' }}">

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.country_code') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->country_code ?? '—' }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.residency_expiry') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->residency_expiry ? date('d-m-Y', strtotime($getRecord->residency_expiry)) : '—' }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.passport_number') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->passport_number ?? '—' }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.passport_expiry') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->passport_expiry ? date('d-m-Y', strtotime($getRecord->passport_expiry)) : '—' }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('dashboard.residency_number') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->residency_number ?? '—' }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{ __('dashboard.iban') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->iban ?? '—' }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-lable">{{ __('dashboard.residency_job') }}</label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->residency_job ?? '—' }}
                                            </div>
                                        </div>

                                    </div>




                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.job_title') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_job_single->job_title) ? $getRecord->get_job_single->job_title : '' }}
                                            {{-- to convert id of job to the job --}}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.salary_type') }} <span
                                                style="color: red;"></span></label>
                                        <div class="col-sm-10 d-flex align-items-center">
                                            @if ($getRecord->salary_type == 1)
                                                {{ __('h_employee.monthly_salary') }}
                                            @elseif($getRecord->salary_type == 2)
                                                {{ __('h_employee.weekly_wage') }}
                                            @elseif($getRecord->salary_type == 3)
                                                {{ __('h_employee.daily_wage') }}
                                            @else
                                                {{ __('h_employee.not_set') }}
                                            @endif
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.salary') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->salary }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> {{ __('h_employee.main_salary') }}
                                            <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->main_salary === 1)
                                                {{ __('h_employee.yes') }}
                                            @elseif($getRecord->main_salary === 0)
                                                {{ __('h_employee.no') }}
                                            @else
                                                {{ __('h_employee.not_set') }}
                                            @endif
                                        </div>
                                    </div>

                                    @if ($getRecord->main_salary == 0)
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-lable"> {{ __('dashboard.additional_salary') }}
                                                <span style="color: red;"></span></label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->additional_salary }}

                                            </div>
                                        </div>
                                    @endif

                                    @if ($getRecord->attachment)
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-lable">{{ __('h_employee.attachment_pdf') }}</label>
                                            <div class="col-sm-10">
                                                <a href="{{ route('view.attachment', $getRecord->attachment) }}"
                                                    target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-file-pdf"></i> {{ __('h_employee.View PDF') }}
                                                </a>
                                                <a href="{{ route('view.attachment', $getRecord->attachment) }}" download
                                                    class="btn btn-success ml-2">
                                                    <i class="fas fa-download"></i> {{ __('h_employee.Download') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.mobile_mac_address') }}
                                            <span style="color: red;"></span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->macaddress }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_employee.branch') }} <span
                                                style="color: red;"></span></label>
                                        <div class="col-sm-10">
                                            <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.work_start_time') }}
                                        </label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->work_start_time)
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_start_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.work_end_time') }}
                                        </label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->work_end_time)
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_end_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.shift_count') }}</label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->shift_count !== null)
                                                {{ $getRecord->shift_count }}
                                            @else
                                                <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($getRecord->shift_count == 2)
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('h_employee.second_work_start_time') }}</label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->second_work_start_time)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->second_work_start_time)->format('h:i A') }}
                                                @else
                                                    <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label">{{ __('h_employee.second_work_end_time') }}</label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->second_work_end_time)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->second_work_end_time)->format('h:i A') }}
                                                @else
                                                    <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-2 col-form-lable">{{ __('h_employee.work_hours_per_day') }}</label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->work_hours_per_day)
                                                {{ $getRecord->work_hours_per_day }} {{ __('h_employee.hours') }}
                                            @else
                                                <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> {{ __('h_employee.free_biometric') }}
                                            <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->is_biometric === 1)
                                                {{ __('h_employee.yes') }}
                                            @elseif($getRecord->is_biometric === 0)
                                                {{ __('h_employee.no') }}
                                            @else
                                                {{ __('h_employee.not_set') }}
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.manager_name') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_manager_single->name) ? $getRecord->get_manager_single->name : '' }}
                                            {{-- to convert id of job to the job --}}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> {{ __('h_employee.department_name') }}
                                            <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_department_single->department_name) ? $getRecord->get_department_single->department_name : '' }}
                                            {{-- to convert id of job to the job --}}
                                        </div>
                                    </div>







<div class="form-group row">
    <label class="col-sm-2 col-form-label">{{ __('h_employee.role') }}</label>
    <div class="col-sm-10">
        @if(!empty($getRecord->is_role) && $getRecord->is_role == 1)
            <span class="badge bg-success">{{ __('h_employee.hrs') }}</span>
        @else
            <span class="badge bg-secondary">{{ __('h_employee.employee') }}</span>
        @endif
    </div>
</div>

@if(!empty($getRecord->is_role) && $getRecord->is_role == 1)
    <!-- HR Permissions Display Section -->
    <div class="mt-4">
        <h4>{{ __('dashboard.hr_permissions') }}</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">

                    @php
                        // Retrieve HR permissions from DB
                        $hrPermissions = \App\Models\HrPermission::where('user_id', $getRecord->id)
                            ->where('company_id', session('company_id'))
                            ->first();

                        $savedPermissions = [];

                        if ($hrPermissions) {
                            if (is_array($hrPermissions->permissions)) {
                                $savedPermissions = $hrPermissions->permissions;
                            } elseif (is_string($hrPermissions->permissions)) {
                                $decoded = json_decode($hrPermissions->permissions, true);
                                $savedPermissions = is_array($decoded) ? $decoded : [];
                            }
                        }

                        // Map keys to translated labels
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

                    @if(!empty($savedPermissions))
                        @foreach($savedPermissions as $perm)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">
                                        {{ $permissionLabels[$perm] ?? $perm }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">{{ __('dashboard.no_permissions') }}</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endif






                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.Created Date') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->created_at ? date('d-m-Y h:i A', strtotime($getRecord->created_at)) : 'Not Set' }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_employee.Updated Date') }} <span
                                                style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->updated_at ? date('d-m-Y h:i A', strtotime($getRecord->updated_at)) : 'Not Set' }}
                                        </div>
                                    </div>




                                </div>
                                <div class="card-footer">
                                    <a href="{{ url('admin/employees') }}"
                                        class="btn btn-default float-left">{{ __('h_employee.back') }}</a>
                                    {{-- float for the place of the button --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content-body -->

    </div>
@endsection
