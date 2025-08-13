@extends('EmployeeInterface.layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('E_late.attendance_requests') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('employee.home') }}">{{ __('E_late.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('E_late.late_half_day_requests') }}</li>
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
                                    <i class="fas fa-history mr-2"></i>{{ __('E_late.late_half_day_records') }}
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
                                                    <th>{{ __('E_late.date') }}</th>
                                                    <th>{{ __('E_late.type') }}</th>
                                                    <th>{{ __('E_late.status') }}</th>
                                                    <th>{{ __('E_late.actions') }}</th>
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
                                                                    <i class="fas fa-clock mr-1"></i>{{ __('E_late.late') }}
                                                                </span>
                                                            @elseif($record->attendance_type == 4)
                                                                <span class="badge badge-info">
                                                                    <i class="fas fa-hourglass-half mr-1"></i>{{ __('E_late.half_day') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">
                                                                    <i class="fas fa-question mr-1"></i>{{ __('E_late.unknown') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($requests[$record->id]))
                                                                @php
                                                                    $request = $requests[$record->id];
                                                                    $status = $request->status ?? 'pending';
                                                                    $statusClass = '';
                                                                    $statusIcon = '';
                                                                    switch ($status) {
                                                                        case 'pending':
                                                                            $statusClass = 'badge-warning';
                                                                            $statusIcon = 'fa-hourglass-half';
                                                                            break;
                                                                        case 'approved':
                                                                        case 'accepted':
                                                                            $statusClass = 'badge-success';
                                                                            $statusIcon = 'fa-check-circle';
                                                                            $status = 'approved'; // Standardize to approved
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
                                                                    <i class="fas {{ $statusIcon }} mr-1"></i>{{ __('E_late.' . $status) }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-light">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('E_late.not_requested') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!isset($requests[$record->id]))
                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                        onclick="openRequestModal({{ $record->id }}, '{{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}', '{{ $record->attendance_type == 2 ? __('E_late.late') : __('E_late.half_day') }}')">
                                                                    <i class="fas fa-paper-plane mr-1"></i>{{ __('E_late.request') }}
                                                                </button>
                                                            @else
                                                                <span class="text-muted">
                                                                    <i class="fas fa-check mr-1"></i>{{ __('E_late.requested') }}
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
                                        <h4 class="text-muted">{{ __('E_late.perfect_attendance') }}</h4>
                                        <p class="text-muted">{{ __('E_late.no_late_half_day_records') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="col-md-4">
                        <!-- Attendance Information Card -->
                        <div class="card card-info mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-info-circle mr-2"></i>{{ __('E_late.attendance_information') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                @php
                                    $totalRecords = $lateOrHalfDayAttendances->count();
                                    $requestedRecords = collect($requests)->count();
                                    $pendingRequests = collect($requests)->where('status', 'pending')->count();
                                    $approvedRequests = collect($requests)->whereIn('status', ['approved', 'accepted'])->count();
                                    $rejectedRequests = collect($requests)->where('status', 'rejected')->count();
                                @endphp

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-calendar-alt mr-2 text-info"></i>{{ __('E_late.current_month') }}
                                        </span>
                                        <span class="badge badge-info badge-pill">{{ \Carbon\Carbon::now()->format('M Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-list mr-2 text-warning"></i>{{ __('E_late.late_days') }}
                                        </span>
                                        <span class="badge badge-warning badge-pill">{{ $lateDays }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-hourglass-half mr-2 text-info"></i>{{ __('E_late.half_days') }}
                                        </span>
                                        <span class="badge badge-info badge-pill">{{ $halfDays }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-times-circle mr-2 text-danger"></i>{{ __('E_late.absent_days') }}
                                        </span>
                                        <span class="badge badge-danger badge-pill">{{ $absentDays }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Request Information Card -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-paper-plane mr-2"></i>{{ __('E_late.request_information') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-hourglass-half mr-2 text-warning"></i>{{ __('E_late.pending') }}
                                        </span>
                                        <span class="badge badge-warning badge-pill">{{ $pendingRequests }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-check-circle mr-2 text-success"></i>{{ __('E_late.approved') }}
                                        </span>
                                        <span class="badge badge-success badge-pill">{{ $approvedRequests }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-times-circle mr-2 text-danger"></i>{{ __('E_late.rejected') }}
                                        </span>
                                        <span class="badge badge-danger badge-pill">{{ $rejectedRequests }}</span>
                                    </li>
                                </ul>

                                <hr>

                                <h6><i class="fas fa-clock mr-2"></i>{{ __('E_late.processing_time') }}</h6>
                                <p class="text-sm text-muted">
                                    • {{ __('E_late.normal_requests') }}<br>
                                    • {{ __('E_late.urgent_requests') }}<br>
                                </p>

                                <h6><i class="fas fa-exclamation-triangle mr-2"></i>{{ __('E_late.important_notes') }}</h6>
                                <p class="text-sm text-muted">
                                    • {{ __('E_late.submit_asap') }}<br>
                                    • {{ __('E_late.provide_justification') }}<br>
                                    • {{ __('E_late.contact_hr') }}
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
                        <i class="fas fa-paper-plane mr-2"></i>{{ __('E_late.submit_late_half_day_request') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('E_late.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('employee.late.request') }}" method="POST" id="requestForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="attendance_id" id="modal_attendance_id">

                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt mr-1"></i>{{ __('E_late.date_label') }}</label>
                            <p class="form-control-plaintext font-weight-bold" id="modal_date"></p>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-tag mr-1"></i>{{ __('E_late.type_label') }}</label>
                            <p class="form-control-plaintext" id="modal_type"></p>
                        </div>

                        <div class="form-group">
                            <label for="reason">
                                <i class="fas fa-comment mr-1"></i>{{ __('E_late.reason') }} <span class="text-danger">*</span>
                            </label>
                            <textarea name="reason" id="reason" rows="4" class="form-control"
                                placeholder="{{ __('E_late.reason_placeholder') }}" required></textarea>
                            <small class="text-muted">{{ __('E_late.minimum_characters') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>{{ __('E_late.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i>{{ __('E_late.submit_request') }}
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

            // Determine type display based on current locale
            let typeDisplay = '';
            let badgeClass = '';
            let iconClass = '';

            if (type === '{{ __('E_late.late') }}' || type === 'Late') {
                typeDisplay = '{{ __('E_late.late') }}';
                badgeClass = 'warning';
                iconClass = 'clock';
            } else {
                typeDisplay = '{{ __('E_late.half_day') }}';
                badgeClass = 'info';
                iconClass = 'hourglass-half';
            }

            document.getElementById('modal_type').innerHTML = '<span class="badge badge-' + badgeClass + '">' +
                '<i class="fas fa-' + iconClass + ' mr-1"></i>' + typeDisplay + '</span>';
            document.getElementById('reason').value = '';
            $('#requestModal').modal('show');
        }

        // Form validation
        document.getElementById('requestForm').addEventListener('submit', function(e) {
            const reason = document.getElementById('reason').value.trim();
            if (reason.length < 10) {
                e.preventDefault();
                alert('{{ __('E_late.reason_min_length') }}');
                return false;
            }
        });
    </script>

@endsection
