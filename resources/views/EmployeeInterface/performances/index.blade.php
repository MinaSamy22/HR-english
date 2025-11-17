@extends('EmployeeInterface.layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h2>{{ __('E_performance.performance_management') }}</h2>
                    </div>
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home">{{ __('E_performance.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('E_performance.performance') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Performance Criteria Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">{{ __('E_performance.evaluation_criteria') }}</h4>
                        </div>
                        <div class="card-body">
                            @if ($performanceCriteria->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 25%;">{{ __('E_performance.criteria_name') }}</th>
                                                <th style="width: 60%;">{{ __('E_performance.description') }}</th>
                                                <th style="width: 10%;">{{ __('E_performance.status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($performanceCriteria as $index => $criteria)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td class="fw-medium">{{ $criteria->name }}</td>
                                                    <td>
                                                        <div class="text-truncate" style="max-width: 300px;"
                                                             title="{{ $criteria->description ?? __('E_performance.no_description_available') }}">
                                                            {{ $criteria->description ?? __('E_performance.no_description_available') }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">{{ __('E_performance.active') }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>{{ __('E_performance.no_criteria_message') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Evaluations Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">{{ __('E_performance.my_performance_evaluations') }}</h4>
                        </div>
                        <div class="card-body">
                            @if ($evaluations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 25%;">{{ __('E_performance.evaluation_date') }}</th>
                                                <th style="width: 20%;">{{ __('E_performance.overall_score') }}</th>
                                                <th style="width: 20%;">{{ __('E_performance.status') }}</th>
                                                <th style="width: 35%;">{{ __('E_performance.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evaluations as $evaluation)
                                                <tr>
                                                    <td>
                                                        <span class="d-block">
                                                            {{ \Carbon\Carbon::parse($evaluation->created_at)->format('M d, Y') }}
                                                        </span>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($evaluation->created_at)->format('H:i') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="badge fs-6
                                                            @if ($evaluation->overall_score >= 4.0) bg-success
                                                            @elseif($evaluation->overall_score >= 3.0) bg-warning
                                                            @else bg-danger @endif">
                                                            {{ number_format($evaluation->overall_score, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge
                                                            @if ($evaluation->status == 'completed') bg-success
                                                            @elseif($evaluation->status == 'reviewed') bg-primary
                                                            @else bg-secondary @endif">
                                                            @switch($evaluation->status)
                                                                @case('completed')
                                                                    {{ __('E_performance.completed') }}
                                                                    @break
                                                                @case('reviewed')
                                                                    {{ __('E_performance.reviewed') }}
                                                                    @break
                                                                @case('pending')
                                                                    {{ __('E_performance.pending') }}
                                                                    @break
                                                                @case('draft')
                                                                    {{ __('E_performance.draft') }}
                                                                    @break
                                                                @default
                                                                    {{ ucfirst($evaluation->status) }}
                                                            @endswitch
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('employee.performances.show', $evaluation->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="d-none d-sm-inline">{{ __('E_performance.view_details') }}</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>{{ __('E_performance.no_evaluations_message') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
