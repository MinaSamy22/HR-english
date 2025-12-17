@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <!-- Page Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_employee.View Employees') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/employees') }}">{{ __('h_employee.employees_breadcrumb') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('h_employee.View') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_employee.View Employees') }}</h3>
                            </div>

                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="card-body">

                                    {{-- BASIC INFORMATION --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-user mr-2"></i>{{ __('h_employee.basic_information') ?? 'Basic Information' }}
                                        </h5>

                                        <!-- ID -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.id') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->id }}
                                            </div>
                                        </div>

                                        <!-- Name -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.name') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->name }}
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.email') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->email }}
                                            </div>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.phone_number') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->phone_number ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- MAC Address -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.mobile_mac_address') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->macaddress ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Birth Date -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.birth_date') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->birth_date ? date('d-m-Y', strtotime($getRecord->birth_date)) : __('h_employee.not_set') }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- EMPLOYMENT DETAILS --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-briefcase mr-2"></i>{{ __('h_employee.employment_details') ?? 'Employment Details' }}
                                        </h5>

                                        <!-- Hire Date -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.hire_date') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->hire_date ? date('d-m-Y', strtotime($getRecord->hire_date)) : __('h_employee.not_set') }}
                                            </div>
                                        </div>

                                        <!-- Job Title -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.job_title') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->get_job_single->job_title ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Manager -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.manager_name') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->get_manager_single->name ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Department -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.department_name') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->get_department_single->department_name ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Branch -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.branch') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->branch_name ?? __('h_dashboard.main_branch') }}
                                            </div>
                                        </div>

                                        <!-- Role -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.role') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if (!empty($getRecord->is_role) && $getRecord->is_role == 1)
                                                    <span class="badge bg-success">{{ __('h_employee.hrs') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('h_employee.employee') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SALARY INFORMATION --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-dollar-sign mr-2"></i>{{ __('h_employee.salary_information') ?? 'Salary Information' }}
                                        </h5>

                                        <!-- Salary Type -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.salary_type') }}
                                            </label>
                                            <div class="col-sm-10">
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

                                        <!-- Salary Amount -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.salary') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->salary ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Main Salary -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.main_salary') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->main_salary === 1)
                                                    <span class="badge badge-success">{{ __('h_employee.yes') }}</span>
                                                @elseif($getRecord->main_salary === 0)
                                                    <span class="badge badge-warning">{{ __('h_employee.no') }}</span>
                                                @else
                                                    {{ __('h_employee.not_set') }}
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Additional Salary (Conditional) -->
                                        @if ($getRecord->main_salary == 0)
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label font-weight-bold">
                                                    {{ __('dashboard.additional_salary') }}
                                                </label>
                                                <div class="col-sm-10">
                                                    {{ $getRecord->additional_salary ?? '—' }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- NATIONALITY & RESIDENCY --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-globe mr-2"></i>{{ __('h_employee.nationality_residency') ?? 'Nationality & Residency' }}
                                        </h5>

                                        <!-- Nationality -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('dashboard.nationality') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->nationality == 'foreign')
                                                    <span
                                                        class="badge badge-info">{{ __('dashboard.nationality_foreign') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-primary">{{ __('dashboard.nationality_local') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Foreign Employee Details (Conditional) -->
                                        @if ($getRecord->nationality == 'foreign')
                                            <div class="ml-4">
                                                <!-- Country Code -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.country_code') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->country_code ?? '—' }}
                                                    </div>
                                                </div>

                                                <!-- Residency Number -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.residency_number') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->residency_number ?? '—' }}
                                                    </div>
                                                </div>

                                                <!-- Residency Expiry -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.residency_expiry') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->residency_expiry ? date('d-m-Y', strtotime($getRecord->residency_expiry)) : '—' }}
                                                    </div>
                                                </div>

                                                <!-- Residency Job -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.residency_job') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->residency_job ?? '—' }}
                                                    </div>
                                                </div>

                                                <!-- Passport Number -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.passport_number') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->passport_number ?? '—' }}
                                                    </div>
                                                </div>

                                                <!-- Passport Expiry -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.passport_expiry') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->passport_expiry ? date('d-m-Y', strtotime($getRecord->passport_expiry)) : '—' }}
                                                    </div>
                                                </div>

                                                <!-- IBAN -->
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('dashboard.iban') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        {{ $getRecord->iban ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- WORK SCHEDULE --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-clock mr-2"></i>{{ __('h_employee.work_schedule') ?? 'Work Schedule' }}
                                        </h5>

                                        <!-- Shift Count -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.shift_count') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->shift_count !== null)
                                                    <span class="badge badge-info">{{ $getRecord->shift_count }}
                                                        {{ $getRecord->shift_count == 1 ? __('h_employee.shift') : __('h_employee.shifts') }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- First Shift Times -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.work_start_time') }}
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
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.work_end_time') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->work_end_time)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_end_time)->format('h:i A') }}
                                                @else
                                                    <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Second Shift Times (Conditional) -->
                                        @if ($getRecord->shift_count == 2)
                                            <div class="ml-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('h_employee.second_work_start_time') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        @if ($getRecord->second_work_start_time)
                                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->second_work_start_time)->format('h:i A') }}
                                                        @else
                                                            <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label font-weight-bold">
                                                        {{ __('h_employee.second_work_end_time') }}
                                                    </label>
                                                    <div class="col-sm-10">
                                                        @if ($getRecord->second_work_end_time)
                                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->second_work_end_time)->format('h:i A') }}
                                                        @else
                                                            <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.early_minutes') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->checkin_early_minutes ?? '—' }}
                                            </div>
                                        </div>

                                        <!-- Work Hours Per Day -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.work_hours_per_day') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->work_hours_per_day)
                                                    {{ $getRecord->work_hours_per_day }} {{ __('h_employee.hours') }}
                                                @else
                                                    <span class="text-muted">{{ __('h_employee.not_set') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ATTENDANCE & BIOMETRIC --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-fingerprint mr-2"></i>{{ __('h_employee.attendance_settings') ?? 'Attendance Settings' }}
                                        </h5>

                                        <!-- Free Biometric -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.free_biometric') }}
                                            </label>
                                            <div class="col-sm-10">
                                                @if ($getRecord->is_biometric === 1)
                                                    <span class="badge badge-success">{{ __('h_employee.yes') }}</span>
                                                @elseif($getRecord->is_biometric === 0)
                                                    <span class="badge badge-danger">{{ __('h_employee.no') }}</span>
                                                @else
                                                    {{ __('h_employee.not_set') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- HR PERMISSIONS (Conditional) --}}
                                    @if (!empty($getRecord->is_role) && $getRecord->is_role == 1)
                                        <div class="mb-4">
                                            <h5 class="border-bottom pb-2 mb-3">
                                                <i
                                                    class="fas fa-user-shield mr-2"></i>{{ __('dashboard.hr_permissions') }}
                                            </h5>

                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @php
                                                            // Retrieve HR permissions from DB
                                                            $hrPermissions = \App\Models\HrPermission::where(
                                                                'user_id',
                                                                $getRecord->id,
                                                            )
                                                                ->where('company_id', session('company_id'))
                                                                ->first();

                                                            $savedPermissions = [];

                                                            if ($hrPermissions) {
                                                                if (is_array($hrPermissions->permissions)) {
                                                                    $savedPermissions = $hrPermissions->permissions;
                                                                } elseif (is_string($hrPermissions->permissions)) {
                                                                    $decoded = json_decode(
                                                                        $hrPermissions->permissions,
                                                                        true,
                                                                    );
                                                                    $savedPermissions = is_array($decoded)
                                                                        ? $decoded
                                                                        : [];
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
                                                                'attendance_reports' => __(
                                                                    'dashboard.attendance_reports',
                                                                ),
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

                                                        @if (!empty($savedPermissions))
                                                            @foreach ($savedPermissions as $perm)
                                                                <div class="col-md-4 mb-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            checked disabled>
                                                                        <label class="form-check-label">
                                                                            <i
                                                                                class="fas fa-check-circle text-success mr-1"></i>
                                                                            {{ $permissionLabels[$perm] ?? $perm }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-12">
                                                                <p class="text-muted text-center">
                                                                    <i class="fas fa-info-circle mr-1"></i>
                                                                    {{ __('dashboard.no_permissions') ?? 'No permissions assigned' }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- ATTACHMENTS & DOCUMENTS --}}
                                    @if ($getRecord->attachment)
                                        <div class="mb-4">
                                            <h5 class="border-bottom pb-2 mb-3">
                                                <i
                                                    class="fas fa-paperclip mr-2"></i>{{ __('h_employee.attachments') ?? 'Attachments & Documents' }}
                                            </h5>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label font-weight-bold">
                                                    {{ __('h_employee.attachment_pdf') }}
                                                </label>
                                                <div class="col-sm-10">
                                                    <a href="{{ route('view.attachment', $getRecord->attachment) }}"
                                                        target="_blank" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-file-pdf"></i> {{ __('h_employee.View PDF') }}
                                                    </a>
                                                    <a href="{{ route('view.attachment', $getRecord->attachment) }}"
                                                        download class="btn btn-success btn-sm ml-2">
                                                        <i class="fas fa-download"></i> {{ __('h_employee.Download') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- SYSTEM INFORMATION --}}
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2 mb-3">
                                            <i
                                                class="fas fa-info-circle mr-2"></i>{{ __('h_employee.system_information') ?? 'System Information' }}
                                        </h5>

                                        <!-- Created Date -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.Created Date') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->created_at ? date('d-m-Y h:i A', strtotime($getRecord->created_at)) : __('h_employee.not_set') }}
                                            </div>
                                        </div>

                                        <!-- Updated Date -->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label font-weight-bold">
                                                {{ __('h_employee.Updated Date') }}
                                            </label>
                                            <div class="col-sm-10">
                                                {{ $getRecord->updated_at ? date('d-m-Y h:i A', strtotime($getRecord->updated_at)) : __('h_employee.not_set') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Form Actions -->
                                <div class="card-footer">
                                    <a href="{{ url('admin/employees') }}" class="btn btn-default float-left">
                                        <i class="fas fa-arrow-left mr-1"></i>{{ __('h_employee.back') }}
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
