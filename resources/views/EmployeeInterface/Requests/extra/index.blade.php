@extends('EmployeeInterface.layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Extra Time Requests</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('employee.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Extra Time Requests</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- New Extra Time Request Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-clock mr-2"></i>Submit New Extra Time Request
                                </h3>
                            </div>
                            <form action="{{ route('employee.extra.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                            <i class="icon fas fa-check"></i>{{ session('success') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                            <i class="icon fas fa-ban"></i>
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date">
                                                    <i class="fas fa-calendar-alt mr-1"></i>Date <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="date" name="date" id="date"
                                                    class="form-control @error('date') is-invalid @enderror"
                                                    value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                                                @error('date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hours">
                                                    <i class="fas fa-hourglass-half mr-1"></i>Hours <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" step="0.5" min="0.5" max="12" name="hours" id="hours"
                                                    class="form-control @error('hours') is-invalid @enderror"
                                                    value="{{ old('hours') }}" placeholder="e.g., 2.5" required>
                                                <small class="text-muted">Enter hours in decimal format (e.g., 1.5 for 1 hour 30 minutes)</small>
                                                @error('hours')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="reason">
                                            <i class="fas fa-comment mr-1"></i>Reason <span class="text-muted">(Optional)</span>
                                        </label>
                                        <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror"
                                            placeholder="Please provide a brief description of why extra time is needed...">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <!-- Hours Summary Display -->
                                    <div class="alert alert-info" id="hours_summary" style="display: none;">
                                        <i class="fas fa-calculator mr-2"></i>
                                        <strong>Extra Hours Requested: <span id="total_hours">0</span> hours</strong>
                                        <br>
                                        <small class="text-muted">Date: <span id="selected_date">-</span></small>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i>Submit Request
                                    </button>
                                    <button type="reset" class="btn btn-secondary ml-2">
                                        <i class="fas fa-undo mr-1"></i>Reset Form
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar mr-2"></i>My Extra Time Summary
                                </h3>
                            </div>
                            <div class="card-body">
                                @php
                                    $pending = $requests->where('status', 'pending')->count();
                                    $approved = $requests->where('status', 'approved')->count();
                                    $rejected = $requests->where('status', 'rejected')->count();
                                    $totalHours = $requests->where('status', 'approved')->sum('hours');
                                    $pendingHours = $requests->where('status', 'pending')->sum('hours');
                                @endphp

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-check-circle mr-2 text-success"></i>Approved Hours
                                        </span>
                                        <span class="badge badge-success badge-pill">{{ number_format($totalHours, 1) }} hrs</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-hourglass-half mr-2 text-warning"></i>Pending Hours
                                        </span>
                                        <span class="badge badge-warning badge-pill">{{ number_format($pendingHours, 1) }} hrs</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-list mr-2 text-info"></i>Total Requests
                                        </span>
                                        <span class="badge badge-info badge-pill">{{ $requests->count() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-times-circle mr-2 text-danger"></i>Rejected
                                        </span>
                                        <span class="badge badge-danger badge-pill">{{ $rejected }}</span>
                                    </li>
                                </ul>

                                <hr>

                                <h6><i class="fas fa-clock mr-2"></i>Processing Time</h6>
                                <p class="text-sm text-muted">
                                    • Normal requests: 1-2 business days<br>
                                    • Urgent requests: Same day<br>
                                </p>

                                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Important Notes</h6>
                                <p class="text-sm text-muted">
                                    • Submit requests in advance when possible<br>
                                    • Provide clear justification for approval<br>
                                    • Check with supervisor before submitting
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Time Requests History -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history mr-2"></i>My Extra Time Requests
                                </h3>
                            </div>
                            <div class="card-body">
                                @if (isset($requests) && $requests->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Hours</th>
                                                    <th>Reason</th>
                                                    <th>Status</th>
                                                    <th>Submitted</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($requests as $index => $request)
                                                    <tr>
                                                        <td>{{ $requests->firstItem() + $index }}</td>
                                                        <td>
                                                            <strong>{{ date('d M Y', strtotime($request->date)) }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ date('l', strtotime($request->date)) }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light badge-pill">{{ number_format($request->hours, 1) }} hrs</span>
                                                        </td>
                                                        <td>
                                                            @if($request->reason)
                                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $request->reason }}">
                                                                    {{ $request->reason }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted font-italic">No reason provided</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusClass = '';
                                                                $statusIcon = '';
                                                                switch ($request->status) {
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
                                                                <i class="fas {{ $statusIcon }} mr-1"></i>{{ ucfirst($request->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <strong>{{ date('d M Y', strtotime($request->created_at)) }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ date('h:i A', strtotime($request->created_at)) }}</small>
                                                        </td>
                                                        <td>
                                                            @if ($request->status == 'pending')
                                                                <form action="{{ route('employee.extra.destroy', $request->id) }}" method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $requests->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No extra time requests found</h4>
                                        <p class="text-muted">Submit your first extra time request using the form above</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Update hours summary when form values change
                $('#date, #hours').on('input change', function() {
                    updateHoursSummary();
                });

                function updateHoursSummary() {
                    var date = $('#date').val();
                    var hours = $('#hours').val();

                    if (date && hours) {
                        var formattedDate = new Date(date).toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        $('#selected_date').text(formattedDate);
                        $('#total_hours').text(parseFloat(hours).toFixed(1));
                        $('#hours_summary').show();
                    } else {
                        $('#hours_summary').hide();
                    }
                }

                // Validate hours input
                $('#hours').on('input', function() {
                    var hours = parseFloat($(this).val());
                    if (hours > 12) {
                        $(this).val(12);
                    } else if (hours < 0.5 && $(this).val() !== '') {
                        $(this).val(0.5);
                    }
                });

                // Reset form handler
                $('button[type="reset"]').click(function() {
                    $('#hours_summary').hide();
                    setTimeout(function() {
                        $('#date, #hours, #reason').removeClass('is-invalid');
                    }, 100);
                });

                // Form validation
                $('form').submit(function(e) {
                    var date = $('#date').val();
                    var hours = parseFloat($('#hours').val());
                    var today = new Date().toISOString().split('T')[0];

                    if (date < today) {
                        alert('Please select a current or future date.');
                        e.preventDefault();
                        return false;
                    }

                    if (hours < 0.5 || hours > 12) {
                        alert('Hours must be between 0.5 and 12.');
                        e.preventDefault();
                        return false;
                    }
                });
            });
        </script>
    @endpush

@endsection
