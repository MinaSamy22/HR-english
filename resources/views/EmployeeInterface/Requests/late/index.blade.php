@extends('EmployeeInterface.layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Attendance Requests</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('employee.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Late & Half Day Requests</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-check"></i>{{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-ban"></i>{{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-ban"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Main Content Row -->
                <div class="row">
                    <!-- Late & Half Day Records -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history mr-2"></i>Late & Half Day Records
                                    <small class="text-muted ml-2">({{ \Carbon\Carbon::now()->format('F Y') }})</small>
                                </h3>
                            </div>
                            <div class="card-body">
                                @if($lateOrHalfDayAttendances->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lateOrHalfDayAttendances as $index => $record)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong>{{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}</small>
                                                        </td>
                                                        <td>
                                                            @if($record->attendance_type == 2)
                                                                <span class="badge badge-warning">
                                                                    <i class="fas fa-clock mr-1"></i>Late
                                                                </span>
                                                            @elseif($record->attendance_type == 4)
                                                                <span class="badge badge-info">
                                                                    <i class="fas fa-hourglass-half mr-1"></i>Half Day
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">
                                                                    <i class="fas fa-question mr-1"></i>Unknown
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($requests[$record->id]))
                                                                @php
                                                                    $request = $requests[$record->id];
                                                                    $statusClass = '';
                                                                    $statusIcon = '';
                                                                    switch ($request->status ?? 'pending') {
                                                                        case 'pending':
                                                                            $statusClass = 'badge-warning';
                                                                            $statusIcon = 'fa-hourglass-half';
                                                                            break;
                                                                        case 'approved':
                                                                            $statusClass = 'badge-success';
                                                                            $statusIcon = 'fa-check-circle';
                                                                            break;
                                                                        case 'rejected':
                                                                            $statusClass = 'badge-danger';
                                                                            $statusIcon = 'fa-times-circle';
                                                                            break;
                                                                        default:
                                                                            $statusClass = 'badge-secondary';
                                                                            $statusIcon = 'fa-question';
                                                                    }
                                                                @endphp
                                                                <span class="badge {{ $statusClass }}">
                                                                    <i class="fas {{ $statusIcon }} mr-1"></i>{{ ucfirst($request->status ?? 'pending') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-light">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Not Requested
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!isset($requests[$record->id]))
                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                        onclick="openRequestModal({{ $record->id }}, '{{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}', '{{ $record->attendance_type == 2 ? 'Late' : 'Half Day' }}')">
                                                                    <i class="fas fa-paper-plane mr-1"></i>Request
                                                                </button>
                                                            @else
                                                                <span class="text-muted">
                                                                    <i class="fas fa-check mr-1"></i>Requested
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h4 class="text-muted">Perfect Attendance!</h4>
                                        <p class="text-muted">No late or half-day records found for this month</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-info-circle mr-2"></i>Attendance Information
                                </h3>
                            </div>
                            <div class="card-body">
                                @php
                                    $totalRecords = $lateOrHalfDayAttendances->count();
                                    $requestedRecords = collect($requests)->count();
                                    $pendingRequests = collect($requests)->where('status', 'pending')->count();
                                    $approvedRequests = collect($requests)->where('status', 'approved')->count();
                                    $rejectedRequests = collect($requests)->where('status', 'rejected')->count();
                                @endphp

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-calendar-alt mr-2 text-info"></i>Current Month
                                        </span>
                                        <span class="badge badge-info badge-pill">{{ \Carbon\Carbon::now()->format('M Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-list mr-2 text-warning"></i>Late days
                                        </span>
                                        <span class="badge badge-warning badge-pill">{{ $lateDays }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-paper-plane mr-2 text-primary"></i>Half days
                                        </span>
                                        <span class="badge badge-primary badge-pill">{{ $halfDays }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-paper-plane mr-2 text-primary"></i>Absent days
                                        </span>
                                        <span class="badge badge-primary badge-pill">{{ $absentDays }}</span>
                                    </li>

                                      <div class="card-header">
                                     <h3 class="card-title">
                                    <i class="fas fa-info-circle mr-2"></i>Request Information
                                </h3>
                                      </div>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-hourglass-half mr-2 text-warning"></i>Pending
                                        </span>
                                        <span class="badge badge-warning badge-pill">{{ $pendingRequests }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-check-circle mr-2 text-success"></i>Approved
                                        </span>
                                        <span class="badge badge-success badge-pill">{{ $approvedRequests }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-times-circle mr-2 text-danger"></i>Rejected
                                        </span>
                                        <span class="badge badge-danger badge-pill">{{ $rejectedRequests }}</span>
                                    </li>
                                </ul>

                                <hr>

                                <h6><i class="fas fa-clock mr-2"></i>Processing Time</h6>
                                <p class="text-sm text-muted">
                                    • Normal requests: 2-3 business days<br>
                                    • Urgent requests: Same day<br>
                                </p>

                                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Important Notes</h6>
                                <p class="text-sm text-muted">
                                    • Submit requests as soon as possible<br>
                                    • Provide clear justification for approval<br>
                                    • Contact HR for urgent requests
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Late/Half Day Request
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('employee.late.request') }}" method="POST" id="requestForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="attendance_id" id="modal_attendance_id">

                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt mr-1"></i>Date</label>
                            <p class="form-control-plaintext font-weight-bold" id="modal_date"></p>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-tag mr-1"></i>Type</label>
                            <p class="form-control-plaintext" id="modal_type"></p>
                        </div>

                        <div class="form-group">
                            <label for="reason">
                                <i class="fas fa-comment mr-1"></i>Reason <span class="text-danger">*</span>
                            </label>
                            <textarea name="reason" id="reason" rows="4" class="form-control"
                                placeholder="Please provide a detailed explanation for the attendance issue..." required></textarea>
                            <small class="text-muted">Minimum 10 characters required</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i>Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRequestModal(attendanceId, date, type) {
            document.getElementById('modal_attendance_id').value = attendanceId;
            document.getElementById('modal_date').textContent = date;
            document.getElementById('modal_type').innerHTML = '<span class="badge badge-' +
                (type === 'Late' ? 'warning' : 'info') + '">' +
                '<i class="fas fa-' + (type === 'Late' ? 'clock' : 'hourglass-half') + ' mr-1"></i>' +
                type + '</span>';
            document.getElementById('reason').value = '';
            $('#requestModal').modal('show');
        }

        // Form validation
        document.getElementById('requestForm').addEventListener('submit', function(e) {
            const reason = document.getElementById('reason').value.trim();
            if (reason.length < 10) {
                e.preventDefault();
                alert('Please provide a reason with at least 10 characters.');
                return false;
            }
        });
    </script>

@endsection
