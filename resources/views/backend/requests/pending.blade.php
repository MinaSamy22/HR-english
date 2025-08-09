@extends('backend.layouts.app')

@section('content')
    <div class="content-wrapper dashboard"
        style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Pending Employee Requests</h3>
            <a href="{{ route('Requests.processed') }}" class="btn btn-outline-primary">
                <i class="fas fa-archive"></i> View Processed Requests
            </a>
        </div>

        <!-- Pending Requests Badge Summary -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-calendar-alt text-info" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $pendingVacations->count() }}</h4>
                            <p class="text-muted">Vacation Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-clock text-warning" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $pendingExtraTimes->count() }}</h4>
                            <p class="text-muted">Extra Time Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-user-clock text-secondary" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $pendingLateRemovals->count() }}</h4>
                            <p class="text-muted">Late Removal Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-sign-out-alt text-danger" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $pendingResignations->count() }}</h4>
                            <p class="text-muted">Resignation Requests</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Vacation Requests --}}
        @if($pendingVacations->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <i class="fas fa-calendar-alt"></i> Vacation Requests
                <span class="badge badge-light ml-2">{{ $pendingVacations->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($pendingVacations as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Type:</small> {{ $request->vacation_type }}<br>
                            <small class="text-muted">From:</small> {{ $request->start_date }} <small class="text-muted">to</small> {{ $request->end_date }}<br>
                            <small class="text-muted">Days:</small> {{ $request->total_days }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}
                        </div>
                        <div class="text-right">
                            <form method="POST" action="{{ route('Requests.accept', ['type' => 'vacation', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to accept this vacation request?')">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            <form method="POST" action="{{ route('Requests.reject', ['type' => 'vacation', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm ml-1" onclick="return confirm('Are you sure you want to reject this vacation request?')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Pending Extra Time Requests --}}
        @if($pendingExtraTimes->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-clock"></i> Extra Time Requests
                <span class="badge badge-dark ml-2">{{ $pendingExtraTimes->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($pendingExtraTimes as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Date:</small> {{ $request->date }}<br>
                            <small class="text-muted">Hours:</small> {{ $request->hours }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}
                        </div>
                        <div class="text-right">
                            <form method="POST" action="{{ route('Requests.accept', ['type' => 'extra_time', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to accept this extra time request?')">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            <form method="POST" action="{{ route('Requests.reject', ['type' => 'extra_time', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm ml-1" onclick="return confirm('Are you sure you want to reject this extra time request?')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Pending Late Removal Requests --}}
        @if($pendingLateRemovals->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-user-clock"></i> Late Removal Requests
                <span class="badge badge-light ml-2">{{ $pendingLateRemovals->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($pendingLateRemovals as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Date:</small> {{ $request->created_at->format('Y-m-d') }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}
                        </div>
                        <div class="text-right">
                            <form method="POST" action="{{ route('Requests.accept', ['type' => 'late_removal', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to accept this late removal request?')">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            <form method="POST" action="{{ route('Requests.reject', ['type' => 'late_removal', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm ml-1" onclick="return confirm('Are you sure you want to reject this late removal request?')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Pending Resignation Requests --}}
        @if($pendingResignations->count() > 0)
        <div class="card mt-3 mb-5">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-sign-out-alt"></i> Resignation Requests
                <span class="badge badge-light ml-2">{{ $pendingResignations->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($pendingResignations as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Submitted at:</small> {{ $request->created_at->format('Y-m-d') }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}
                        </div>
                        <div class="text-right">
                            <form method="POST" action="{{ route('Requests.accept', ['type' => 'resignation', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to accept this resignation request?')">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            <form method="POST" action="{{ route('Requests.reject', ['type' => 'resignation', 'id' => $request->id]) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm ml-1" onclick="return confirm('Are you sure you want to reject this resignation request?')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- No Pending Requests Message --}}
        @if($pendingVacations->count() == 0 && $pendingExtraTimes->count() == 0 && $pendingLateRemovals->count() == 0 && $pendingResignations->count() == 0)
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">No Pending Requests</h4>
                    <p class="text-muted">There are currently no pending requests to review.</p>

                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips if using Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush
@endsection
