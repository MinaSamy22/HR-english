@extends('EmployeeInterface.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ url('dist/css/payslip.css') }}">

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('E_payroll.payslips_report') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('employee/payroll') }}">{{ __('E_payroll.payroll') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('E_payroll.employee_payslips') }}</li>
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
                                <h3 class="card-title">{{ __('E_payroll.search_payslip') }}</h3>
                            </div>
                            <div class="card-body">
                                <!-- Search Form -->
                                <form method="GET" action="{{ url('employee/payroll') }}">
                                    <div class="row">

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="month">{{ __('E_payroll.month') }}</label>
                                                <select class="form-control" id="month" name="month">
                                                    <option value="">{{ __('E_payroll.select_month') }}</option>
                                                    <option value="1"
                                                        {{ Request::get('month') == '1' ? 'selected' : '' }}>{{ __('E_payroll.january') }}
                                                    </option>
                                                    <option value="2"
                                                        {{ Request::get('month') == '2' ? 'selected' : '' }}>{{ __('E_payroll.february') }}
                                                    </option>
                                                    <option value="3"
                                                        {{ Request::get('month') == '3' ? 'selected' : '' }}>{{ __('E_payroll.march') }}</option>
                                                    <option value="4"
                                                        {{ Request::get('month') == '4' ? 'selected' : '' }}>{{ __('E_payroll.april') }}</option>
                                                    <option value="5"
                                                        {{ Request::get('month') == '5' ? 'selected' : '' }}>{{ __('E_payroll.may') }}</option>
                                                    <option value="6"
                                                        {{ Request::get('month') == '6' ? 'selected' : '' }}>{{ __('E_payroll.june') }}</option>
                                                    <option value="7"
                                                        {{ Request::get('month') == '7' ? 'selected' : '' }}>{{ __('E_payroll.july') }}</option>
                                                    <option value="8"
                                                        {{ Request::get('month') == '8' ? 'selected' : '' }}>{{ __('E_payroll.august') }}
                                                    </option>
                                                    <option value="9"
                                                        {{ Request::get('month') == '9' ? 'selected' : '' }}>{{ __('E_payroll.september') }}
                                                    </option>
                                                    <option value="10"
                                                        {{ Request::get('month') == '10' ? 'selected' : '' }}>{{ __('E_payroll.october') }}
                                                    </option>
                                                    <option value="11"
                                                        {{ Request::get('month') == '11' ? 'selected' : '' }}>{{ __('E_payroll.november') }}
                                                    </option>
                                                    <option value="12"
                                                        {{ Request::get('month') == '12' ? 'selected' : '' }}>{{ __('E_payroll.december') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="year">{{ __('E_payroll.year') }}</label>
                                                <select class="form-control" id="year" name="year">
                                                    <option value="">{{ __('E_payroll.select_year') }}</option>
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
                                                    style="margin-right: 10px;" title="{{ __('E_payroll.search') }}">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <a href="{{ url('employee/payroll') }}" class="btn btn-success rounded-pill"
                                                    title="{{ __('E_payroll.reset') }}">
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

                <!-- payslip Results -->
                @if (Request::get('name') || Request::get('month') || Request::get('year'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        {{ __('E_payroll.payslip_results') }}
                                        @if (Request::get('name'))
                                            {{ __('E_payroll.for') }} "{{ Request::get('name') }}"
                                        @endif
                                        @if (Request::get('month') || Request::get('year'))
                                            ({{ Request::get('month') ? __('E_payroll.' . strtolower(date('F', mktime(0, 0, 0, Request::get('month'), 1)))) : '' }}
                                            {{ Request::get('year') }})
                                        @endif
                                    </h3>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @forelse($getRecord as $payroll)
                                            <div class="col-md-8 offset-md-2 mb-4">
                                                <div class="payslip-container" id="payslip-{{ $payroll->id }}">
                                                    <div class="payslip-header">
                                                        <div class="company-info text-center">
                                                            <h3 class="company-name">
                                                                {{ $payroll->company->name ?? __('E_payroll.company_name') }}</h3>
                                                            <p class="company-address">
                                                                {{ $payroll->company->address ?? __('E_payroll.company_address') }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="payslip-body">
                                                        <!-- Employee Information -->
                                                        <div class="employee-info">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <strong>{{ __('E_payroll.employee_id') }}:</strong>
                                                                    {{ $payroll->employee->id }}<br>
                                                                    <strong>{{ __('E_payroll.employee_name') }}:</strong>
                                                                    {{ $payroll->employee->name }}<br>
                                                                    <strong>{{ __('E_payroll.department') }}:</strong>
                                                                    {{ $payroll->employee->department->department_name ?? __('E_payroll.na') }}<br>
                                                                    <strong>{{ __('E_payroll.job_title') }}:</strong>
                                                                    {{ $payroll->employee->job->job_title ?? __('E_payroll.na') }}
                                                                </div>
                                                                <div class="col-6">
                                                                    <strong>{{ __('E_payroll.pay_period') }}:</strong>
                                                                    {{ $payroll->start_date ? date('M d, Y', strtotime($payroll->start_date)) : __('E_payroll.na') }}
                                                                    -
                                                                    {{ $payroll->end_date ? date('M d, Y', strtotime($payroll->end_date)) : __('E_payroll.na') }}<br>
                                                                    <strong>{{ __('E_payroll.hire_date') }}:</strong>
                                                                    {{ $payroll->employee->hire_date ? date('M d, Y', strtotime($payroll->employee->hire_date)) : __('E_payroll.na') }}<br>
                                                                    <strong>{{ __('E_payroll.phone') }}:</strong>
                                                                    {{ $payroll->employee->phone_number ?? __('E_payroll.na') }}<br>
                                                                    <strong>{{ __('E_payroll.email') }}:</strong>
                                                                    {{ $payroll->employee->email ?? __('E_payroll.na') }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Earnings Section -->
                                                        <div class="earnings-section mt-3">
                                                            <h5 class="section-title">{{ __('E_payroll.earnings') }}</h5>
                                                            <table class="table table-sm table-bordered">

                                                                <tbody>
                                                                    <tr>
                                                                        <td>{{ __('E_payroll.basic_salary') }}</td>
                                                                        <td class="text-right">
                                                                            {{ number_format($payroll->basic_salary ?? 0, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                    @if ($payroll->bounas > 0)
                                                                        <tr>
                                                                            <td>{{ __('E_payroll.bonus') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->bounas, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr class="table-info">
                                                                        <td><strong>{{ __('E_payroll.total_earnings') }}</strong></td>
                                                                        <td class="text-right">
                                                                            <strong>{{ number_format(($payroll->basic_salary ?? 0) + ($payroll->bounas ?? 0), 2) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Deductions Section -->
                                                        <div class="deductions-section mt-3">
                                                            <h5 class="section-title">{{ __('E_payroll.deductions') }}</h5>
                                                            <table class="table table-sm table-bordered">

                                                                <tbody>
                                                                    @if ($payroll->taxes > 0)
                                                                        <tr>
                                                                            <td>{{ __('E_payroll.taxes_insurance') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->taxes, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($payroll->deductions > 0)
                                                                        <tr>
                                                                            <td>{{ __('E_payroll.other_deductions') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->deductions, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($payroll->attendance_deduction > 0)
                                                                        <tr>
                                                                            <td>{{ __('E_payroll.attendance_deduction') }}</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->attendance_deduction, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr class="table-warning">
                                                                        <td><strong>{{ __('E_payroll.total_deductions') }}</strong></td>
                                                                        <td class="text-right">
                                                                            <strong>{{ number_format(($payroll->taxes ?? 0) + ($payroll->deductions ?? 0) + ($payroll->attendance_deduction ?? 0), 2) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Attendance Information Table -->
                                                        <div class="attendance-section mt-3">
                                                            <h5 class="section-title">{{ __('E_payroll.attendance_details') }}</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped">

                                                                    <tbody>
                                                                        <tr>
                                                                            <td><strong>{{ __('E_payroll.days_absent') }}</strong></td>
                                                                            <td>{{ $payroll->days_absent ?? 0 }} {{ __('E_payroll.days') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>{{ __('E_payroll.rest_vacation') }}</strong></td>
                                                                            <td>{{ $payroll->rest_vacancy ?? 0 }} {{ __('E_payroll.days') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>{{ __('E_payroll.daily_wage') }}</strong></td>
                                                                            <td>${{ number_format($payroll->daily_wage ?? 0, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>{{ __('E_payroll.work_hours') }}</strong></td>
                                                                            <td>{{ $payroll->employee->work_start_time ?? __('E_payroll.na') }}
                                                                                -
                                                                                {{ $payroll->employee->work_end_time ?? __('E_payroll.na') }}
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
                                                                    <strong>{{ __('E_payroll.net_pay') }}:
                                                                        {{ number_format($payroll->net_pay ?? 0, 2) }}
                                                                    </strong>
                                                                </h4>
                                                            </div>
                                                        </div>

                                                        <!-- Footer -->
                                                        <div class="payslip-footer mt-3">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p class="small">{{ __('E_payroll.generated_on') }}:
                                                                        {{ date('M d, Y H:i A') }}</p>
                                                                    <p class="small">{{ __('E_payroll.created') }}:
                                                                        {{ date('M d, Y', strtotime($payroll->created_at)) }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-6 text-right">

                                                                    <button class="btn btn-sm btn-info"
                                                                        onclick="downloadPayslip({{ $payroll->id }})">
                                                                        <i class="fas fa-download"></i> {{ __('E_payroll.download') }}
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
                                                    <h5>{{ __('E_payroll.no_payroll_records_found') }}</h5>
                                                    <p>{{ __('E_payroll.no_payroll_records_match') }}</p>
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
                                    <h4 class="text-muted">{{ __('E_payroll.search_for_payslip') }}</h4>
                                    <p class="text-muted">{{ __('E_payroll.search_instructions') }}</p>
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
            downloadUrl: '{{ url('employee/payroll/download-pdf') }}',
            downloadAllUrl: '{{ url('employee/payroll/download-all-pdf') }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <!-- Link to the new JavaScript file -->
    <script src="{{ url('dist/js/payslip.js') }}"></script>

@endsection
