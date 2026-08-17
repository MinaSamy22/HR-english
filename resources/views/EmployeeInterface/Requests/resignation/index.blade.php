@extends('EmployeeInterface.layouts.app')

@section('content')
    <div class="content-wrapper" style="background-color: white;">
        <div class="container mt-4">
            <!-- Resignation Form Section -->
            @if (!$hasApprovedResignation)
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h3 class="mb-3">{{ __('E_resignation.submit_resignation') }}</h3>

                        <form method="POST" action="{{ route('employee.resignation.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="resignation_date">{{ __('E_resignation.resignation_date') }}</label>
                                <input type="date" id="resignation_date" name="resignation_date" class="form-control" required>
                                @error('resignation_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="type">{{ __('E_resignation.resignation') }}</label>
                                <select name="type" class="form-control" required>
                                    <option value="">{{ __('E_resignation.placeholder') }}</option>
                                    <option value="{{ __('E_resignation.end_contract') }}">
                                        {{ __('E_resignation.end_contract') }}</option>
                                    <option value="{{ __('E_resignation.terminate_contract') }}">
                                        {{ __('E_resignation.terminate_contract') }}</option>
                                    <option value="{{ __('E_resignation.normal_resignation') }}">
                                        {{ __('E_resignation.normal_resignation') }}</option>
                                    <option value="{{ __('E_resignation.sponsorship_transfer') }}">
                                        {{ __('E_resignation.sponsorship_transfer') }}</option>
                                    <option value="{{ __('E_resignation.final_exit') }}">
                                        {{ __('E_resignation.final_exit') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="reason">{{ __('E_resignation.reason') }}</label>
                                <textarea name="reason" class="form-control" rows="4"
                                    placeholder="{{ __('E_resignation.reason_placeholder') }}"></textarea>
                            </div>

                            <button type="submit" class="btn btn-danger">
                    <i class="fas fa-paper-plane"></i> {{ __('E_resignation.submit_resignation_btn') }}
                </button>
                        </form>
                    </div>
                </div>

                <hr class="my-5">
            @endif

            <!-- Resignation Requests List Section -->
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-3">{{ __('E_resignation.my_resignation_requests') }}</h3>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('E_resignation.date') }}</th>
                                    <th>{{ __('E_resignation.resignation') }}</th>
                                    <th>{{ __('E_resignation.reason_table') }}</th>
                                    <th>{{ __('E_resignation.status') }}</th>
                                    <th>{{ __('E_resignation.submitted_at') }}</th>
                                    <th>{{ __('E_resignation.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resignations as $resignation)
                                    <tr>
                                        <td>{{ $resignation->resignation_date }}</td>
                                        <td>{{ $resignation->type }}</td>
                                        <td>{{ $resignation->reason ?? __('E_resignation.no_reason') }}</td>
                                        <td>
                                            @php
                                                $status = trim(strtolower($resignation->status));
                                            @endphp

                                            @if ($status === 'pending')
                                                <span class="badge badge-warning">{{ __('E_resignation.pending') }}</span>
                                            @elseif($status === 'approved')
                                                <span class="badge badge-success">{{ __('E_resignation.approved') }}</span>
                                            @elseif($status === 'rejected')
                                                <span class="badge badge-danger">{{ __('E_resignation.rejected') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($resignation->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $resignation->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if ($resignation->status === 'pending')
                                                <form method="POST"
                                                    action="{{ route('employee.resignation.destroy', $resignation->id) }}"
                                                    onsubmit="return confirm('{{ __('E_resignation.confirm_delete') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">{{ __('E_resignation.no_action') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('E_resignation.no_requests_submitted') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-block d-md-none">
                        @forelse($resignations as $resignation)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>{{ __('E_resignation.date') }}:</strong>
                                        <span>{{ $resignation->resignation_date }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ __('E_resignation.resignation') }}:</strong>
                                        <span>{{ $resignation->type }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ __('E_resignation.reason_table') }}:</strong>
                                        <span>{{ $resignation->reason ?? __('E_resignation.no_reason') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ __('E_resignation.status') }}:</strong>
                                        @php
                                            $status = trim(strtolower($resignation->status));
                                        @endphp

                                        @if ($status === 'pending')
                                            <span class="badge badge-warning">{{ __('E_resignation.pending') }}</span>
                                        @elseif($status === 'approved')
                                            <span class="badge badge-success">{{ __('E_resignation.approved') }}</span>
                                        @elseif($status === 'rejected')
                                            <span class="badge badge-danger">{{ __('E_resignation.rejected') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($resignation->status) }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('E_resignation.submitted_at') }}:</strong>
                                        <span>{{ $resignation->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    <div>
                                        @if ($resignation->status === 'pending')
                                            <form method="POST"
                                                action="{{ route('employee.resignation.destroy', $resignation->id) }}"
                                                onsubmit="return confirm('{{ __('E_resignation.confirm_delete') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-block">
                                                    <i class="fas fa-trash"></i> {{ __('E_resignation.actions') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">{{ __('E_resignation.no_action') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center">
                                {{ __('E_resignation.no_requests_submitted') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today for resignation date input
        const resignationDateInput = document.getElementById('resignation_date');
        if (resignationDateInput) {
            // Get today's date in YYYY-MM-DD format
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const minDate = `${year}-${month}-${day}`;
            
            // Set the min attribute to prevent selection of past dates
            resignationDateInput.setAttribute('min', minDate);
        }
    });
</script>
@endsection
