@php
    $locale = app()->getLocale();
    // Treat Arabic, Urdu, and Ardo as RTL
    $isRtl = in_array($locale, ['ar', 'au']);
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.attendance_report') }}</title>
    <style>
        body {
            font-family: {{ $isRtl ? "'Amiri'" : "'DejaVu Sans'" }}, sans-serif;
            margin: 20px;
            direction: {{ $isRtl ? 'rtl' : 'ltr' }};
            unicode-bidi: {{ $isRtl ? 'embed' : 'normal' }};
            text-align: {{ $isRtl ? 'right' : 'left' }};
            font-size: 14px;
            line-height: 1.5;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .company-logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #444;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px 6px;
            border: 1px solid #ddd;
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .no-record {
            text-align: center;
            color: #777;
            font-style: italic;
        }

        /* Signature */
        .signature-section {
            margin-top: 50px;
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        .signature-line {
            border-bottom: 2px solid #333;
            width: 200px;
            display: inline-block;
            margin-{{ $isRtl ? 'right' : 'left' }}: 10px;
        }
    </style>

</head>
<body>

    <div class="header">
        @if(Auth::user()->company && Auth::user()->company->logo)
            <img src="{{ public_path('../../HR-Uploads/company_logos/' . Auth::user()->company->logo) }}"
                 alt="Company Logo" class="company-logo">
        @endif
        <div class="report-title">{{ __('dashboard.attendance_report') }}</div>
    </div>

    <table>
    <thead>
        <tr>
            <th style="width: 10%;">{{ __('dashboard.employee_id') }}</th>
            <th style="width: 18%;">{{ __('dashboard.employee_name') }}</th>
            <th style="width: 15%;">{{ __('h_employee.branch') }}</th>
            <th style="width: 12%;">{{ __('dashboard.attendance') }}</th>
            <th style="width: 12%;">{{ __('dashboard.check_in') }}</th>
            <th style="width: 12%;">{{ __('dashboard.check_out') }}</th>
            <th style="width: 13%;">{{ __('dashboard.attendance_date') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($getRecord as $value)
        <tr>
            <td style="width: 10%;">{{ $value->employee_id }}</td>
            <td style="width: 18%;">{{ $value->employee_name }}</td>
            <td style="width: 15%;">{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
            <td style="width: 12%;">
                @switch($value->attendance_type)
                    @case(1) {{ __('dashboard.present') }} @break
                    @case(2) {{ __('dashboard.late') }} @break
                    @case(3) {{ __('dashboard.absent') }} @break
                    @case(4) {{ __('dashboard.half_day') }} @break
                @endswitch
            </td>
            <td style="width: 12%; white-space: nowrap;">{{ $value->check_in ? date('h:i A', strtotime($value->check_in)) : '-' }}</td>
            <td style="width: 12%; white-space: nowrap;">{{ $value->check_out ? date('h:i A', strtotime($value->check_out)) : '-' }}</td>
            <td style="width: 13%; white-space: nowrap;">{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="no-record">{{ __('dashboard.no_records_found') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>

    <div class="signature-section">
        <strong>{{ __('dashboard.signature') }}:</strong>
        <span class="signature-line"></span>
    </div>

</body>
</html>
