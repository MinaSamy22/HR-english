<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        direction: ltr;
        text-align: left;
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
    .company-name {
        font-size: 24px;
        font-weight: bold;
        margin: 10px 0;
        color: #333;
    }
    .report-title {
        font-size: 20px;
        margin: 10px 0;
        color: #666;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 8px 6px; /* Slightly increased */
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
        color: #333;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .no-record {
        text-align: center;
        color: #666;
        font-style: italic;
    }
    .signature-section {
        margin-top: 50px;
        text-align: left;
    }
    .signature-line {
        border-bottom: 2px solid #333;
        width: 200px;
        display: inline-block;
        margin-left: 10px;
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

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Attendance</th>
                <th>Attendance Date</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($getRecord as $value)
            <tr>
                <td>{{ $value->employee_id }}</td>
                <td>{{ $value->employee_name }}</td>
                <td>
                    @if ($value->attendance_type == 1)
                        Present
                    @elseif($value->attendance_type == 2)
                        Late
                    @elseif($value->attendance_type == 3)
                        Absent
                    @elseif($value->attendance_type == 4)
                        Half Day
                    @endif
                </td>
                <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                <td>{{ date('d-m-Y (h:i A)', strtotime($value->created_at)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="no-record">No records found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <h3>Signature: <span class="signature-line"></span></h3>
    </div>
</body>
</html>
