{{-- resources/views/backend/performance-criteria/create.blade.php --}}
@extends('backend.layouts.app')

@section('title', __('h_criteria.create_criteria'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="">
                    <h1>{{ __('h_criteria.create_criteria') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('h_criteria.dashboard') }}</a></li> --}}
                        <li class="breadcrumb-item"><a href="{{ route('performance-criteria.index') }}">{{ __('h_criteria.title') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_criteria.add') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('h_criteria.create_new_criteria') }}</h3>
                </div>

                <form action="{{ route('performance-criteria.store') }}" method="POST" id="addForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Criteria Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('h_criteria.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="{{ __('h_criteria.name_placeholder') }}"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('h_criteria.name_help') }}</small>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_active">{{ __('h_criteria.status') }}</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>{{ __('h_criteria.active') }}</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>{{ __('h_criteria.inactive') }}</option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('h_criteria.status_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{ __('h_criteria.description') }}</label>
                                    <textarea name="description" id="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="{{ __('h_criteria.description_placeholder') }}">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('h_criteria.description_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Examples Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-lightbulb"></i> {{ __('h_criteria.examples_title') }}</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>{{ __('h_criteria.example_quality') }}</li>
                                                <li>{{ __('h_criteria.example_productivity') }}</li>
                                                <li>{{ __('h_criteria.example_communication') }}</li>
                                                <li>{{ __('h_criteria.example_teamwork') }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>{{ __('h_criteria.example_problem_solving') }}</li>
                                                <li>{{ __('h_criteria.example_leadership') }}</li>
                                                <li>{{ __('h_criteria.example_adaptability') }}</li>
                                                <li>{{ __('h_criteria.example_customer_service') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('h_criteria.add_criteria_btn') }}
                        </button>
                        <a href="{{ route('performance-criteria.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('h_criteria.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
