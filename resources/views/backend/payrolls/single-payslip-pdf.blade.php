@php
    $locale = app()->getLocale();
    $isArabic = ($locale === 'ar');
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isArabic ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('home.single_pdf.payslip_title') }} - {{ $payrollRecord->employee->name }}</title>
    <style>
        body {
            font-family: {{ $isArabic ? 'Arial, "Arial Unicode MS", sans-serif' : 'Arial, sans-serif' }};
            font-size: 13px;
            line-height: 1.3;
            margin: 0;
            padding: 5px;
            direction: {{ $isArabic ? 'rtl' : 'ltr' }};
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
        }
        .company-address {
            font-size: 13px;
            margin: 0;
            color: #666;
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
            padding-{{ $isArabic ? 'left' : 'right' }}: 10px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 8px;
            margin: 15px 0 8px 0;
            border-inline-start: 4px solid #007bff;
        }

        /* RTL and LTR border support for section titles */
        [dir="ltr"] .section-title {
            border-left: 4px solid #007bff;
            border-right: none;
        }

        [dir="rtl"] .section-title {
            border-right: 4px solid #007bff;
            border-left: none;
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
            text-align: {{ $isArabic ? 'right' : 'left' }};
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: {{ $isArabic ? 'left' : 'right' }};
        }
        .text-left {
            text-align: {{ $isArabic ? 'right' : 'left' }};
        }
        .text-center {
            text-align: center;
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
            text-align: {{ $isArabic ? 'left' : 'right' }};
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
            <h3 class="company-name">{{ $payrollRecord->company->name ?? __('home.single_pdf.company_name_default') }}</h3>
            <p class="company-address">{{ $payrollRecord->company->address ?? __('home.single_pdf.company_address_default') }}</p>
            <h4 style="margin: 10px 0 0 0; font-size: 17px;">{{ __('home.single_pdf.employee_payslip') }}</h4>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div class="info-row">
                <div class="info-col">
                    <strong>{{ __('home.single_pdf.employee_id') }}:</strong> {{ $payrollRecord->employee->id }}<br>
                    <strong>{{ __('home.single_pdf.employee_name') }}:</strong> {{ $payrollRecord->employee->name }}<br>
                    <strong>{{ __('home.single_pdf.department') }}:</strong> {{ $payrollRecord->employee->department->department_name ?? __('home.single_pdf.na') }}<br>
                    <strong>{{ __('home.single_pdf.job_title') }}:</strong> {{ $payrollRecord->employee->job->job_title ?? __('home.single_pdf.na') }}
                </div>
                <div class="info-col">
                    <strong>{{ __('home.single_pdf.pay_period') }}:</strong> {{ $payrollRecord->start_date ? date('M d, Y', strtotime($payrollRecord->start_date)) : __('home.single_pdf.na') }} - {{ $payrollRecord->end_date ? date('M d, Y', strtotime($payrollRecord->end_date)) : __('home.single_pdf.na') }}<br>
                    <strong>{{ __('home.single_pdf.hire_date') }}:</strong> {{ $payrollRecord->employee->hire_date ? date('M d, Y', strtotime($payrollRecord->employee->hire_date)) : __('home.single_pdf.na') }}<br>
                    <strong>{{ __('home.single_pdf.phone') }}:</strong> {{ $payrollRecord->employee->phone_number ?? __('home.single_pdf.na') }}<br>
                    <strong>{{ __('home.single_pdf.email') }}:</strong> {{ $payrollRecord->employee->email ?? __('home.single_pdf.na') }}
                </div>
            </div>
        </div>

        <!-- Earnings Section -->
        <div class="section-title">{{ __('home.single_pdf.earnings') }}</div>
        <table>
            <tbody>
                <tr>
                    <td>{{ __('home.single_pdf.basic_salary') }}</td>
                    <td class="text-right">{{ number_format($payrollRecord->basic_salary ?? 0, 2) }}</td>
                </tr>
                @if($payrollRecord->bounas > 0)
                <tr>
                    <td>{{ __('home.single_pdf.bonus') }}</td>
                    <td class="text-right">{{ number_format($payrollRecord->bounas, 2) }}</td>
                </tr>
                @endif
                <tr style="background-color: #e3f2fd;">
                    <td><strong>{{ __('home.single_pdf.total_earnings') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format(($payrollRecord->basic_salary ?? 0) + ($payrollRecord->bounas ?? 0), 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Deductions Section -->
        <div class="section-title">{{ __('home.single_pdf.deductions') }}</div>
        <table>
            <tbody>
                @if($payrollRecord->taxes > 0)
                <tr>
                    <td>{{ __('home.single_pdf.taxes_insurance') }}</td>
                    <td class="text-right">{{ number_format($payrollRecord->taxes, 2) }}</td>
                </tr>
                @endif
                @if($payrollRecord->deductions > 0)
                <tr>
                    <td>{{ __('home.single_pdf.other_deductions') }}</td>
                    <td class="text-right">{{ number_format($payrollRecord->deductions, 2) }}</td>
                </tr>
                @endif
                @if($payrollRecord->attendance_deduction > 0)
                <tr>
                    <td>{{ __('home.single_pdf.attendance_deduction') }}</td>
                    <td class="text-right">{{ number_format($payrollRecord->attendance_deduction, 2) }}</td>
                </tr>
                @endif
                <tr style="background-color: #fff3cd;">
                    <td><strong>{{ __('home.single_pdf.total_deductions') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format(($payrollRecord->taxes ?? 0) + ($payrollRecord->deductions ?? 0) + ($payrollRecord->attendance_deduction ?? 0), 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Attendance Information -->
        <div class="section-title">{{ __('home.single_pdf.attendance_details') }}</div>
        <table>
            <tbody>
                <tr>
                    <td><strong>{{ __('home.single_pdf.days_absent') }}</strong></td>
                    <td>{{ $payrollRecord->days_absent ?? 0 }} {{ __('home.single_pdf.days') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('home.single_pdf.rest_vacation') }}</strong></td>
                    <td>{{ $payrollRecord->rest_vacancy ?? 0 }} {{ __('home.single_pdf.days') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('home.single_pdf.daily_wage') }}</strong></td>
                    <td>${{ number_format($payrollRecord->daily_wage ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('home.single_pdf.work_hours') }}</strong></td>
                    <td>{{ $payrollRecord->employee->work_start_time ?? __('home.single_pdf.na') }} - {{ $payrollRecord->employee->work_end_time ?? __('home.single_pdf.na') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Net Pay Section -->
        <div class="net-pay">
            <h4><strong>{{ __('home.single_pdf.net_pay') }}: {{ number_format($payrollRecord->net_pay ?? 0, 2) }}</strong></h4>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-row">
                <div class="footer-col">
                    <p>{{ __('home.single_pdf.generated_on') }}: {{ date('M d, Y H:i A') }}</p>
                    <p>{{ __('home.single_pdf.created') }}: {{ date('M d, Y', strtotime($payrollRecord->created_at)) }}</p>
                </div>
                <div class="footer-col footer-right">
                    <p>{{ __('home.single_pdf.computer_generated') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
