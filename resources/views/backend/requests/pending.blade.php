@extends('backend.layouts.app')

@section('content')
    <div class="content-wrapper dashboard"
        style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h3>{{ __('h_requests.pending_requests_title') }}</h3>
                <a href="{{ route('Requests.processed') }}" class="btn btn-outline-primary">
                    <i class="fas fa-archive"></i> {{ __('h_requests.view_processed_requests') }}
                </a>
            </div>

            <!-- Pending Requests Badge Summary -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row text-center justify-content-between">
                        <div class="col">
                            <div class="p-3">
                                <i class="fas fa-calendar-alt text-info" style="font-size: 2rem;"></i>
                                <h4 class="mt-2">{{ $pendingVacations->count() }}</h4>
                                <p class="text-muted">{{ __('h_requests.vacation_requests') }}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <i class="fas fa-clock text-warning" style="font-size: 2rem;"></i>
                                <h4 class="mt-2">{{ $pendingExtraTimes->count() }}</h4>
                                <p class="text-muted">{{ __('h_requests.extra_time_requests') }}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <i class="fas fa-user-clock text-secondary" style="font-size: 2rem;"></i>
                                <h4 class="mt-2">{{ $pendingLateRemovals->count() }}</h4>
                                <p class="text-muted">{{ __('h_requests.late_removal_requests') }}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <i class="fas fa-door-open text-primary" style="font-size: 2rem;"></i>
                                <h4 class="mt-2">{{ $pendingEarlyLeaves->count() }}</h4>
                                <p class="text-muted">{{ __('h_requests.early_leave_requests') }}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <i class="fas fa-sign-out-alt text-danger" style="font-size: 2rem;"></i>
                                <h4 class="mt-2">{{ $pendingResignations->count() }}</h4>
                                <p class="text-muted">{{ __('h_requests.resignation_requests') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          {{-- Pending Vacation Requests --}}
@if ($pendingVacations->count() > 0)
    <div class="card mt-3">
        <div class="card-header bg-info text-white">
            <i class="fas fa-calendar-alt"></i> {{ __('h_requests.vacation_requests') }}
            <span class="badge badge-light ml-2">{{ $pendingVacations->count() }}</span>
        </div>

        <div class="card-body">
            @foreach ($pendingVacations as $request)

                @php
                    $vacationLimit = $request->user->vacation_balance; // can be null
                    $usedDays = \App\Models\Vacation::where('employee_id', $request->user->id)->sum('total');
                    $remaining = $vacationLimit !== null ? $vacationLimit - $usedDays : null;
                @endphp

                <div class="border-bottom py-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                            <small class="text-muted">{{ __('h_requests.type') }}:</small> {{ $request->vacation_type }}<br>
                            <small class="text-muted">{{ __('h_requests.from') }}:</small> {{ $request->start_date }}
                            <small class="text-muted">{{ __('h_requests.to') }}</small> {{ $request->end_date }}<br>
                            <small class="text-muted">{{ __('h_requests.days') }}:</small> {{ $request->total_days }}<br>
                            <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}
                        </div>

                        <div class="text-right">
                            <form method="POST"
                                action="{{ route('Requests.accept', ['type' => 'vacation', 'id' => $request->id]) }}"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('{{ __('h_requests.confirm_accept_vacation') }}')">
                                    <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
                                </button>
                            </form>

                            <form method="POST"
                                action="{{ route('Requests.reject', ['type' => 'vacation', 'id' => $request->id]) }}"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm ml-1"
                                    onclick="return confirm('{{ __('h_requests.confirm_reject_vacation') }}')">
                                    <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Warning line at bottom of the item --}}
                    @if ($remaining === null || $remaining <= 0)
                        <div class="alert alert-warning mt-3 mb-0 p-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('h_requests.no_vacation_balance_warning') }}
                        </div>
                    @endif

                </div>

            @endforeach
        </div>
    </div>
