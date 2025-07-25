<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: 'dejavusans';
            direction: rtl;
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
            text-align: right;
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
            text-align: left;
            font-size: 18px;
        }
    </style>

</head>

<body>
    <div class="header">
        @if(Auth::user()->company && Auth::user()->company->logo)
            <img src="{{ public_path('uploads/company_logos/' . Auth::user()->company->logo) }}"
                 alt="Company Logo"
                 class="company-logo">
        @endif

        <div class="report-title">Attendance Report</div>
    </div>

    <table class="table table-bordered" style="width: 100%; font-size: 10px;">
        <thead>
            <tr>
                <th>Emp ID</th>
                <th>Name</th>
                <th>Basic Salary</th>
                <th>Bonuses</th>
                <th>Deductions</th>
                <th>Attendance Deduction</th>
                <th>Taxes/Insurance</th>
                <th>Vacation</th>
                <th>Net Pay</th>
                <th>Payroll Type</th>
                <th>Pay Date</th>
                <th>Month</th>
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
                    <td>
                        @if ($value->payroll_type == 'monthly')
                            {{ $value->rest_vacancy }} day
                        @else
                            0
                        @endif
                    </td>
                    <td>{{ number_format($value->net_pay, 2) }}</td>
                    <td>{{ $value->payroll_type }} </td>
                    <td>{{ date('d-m-Y h:i', strtotime($value->created_at)) }}</td>
                    <td>{{ date('M Y', strtotime($value->start_date)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>









    <br>
    <h2 class="signature"> Signature: ________ </h2>
</body>

</html>
