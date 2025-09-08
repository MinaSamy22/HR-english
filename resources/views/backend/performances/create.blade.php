{{-- resources/views/admin/performance/create.blade.php --}}
@extends('backend.layouts.app')

@section('title', __('h_performance.create_performance_evaluation'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('h_performance.create_performance_evaluation') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('h_performance.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance.index') }}">{{ __('h_performance.performance') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_performance.create') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('h_performance.performance_evaluation_form') }}</h3>
                    @if($customCriteria->count() > 0)
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $customCriteria->count() }} {{ __('h_performance.custom_criteria') }}</span>
                        </div>
                    @endif
                </div>

                <form action="{{ route('performance.store') }}" method="POST" id="evaluationForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">{{ __('h_performance.employee') }} <span class="text-danger">{{ __('h_performance.required') }}</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">{{ __('h_performance.select_employee') }}</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Evaluation Period -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="evaluation_period">{{ __('h_performance.evaluation_period') }} <span class="text-danger">{{ __('h_performance.required') }}</span></label>
                                    <input type="text" name="evaluation_period" id="evaluation_period"
                                           class="form-control @error('evaluation_period') is-invalid @enderror"
                                           placeholder="{{ __('h_performance.evaluation_period_placeholder') }}" value="{{ old('evaluation_period') }}" required>
                                    @error('evaluation_period')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Year -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="evaluation_year">{{ __('h_performance.year') }} <span class="text-danger">{{ __('h_performance.required') }}</span></label>
                                    <select name="evaluation_year" id="evaluation_year" class="form-control @error('evaluation_year') is-invalid @enderror" required>
                                        @for($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ (old('evaluation_year', date('Y')) == $year) ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('evaluation_year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                       <!-- Custom Criteria Section -->
@if($customCriteria->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <h5 class="text-primary">{{ __('h_performance.performance_metrics') }}</h5>
        <small class="text-muted">{{ __('h_performance.rating_description') }}</small>
        <hr>
    </div>
</div>

@foreach($customCriteria as $criteria)
<div class="row mt-3">
    <div class="col-12">
        <div class="form-group">
            <label for="criteria_{{ $criteria->id }}">
                {{ $criteria->name }}
                <span class="text-danger">{{ __('h_performance.required') }}</span>
            </label>
            @if($criteria->description)
                <div class="text-muted small mb-2">{{ $criteria->description }}</div>
            @endif
            <select name="criteria_{{ $criteria->id }}" id="criteria_{{ $criteria->id }}" class="form-control @error('criteria_'.$criteria->id) is-invalid @enderror" required>
                <option value="">{{ __('h_performance.select_rating') }}</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('criteria_'.$criteria->id) == $i ? 'selected' : '' }}>
                        {{ $i }} - {{ __('h_performance.' . ['', 'poor', 'needs improvement', 'satisfactory', 'good', 'excellent'][$i]) }}
                    </option>
                @endfor
            </select>
            @error('criteria_'.$criteria->id)
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
@endforeach

<!-- Overall Score Display -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6><i class="icon fas fa-info-circle"></i> {{ __('h_performance.overall_score_calculation') }}</h6>
            {{ __('h_performance.overall_score_description') }}
        </div>
    </div>
</div>

@else
<!-- Fallback message if no custom criteria are available -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {{ __('h_performance.no_criteria_found') }}</h5>
                                    {{ __('h_performance.create_criteria_first') }}
                                    <a href="{{ route('performance-criteria.create') }}" class="btn btn-primary btn-sm ml-2">{{ __('h_performance.create_criteria') }}</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Comments Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary">{{ __('h_performance.comments_feedback') }}</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Strengths -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="strengths">{{ __('h_performance.strengths') }}</label>
                                    <textarea name="strengths" id="strengths" rows="4"
                                              class="form-control @error('strengths') is-invalid @enderror"
                                              placeholder="{{ __('h_performance.strengths_placeholder') }}">{{ old('strengths') }}</textarea>
                                    @error('strengths')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Areas for Improvement -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="areas_for_improvement">{{ __('h_performance.areas_for_improvement') }}</label>
                                    <textarea name="areas_for_improvement" id="areas_for_improvement" rows="4"
                                              class="form-control @error('areas_for_improvement') is-invalid @enderror"
                                              placeholder="{{ __('h_performance.areas_improvement_placeholder') }}">{{ old('areas_for_improvement') }}</textarea>
                                    @error('areas_for_improvement')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Goals for Next Period -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="goals_for_next_period">{{ __('h_performance.goals_for_next_period') }}</label>
                                    <textarea name="goals_for_next_period" id="goals_for_next_period" rows="4"
                                              class="form-control @error('goals_for_next_period') is-invalid @enderror"
                                              placeholder="{{ __('h_performance.goals_next_period_placeholder') }}">{{ old('goals_for_next_period') }}</textarea>
                                    @error('goals_for_next_period')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- HR Comments -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hr_comments">{{ __('h_performance.hr_comments') }}</label>
                                    <textarea name="hr_comments" id="hr_comments" rows="4"
                                              class="form-control @error('hr_comments') is-invalid @enderror"
                                              placeholder="{{ __('h_performance.hr_comments_placeholder') }}">{{ old('hr_comments') }}</textarea>
                                    @error('hr_comments')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">{{ __('h_performance.status') }} <span class="text-danger">{{ __('h_performance.required') }}</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>{{ __('h_performance.draft') }}</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>{{ __('h_performance.completed') }}</option>
                                        <option value="reviewed" {{ old('status') == 'reviewed' ? 'selected' : '' }}>{{ __('h_performance.reviewed') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hidden field to always use custom criteria -->
                        <input type="hidden" name="use_custom_criteria" value="true">
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" {{ $customCriteria->count() == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save"></i> {{ __('h_performance.create_evaluation') }}
                        </button>
                        <a href="{{ route('performance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('h_performance.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