@endif


            {{-- Pending Extra Time Requests --}}
            @if ($pendingExtraTimes->count() > 0)
                <div class="card mt-3">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-clock"></i> {{ __('h_requests.extra_time_requests') }}
                        <span class="badge badge-dark ml-2">{{ $pendingExtraTimes->count() }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($pendingExtraTimes as $request)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div>
                                    <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                                    <small class="text-muted">{{ __('h_requests.date') }}:</small>
                                    {{ $request->date }}<br>
                                    <small class="text-muted">{{ __('h_requests.hours') }}:</small>
                                    {{ $request->hours }}<br>
                                    <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}
                                </div>
                                <div class="text-right">
                                    <form method="POST"
                                        action="{{ route('Requests.accept', ['type' => 'extra_time', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('{{ __('h_requests.confirm_accept_extra_time') }}')">
                                            <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('Requests.reject', ['type' => 'extra_time', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm ml-1"
                                            onclick="return confirm('{{ __('h_requests.confirm_reject_extra_time') }}')">
                                            <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Pending Late Removal Requests --}}
            @if ($pendingLateRemovals->count() > 0)
                <div class="card mt-3">
                    <div class="card-header bg-secondary text-white">
                        <i class="fas fa-user-clock"></i> {{ __('h_requests.late_removal_requests') }}
                        <span class="badge badge-light ml-2">{{ $pendingLateRemovals->count() }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($pendingLateRemovals as $request)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div>
                                    <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                                    <small class="text-muted">{{ __('h_requests.date') }}:</small>
                                    {{ $request->created_at->format('Y-m-d') }}<br>
                                    <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}
                                </div>
                                <div class="text-right">
                                    <form method="POST"
                                        action="{{ route('Requests.accept', ['type' => 'late_removal', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('{{ __('h_requests.confirm_accept_late_removal') }}')">
                                            <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('Requests.reject', ['type' => 'late_removal', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm ml-1"
                                            onclick="return confirm('{{ __('h_requests.confirm_reject_late_removal') }}')">
                                            <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Pending Early Leave Requests --}}
            @if ($pendingEarlyLeaves->count() > 0)
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-door-open"></i> {{ __('h_requests.early_leave_requests') }}
                        <span class="badge badge-light ml-2">{{ $pendingEarlyLeaves->count() }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($pendingEarlyLeaves as $request)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div>
                                    <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong>
                                    @if ($request->urgent_request)
                                        <span class="badge badge-danger ml-2">{{ __('h_requests.urgent') }}</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ __('h_requests.date') }}:</small>
                                    {{ \Carbon\Carbon::parse($request->request_date)->format('Y-m-d') }}<br>
                                    <small class="text-muted">{{ __('h_requests.requested_leave_time') }}:</small>
                                    {{ $request->requested_leave_time }}<br>
                                    <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}
                                </div>
                                <div class="text-right">
                                    <form method="POST"
                                        action="{{ route('Requests.accept', ['type' => 'early_leave', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('{{ __('h_requests.confirm_accept_early_leave') }}')">
                                            <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('Requests.reject', ['type' => 'early_leave', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm ml-1"
                                            onclick="return confirm('{{ __('h_requests.confirm_reject_early_leave') }}')">
                                            <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Pending Resignation Requests --}}
            @if ($pendingResignations->count() > 0)
                <div class="card mt-3 mb-5">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-sign-out-alt"></i> {{ __('h_requests.resignation_requests') }}
                        <span class="badge badge-light ml-2">{{ $pendingResignations->count() }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($pendingResignations as $request)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div>
                                    <strong>{{ $request->user->name ?? __('h_requests.unknown') }}</strong><br>
                                    <small class="text-muted">{{ __('h_requests.submitted_at') }}:</small>
                                    {{ $request->created_at->format('Y-m-d') }}<br>

                                    <strong>{{ __('E_resignation.resignation') }}:</strong> {{ $request->type }}<br>

                                    <strong>{{ __('h_requests.reason') }}:</strong> {{ $request->reason }}
                                </div>
                                <div class="text-right">
                                    <form method="POST"
                                        action="{{ route('Requests.accept', ['type' => 'resignation', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('{{ __('h_requests.confirm_accept_resignation') }}')">
                                            <i class="fas fa-check"></i> {{ __('h_requests.accept') }}
                                        </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('Requests.reject', ['type' => 'resignation', 'id' => $request->id]) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm ml-1"
                                            onclick="return confirm('{{ __('h_requests.confirm_reject_resignation') }}')">
                                            <i class="fas fa-times"></i> {{ __('h_requests.reject') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- No Pending Requests Message --}}
            @if (
                $pendingVacations->count() == 0 &&
                    $pendingExtraTimes->count() == 0 &&
                    $pendingLateRemovals->count() == 0 &&
                    $pendingEarlyLeaves->count() == 0 &&
                    $pendingResignations->count() == 0)
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">{{ __('h_requests.no_pending_requests') }}</h4>
                            <p class="text-muted">{{ __('h_requests.no_pending_requests_message') }}</p>
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
