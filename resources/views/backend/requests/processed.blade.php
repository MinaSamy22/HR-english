@extends('backend.layouts.app')

@section('content')
    <div class="content-wrapper dashboard"
        style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Processed Employee Requests</h3>
            <a href="{{ route('Requests') }}" class="btn btn-outline-primary">
                <i class="fas fa-clock"></i> View Pending Requests
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-filter"></i> Filter Processed Requests
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('Requests.processed') }}" class="row align-items-end" id="filter-form">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Filter by Month:</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">All Months</option>
                            @foreach($months as $monthNum => $monthName)
                                <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search_name" class="form-label">Search by Employee Name:</label>
                        <input type="text" name="search_name" id="search_name" class="form-control"
                               placeholder="Enter employee name..." value="{{ $searchName }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('Requests.processed') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-undo"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Processed Vacation Requests --}}
        @if($processedVacations->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <i class="fas fa-calendar-check"></i> Processed Vacation Requests
                <span class="badge badge-secondary ml-2">{{ $processedVacations->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedVacations as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Type:</small> {{ $request->vacation_type }}<br>
                            <small class="text-muted">From:</small> {{ $request->start_date }} <small class="text-muted">to</small> {{ $request->end_date }}<br>
                            <small class="text-muted">Days:</small> {{ $request->total_days }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">Updated:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ ucfirst($request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'vacation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to reject this vacation request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'vacation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Are you sure you want to accept this vacation request?')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Processed Extra Time Requests --}}
        @if($processedExtraTimes->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <i class="fas fa-clock"></i> Processed Extra Time Requests
                <span class="badge badge-secondary ml-2">{{ $processedExtraTimes->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedExtraTimes as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Date:</small> {{ $request->date }}<br>
                            <small class="text-muted">Hours:</small> {{ $request->hours }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">Updated:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ ucfirst($request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'extra_time', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to reject this extra time request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'extra_time', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Are you sure you want to accept this extra time request?')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Processed Late Removal Requests --}}
        @if($processedLateRemovals->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <i class="fas fa-user-clock"></i> Processed Late Removal Requests
                <span class="badge badge-secondary ml-2">{{ $processedLateRemovals->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedLateRemovals as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Date:</small> {{ $request->created_at->format('Y-m-d') }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">Updated:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ ucfirst($request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'late_removal', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to reject this late removal request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'late_removal', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Are you sure you want to accept this late removal request?')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Processed Resignation Requests --}}
        @if($processedResignations->count() > 0)
        <div class="card mt-3 mb-5">
            <div class="card-header bg-light">
                <i class="fas fa-sign-out-alt"></i> Processed Resignation Requests
                <span class="badge badge-secondary ml-2">{{ $processedResignations->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedResignations as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">Submitted at:</small> {{ $request->created_at->format('Y-m-d') }}<br>
                            <strong>Reason:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">Updated:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ ucfirst($request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'resignation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to reject this resignation request?')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'resignation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Are you sure you want to accept this resignation request?')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- No Processed Requests Message --}}
        @if($processedVacations->count() == 0 && $processedExtraTimes->count() == 0 && $processedLateRemovals->count() == 0 && $processedResignations->count() == 0)
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-center py-5">
                    @if($selectedMonth || $searchName)
                        <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No Matching Requests Found</h4>
                        <p class="text-muted">No processed requests match your current filters.</p>
                        <a href="{{ route('Requests.processed') }}" class="btn btn-outline-primary">
                            <i class="fas fa-undo"></i> Clear Filters
                        </a>
                    @else
                        <i class="fas fa-archive text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No Processed Requests</h4>
                        <p class="text-muted">There are currently no processed requests to display.</p>
                        <a href="{{ route('Requests') }}" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-clock"></i> View Pending Requests
                        </a>
                    @endif
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

    // Auto-submit form on month change for better UX
    $('#month').change(function() {
        if ($(this).val()) {
            $(this).closest('form').submit();
        }
    });

    // Real-time search (optional - you can remove this if you prefer the button approach)
    let searchTimeout;
    $('#search_name').on('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();

        searchTimeout = setTimeout(function() {
            if (searchValue.length >= 3 || searchValue.length === 0) {
                $('#search_name').closest('form').submit();
            }
        }, 1000);
    });
});
</script>
@endpush
@endsection
