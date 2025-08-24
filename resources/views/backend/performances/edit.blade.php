{{-- resources/views/backend/performances/edit.blade.php --}}
@extends('backend.layouts.app')

@section('title', 'Edit Performance Evaluation')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Performance Evaluation</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance.index') }}">Performance</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Performance Evaluation</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $evaluation->getStatusBadgeClass() }}">{{ ucfirst($evaluation->status) }}</span>
                        @if($evaluation->uses_custom_criteria && $customCriteria->count() > 0)
                            <span class="badge badge-info ml-2">{{ $customCriteria->count() }} Custom Criteria</span>
                        @else
                            <span class="badge badge-secondary ml-2">Standard Criteria</span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('performance.update', $evaluation->id) }}" method="POST" id="evaluationForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Employee Information -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> Employee Information</h5>
                                    <strong>{{ $evaluation->employee->name }}</strong> - {{ $evaluation->employee->email }}
                                    <br><small>Evaluating: {{ $evaluation->getFullEvaluationPeriod() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Select Employee</option>
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
                                    <label for="evaluation_period">Evaluation Period <span class="text-danger">*</span></label>
                                    <input type="text" name="evaluation_period" id="evaluation_period"
                                           class="form-control @error('evaluation_period') is-invalid @enderror"
                                           placeholder="e.g., Q1, January, Annual"
                                           value="{{ old('evaluation_period', $evaluation->evaluation_period) }}" required>
                                    @error('evaluation_period')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Year -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="evaluation_year">Year <span class="text-danger">*</span></label>
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
                                    <h5 class="text-primary">Performance Metrics (Rate 1-5)</h5>
                                    <small class="text-muted">1 = Poor, 2 = Needs Improvement, 3 = Satisfactory, 4 = Good, 5 = Excellent</small>
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
                <span class="text-danger">*</span>
            </label>
            @if($criteria->description)
                <div class="text-muted small mb-1">{{ $criteria->description }}</div>
            @endif
            <select name="criteria_{{ $criteria->id }}" id="criteria_{{ $criteria->id }}"
                    class="form-control @error('criteria_'.$criteria->id) is-invalid @enderror" required>
                <option value="">Select Rating</option>
                @for($i = 1; $i <= 5; $i++)
                    @php
                        $currentScore = isset($currentScores[$criteria->id]) ? $currentScores[$criteria->id]['score'] : null;
                        $selected = old('criteria_'.$criteria->id, $currentScore) == $i ? 'selected' : '';
                    @endphp
                    <option value="{{ $i }}" {{ $selected }}>
                        {{ $i }} - {{ ['', 'Poor', 'Needs Improvement', 'Satisfactory', 'Good', 'Excellent'][$i] }}
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
                                            <strong>Current Overall Score:</strong>
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
                                <h5 class="text-primary">Comments & Feedback</h5>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Strengths -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="strengths">Strengths</label>
                                    <textarea name="strengths" id="strengths" rows="4"
                                              class="form-control @error('strengths') is-invalid @enderror"
                                              placeholder="Highlight the employee's key strengths and achievements...">{{ old('strengths', $evaluation->strengths) }}</textarea>
                                    @error('strengths')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Areas for Improvement -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="areas_for_improvement">Areas for Improvement</label>
                                    <textarea name="areas_for_improvement" id="areas_for_improvement" rows="4"
                                              class="form-control @error('areas_for_improvement') is-invalid @enderror"
                                              placeholder="Identify areas where the employee can improve...">{{ old('areas_for_improvement', $evaluation->areas_for_improvement) }}</textarea>
                                    @error('areas_for_improvement')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Goals for Next Period -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="goals_for_next_period">Goals for Next Period</label>
                                    <textarea name="goals_for_next_period" id="goals_for_next_period" rows="4"
                                              class="form-control @error('goals_for_next_period') is-invalid @enderror"
                                              placeholder="Set goals and expectations for the next evaluation period...">{{ old('goals_for_next_period', $evaluation->goals_for_next_period) }}</textarea>
                                    @error('goals_for_next_period')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- HR Comments -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hr_comments">HR Comments</label>
                                    <textarea name="hr_comments" id="hr_comments" rows="4"
                                              class="form-control @error('hr_comments') is-invalid @enderror"
                                              placeholder="Additional HR comments and notes...">{{ old('hr_comments', $evaluation->hr_comments) }}</textarea>
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
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', $evaluation->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="completed" {{ old('status', $evaluation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="reviewed" {{ old('status', $evaluation->status) == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Updated</label>
                                    <div class="form-control-plaintext">
                                        <small class="text-muted">
                                            {{ $evaluation->updated_at->format('M d, Y \a\t h:i A') }}
                                            @if($evaluation->evaluator)
                                                by {{ $evaluation->evaluator->name }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Evaluation
                        </button>

                        <a href="{{ route('performance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>

                        @if($evaluation->canBeEdited())
                            <div class="float-right">
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash"></i> Delete Evaluation
                                </button>
                            </div>
                        @endif
                    </div>
                </form>

                <!-- Delete Form (hidden) -->
                <form id="deleteForm" action="{{ route('performance.destroy', $evaluation->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
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
            errorMessage += 'Please select an employee.\n';
        }

        // Check if evaluation period is filled
        if ($('#evaluation_period').val().trim() === '') {
            isValid = false;
            errorMessage += 'Please enter evaluation period.\n';
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
    if (confirm('Are you sure you want to delete this performance evaluation? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
@endsection
