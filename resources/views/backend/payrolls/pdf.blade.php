<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('h_payroll_report.title') }}</title>
    <style>
        body {
            font-family: 'dejavusans';
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            background-color: #fff;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 5px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        th,
        td {
            padding: 10px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            border: 1px solid #ccc;
            font-weight: normal;
        }

        thead {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        tbody tr:nth-child(odd) {
            background-color: #f7f7f7;
        }

        .signature {
            margin-top: 20px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            font-size: 18px;
        }
    </style>

</head>

<body>
    <div class="header">
        @if (Auth::user()->company && Auth::user()->company->logo)
            <img src="{{ public_path('../../HR-Uploads/company_logos/' . Auth::user()->company->logo) }}"
                alt="Company Logo" class="company-logo">
        @endif

        <div class="report-title">{{ __('h_payroll_report.title') }}</div>
    </div>

    <table class="table table-bordered" style="width: 100%; font-size: 10px;">
        <thead>
            <tr>
                <th>{{ __('h_payroll_report.emp_id') }}</th>
                <th>{{ __('h_payroll_report.name') }}</th>
                <th>{{ __('h_payroll_report.basic_salary') }}</th>
                <th>{{ __('h_payroll_report.bonuses') }}</th>
                <th>{{ __('h_payroll_report.deductions') }}</th>
                <th>{{ __('h_payroll_report.attendance_deduction') }}</th>
                <th>{{ __('h_payroll_report.taxes_insurance') }}</th>
                <th>{{ __('h_payroll.is_insure') }}</th>
                <th>{{ __('h_payroll_report.vacation') }}</th>
                <th>{{ __('h_payroll_report.net_pay') }}</th>
                <th>{{ __('h_payroll_report.payroll_type') }}</th>
                <th>{{ __('h_payroll_report.pay_date') }}</th>
                <th>{{ __('h_payroll_report.month') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($getRecord as $value)
                <tr>
                    <td>{{ $value->employee_id }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ number_format($value->basic_salary, 2) }}</td>
                    <td>{{ number_format($value->bounas, 2) }}</td>
                    <td>{{ number_format($value->deductions, 2) }}</td>
                    <td>{{ number_format($value->attendance_deduction, 2) }}</td>
                    <td>{{ number_format($value->taxes, 2) }}</td>
                    <td class="text-center">
                        @if ($value->is_insured == 1)
                            <span title="مؤمن عليه" style="color: green">{{ __('h_payroll.yes') }}</span>
                        @else
                            <span title="غير مؤمن عليه" style="color: red">{{ __('h_payroll.no') }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($value->payroll_type == 'monthly')
                            {{ $value->rest_vacancy }} {{ __('h_payroll_report.day') }}
                        @else
                            0
                        @endif
                    </td>
                    <td>{{ number_format($value->net_pay, 2) }}</td>
                    <td>{{ __('h_payroll_report.' . $value->payroll_type) }}</td>
                    <td>{{ date('d-m-Y h:i', strtotime($value->created_at)) }}</td>
                    <td>{{ date('M Y', strtotime($value->start_date)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <h2 class="signature">{{ __('h_payroll_report.signature') }}: ________ </h2>
</body>

</html>
