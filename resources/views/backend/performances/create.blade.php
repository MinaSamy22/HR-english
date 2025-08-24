{{-- resources/views/admin/performance/create.blade.php --}}
@extends('backend.layouts.app')

@section('title', 'Create Performance Evaluation')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Performance Evaluation</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance.index') }}">Performance</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Performance Evaluation Form</h3>
                    @if($customCriteria->count() > 0)
                        <div class="card-tools">
                            <span class="badge badge-info">{{ $customCriteria->count() }} Custom Criteria</span>
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
                                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Select Employee</option>
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
                                    <label for="evaluation_period">Evaluation Period <span class="text-danger">*</span></label>
                                    <input type="text" name="evaluation_period" id="evaluation_period"
                                           class="form-control @error('evaluation_period') is-invalid @enderror"
                                           placeholder="e.g., Q1, January, Annual" value="{{ old('evaluation_period') }}" required>
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
        <h5 class="text-primary">Performance Metrics (Rate 1-5)</h5>
        <small class="text-muted">1 = Poor, 2 = Needs Improvement, 3 = Satisfactory, 4 = Good, 5 = Excellent</small>
    </div>
</div>

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
            <select name="criteria_{{ $criteria->id }}" id="criteria_{{ $criteria->id }}" class="form-control @error('criteria_'.$criteria->id) is-invalid @enderror" required>
                <option value="">Select Rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('criteria_'.$criteria->id) == $i ? 'selected' : '' }}>
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

<!-- Fallback message if no custom criteria are available -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> No Custom Criteria Found!</h5>
                                    Please create custom evaluation criteria first before creating performance evaluations.
                                    <a href="{{ route('criteria.create') }}" class="btn btn-primary btn-sm ml-2">Create Criteria</a>
                                </div>
                            </div>
                        </div>
                        @endif

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
                                              placeholder="Highlight the employee's key strengths and achievements...">{{ old('strengths') }}</textarea>
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
                                              placeholder="Identify areas where the employee can improve...">{{ old('areas_for_improvement') }}</textarea>
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
                                              placeholder="Set goals and expectations for the next evaluation period...">{{ old('goals_for_next_period') }}</textarea>
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
                                              placeholder="Additional HR comments and notes...">{{ old('hr_comments') }}</textarea>
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
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="reviewed" {{ old('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
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
                            <i class="fas fa-save"></i> Create Evaluation
                        </button>
                        <a href="{{ route('performance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
