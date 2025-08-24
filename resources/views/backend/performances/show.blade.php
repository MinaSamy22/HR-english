{{-- resources/views/backend/performances/show.blade.php --}}
@extends('backend.layouts.app')

@section('title', 'Performance Evaluation Details')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Performance Evaluation Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance.index') }}">Performance</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Performance Evaluation</h3>
                    <div class="card-tools">
                        @if($evaluation->canBeEdited())
                            <a href="{{ route('performance.edit', $evaluation->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif

                    </div>
                </div>

                <div class="card-body">
                    <!-- Employee Information -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Employee</span>
                                    <span class="info-box-number">{{ $evaluation->employee->name }}</span>
                                    <span class="progress-description">{{ $evaluation->employee->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Evaluation Period</span>
                                    <span class="info-box-number">{{ $evaluation->evaluation_period }}</span>
                                    <span class="progress-description">{{ $evaluation->evaluation_year }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-{{ $evaluation->getPerformanceRatingClass() }}">
                                    <i class="fas fa-star"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Overall Score</span>
                                    <span class="info-box-number">{{ $evaluation->overall_score }}/5.00</span>
                                    <span class="progress-description">{{ $evaluation->getPerformanceRating() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Type Indicator -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Evaluation Type:</strong>
                                        <span class="badge badge-{{ $evaluation->uses_custom_criteria ? 'info' : 'secondary' }}">
                                            {{ $evaluation->uses_custom_criteria ? 'Custom Criteria' : 'Standard Criteria' }}
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <span class="badge badge-{{ $evaluation->getStatusBadgeClass() }}">
                                            {{ ucfirst($evaluation->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<!-- Enhanced Performance Metrics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-4">
                                <i class="fas fa-chart-bar mr-2"></i>Performance Metrics
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-gradient-primary">
                                    <h3 class="card-title text-white">
                                        <i class="fas fa-trophy mr-2"></i>Score Breakdown
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if($evaluation->uses_custom_criteria)
                                        @php
                                            $criteriaScores = $evaluation->getCustomCriteriaScores();
                                        @endphp
                                        @if($criteriaScores->count() > 0)
                                            <div class="row">
                                                @foreach($criteriaScores as $criteriaId => $scoreData)
                                                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                                        <div class="card shadow-sm h-100">
                                                            <div class="card-body text-center">
                                                                <div class="score-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; color: white; background: linear-gradient(135deg,
                                                                    @if($scoreData['score'] >= 4.5) #28a745, #20c997
                                                                    @elseif($scoreData['score'] >= 4) #17a2b8, #007bff
                                                                    @elseif($scoreData['score'] >= 3) #ffc107, #fd7e14
                                                                    @else #dc3545, #e83e8c @endif);">
                                                                    {{ $scoreData['score'] }}
                                                                </div>
                                                                <h6 class="card-title font-weight-bold">{{ $scoreData['name'] }}</h6>
                                                                <div class="progress mb-2" style="height: 8px;">
                                                                    <div class="progress-bar
                                                                        @if($scoreData['score'] >= 4.5) bg-success
                                                                        @elseif($scoreData['score'] >= 4) bg-info
                                                                        @elseif($scoreData['score'] >= 3) bg-warning
                                                                        @else bg-danger @endif"
                                                                        style="width: {{ ($scoreData['score'] / 5) * 100 }}%"></div>
                                                                </div>
                                                                <span class="badge badge-{{ $scoreData['score'] >= 4 ? 'success' : ($scoreData['score'] >= 3 ? 'warning' : 'danger') }}">
                                                                    {{ $scoreData['score'] }}/5.0

                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-5">
                                                <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                                                <h5>No Custom Criteria Scores Available</h5>
                                                <p class="text-muted">No performance criteria have been configured for this evaluation.</p>
                                            </div>
                                        @endif
                                    @else
                                        <!-- Enhanced Standard Criteria Display -->
                                        <div class="row">


                                            @foreach($standardCriteria as $criteria)
                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                                    <div class="card shadow-sm h-100">
                                                        <div class="card-body text-center">
                                                            <div class="mb-3">
                                                                <i class="{{ $criteria['icon'] }} fa-2x text-primary"></i>
                                                            </div>
                                                            <div class="score-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; color: white; background: linear-gradient(135deg,
                                                                @if(($criteria['score'] ?? 0) >= 4.5) #28a745, #20c997
                                                                @elseif(($criteria['score'] ?? 0) >= 4) #17a2b8, #007bff
                                                                @elseif(($criteria['score'] ?? 0) >= 3) #ffc107, #fd7e14
                                                                @else #dc3545, #e83e8c @endif);">
                                                                {{ $criteria['score'] ?? 'N/A' }}
                                                            </div>
                                                            <h6 class="card-title font-weight-bold">{{ $criteria['name'] }}</h6>
                                                            @if($criteria['score'])
                                                                <div class="progress mb-2" style="height: 8px;">
                                                                    <div class="progress-bar
                                                                        @if($criteria['score'] >= 4.5) bg-success
                                                                        @elseif($criteria['score'] >= 4) bg-info
                                                                        @elseif($criteria['score'] >= 3) bg-warning
                                                                        @else bg-danger @endif"
                                                                        style="width: {{ ($criteria['score'] / 5) * 100 }}%"></div>
                                                                </div>
                                                                <span class="badge badge-{{ $criteria['score'] >= 4 ? 'success' : ($criteria['score'] >= 3 ? 'warning' : 'danger') }}">
                                                                    {{ $criteria['score'] }}/5.0
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">Not Rated</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary">Comments & Feedback</h5>
                        </div>
                    </div>

                    <div class="row">
                        @if($evaluation->strengths)
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-thumbs-up"></i> Strengths
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $evaluation->strengths }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($evaluation->areas_for_improvement)
                        <div class="col-md-6">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-line"></i> Areas for Improvement
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $evaluation->areas_for_improvement }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($evaluation->goals_for_next_period)
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-target"></i> Goals for Next Period
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $evaluation->goals_for_next_period }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($evaluation->hr_comments)
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-comments"></i> HR Comments
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $evaluation->hr_comments }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Evaluation Details -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Evaluation Details</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Status:</strong><br>
                                            <span class="badge badge-{{ $evaluation->getStatusBadgeClass() }}">
                                                {{ ucfirst($evaluation->status) }}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Evaluated By:</strong><br>
                                            {{ $evaluation->evaluator->name ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Created:</strong><br>
                                            {{ $evaluation->created_at->format('M d, Y h:i A') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Last Updated:</strong><br>
                                            {{ $evaluation->updated_at->format('M d, Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Form (hidden) -->
<form id="deleteForm" action="{{ route('performance.destroy', $evaluation->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>

function confirmDelete() {
    if (confirm('Are you sure you want to delete this performance evaluation? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

</script>
@endpush
@endsection
