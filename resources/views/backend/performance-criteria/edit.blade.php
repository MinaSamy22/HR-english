{{-- resources/views/backend/performance-criteria/edit.blade.php --}}
@extends('backend.layouts.app')

@section('title', 'Edit Performance Criteria')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1>Edit Performance Criteria</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('performance-criteria.index') }}">Performance Criteria</a></li>
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
                    <h3 class="card-title">Edit Evaluation Criteria</h3>
                </div>

                <form action="{{ route('performance-criteria.update', $criteria->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Criteria Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Criteria Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="e.g., Quality of Work, Communication Skills"
                                           value="{{ old('name', $criteria->name) }}" required>
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
                                        <option value="1" {{ old('is_active', $criteria->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $criteria->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
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
                                              placeholder="Provide a detailed description of what this criteria evaluates...">{{ old('description', $criteria->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Optional: Add details about what this criteria measures and how it should be evaluated.</small>
                                </div>
                            </div>
                        </div>

                        @if($criteria->created_at)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-light">
                                    <small class="text-muted">
                                        <strong>Created:</strong> {{ $criteria->created_at->format('M d, Y \a\t h:i A') }}
                                        @if($criteria->updated_at && $criteria->updated_at != $criteria->created_at)
                                            | <strong>Last Updated:</strong> {{ $criteria->updated_at->format('M d, Y \a\t h:i A') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Criteria
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
