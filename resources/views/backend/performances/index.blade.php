{{-- resources/views/admin/performance/index.blade.php --}}
@extends('backend.layouts.app')

@section('title', __('h_performance.employee_performance'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1>{{ __('h_performance.employee_performance') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('h_performance.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_performance.performance') }}</li>
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
        <h3 class="card-title">{{ __('h_performance.performance_evaluations') }}</h3>
        <div class="card-tools">
            <a href="{{ route('performance-criteria.index') }}" class="btn btn-info btn-sm rounded-pill mr-2">
                <i class="fas fa-cog"></i> {{ __('h_performance.manage_criteria') }}
            </a>
            <a href="{{ route('performance.create') }}" class="btn btn-primary btn-sm rounded-pill">
                <i class="fas fa-plus"></i> {{ __('h_performance.new_employee_evaluation') }}
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
                                            <label for="employee_name">{{ __('h_performance.search_by_employee') }}</label>
                                            <input type="text" name="employee_name" id="employee_name"
                                                   class="form-control" placeholder="{{ __('h_performance.enter_employee_name') }}"
                                                   value="{{ request('employee_name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="year">{{ __('h_performance.year') }}</label>
                                            <select name="year" id="year" class="form-control">
                                                <option value="">{{ __('h_performance.all_years') }}</option>
                                                @for($year = date('Y'); $year >= 2020; $year--)
                                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="status">{{ __('h_performance.status') }}</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('h_performance.all_status') }}</option>
                                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('h_performance.draft') }}</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('h_performance.completed') }}</option>
                                                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>{{ __('h_performance.reviewed') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="d-flex">
    <button type="submit" class="btn btn-primary rounded-pill" style="margin-right: 10px;">
        <i class="fas fa-search"></i> {{ __('h_performance.search') }}
    </button>
    <a href="{{ route('performance.index') }}" class="btn btn-secondary rounded-pill" style="margin-right: 10px;">
        <i class="fas fa-redo"></i> {{ __('h_performance.reset') }}
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
                            {{ __('h_performance.showing_filtered_results') }}
                            @if(request('employee_name'))
                                {{ __('h_performance.for_employee') }} <strong>{{ request('employee_name') }}</strong>
                            @endif
                            @if(request('month'))
                                {{ __('h_performance.for_period') }} <strong>{{ request('month') }}</strong>
                            @endif
                            @if(request('year'))
                                {{ __('h_performance.in_year') }} <strong>{{ request('year') }}</strong>
                            @endif
                            @if(request('status'))
                                {{ __('h_performance.with_status') }} <strong>{{ __(
                                'h_performance.' . request('status')) }}</strong>
                            @endif
                            ({{ $evaluations->total() }} {{ $evaluations->total() != 1 ? __('h_performance.results') : __('h_performance.result') }})
                        </div>
                    @endif

                    @if($evaluations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('h_performance.employee') }}</th>
                                        <th>{{ __('h_performance.rating') }}</th>
                                        <th>{{ __('h_performance.status') }}</th>
                                        <th>{{ __('h_performance.evaluated_period') }}</th>
                                        <th>{{ __('h_performance.year') }}</th>
                                        <th>{{ __('h_performance.actions') }}</th>
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
        {{ __('h_performance.' . strtolower($evaluation->getPerformanceRating())) }}
    </span>
</td>

                                            <td>
                                                <span class="badge badge-{{ $evaluation->status == 'completed' ? 'success' : ($evaluation->status == 'reviewed' ? 'info' : 'warning') }}">
                                                    {{ __('h_performance.' . $evaluation->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $evaluation->evaluation_period }}</td>

                                            <td>{{ $evaluation->evaluation_year }}</td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('performance.show', $evaluation->id) }}"
                                                       class="btn btn-info rounded-pill" title="{{ __('h_performance.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('performance.edit', $evaluation->id) }}"
                                                       class="btn btn-warning rounded-pill" title="{{ __('h_performance.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('performance.destroy', $evaluation->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('{{ __('h_performance.delete_criteria_confirm') }}')"
                                                                class="btn btn-danger rounded-pill"
                                                                title="{{ __('h_performance.delete') }}">
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
                                <h5>{{ __('h_performance.no_matching_results') }}</h5>
                                <p class="text-muted">{{ __('h_performance.adjust_search_criteria') }} <a href="{{ route('performance.index') }}">{{ __('h_performance.clear_filters') }}</a>.</p>
                            @else
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <h5>{{ __('h_performance.no_performance_evaluations') }}</h5>
                                <p class="text-muted">{{ __('h_performance.start_creating') }}</p>
                                <div class="mt-3">
                                    <a href="{{ route('performance-criteria.index') }}" class="btn btn-info mr-2">
                                        <i class="fas fa-cog"></i> {{ __('h_performance.setup_criteria_first') }}
                                    </a>
                                    <a href="{{ route('performance.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('h_performance.create_employee_evaluation') }}
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
                <h4 class="modal-title">{{ __('h_performance.confirm_delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>{{ __('h_performance.delete_evaluation_confirm') }}</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('h_performance.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('h_performance.delete') }}</button>
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
