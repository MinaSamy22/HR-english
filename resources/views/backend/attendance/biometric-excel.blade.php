@extends('backend.layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1><i class="fas fa-file-excel mr-2"></i>{{ __('dashboard.biometric_excel_upload') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('dashboard.biometric') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('dashboard.upload_excel') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Upload Form -->
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-upload mr-1"></i>
                                {{ __('dashboard.upload_attendance_sheet') }}
                            </h3>
                        </div>

                        <!-- Upload Form -->
                        <form method="POST" action="{{ route('attendance.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="excel_file">
                                        <i class="fas fa-file-excel text-success mr-1"></i>
                                        {{ __('dashboard.choose_excel_file') }}
                                    </label>
                                    <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                    <small class="form-text text-muted">{{ __('dashboard.file_must_contain') }}</small>
                                </div>
                            </div>

                            <div class="card-footer bg-light">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-cloud-upload-alt mr-1"></i>
                                    {{ __('dashboard.upload_file') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Side Help Panel -->
                <div class="col-md-4">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('dashboard.help_instructions') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> {{ __('dashboard.important_notes') }}:</h5>
                                <p>{{ __('dashboard.excel_columns_order') }}: <strong>{{ __('dashboard.employee_code_checkin_checkout_date') }}</strong>.</p>
                            </div>

                            <div class="text-muted mt-3">
                                <p><strong>{{ __('dashboard.format_example') }}:</strong></p>
                                <ul class="mb-0">
                                    <li>12, 08:00 PM, 17:00 PM, 2025-06-14</li>
                                    <li>19, 08:10 AM, 17:00 AM, 2025-06-14</li>
                                </ul>
                                <p class="mt-2">{{ __('dashboard.employee_code_match_system') }}.</p>
                            </div>

                            <div class="mt-3">
                                <a href="{{ asset(path: 'dist/sample.xlsx') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download mr-1"></i>
                                    {{ __('dashboard.download_sample_file') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Side Help Panel -->
            </div>
        </div>
    </section>
</div>
@endsection
