@extends('EmployeeInterface.layouts.app')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Vacation Requests</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('employee.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Vacation Requests</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- New Vacation Request Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-plus mr-2"></i>Submit New Vacation Request
                                </h3>
                            </div>
                            <form action="{{ route('employee.vacation.store') }}" method="POST">
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
                                                <label for="vacation_type">
                                                    <i class="fas fa-list mr-1"></i>Vacation Type <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <select name="vacation_type" id="vacation_type"
                                                    class="form-control @error('vacation_type') is-invalid @enderror"
                                                    required>
                                                    <option value="">Select Vacation Type</option>
                                                    <option value="annual"
                                                        {{ old('vacation_type') == 'annual' ? 'selected' : '' }}>Annual
                                                        Leave</option>
                                                    <option value="sick"
                                                        {{ old('vacation_type') == 'sick' ? 'selected' : '' }}>Sick Leave
                                                    </option>
                                                    <option value="emergency"
                                                        {{ old('vacation_type') == 'emergency' ? 'selected' : '' }}>
                                                        Emergency Leave</option>
                                                    <option value="personal"
                                                        {{ old('vacation_type') == 'personal' ? 'selected' : '' }}>Personal
                                                        Leave</option>
                                                    <option value="maternity"
                                                        {{ old('vacation_type') == 'maternity' ? 'selected' : '' }}>
                                                        Maternity Leave</option>
                                                    <option value="paternity"
                                                        {{ old('vacation_type') == 'paternity' ? 'selected' : '' }}>
                                                        Paternity Leave</option>
                                                </select>
                                                @error('vacation_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">
                                                    <i class="fas fa-calendar-alt mr-1"></i>Start Date <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="date" name="start_date" id="start_date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                                @error('start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">
                                                    <i class="fas fa-calendar-alt mr-1"></i>End Date <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="date" name="end_date" id="end_date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                                @error('end_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="half_day_options" style="display: none;">
                                        <label for="half_day_period">
                                            <i class="fas fa-clock mr-1"></i>Half Day Period
                                        </label>
                                        <select name="half_day_period" id="half_day_period" class="form-control">
                                            <option value="">Select Period</option>
                                            <option value="morning"
                                                {{ old('half_day_period') == 'morning' ? 'selected' : '' }}>Morning (First
                                                Half)</option>
                                            <option value="afternoon"
                                                {{ old('half_day_period') == 'afternoon' ? 'selected' : '' }}>Afternoon
                                                (Second Half)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="reason">
                                            <i class="fas fa-comment mr-1"></i>Reason <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror"
                                            placeholder="Please provide a detailed reason for your vacation request..." required>{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact">
                                            <i class="fas fa-phone mr-1"></i>Emergency Contact (Optional)
                                        </label>
                                        <input type="text" name="emergency_contact" id="emergency_contact"
                                            class="form-control @error('emergency_contact') is-invalid @enderror"
                                            value="{{ old('emergency_contact') }}"
                                            placeholder="Emergency contact number during vacation">
                                        @error('emergency_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="urgent_request"
                                                name="urgent_request" value="1"
                                                {{ old('urgent_request') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="urgent_request">
                                                <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                                                This is an urgent request
                                            </label>
                                        </div>
                                        <small class="text-muted">Check this if you need immediate approval due to
                                            emergency</small>
                                    </div>

                                    <!-- Days Calculation Display -->
                                    <div class="alert alert-info" id="days_calculation" style="display: none;">
                                        <i class="fas fa-calculator mr-2"></i>
                                        <strong>Total Days Requested: <span id="total_days">0</span></strong>
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
                                    <i class="fas fa-info-circle mr-2"></i>My Vacations
                                </h3>
                            </div>
                            <div class="card-body">
                               <ul class="list-group list-group-flush">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-check-circle mr-2 text-success"></i>Total Balance
        </span>
        <span class="badge badge-success badge-pill">{{ $totalVacationAllowed }} days</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-calendar-times mr-2 text-danger"></i>Used Days
        </span>
        <span class="badge badge-danger badge-pill">{{ $vacationsTaken }} days</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-hourglass-half mr-2 text-warning"></i>Pending Requests
        </span>
        <span class="badge badge-warning badge-pill">{{ $pendingVacations }} days</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-balance-scale mr-2 text-primary"></i>Remaining Balance
        </span>
        <span class="badge badge-primary badge-pill">{{ $vacationBalance }} days</span>
    </li>

</ul>

                                <hr>

                                <h6><i class="fas fa-clock mr-2"></i>Processing Time</h6>
                                <p class="text-sm text-muted">
                                    • Normal requests: 2-3 business days<br>
                                    • Urgent requests: 24 hours<br>
                                    • Emergency: Same day approval
                                </p>

                                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Important Notes</h6>
                                <p class="text-sm text-muted">
                                    • Submit requests at least 1 week in advance<br>
                                    • Emergency requests require documentation<br>
                                    • Maximum consecutive days: 15 days
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vacation Requests History -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history mr-2"></i>My Vacation Requests
                                </h3>

                            </div>
                            <div class="card-body">
                                @if (isset($vacationRequests) && $vacationRequests->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Type</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Days</th>
                                                    <th>Status</th>
                                                    <th>Submitted</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vacationRequests as $index => $request)
                                                    <tr>
                                                        <td>{{ $vacationRequests->firstItem() + $index }}</td>
                                                        <td>
                                                            <span class="badge badge-secondary">
                                                                {{ ucfirst(str_replace('_', ' ', $request->vacation_type)) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ date('d M Y', strtotime($request->start_date)) }}</td>
                                                        <td>{{ date('d M Y', strtotime($request->end_date)) }}</td>
                                                        <td>
                                                            <span class="badge badge-info">{{ $request->total_days }}
                                                                days</span>
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
                                                                <i
                                                                    class="fas {{ $statusIcon }} mr-1"></i>{{ ucfirst($request->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ date('d M Y', strtotime($request->created_at)) }}</td>
                                                        <td>

                                                            @if ($request->status == 'pending')
                                                                <form
                                                                    action="{{ route('vacation.cancel', $request->id) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger ml-1">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $vacationRequests->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No vacation requests found</h4>
                                        <p class="text-muted">Submit your first vacation request using the form above</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Show/hide half day options
                $('#duration_type').change(function() {
                    if ($(this).val() === 'half_day') {
                        $('#half_day_options').show();
                        $('#half_day_period').prop('required', true);
                        $('#end_date').val($('#start_date').val());
                        $('#end_date').prop('readonly', true);
                    } else {
                        $('#half_day_options').hide();
                        $('#half_day_period').prop('required', false);
                        $('#end_date').prop('readonly', false);
                    }
                    calculateDays();
                });

                // Calculate days when dates change
                $('#start_date, #end_date').change(function() {
                    calculateDays();

                    // Ensure end date is not before start date
                    if ($('#start_date').val() && $('#end_date').val()) {
                        if ($('#end_date').val() < $('#start_date').val()) {
                            $('#end_date').val($('#start_date').val());
                        }
                    }
                });

                // Calculate total days
                function calculateDays() {
                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();
                    var durationType = $('#duration_type').val();

                    if (startDate && endDate) {
                        var start = new Date(startDate);
                        var end = new Date(endDate);
                        var timeDiff = end.getTime() - start.getTime();
                        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                        if (durationType === 'half_day') {
                            daysDiff = 0.5;
                        }

                        $('#total_days').text(daysDiff);
                        $('#days_calculation').show();
                    } else {
                        $('#days_calculation').hide();
                    }
                }

                // Auto-fill end date for single day requests
                $('#start_date').change(function() {
                    if ($('#duration_type').val() === 'full_day' && !$('#end_date').val()) {
                        $('#end_date').val($(this).val());
                    }
                });
            });
        </script>
    @endpush


@endsection
