@extends('EmployeeInterface.layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('E_early.early_leave_requests') }}</h1>
                </div>
                <div>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home">{{ __('Calender.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('E_early.early_leave_requests') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <!-- Submit Form -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-running mr-2"></i>{{ __('E_early.submit_new_request') }}
                            </h3>
                        </div>

                        <form action="{{ route('employee.early.store') }}" method="POST">
                            @csrf
                            <div class="card-body">

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <i class="icon fas fa-check"></i> {{ session('success') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <i class="icon fas fa-ban"></i>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <!-- Request Date -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-calendar mr-1"></i>{{ __('E_early.date') }} <span class="text-danger">*</span></label>
                                            <input type="date" name="request_date" class="form-control @error('request_date') is-invalid @enderror"
                                                value="{{ old('request_date') }}" min="{{ date('Y-m-d') }}" required>
                                            @error('request_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Requested Leave Time -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-clock mr-1"></i>{{ __('E_early.leave_time') }} <span class="text-danger">*</span></label>
                                            <input type="time" name="requested_leave_time" class="form-control @error('requested_leave_time') is-invalid @enderror"
                                                value="{{ old('requested_leave_time') }}" required>
                                            @error('requested_leave_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div class="form-group">
                                    <label><i class="fas fa-comment mr-1"></i>{{ __('E_early.reason_optional') }}</label>
                                    <textarea name="reason" rows="4" class="form-control @error('reason') is-invalid @enderror"
                                        placeholder="{{ __('E_early.reason_placeholder') }}">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Urgent Checkbox -->
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="urgent_request" value="1">
                                        {{ __('E_early.urgent_request') }}
                                    </label>
                                </div>

                                <!-- Summary -->
                                <div class="alert alert-info" id="leave_summary" style="display:none;">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>{{ __('E_early.leave_time_selected') }}:
                                        <span id="selected_time">-</span>
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ __('E_early.date_label') }}:
                                        <span id="selected_date">-</span>
                                    </small>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i>{{ __('E_early.submit_request') }}</button>
                                <button type="reset" class="btn btn-secondary ml-2"><i class="fas fa-undo mr-1"></i>{{ __('E_early.reset_form') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>{{ __('E_early.summary') }}</h3>
                        </div>
                        <div class="card-body">

                            @php
                                $pending = $requests->where('status', 'pending')->count();
                                $approved = $requests->where('status', 'approved')->count();
                                $rejected = $requests->where('status', 'rejected')->count();
                            @endphp

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="fas fa-check mr-2 text-success"></i>{{ __('E_early.approved') }}</span>
                                    <span class="badge badge-success badge-pill">{{ $approved }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="fas fa-hourglass-half mr-2 text-warning"></i>{{ __('E_early.pending') }}</span>
                                    <span class="badge badge-warning badge-pill">{{ $pending }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="fas fa-times-circle mr-2 text-danger"></i>{{ __('E_early.rejected') }}</span>
                                    <span class="badge badge-danger badge-pill">{{ $rejected }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><i class="fas fa-list mr-2 text-primary"></i>{{ __('E_early.total') }}</span>
                                    <span class="badge badge-primary badge-pill">{{ $requests->count() }}</span>
                                </li>
                            </ul>

                            <hr>

                            <h6><i class="fas fa-exclamation-triangle mr-2"></i>{{ __('E_early.notes') }}</h6>
                            <p class="text-sm text-muted">
                                • {{ __('E_early.note1') }}<br>
                                • {{ __('E_early.note2') }}<br>
                                • {{ __('E_early.note3') }}<br>
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            <!-- History Table -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-history mr-2"></i>{{ __('E_early.history') }}</h3>
                        </div>
                        <div class="card-body">

                            @if ($requests->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('E_early.date') }}</th>
                                                <th>{{ __('E_early.leave_time') }}</th>
                                                <th>{{ __('E_early.reason') }}</th>
                                                <th>{{ __('E_early.status') }}</th>
                                                <th>{{ __('E_early.submitted_at') }}</th>
                                                <th>{{ __('E_early.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requests as $index => $request)
                                            <tr>
                                                <td>{{ $requests->firstItem() + $index }}</td>
                                                <td>{{ date('d M Y', strtotime($request->request_date)) }}</td>
                                                <td>{{ $request->requested_leave_time }}</td>
                                                <td>{{ $request->reason ?? __('E_early.no_reason') }}</td>
                                                <td>
                                                    @if($request->status == 'pending')
                                                        <span class="badge badge-warning"><i class="fas fa-hourglass"></i>{{ __('E_early.pending') }}</span>
                                                    @elseif($request->status == 'approved')
                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i>{{ __('E_early.approved') }}</span>
                                                    @else
                                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i>{{ __('E_early.rejected') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ date('d M Y h:i A', strtotime($request->created_at)) }}</td>
                                                <td>
                                                    @if($request->status == 'pending')
                                                        <form action="{{ route('employee.early.destroy', $request->id) }}" method="POST"
                                                            onsubmit="return confirm('{{ __('E_early.confirm_cancel') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
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

                                <div class="d-flex justify-content-center mt-3">
                                    {{ $requests->links() }}
                                </div>

                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">{{ __('E_early.no_requests') }}</h4>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div><!-- container -->
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function(){
    $('input[name="requested_leave_time"], input[name="request_date"]').on('change', function(){
        let date = $('input[name="request_date"]').val();
        let time = $('input[name="requested_leave_time"]').val();
        if(date && time){
            $('#selected_date').text(date);
            $('#selected_time').text(time);
            $('#leave_summary').show();
        }else{
            $('#leave_summary').hide();
        }
    });

    $('button[type="reset"]').click(function(){
        $('#leave_summary').hide();
    });
});
</script>
@endpush

@endsection
