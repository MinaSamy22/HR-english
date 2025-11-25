{{-- resources/views/backend/performances/edit.blade.php --}}
@extends('backend.layouts.app')

@section('title', __('h_performance.edit_performance_evaluation'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="">
                    <h1>{{ __('h_performance.edit_performance_evaluation') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('h_performance.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance.index') }}">{{ __('h_performance.performance') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_performance.edit') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('h_performance.edit_performance_evaluation') }}</h3>

                </div>

                <form action="{{ route('performance.update', $evaluation->id) }}" method="POST" id="evaluationForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Employee Information -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> {{ __('h_performance.employee_information') }}</h5>
                                    <strong>{{ $evaluation->employee->name }}</strong> - {{ $evaluation->employee->email }}
                                    <br><small>{{ __('h_performance.evaluating') }} {{ $evaluation->getFullEvaluationPeriod() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">{{ __('h_performance.employee') }} <span class="text-danger">{{ __('h_performance.required') }}</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">{{ __('h_performance.select_employee') }}</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id', $evaluation->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }} - {{ $employee->email }}
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
                                           placeholder="{{ __('h_performance.evaluation_period_placeholder') }}"
                                           value="{{ old('evaluation_period', $evaluation->evaluation_period) }}" required>
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
                                            <option value="{{ $year }}" {{ old('evaluation_year', $evaluation->evaluation_year) == $year ? 'selected' : '' }}>
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

                        <!-- Performance Criteria Section -->
                        @if($evaluation->uses_custom_criteria && $customCriteria->count() > 0)
                            <!-- Custom Criteria Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="text-primary">{{ __('h_performance.performance_metrics') }}</h5>
                                    <small class="text-muted">{{ __('h_performance.rating_description') }}</small>
                                </div>
                            </div>

                            @php
    $currentScores = $evaluation->getCustomCriteriaScores();
@endphp

@foreach($customCriteria as $criteria)
<div class="row mt-3">
    <div class="col-12">
        <div class="form-group">
            <label for="criteria_{{ $criteria->id }}">
                {{ $criteria->name }}
                <span class="text-danger">{{ __('h_performance.required') }}</span>
            </label>
            @if($criteria->description)
                <div class="text-muted small mb-1">{{ $criteria->description }}</div>
            @endif
            <select name="criteria_{{ $criteria->id }}" id="criteria_{{ $criteria->id }}"
                    class="form-control @error('criteria_'.$criteria->id) is-invalid @enderror" required>
                <option value="">{{ __('h_performance.select_rating') }}</option>
                @for($i = 1; $i <= 5; $i++)
                    @php
                        $currentScore = isset($currentScores[$criteria->id]) ? $currentScores[$criteria->id]['score'] : null;
                        $selected = old('criteria_'.$criteria->id, $currentScore) == $i ? 'selected' : '';
                    @endphp
                    <option value="{{ $i }}" {{ $selected }}>
                        {{ $i }} - {{ __('h_performance.' . ['', 'poor', 'needs_improvement', 'satisfactory', 'good', 'excellent'][$i]) }}
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
                        @else



                        @endif

                        <!-- Current Score Display -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('h_performance.current_overall_score') }}:</strong>
                                            <span class="badge badge-{{ $evaluation->getPerformanceRatingClass() }} badge-lg">
                                                {{ $evaluation->overall_score }}/5.0 - {{ $evaluation->getPerformanceRating() }}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary">{{ __('h_performance.comments_feedback') }}</h5>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Strengths -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="strengths">{{ __('h_performance.strengths') }}</label>
                                    <textarea name="strengths" id="strengths" rows="4"
                                              class="form-control @error('strengths') is-invalid @enderror"
                                              placeholder="{{ __('h_performance.strengths_placeholder') }}">{{ old('strengths', $evaluation->strengths) }}</textarea>
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
                                              placeholder="{{ __('h_performance.areas_improvement_placeholder') }}">{{ old('areas_for_improvement', $evaluation->areas_for_improvement) }}</textarea>
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
                                              placeholder="{{ __('h_performance.goals_next_period_placeholder') }}">{{ old('goals_for_next_period', $evaluation->goals_for_next_period) }}</textarea>
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
                                              placeholder="{{ __('h_performance.hr_comments_placeholder') }}">{{ old('hr_comments', $evaluation->hr_comments) }}</textarea>
                                    @error('hr_comments')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{ __('h_performance.status') }} <span class="text-danger">{{ __('h_performance.required') }}</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', $evaluation->status) == 'draft' ? 'selected' : '' }}>{{ __('h_performance.draft') }}</option>
                                        <option value="completed" {{ old('status', $evaluation->status) == 'completed' ? 'selected' : '' }}>{{ __('h_performance.completed') }}</option>
                                        <option value="reviewed" {{ old('status', $evaluation->status) == 'reviewed' ? 'selected' : '' }}>{{ __('h_performance.reviewed') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('h_performance.last_updated') }}</label>
                                    <div class="form-control-plaintext">
                                        <small class="text-muted">
                                            {{ $evaluation->updated_at->format('M d, Y \a\t h:i A') }}
                                            @if($evaluation->evaluator)
                                                {{ __('h_performance.by') }} {{ $evaluation->evaluator->name }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('h_performance.update_evaluation') }}
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

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation before submit
    $('#evaluationForm').on('submit', function(e) {
        var isValid = true;
        var errorMessage = '';

        // Check if employee is selected
        if ($('#employee_id').val() === '') {
            isValid = false;
            errorMessage += '{{ __('h_performance.select_employee') }}.\n';
        }

        // Check if evaluation period is filled
        if ($('#evaluation_period').val().trim() === '') {
            isValid = false;
            errorMessage += '{{ __('h_performance.evaluation_period') }}.\n';
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }
    });

    // Auto-save functionality (optional)
    var autoSaveTimeout;
    $('form input, form select, form textarea').on('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // You can implement auto-save here if needed
            console.log('Form data changed - could auto-save');
        }, 2000);
    });
});

function confirmDelete() {
    if (confirm('{{ __('h_performance.delete_evaluation_confirm') }}')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
@endsection
