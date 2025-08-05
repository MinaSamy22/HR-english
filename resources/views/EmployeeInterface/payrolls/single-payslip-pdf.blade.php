{{-- Create this file as: resources/views/EmployeeInterface/payrolls/single-payslip-pdf.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payrollRecord->employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.3;
            margin: 0;
            padding: 5px;
            color: #333;
        }
        .payslip-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 15px;
            height: auto;
        }
        .payslip-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #2c3e50;
        }
        .company-address {
            font-size: 13px;
            margin: 0;
            color: #666;
        }
        .payslip-title {
            margin: 10px 0 0 0;
            font-size: 17px;
            color: #34495e;
            font-weight: bold;
        }
        .employee-info {
            margin-bottom: 15px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 8px;
            margin: 15px 0 8px 0;
            border-left: 4px solid #007bff;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .earnings-total {
            background-color: #e3f2fd;
            font-weight: bold;
        }
        .deductions-total {
            background-color: #fff3cd;
            font-weight: bold;
        }
        .net-pay {
            background-color: #d4edda;
            border: 2px solid #28a745;
            padding: 15px;
            text-align: center;
            margin: 15px 0 10px 0;
        }
        .net-pay h4 {
            margin: 0;
            font-size: 19px;
            color: #155724;
        }
        .footer {
            margin-top: 15px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
        }
        .footer-row {
            display: table;
            width: 100%;
        }
        .footer-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .footer-right {
            text-align: right;
        }
        .confidential-notice {
            text-align: center;
            font-size: 10px;
            color: #888;
            margin-top: 10px;
            font-style: italic;
        }
        @media print {
            body {
                padding: 0;
            }
            .payslip-container {
                border: 2px solid #333;
                padding: 15px;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="payslip-container">
        <!-- Header -->
        <div class="payslip-header">
            <h3 class="company-name">{{ $payrollRecord->company->name ?? 'Company Name' }}</h3>
            <p class="company-address">{{ $payrollRecord->company->address ?? 'Company Address' }}</p>
            <h4 class="payslip-title">EMPLOYEE PAYSLIP</h4>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div class="info-row">
                <div class="info-col">
                    <strong>Employee ID:</strong> {{ $payrollRecord->employee->id }}<br>
                    <strong>Employee Name:</strong> {{ $payrollRecord->employee->name }}<br>
                    <strong>Department:</strong> {{ $payrollRecord->employee->department->department_name ?? 'N/A' }}<br>
                    <strong>Job Title:</strong> {{ $payrollRecord->employee->job->job_title ?? 'N/A' }}
                </div>
                <div class="info-col">
                    <strong>Pay Period:</strong>
                    {{ $payrollRecord->start_date ? date('M d, Y', strtotime($payrollRecord->start_date)) : 'N/A' }}
                    -
                    {{ $payrollRecord->end_date ? date('M d, Y', strtotime($payrollRecord->end_date)) : 'N/A' }}<br>
                    <strong>Hire Date:</strong> {{ $payrollRecord->employee->hire_date ? date('M d, Y', strtotime($payrollRecord->employee->hire_date)) : 'N/A' }}<br>
                    <strong>Phone:</strong> {{ $payrollRecord->employee->phone_number ?? 'N/A' }}<br>
                    <strong>Email:</strong> {{ $payrollRecord->employee->email ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Earnings Section -->
        <div class="section-title">EARNINGS</div>
        <table>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="text-right">${{ number_format($payrollRecord->basic_salary ?? 0, 2) }}</td>
                </tr>
                @if($payrollRecord->bounas > 0)
                <tr>
                    <td>Bonus</td>
                    <td class="text-right">${{ number_format($payrollRecord->bounas, 2) }}</td>
                </tr>
                @endif
                <tr class="earnings-total">
                    <td><strong>Total Earnings</strong></td>
                    <td class="text-right">
                        <strong>${{ number_format(($payrollRecord->basic_salary ?? 0) + ($payrollRecord->bounas ?? 0), 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Deductions Section -->
        <div class="section-title">DEDUCTIONS</div>
        <table>
            <tbody>
                @if($payrollRecord->taxes > 0)
                <tr>
                    <td>Taxes & Insurance</td>
                    <td class="text-right">${{ number_format($payrollRecord->taxes, 2) }}</td>
                </tr>
                @endif
                @if($payrollRecord->deductions > 0)
                <tr>
                    <td>Other Deductions</td>
                    <td class="text-right">${{ number_format($payrollRecord->deductions, 2) }}</td>
                </tr>
                @endif
                @if($payrollRecord->attendance_deduction > 0)
                <tr>
                    <td>Attendance Deduction</td>
                    <td class="text-right">${{ number_format($payrollRecord->attendance_deduction, 2) }}</td>
                </tr>
                @endif
                <tr class="deductions-total">
                    <td><strong>Total Deductions</strong></td>
                    <td class="text-right">
                        <strong>${{ number_format(($payrollRecord->taxes ?? 0) + ($payrollRecord->deductions ?? 0) + ($payrollRecord->attendance_deduction ?? 0), 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Attendance Information -->
        <div class="section-title">ATTENDANCE DETAILS</div>
        <table>
            <tbody>
                <tr>
                    <td><strong>Days Absent</strong></td>
                    <td>{{ $payrollRecord->days_absent ?? 0 }} days</td>
                </tr>
                <tr>
                    <td><strong>Rest/Vacation</strong></td>
                    <td>{{ $payrollRecord->rest_vacancy ?? 0 }} days</td>
                </tr>
                <tr>
                    <td><strong>Daily Wage</strong></td>
                    <td>${{ number_format($payrollRecord->daily_wage ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Work Hours</strong></td>
                    <td>{{ $payrollRecord->employee->work_start_time ?? 'N/A' }} - {{ $payrollRecord->employee->work_end_time ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Net Pay Section -->
        <div class="net-pay">
            <h4><strong>NET PAY: ${{ number_format($payrollRecord->net_pay ?? 0, 2) }}</strong></h4>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-row">
                <div class="footer-col">
                    <p><strong>Generated on:</strong> {{ date('M d, Y H:i A') }}</p>
                    <p><strong>Pay Date:</strong> {{ date('M d, Y', strtotime($payrollRecord->created_at)) }}</p>
                </div>
                <div class="footer-col footer-right">
                    <p>This is a computer-generated document.</p>
                    <p>No signature required.</p>
                </div>
            </div>
            <div class="confidential-notice">
                <p>This document contains confidential and proprietary information. Please handle with care.</p>
            </div>
        </div>
    </div>
</body>
</html>
