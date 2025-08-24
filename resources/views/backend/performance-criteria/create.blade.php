{{-- resources/views/backend/performance-criteria/create.blade.php --}}
@extends('backend.layouts.app')

@section('title', 'Add Performance Criteria')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1>Add Performance Criteria</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance-criteria.index') }}">Performance Criteria</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Evaluation Criteria</h3>
                </div>

                <form action="{{ route('performance-criteria.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Criteria Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Criteria Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="e.g., Quality of Work, Communication Skills"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Enter a clear and descriptive name for this evaluation criteria.</small>
                                </div>
                            </div>


                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <small class="form-text text-muted">Only active criteria will appear in evaluations.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Provide a detailed description of what this criteria evaluates...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Optional: Add details about what this criteria measures and how it should be evaluated.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Examples Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-lightbulb"></i> Common Performance Criteria Examples:</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>Quality of Work</li>
                                                <li>Productivity & Efficiency</li>
                                                <li>Communication Skills</li>
                                                <li>Teamwork & Collaboration</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>Problem Solving</li>
                                                <li>Leadership Potential</li>
                                                <li>Adaptability</li>
                                                <li>Customer Service</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Criteria
                        </button>
                        <a href="{{ route('performance-criteria.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
