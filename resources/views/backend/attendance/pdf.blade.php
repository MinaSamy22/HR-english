@php
    $locale = app()->getLocale();
    $isArabic = ($locale === 'ar');
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isArabic ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.attendance_report') }}</title>
    <style>


        body {
            font-family: {{ $isArabic ? "'Amiri'" : "'DejaVu Sans'" }}, sans-serif;
            margin: 20px;
            direction: {{ $isArabic ? 'rtl' : 'ltr' }};
            unicode-bidi: {{ $isArabic ? 'embed' : 'normal' }};
            text-align: {{ $isArabic ? 'right' : 'left' }};
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
            text-align: {{ $isArabic ? 'right' : 'left' }};
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
            text-align: {{ $isArabic ? 'right' : 'left' }};
        }
        .signature-line {
            border-bottom: 2px solid #333;
            width: 200px;
            display: inline-block;
            margin-{{ $isArabic ? 'right' : 'left' }}: 10px;
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
                <th>{{ __('dashboard.employee_id') }}</th>
                <th>{{ __('dashboard.employee_name') }}</th>
                <th>{{ __('h_employee.branch') }}</th>

                <th>{{ __('dashboard.attendance') }}</th>
                <th>{{ __('dashboard.attendance_date') }}</th>
                <th>{{ __('dashboard.created_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($getRecord as $value)
            <tr>
                <td>{{ $value->employee_id }}</td>
                <td>{{ $value->employee_name }}</td>
                <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                
                <td>
                    @switch($value->attendance_type)
                        @case(1) {{ __('dashboard.present') }} @break
                        @case(2) {{ __('dashboard.late') }} @break
                        @case(3) {{ __('dashboard.absent') }} @break
                        @case(4) {{ __('dashboard.half_day') }} @break
                    @endswitch
                </td>
                <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                <td>{{ date('d-m-Y (h:i A)', strtotime($value->created_at)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="no-record">{{ __('dashboard.no_records_found') }}</td>
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
