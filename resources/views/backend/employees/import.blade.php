@extends('backend.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-file-import mr-2"></i> {{ __('h_employee.import_employees') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/employees') }}">{{ __('h_employee.employees') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_employee.import_excel') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Upload Form -->
                    <div class="col-md-8">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-upload mr-1"></i> {{ __('h_employee.upload_excel_file') }}</h3>
                            </div>

                            <form method="POST" action="{{ url('admin/import-employees') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="excel_file">
                                            <i class="fas fa-file-excel text-success mr-1"></i> {{ __('h_employee.choose_excel_file') }}
                                        </label>
                                        <input type="file" name="excel_file" class="form-control"
                                            accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            {{ __('h_employee.required_columns') }}:
                                            <strong>
                                                {{ __('h_employee.column_list') }}
                                            </strong>
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer bg-light clearfix">
                                    <button type="submit" class="btn btn-primary float-left">
                                        <i class="fas fa-cloud-upload-alt mr-1"></i> {{ __('h_employee.upload_file') }}
                                    </button>

                                    <a href="{{ url('admin/employees') }}" class="btn btn-secondary float-right"
                                        style="background-color: rgb(238, 237, 237)">
                                        <i class="fas fa-arrow-left mr-1"></i> {{ __('h_employee.back') }}
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="col-md-4">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> {{ __('h_employee.guidelines') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="callout callout-info">
                                    <h5><i class="fas fa-check-circle"></i> {{ __('h_employee.important_notes') }}</h5>
                                    <p>{{ __('h_employee.excel_fields_note') }}:</p>
                                    <ul class="mb-1">
                                        <li>{{ __('h_employee.id') }}</li>
                                        <li>{{ __('h_employee.name') }}</li>
                                        <li>{{ __('h_employee.email') }}</li>
                                        <li>{{ __('h_employee.phone_number') }}</li>
                                        <li>{{ __('h_employee.birth_date') }} ({{ __('h_employee.date_format') }})</li>
                                        <li>{{ __('h_employee.hire_date') }} ({{ __('h_employee.date_format') }})</li>
                                        <li>{{ __('h_employee.job_title_id') }}</li>
                                        <li>{{ __('h_employee.salary_type') }}</li>
                                        <p class="mt-2 text-muted">{{ __('h_employee.salary_type_note') }}</p>
                                        <p class="mt-2 text-muted">{{ __('h_employee.monthly') }}</p>
                                        <p class="mt-2 text-muted">{{ __('h_employee.weekly') }}</p>
                                        <p class="mt-2 text-muted">{{ __('h_employee.daily') }}</p>
                                        <li>{{ __('h_employee.salary') }}</li>
                                        <li>{{ __('h_employee.start_time') }} ({{ __('h_employee.time_format') }})</li>
                                        <li>{{ __('h_employee.end_time') }} ({{ __('h_employee.time_format') }})</li>
                                        <li>{{ __('h_employee.manager_id') }}</li>
                                        <li>{{ __('h_employee.department_id') }}</li>
                                        <li>{{ __('h_employee.role') }} ({{ __('h_employee.role_options') }})</li>
                                    </ul>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ asset('dist/sample_employee..xlsx') }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download mr-1"></i> {{ __('h_employee.download_sample_template') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Instructions -->
                </div>
            </div>
        </section>
    </div>
@endsection
