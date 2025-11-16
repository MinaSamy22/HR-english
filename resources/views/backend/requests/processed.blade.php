@extends('backend.layouts.app')

@section('content')
    <div class="content-wrapper dashboard"
        style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>{{ __('h_requests.processed_requests_title') }}</h3>
            <a href="{{ route('Requests') }}" class="btn btn-outline-primary">
                <i class="fas fa-clock"></i> {{ __('h_requests.view_pending_requests') }}
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-filter"></i> {{ __('h_requests.filter_processed_requests') }}
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('Requests.processed') }}" class="row align-items-end" id="filter-form">
                    <div class="col-md-4">
                        <label for="month" class="form-label">{{ __('h_requests.filter_by_month') }}</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">{{ __('h_requests.all_months') }}</option>
                            @foreach($months as $monthNum => $monthName)
                                <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                                    {{ __('h_requests.' . strtolower($monthName)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search_name" class="form-label">{{ __('h_requests.search_by_employee_name') }}</label>
                        <input type="text" name="search_name" id="search_name" class="form-control"
                               placeholder="{{ __('h_requests.enter_employee_name') }}" value="{{ $searchName }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> {{ __('h_requests.apply_filters') }}
                        </button>
                        <a href="{{ route('Requests.processed') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-undo"></i> {{ __('h_requests.clear') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Processed Vacation Requests --}}
        @if($processedVacations->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <i class="fas fa-calendar-check"></i> {{ __('h_requests.processed_vacation_requests') }}
                <span class="badge badge-secondary ml-2">{{ $processedVacations->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedVacations as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                            <small class="text-muted">{{ __('h_requests.type') }}:</small> {{ $request->vacation_type }}<br>
                            <small class="text-muted">{{ __('h_requests.from') }}:</small> {{ $request->start_date }} <small class="text-muted">{{ __('h_requests.to') }}</small> {{ $request->end_date }}<br>
                            <small class="text-muted">{{ __('h_requests.days') }}:</small> {{ $request->total_days }}<br>
                            <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">{{ __('h_requests.updated') }}:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ __('h_requests.' . $request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'vacation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('h_requests.confirm_reject_vacation') }}')">
                                        <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'vacation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('{{ __('h_requests.confirm_accept_vacation') }}')">
                                        <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
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
                <i class="fas fa-clock"></i> {{ __('h_requests.processed_extra_time_requests') }}
                <span class="badge badge-secondary ml-2">{{ $processedExtraTimes->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedExtraTimes as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                            <small class="text-muted">{{ __('h_requests.date') }}:</small> {{ $request->date }}<br>
                            <small class="text-muted">{{ __('h_requests.hours') }}:</small> {{ $request->hours }}<br>
                            <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">{{ __('h_requests.updated') }}:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ __('h_requests.' . $request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'extra_time', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('h_requests.confirm_reject_extra_time') }}')">
                                        <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'extra_time', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('{{ __('h_requests.confirm_accept_extra_time') }}')">
                                        <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
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
                <i class="fas fa-user-clock"></i> {{ __('h_requests.processed_late_removal_requests') }}
                <span class="badge badge-secondary ml-2">{{ $processedLateRemovals->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedLateRemovals as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                            <small class="text-muted">{{ __('h_requests.date') }}:</small> {{ $request->created_at->format('Y-m-d') }}<br>
                            <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">{{ __('h_requests.updated') }}:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ __('h_requests.' . $request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'late_removal', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('h_requests.confirm_reject_late_removal') }}')">
                                        <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'late_removal', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('{{ __('h_requests.confirm_accept_late_removal') }}')">
                                        <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Processed Early Leave Requests --}}
        @if($processedEarlyLeaves->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-light">
                <i class="fas fa-door-open"></i> {{ __('h_requests.processed_early_leave_requests') }}
                <span class="badge badge-secondary ml-2">{{ $processedEarlyLeaves->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedEarlyLeaves as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                            <small class="text-muted">{{ __('h_requests.date') }}:</small>  {{ \Carbon\Carbon::parse($request->request_date)->format('Y-m-d') }}<br>
                            <small class="text-muted">{{ __('h_requests.leave_time') }}:</small> {{ $request->requested_leave_time }}<br>
                            @if($request->urgent_request)
                                <span class="badge badge-warning">{{ __('h_requests.urgent') }}</span><br>
                            @endif
                            <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">{{ __('h_requests.updated') }}:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ __('h_requests.' . $request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'early_leave', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('h_requests.confirm_reject_early_leave') }}')">
                                        <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'early_leave', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('{{ __('h_requests.confirm_accept_early_leave') }}')">
                                        <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
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
                <i class="fas fa-sign-out-alt"></i> {{ __('h_requests.processed_resignation_requests') }}
                <span class="badge badge-secondary ml-2">{{ $processedResignations->count() }}</span>
            </div>
            <div class="card-body">
                @foreach($processedResignations as $request)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                            <small class="text-muted">{{ __('h_requests.submitted_at') }}:</small> {{ $request->created_at->format('Y-m-d') }}<br>
                            <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}<br>
                            <small class="text-muted">{{ __('h_requests.updated') }}:</small> {{ $request->updated_at->format('Y-m-d H:i') }}
                        </div>
                        <div class="text-right">
                            <span class="badge badge-{{ $request->status == 'accepted' ? 'success' : 'danger' }} mb-2">
                                <i class="fas fa-{{ $request->status == 'accepted' ? 'check' : 'times' }}"></i>
                                {{ __('h_requests.' . $request->status) }}
                            </span><br>
                            @if($request->status == 'accepted')
                                <form method="POST" action="{{ route('Requests.reject', ['type' => 'resignation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('h_requests.confirm_reject_resignation') }}')">
                                        <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('Requests.accept', ['type' => 'resignation', 'id' => $request->id]) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('{{ __('h_requests.confirm_accept_resignation') }}')">
                                        <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
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
        @if($processedVacations->count() == 0 && $processedExtraTimes->count() == 0 && $processedLateRemovals->count() == 0 && $processedEarlyLeaves->count() == 0 && $processedResignations->count() == 0)
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-center py-5">
                    @if($selectedMonth || $searchName)
                        <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">{{ __('h_requests.no_matching_requests') }}</h4>
                        <p class="text-muted">{{ __('h_requests.no_matching_requests_message') }}</p>
                        <a href="{{ route('Requests.processed') }}" class="btn btn-outline-primary">
                            <i class="fas fa-undo"></i> {{ __('h_requests.clear_filters') }}
                        </a>
                    @else
                        <i class="fas fa-archive text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">{{ __('h_requests.no_processed_requests') }}</h4>
                        <p class="text-muted">{{ __('h_requests.no_processed_requests_message') }}</p>
                        <a href="{{ route('Requests') }}" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-clock"></i> {{ __('h_requests.view_pending_requests') }}
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
