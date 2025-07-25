@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ url('dist/css/payslip.css') }}">

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Employee Payslips Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/payroll') }}">Payroll</a></li>
                            <li class="breadcrumb-item active">Employee Payslips</li>
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
                                <h3 class="card-title">Search Employee Payslip</h3>
                            </div>
                            <div class="card-body">
                                <!-- Search Form -->
                                <form method="GET" action="{{ url('admin/payslip') }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Employee Name</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter employee name" value="{{ Request::get('name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="month">Month</label>
                                                <select class="form-control" id="month" name="month">
                                                    <option value="">Select Month</option>
                                                    <option value="1"
                                                        {{ Request::get('month') == '1' ? 'selected' : '' }}>January
                                                    </option>
                                                    <option value="2"
                                                        {{ Request::get('month') == '2' ? 'selected' : '' }}>February
                                                    </option>
                                                    <option value="3"
                                                        {{ Request::get('month') == '3' ? 'selected' : '' }}>March</option>
                                                    <option value="4"
                                                        {{ Request::get('month') == '4' ? 'selected' : '' }}>April</option>
                                                    <option value="5"
                                                        {{ Request::get('month') == '5' ? 'selected' : '' }}>May</option>
                                                    <option value="6"
                                                        {{ Request::get('month') == '6' ? 'selected' : '' }}>June</option>
                                                    <option value="7"
                                                        {{ Request::get('month') == '7' ? 'selected' : '' }}>July</option>
                                                    <option value="8"
                                                        {{ Request::get('month') == '8' ? 'selected' : '' }}>August
                                                    </option>
                                                    <option value="9"
                                                        {{ Request::get('month') == '9' ? 'selected' : '' }}>September
                                                    </option>
                                                    <option value="10"
                                                        {{ Request::get('month') == '10' ? 'selected' : '' }}>October
                                                    </option>
                                                    <option value="11"
                                                        {{ Request::get('month') == '11' ? 'selected' : '' }}>November
                                                    </option>
                                                    <option value="12"
                                                        {{ Request::get('month') == '12' ? 'selected' : '' }}>December
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="year">Year</label>
                                                <select class="form-control" id="year" name="year">
                                                    <option value="">Select Year</option>
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
                                        Payslip Results
                                        @if (Request::get('name'))
                                            for "{{ Request::get('name') }}"
                                        @endif
                                        @if (Request::get('month') || Request::get('year'))
                                            ({{ Request::get('month') ? date('F', mktime(0, 0, 0, Request::get('month'), 1)) : '' }}
                                            {{ Request::get('year') }})
                                        @endif
                                    </h3>
                                    @if ($getRecord->count() > 0)
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-primary" onclick="printAllPayslips()">
                                                <i class="fas fa-print"></i> Print All Results
                                            </button>
                                            <button type="button" class="btn btn-success" onclick="downloadAllPayslips()">
                                                <i class="fas fa-download"></i> Download All PDFs
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
                                                                    <strong>Employee ID:</strong>
                                                                    {{ $payroll->employee->id }}<br>
                                                                    <strong>Employee Name:</strong>
                                                                    {{ $payroll->employee->name }}<br>
                                                                    <strong>Department:</strong>
                                                                    {{ $payroll->employee->department->department_name ?? 'N/A' }}<br>
                                                                    <strong>Job Title:</strong>
                                                                    {{ $payroll->employee->job->job_title ?? 'N/A' }}
                                                                </div>
                                                                <div class="col-6">
                                                                    <strong>Pay Period:</strong>
                                                                    {{ $payroll->start_date ? date('M d, Y', strtotime($payroll->start_date)) : 'N/A' }}
                                                                    -
                                                                    {{ $payroll->end_date ? date('M d, Y', strtotime($payroll->end_date)) : 'N/A' }}<br>
                                                                    <strong>Hire Date:</strong>
                                                                    {{ $payroll->employee->hire_date ? date('M d, Y', strtotime($payroll->employee->hire_date)) : 'N/A' }}<br>
                                                                    <strong>Phone:</strong>
                                                                    {{ $payroll->employee->phone_number ?? 'N/A' }}<br>
                                                                    <strong>Email:</strong>
                                                                    {{ $payroll->employee->email ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Earnings Section -->
                                                        <div class="earnings-section mt-3">
                                                            <h5 class="section-title">EARNINGS</h5>
                                                            <table class="table table-sm table-bordered">

                                                                <tbody>
                                                                    <tr>
                                                                        <td>Basic Salary</td>
                                                                        <td class="text-right">
                                                                            {{ number_format($payroll->basic_salary ?? 0, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                    @if ($payroll->bounas > 0)
                                                                        <tr>
                                                                            <td>Bonus</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->bounas, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr class="table-info">
                                                                        <td><strong>Total Earnings</strong></td>
                                                                        <td class="text-right">
                                                                            <strong>{{ number_format(($payroll->basic_salary ?? 0) + ($payroll->bounas ?? 0), 2) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Deductions Section -->
                                                        <div class="deductions-section mt-3">
                                                            <h5 class="section-title">DEDUCTIONS</h5>
                                                            <table class="table table-sm table-bordered">

                                                                <tbody>
                                                                    @if ($payroll->taxes > 0)
                                                                        <tr>
                                                                            <td>Taxes & Insurance</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->taxes, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($payroll->deductions > 0)
                                                                        <tr>
                                                                            <td>Other Deductions</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->deductions, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if ($payroll->attendance_deduction > 0)
                                                                        <tr>
                                                                            <td>Attendance Deduction</td>
                                                                            <td class="text-right">
                                                                                {{ number_format($payroll->attendance_deduction, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr class="table-warning">
                                                                        <td><strong>Total Deductions</strong></td>
                                                                        <td class="text-right">
                                                                            <strong>{{ number_format(($payroll->taxes ?? 0) + ($payroll->deductions ?? 0) + ($payroll->attendance_deduction ?? 0), 2) }}</strong>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Attendance Information Table -->
                                                        <div class="attendance-section mt-3">
                                                            <h5 class="section-title">ATTENDANCE DETAILS</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped">

                                                                    <tbody>
                                                                        <tr>
                                                                            <td><strong>Days Absent</strong></td>
                                                                            <td>{{ $payroll->days_absent ?? 0 }} days</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Rest/Vacation</strong></td>
                                                                            <td>{{ $payroll->rest_vacancy ?? 0 }} days</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Daily Wage</strong></td>
                                                                            <td>${{ number_format($payroll->daily_wage ?? 0, 2) }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Work Hours</strong></td>
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
                                                                    <strong>NET PAY:
                                                                        {{ number_format($payroll->net_pay ?? 0, 2) }}
                                                                    </strong>
                                                                </h4>
                                                            </div>
                                                        </div>

                                                        <!-- Footer -->
                                                        <div class="payslip-footer mt-3">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p class="small">Generated on:
                                                                        {{ date('M d, Y H:i A') }}</p>
                                                                    <p class="small">Created:
                                                                        {{ date('M d, Y', strtotime($payroll->created_at)) }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-6 text-right">
                                                                    <button class="btn btn-sm btn-primary"
                                                                        onclick="printPayslip({{ $payroll->id }})">
                                                                        <i class="fas fa-print"></i> Print
                                                                    </button>
                                                                    <button class="btn btn-sm btn-info"
                                                                        onclick="downloadPayslip({{ $payroll->id }})">
                                                                        <i class="fas fa-download"></i> Download
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
                                                    <h5>No Payroll Records Found</h5>
                                                    <p>No payroll records match your search criteria. Please try different
                                                        search terms.</p>
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
                                    <h4 class="text-muted">Search for Employee Payslips</h4>
                                    <p class="text-muted">Use the search form above to find and view employee payslips by
                                        name, month, or year.</p>
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
