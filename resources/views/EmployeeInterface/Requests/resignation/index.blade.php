@extends('EmployeeInterface.layouts.app')

@section('content')
<div class="content-wrapper" style="background-color: white;">
    <div class="container mt-4">
        <!-- Resignation Form Section -->
       @if (!$hasApprovedResignation)
    <!-- Resignation Form Section -->
    <div class="row">
        <div class="col-md-6">
            <h3>Submit Resignation</h3>

            <form method="POST" action="{{ route('employee.resignation.store') }}">
                @csrf

                <div class="form-group">
                    <label for="resignation_date">Resignation Date</label>
                    <input type="date" name="resignation_date" class="form-control" required>
                    @error('resignation_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reason">Reason (Optional)</label>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Write your reason (optional)..."></textarea>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-paper-plane"></i> Submit Resignation
                </button>
            </form>
        </div>
    </div>

    <hr class="my-5">
@endif



        <!-- Resignation Requests List Section -->
        <div class="row">
            <div class="col-12">
                <h3>My Resignation Requests</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
    @forelse($resignations as $resignation)
        <tr>
            <td>{{ $resignation->resignation_date }}</td>
            <td>{{ $resignation->reason ?? '-' }}</td>
            <td>
                @if($resignation->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif($resignation->status === 'approved')
                    <span class="badge badge-success">Approved</span>
                @else
                    <span class="badge badge-danger">Rejected</span>
                @endif
            </td>
            <td>{{ $resignation->created_at->format('Y-m-d') }}</td>
            <td>
                @if($resignation->status === 'pending')
                    <form method="POST" action="{{ route('employee.resignation.destroy', $resignation->id) }}" onsubmit="return confirm('Are you sure you want to delete this resignation?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No resignation requests submitted yet.</td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
