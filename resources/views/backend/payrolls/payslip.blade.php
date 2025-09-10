@extends('backend.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ url('dist/css/payslip.css') }}?v=2">

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1>{{ __('dashboard.employee_payslips_report') }}</h1>
                    </div>
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/payroll') }}">{{ __('dashboard.payroll') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('dashboard.employee_payslips') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('dashboard.search_employee_payslip') }}</h3>
                            </div>
                            <div class="card-body">
                                <!-- Search Form -->
                                <form method="GET" action="{{ url('admin/payslip') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">{{ __('dashboard.employee_name') }}</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="{{ __('dashboard.enter_employee_name') }}" value="{{ Request::get('name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="month">{{ __('dashboard.month') }}</label>
                                                <select class="form-control" id="month" name="month">
                                                    <option value="">{{ __('dashboard.select_month') }}</option>
                                                    <option value="1"
                                                        {{ Request::get('month') == '1' ? 'selected' : '' }}>{{ __('dashboard.january') }}
                                                    </option>
                                                    <option value="2"
                                                        {{ Request::get('month') == '2' ? 'selected' : '' }}>{{ __('dashboard.february') }}
                                                    </option>
                                                    <option value="3"
                                                        {{ Request::get('month') == '3' ? 'selected' : '' }}>{{ __('dashboard.march') }}</option>
                                                    <option value="4"
                                                        {{ Request::get('month') == '4' ? 'selected' : '' }}>{{ __('dashboard.april') }}</option>
                                                    <option value="5"
                                                        {{ Request::get('month') == '5' ? 'selected' : '' }}>{{ __('dashboard.may') }}</option>
                                                    <option value="6"
                                                        {{ Request::get('month') == '6' ? 'selected' : '' }}>{{ __('dashboard.june') }}</option>
                                                    <option value="7"
                                                        {{ Request::get('month') == '7' ? 'selected' : '' }}>{{ __('dashboard.july') }}</option>
                                                    <option value="8"
                                                        {{ Request::get('month') == '8' ? 'selected' : '' }}>{{ __('dashboard.august') }}
                                                    </option>
                                                    <option value="9"
                                                        {{ Request::get('month') == '9' ? 'selected' : '' }}>{{ __('dashboard.september') }}
                                                    </option>
                                                    <option value="10"
                                                        {{ Request::get('month') == '10' ? 'selected' : '' }}>{{ __('dashboard.october') }}
                                                    </option>
                                                    <option value="11"
                                                        {{ Request::get('month') == '11' ? 'selected' : '' }}>{{ __('dashboard.november') }}
                                                    </option>
                                                    <option value="12"
                                                        {{ Request::get('month') == '12' ? 'selected' : '' }}>{{ __('dashboard.december') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="year">{{ __('dashboard.year') }}</label>
                                                <select class="form-control" id="year" name="year">
                                                    <option value="">{{ __('dashboard.select_year') }}</option>
                                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                        <option value="{{ $i }}"
                                                            {{ Request::get('year') == $i ? 'selected' : '' }}>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br>
                                                <button class="btn btn-primary rounded-pill" type="submit"
                                                    style="margin-right: 10px;" title="Search">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <a href="{{ url('admin/payslip') }}" class="btn btn-success rounded-pill"
                                                    title="Reset">
                                                    <i class="fas fa-sync-alt"></i>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payslip Results -->
                @if (Request::get('name') || Request::get('month') || Request::get('year'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        {{ __('dashboard.payslip_results') }}
                                        @if (Request::get('name'))
                                            {{ __('dashboard.for') }} "{{ Request::get('name') }}"
                                        @endif
                                        @if (Request::get('month') || Request::get('year'))
                                            ({{ Request::get('month') ? date('F', mktime(0, 0, 0, Request::get('month'), 1)) : '' }}
                                            {{ Request::get('year') }})
                                        @endif
                                    </h3>
                                    @if ($getRecord->count() > 0)
                    <div class="col-sm-13 text-end" style="text-align: right;">
                                            <button type="button" class="btn btn-primary" onclick="printAllPayslips()">
                                                <i class="fas fa-print"></i> {{ __('dashboard.print_all_results') }}
                                            </button>
                                            <button type="button" class="btn btn-success" onclick="downloadAllPayslips()">
                                                <i class="fas fa-download"></i> {{ __('dashboard.download_all_pdfs') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @forelse($getRecord as $payroll)
                                            <div class="col-md-8 offset-md-2 mb-4">
                                                <div class="payslip-container" id="payslip-{{ $payroll->id }}">
                                                    <div class="payslip-header">
                                                        <div class="company-info text-center">
                                                            <h3 class="company-name">
                                                                {{ $payroll->company->name ?? 'Company Name' }}</h3>
                                                            <p class="company-address">
                                                                {{ $payroll->company->address ?? 'Company Address' }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="payslip-body">
                                                        <!-- Employee Information -->
                                                        <div class="employee-info">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <strong>{{ __('dashboard.employee_id') }}:</strong>
                                                                    {{ $payroll->employee->id }}<br>
                                                                    <strong>{{ __('dashboard.employee_name') }}:</strong>
                                                                    {{ $payroll->employee->name }}<br>
                                                                    <strong>{{ __('dashboard.department') }}:</strong>
                                                                    {{ $payroll->employee->department->department_name ?? 'N/A' }}<br>
                                                                    <strong>{{ __('dashboard.job_title') }}:</strong>
                                                                    {{ $payroll->employee->job->job_title ?? 'N/A' }}
                                                                </div>
                                                                <div class="col-6">
                                                                    <strong>{{ __('dashboard.pay_period') }}:</strong>
                                                                    {{ $payroll->start_date ? date('M d, Y', strtotime($payroll->start_date)) : 'N/A' }}
                                                                    -
                                                                    {{ $payroll->end_date ? date('M d, Y', strtotime($payroll->end_date)) : 'N/A' }}<br>
                                                                    <strong>{{ __('dashboard.hire_date') }}:</strong>
                                                                    {{ $payroll->employee->hire_date ? date('M d, Y', strtotime($payroll->employee->hire_date)) : 'N/A' }}<br>
                                                                    <strong>{{ __('dashboard.phone') }}:</strong>
                                                                    {{ $payroll->employee->phone_number ?? 'N/A' }}<br>
                                                                    <strong>{{ __('dashboard.email') }}:</strong>
                                                                    {{ $payroll->employee->email ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Earnings Section -->
                                                        <div class="earnings-section mt-3">
                                                            <h5 class="section-title">{{ __('dashboard.earnings') }}</h5>
                                                            <table class="table table-sm table-bordered">

                                                                <tbody>
                                                                    <tr>
                                                                        <td>{{ __('dashboard.basic_salary') }}</td>
                                                                        <td class="text-right">
                                                                            {{ number_format($payroll->basic_salary ?? 0, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                    @if ($payroll->bounas > 0)
                                                                        <tr>
                                                                            <td>{{ __('dashboard.bonus') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->bounas, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr class="table-info">
                                                                        <td><strong>{{ __('dashboard.total_earnings') }}</strong></td>
                                                                        <td class="text-right">
                                                                            <strong>{{ number_format(($payroll->basic_salary ?? 0) + ($payroll->bounas ?? 0), 2) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Deductions Section -->
                                                        <div class="deductions-section mt-3">
                                                            <h5 class="section-title">{{ __('dashboard.deductions') }}</h5>
                                                            <table class="table table-sm table-bordered">

                                                                <tbody>
                                                                    @if ($payroll->taxes > 0)
                                                                        <tr>
                                                                            <td>{{ __('dashboard.taxes_and_insurance') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->taxes, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($payroll->deductions > 0)
                                                                        <tr>
                                                                            <td>{{ __('dashboard.other_deductions') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->deductions, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($payroll->attendance_deduction > 0)
                                                                        <tr>
                                                                            <td>{{ __('dashboard.attendance_deduction') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->attendance_deduction, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr class="table-warning">
                                                                        <td><strong>{{ __('dashboard.total_deductions') }}</strong></td>
                                                                        <td class="text-right">
                                                                            <strong>{{ number_format(($payroll->taxes ?? 0) + ($payroll->deductions ?? 0) + ($payroll->attendance_deduction ?? 0), 2) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Attendance Information Table -->
                                                        <div class="attendance-section mt-3">
                                                            <h5 class="section-title">{{ __('dashboard.attendance_details') }}</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped">

                                                                    <tbody>
                                                                        <tr>
                                                                            <td><strong>{{ __('dashboard.days_absent') }}</strong></td>
                                                                            <td>{{ $payroll->days_absent ?? 0 }} {{ __('dashboard.days') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>{{ __('dashboard.rest_vacation') }}</strong></td>
                                                                            <td>{{ $payroll->rest_vacancy ?? 0 }} {{ __('dashboard.days') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>{{ __('dashboard.daily_wage') }}</strong></td>
                                                                            <td>${{ number_format($payroll->daily_wage ?? 0, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>{{ __('dashboard.work_hours') }}</strong></td>
                                                                            <td>{{ $payroll->employee->work_start_time ?? 'N/A' }}
                                                                                -
                                                                                {{ $payroll->employee->work_end_time ?? 'N/A' }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>


                                                        <!-- Net Pay Section -->
                                                        <div class="net-pay-section mt-3">
                                                            <div class="alert alert-success">
                                                                <h4 class="text-center mb-0">
                                                                    <strong>{{ __('dashboard.net_pay') }}:
                                                                        {{ number_format($payroll->net_pay ?? 0, 2) }}
                                                                    </strong>
                                                                </h4>
                                                            </div>
                                                        </div>

                                                        <!-- Footer -->
                                                        <div class="payslip-footer mt-3">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p class="small">{{ __('dashboard.generated_on') }}:
                                                                        {{ date('M d, Y H:i A') }}</p>
                                                                    <p class="small">{{ __('dashboard.created') }}:
                                                                        {{ date('M d, Y', strtotime($payroll->created_at)) }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-6 text-right">
                                                                    <button class="btn btn-sm btn-primary"
                                                                        onclick="printPayslip({{ $payroll->id }})">
                                                                        <i class="fas fa-print"></i> {{ __('dashboard.print') }}
                                                                    </button>
                                                                    <button class="btn btn-sm btn-info"
                                                                        onclick="downloadPayslip({{ $payroll->id }})">
                                                                        <i class="fas fa-download"></i> {{ __('dashboard.download') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-warning text-center">
                                                    <h5>{{ __('dashboard.no_payroll_records_found') }}</h5>
                                                    <p>{{ __('dashboard.no_payroll_records_match') }}</p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Pagination -->
                                    @if ($getRecord->count() > 0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-center">
                                                    {{ $getRecord->appends(request()->query())->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Instructions when no search is performed -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">{{ __('dashboard.search_for_employee_payslips') }}</h4>
                                    <p class="text-muted">{{ __('dashboard.search_form_instruction') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <script>
        window.appConfig = {
            downloadUrl: '{{ url('admin/payslip/download-pdf') }}',
            downloadAllUrl: '{{ url('admin/payslip/download-all-pdf') }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <!-- Link to the new JavaScript file -->
    <script src="{{ url('dist/js/payslip.js') }}"></script>

@endsection
