{{-- Create this file as: resources/views/EmployeeInterface/payrolls/h_payslip.blade.php --}}

<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('h_payslip.payslip_title') }} - {{ $payrollRecord->employee->name }}</title>
    <style>
        body {
            font-family: {{ app()->getLocale() == 'ar' ? 'cairo, "DejaVu Sans", sans-serif' : 'DejaVu Sans, Arial, sans-serif' }};
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
            padding: 5px;
            color: #333;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
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
            padding-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 8px;
            margin: 15px 0 8px 0;
            border-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 4px solid #007bff;
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
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
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
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
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
            <h3 class="company-name">{{ $payrollRecord->company->name ?? __('h_payslip.company_name') }}</h3>
            <p class="company-address">{{ $payrollRecord->company->address ?? __('h_payslip.company_address') }}</p>
            <h4 class="payslip-title">{{ __('h_payslip.employee_payslip') }}</h4>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div class="info-row">
                <div class="info-col">
                    <strong>{{ __('h_payslip.employee_id') }}:</strong> {{ $payrollRecord->employee->id }}<br>
                    <strong>{{ __('h_payslip.employee_name') }}:</strong> {{ $payrollRecord->employee->name }}<br>
                    <strong>{{ __('h_payslip.department') }}:</strong> {{ $payrollRecord->employee->department->department_name ?? __('h_payslip.not_available') }}<br>
                    <strong>{{ __('h_payslip.job_title') }}:</strong> {{ $payrollRecord->employee->job->job_title ?? __('h_payslip.not_available') }}
                </div>
                <div class="info-col">
                    <strong>{{ __('h_payslip.pay_period') }}:</strong>
                    {{ $payrollRecord->start_date ? date('M d, Y', strtotime($payrollRecord->start_date)) : __('h_payslip.not_available') }}
                    -
                    {{ $payrollRecord->end_date ? date('M d, Y', strtotime($payrollRecord->end_date)) : __('h_payslip.not_available') }}<br>
                    <strong>{{ __('h_payslip.hire_date') }}:</strong> {{ $payrollRecord->employee->hire_date ? date('M d, Y', strtotime($payrollRecord->employee->hire_date)) : __('h_payslip.not_available') }}<br>
                    <strong>{{ __('h_payslip.phone') }}:</strong> {{ $payrollRecord->employee->phone_number ?? __('h_payslip.not_available') }}<br>
                    <strong>{{ __('h_payslip.email') }}:</strong> {{ $payrollRecord->employee->email ?? __('h_payslip.not_available') }}
                </div>
            </div>
        </div>

        <!-- Earnings Section -->
        <div class="section-title">{{ __('h_payslip.earnings') }}</div>
        <table>
            <tbody>
                <tr>
                    <td>{{ __('h_payslip.basic_salary') }}</td>
                    <td class="text-right">${{ number_format($payrollRecord->basic_salary ?? 0, 2) }}</td>
                </tr>
                @if($payrollRecord->bounas > 0)
                <tr>
                    <td>{{ __('h_payslip.bonus') }}</td>
                    <td class="text-right">${{ number_format($payrollRecord->bounas, 2) }}</td>
                </tr>
                @endif
                <tr class="earnings-total">
                    <td><strong>{{ __('h_payslip.total_earnings') }}</strong></td>
                    <td class="text-right">
                        <strong>${{ number_format(($payrollRecord->basic_salary ?? 0) + ($payrollRecord->bounas ?? 0), 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Deductions Section -->
        <div class="section-title">{{ __('h_payslip.deductions') }}</div>
        <table>
            <tbody>
                @if($payrollRecord->taxes > 0)
                <tr>
                    <td>{{ __('h_payslip.taxes_insurance') }}</td>
                    <td class="text-right">${{ number_format($payrollRecord->taxes, 2) }}</td>
                </tr>
                @endif
                @if($payrollRecord->deductions > 0)
                <tr>
                    <td>{{ __('h_payslip.other_deductions') }}</td>
                    <td class="text-right">${{ number_format($payrollRecord->deductions, 2) }}</td>
                </tr>
                @endif
                @if($payrollRecord->attendance_deduction > 0)
                <tr>
                    <td>{{ __('h_payslip.attendance_deduction') }}</td>
                    <td class="text-right">${{ number_format($payrollRecord->attendance_deduction, 2) }}</td>
                </tr>
                @endif
                <tr class="deductions-total">
                    <td><strong>{{ __('h_payslip.total_deductions') }}</strong></td>
                    <td class="text-right">
                        <strong>${{ number_format(($payrollRecord->taxes ?? 0) + ($payrollRecord->deductions ?? 0) + ($payrollRecord->attendance_deduction ?? 0), 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Attendance Information -->
        <div class="section-title">{{ __('h_payslip.attendance_details') }}</div>
        <table>
            <tbody>
                <tr>
                    <td><strong>{{ __('h_payslip.days_absent') }}</strong></td>
                    <td>{{ $payrollRecord->days_absent ?? 0 }} {{ __('h_payslip.days') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('h_payslip.rest_vacation') }}</strong></td>
                    <td>{{ $payrollRecord->rest_vacancy ?? 0 }} {{ __('h_payslip.days') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('h_payslip.daily_wage') }}</strong></td>
                    <td>${{ number_format($payrollRecord->daily_wage ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('h_payslip.work_hours') }}</strong></td>
                    <td>{{ $payrollRecord->employee->work_start_time ?? __('h_payslip.not_available') }} - {{ $payrollRecord->employee->work_end_time ?? __('h_payslip.not_available') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Net Pay Section -->
        <div class="net-pay">
            <h4><strong>{{ __('h_payslip.net_pay') }}: ${{ number_format($payrollRecord->net_pay ?? 0, 2) }}</strong></h4>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-row">
                <div class="footer-col">
                    <p><strong>{{ __('h_payslip.generated_on') }}:</strong> {{ date('M d, Y H:i A') }}</p>
                    <p><strong>{{ __('h_payslip.pay_date') }}:</strong> {{ date('M d, Y', strtotime($payrollRecord->created_at)) }}</p>
                </div>
                <div class="footer-col footer-right">
                    <p>{{ __('h_payslip.computer_generated') }}</p>
                    <p>{{ __('h_payslip.no_signature') }}</p>
                </div>
            </div>
            <div class="confidential-notice">
                <p>{{ __('h_payslip.confidential_notice') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
