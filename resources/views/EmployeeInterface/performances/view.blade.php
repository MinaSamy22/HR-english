{{-- Updated Employee Performance View --}}
@extends('EmployeeInterface.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1>{{ __('E_performance.performance_evaluation_details') }}</h1>
                </div>
                <div class="">
                     <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item ">{{ __('E_performance.my_performance_evaluation') }}</li>
                            <li class="breadcrumb-item active">{{ __('E_performance.performance') }}</li>
                     </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">{{ __('E_performance.my_performance_evaluation') }}</h3>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Employee Information -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('E_performance.employee') }}</span>
                                    <span class="info-box-number">{{ $evaluation->employee->name ?? $evaluation->employee_name }}</span>
                                    <span class="progress-description">{{ $evaluation->employee->email ?? $evaluation->employee_email ?? __('E_performance.na') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('E_performance.evaluation_period') }}</span>
                                    <span class="info-box-number">
                                        @switch($evaluation->evaluation_period)
                                            @case('monthly')
                                                {{ __('E_performance.monthly') }}
                                                @break
                                            @case('quarterly')
                                                {{ __('E_performance.quarterly') }}
                                                @break
                                            @case('semi_annual')
                                                {{ __('E_performance.semi_annual') }}
                                                @break
                                            @case('annual')
                                                {{ __('E_performance.annual') }}
                                                @break
                                            @default
                                                {{ ucfirst($evaluation->evaluation_period) }}
                                        @endswitch
                                    </span>
                                    <span class="progress-description">{{ $evaluation->evaluation_year }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-{{ $evaluation->overall_score >= 4.0 ? 'success' : ($evaluation->overall_score >= 3.0 ? 'warning' : 'danger') }}">
                                    <i class="fas fa-star"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('E_performance.overall_score') }}</span>
                                    <span class="info-box-number">{{ number_format($evaluation->overall_score, 2) }}/5.00</span>
                                    <span class="progress-description">
                                        @if($evaluation->overall_score >= 4.5) {{ __('E_performance.excellent') }}
                                        @elseif($evaluation->overall_score >= 4.0) {{ __('E_performance.very_good') }}
                                        @elseif($evaluation->overall_score >= 3.0) {{ __('E_performance.good') }}
                                        @elseif($evaluation->overall_score >= 2.0) {{ __('E_performance.needs_improvement') }}
                                        @else {{ __('E_performance.poor') }}
                                        @endif
                                    </span>
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
                                        <strong>{{ __('E_performance.evaluation_type') }}:</strong>
                                        <span class="badge badge-{{ $evaluation->uses_custom_criteria ? 'info' : 'secondary' }}">
                                            {{ $evaluation->uses_custom_criteria ? __('E_performance.custom_criteria') : __('E_performance.standard_criteria') }}
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>{{ __('E_performance.status') }}:</strong>
                                        <span class="badge badge-{{ $evaluation->status == 'completed' ? 'success' : ($evaluation->status == 'reviewed' ? 'primary' : 'secondary') }}">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Performance Metrics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-4">
                                <i class="fas fa-chart-bar mr-2"></i>{{ __('E_performance.performance_metrics') }}
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-gradient-primary">
                                    <h3 class="card-title text-white">
                                        <i class="fas fa-trophy mr-2"></i>{{ __('E_performance.score_breakdown') }}
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if($evaluation->uses_custom_criteria)
                                        @if($criteriaScores->count() > 0 || (isset($criteriaScores) && count($criteriaScores) > 0))
                                            <div class="row">
                                                @foreach($criteriaScores as $criteriaId => $scoreData)
                                                    @php
                                                        // Handle both object and array structures
                                                        if (is_object($scoreData)) {
                                                            $score = $scoreData->score ?? 0;
                                                            $name = $scoreData->name ?? __('E_performance.unknown');
                                                        } elseif (is_array($scoreData)) {
                                                            $score = $scoreData['score'] ?? 0;
                                                            $name = $scoreData['name'] ?? __('E_performance.unknown');
                                                        } else {
                                                            $score = 0;
                                                            $name = __('E_performance.unknown');
                                                        }
                                                    @endphp
                                                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                                        <div class="card shadow-sm h-100">
                                                            <div class="card-body text-center">
                                                                <div class="score-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; color: white; background: linear-gradient(135deg,
                                                                    @if($score >= 4.5) #28a745, #20c997
                                                                    @elseif($score >= 4) #17a2b8, #007bff
                                                                    @elseif($score >= 3) #ffc107, #fd7e14
                                                                    @else #dc3545, #e83e8c @endif);">
                                                                    {{ is_numeric($score) ? number_format($score, 1) : __('E_performance.na') }}
                                                                </div>
                                                                <h6 class="card-title font-weight-bold">{{ $name }}</h6>
                                                                @if(is_numeric($score) && $score > 0)
                                                                    <div class="progress mb-2" style="height: 8px;">
                                                                        <div class="progress-bar
                                                                            @if($score >= 4.5) bg-success
                                                                            @elseif($score >= 4) bg-info
                                                                            @elseif($score >= 3) bg-warning
                                                                            @else bg-danger @endif"
                                                                            style="width: {{ ($score / 5) * 100 }}%"></div>
                                                                    </div>
                                                                    <span class="badge badge-{{ $score >= 4 ? 'success' : ($score >= 3 ? 'warning' : 'danger') }}">
                                                                        {{ number_format($score, 1) }}/5.0
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">{{ __('E_performance.not_rated') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-5">
                                                <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                                                <h5>{{ __('E_performance.no_custom_criteria_scores') }}</h5>
                                                <p class="text-muted">{{ __('E_performance.no_criteria_configured') }}</p>
                                            </div>
                                        @endif
                                    @else
                                        <!-- Standard Criteria Display -->
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
                                                                {{ isset($criteria['score']) && is_numeric($criteria['score']) ? number_format($criteria['score'], 1) : __('E_performance.na') }}
                                                            </div>
                                                            <h6 class="card-title font-weight-bold">{{ $criteria['name'] }}</h6>
                                                            @if(isset($criteria['score']) && is_numeric($criteria['score']) && $criteria['score'] > 0)
                                                                <div class="progress mb-2" style="height: 8px;">
                                                                    <div class="progress-bar
                                                                        @if($criteria['score'] >= 4.5) bg-success
                                                                        @elseif($criteria['score'] >= 4) bg-info
                                                                        @elseif($criteria['score'] >= 3) bg-warning
                                                                        @else bg-danger @endif"
                                                                        style="width: {{ ($criteria['score'] / 5) * 100 }}%"></div>
                                                                </div>
                                                                <span class="badge badge-{{ $criteria['score'] >= 4 ? 'success' : ($criteria['score'] >= 3 ? 'warning' : 'danger') }}">
                                                                    {{ number_format($criteria['score'], 1) }}/5.0
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">{{ __('E_performance.not_rated') }}</span>
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
                            <h5 class="text-primary">{{ __('E_performance.comments_feedback') }}</h5>
                        </div>
                    </div>

                    <div class="row">
                        @if($evaluation->strengths)
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-thumbs-up"></i> {{ __('E_performance.strengths') }}
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
                                        <i class="fas fa-chart-line"></i> {{ __('E_performance.areas_for_improvement') }}
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
                                        <i class="fas fa-target"></i> {{ __('E_performance.goals_for_next_period') }}
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
                                        <i class="fas fa-comments"></i> {{ __('E_performance.hr_comments') }}
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
                                    <h3 class="card-title">{{ __('E_performance.evaluation_details') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>{{ __('E_performance.status') }}:</strong><br>
                                            <span class="badge badge-{{ $evaluation->status == 'completed' ? 'success' : ($evaluation->status == 'reviewed' ? 'primary' : 'secondary') }}">
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
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('E_performance.evaluated_by') }}:</strong><br>
                                            {{ $evaluation->evaluator->name ?? $evaluation->evaluator_name ?? __('E_performance.na') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('E_performance.created') }}:</strong><br>
                                            {{ \Carbon\Carbon::parse($evaluation->created_at)->format('M d, Y h:i A') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('E_performance.last_updated') }}:</strong><br>
                                            {{ \Carbon\Carbon::parse($evaluation->updated_at)->format('M d, Y h:i A') }}
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
@endsection
