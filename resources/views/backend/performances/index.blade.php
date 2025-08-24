{{-- resources/views/admin/performance/index.blade.php --}}
@extends('backend.layouts.app')

@section('title', 'Employee Performance')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1>Employee Performance</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Performance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                    <h3 class="card-title">Performance Evaluations</h3>
                    <div class="card-tools">
                        <a href="{{ route('performance-criteria.index') }}" class="btn btn-info btn-sm mr-2">
                            <i class="fas fa-cog"></i> Manage Criteria
                        </a>
                        <a href="{{ route('performance.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Employee Evaluation
                        </a>
                    </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('performance.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="employee_name">Search by Employee</label>
                                            <input type="text" name="employee_name" id="employee_name"
                                                   class="form-control" placeholder="Enter employee name..."
                                                   value="{{ request('employee_name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="year">Year</label>
                                            <select name="year" id="year" class="form-control">
                                                <option value="">All Years</option>
                                                @for($year = date('Y'); $year >= 2020; $year--)
                                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">All Status</option>
                                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;" >
                                                    <i class="fas fa-search"></i> Search
                                                </button>
                                                <a href="{{ route('performance.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Results Info -->
                    @if(request()->anyFilled(['employee_name', 'month', 'year', 'status']))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Showing filtered results
                            @if(request('employee_name'))
                                for employee: <strong>{{ request('employee_name') }}</strong>
                            @endif
                            @if(request('month'))
                                for period: <strong>{{ request('month') }}</strong>
                            @endif
                            @if(request('year'))
                                in year: <strong>{{ request('year') }}</strong>
                            @endif
                            @if(request('status'))
                                with status: <strong>{{ ucfirst(request('status')) }}</strong>
                            @endif
                            ({{ $evaluations->total() }} result{{ $evaluations->total() != 1 ? 's' : '' }})
                        </div>
                    @endif

                    @if($evaluations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Evaluated period</th>
                                        <th>Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluations as $evaluation)
                                        <tr>
                                            <td>
                                                <strong>{{ $evaluation->employee->name }}</strong><br>
                                                <small class="text-muted">{{ $evaluation->employee->email }}</small>
                                            </td>

                                            <td>
                                                <span class="badge badge-{{ $evaluation->getPerformanceRatingClass() }}">
                                                    {{ $evaluation->getPerformanceRating() }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge badge-{{ $evaluation->status == 'completed' ? 'success' : ($evaluation->status == 'reviewed' ? 'info' : 'warning') }}">
                                                    {{ ucfirst($evaluation->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $evaluation->evaluation_period }}</td>

                                            <td>{{ $evaluation->evaluation_year }}</td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('performance.show', $evaluation->id) }}"
                                                       class="btn btn-info rounded-pill" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('performance.edit', $evaluation->id) }}"
                                                       class="btn btn-warning rounded-pill" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('performance.destroy', $evaluation->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Are you sure you want to delete this criteria? This action cannot be undone and may affect existing evaluations.')"
                                                                class="btn btn-danger rounded-pill"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $evaluations->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            @if(request()->anyFilled(['employee_name', 'month', 'year', 'status']))
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5>No Matching Results Found</h5>
                                <p class="text-muted">Try adjusting your search criteria or <a href="{{ route('performance.index') }}">clear filters</a>.</p>
                            @else
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <h5>No Performance Evaluations Found</h5>
                                <p class="text-muted">Start by creating your first employee performance evaluation.</p>
                                <div class="mt-3">
                                    <a href="{{ route('performance-criteria.index') }}" class="btn btn-info mr-2">
                                        <i class="fas fa-cog"></i> Setup Criteria First
                                    </a>
                                    <a href="{{ route('performance.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Employee Evaluation
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Delete</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this performance evaluation?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function deleteEvaluation(id) {
    $('#deleteForm').attr('action', '{{ url("admin/performance") }}/' + id);
    $('#deleteModal').modal('show');
}
</script>
@endsection
