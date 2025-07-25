@extends('backend.layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/bio.png') }}'); background-size: cover; background-position: center;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-file-excel mr-2"></i>Biometric Excel Upload</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Biometric</a></li>
                        <li class="breadcrumb-item active">Upload Excel</li>
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
                                Upload Attendance Sheet
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
                                        Choose Excel File
                                    </label>
                                    <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                    <small class="form-text text-muted">File must contain:  Employee Code, Check In, Check Out , Date</small>
                                </div>
                            </div>

                            <div class="card-footer bg-light">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-cloud-upload-alt mr-1"></i>
                                    Upload File
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
                                Help & Instructions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Important Notes:</h5>
                                <p>The Excel file must have the following columns in order: <strong>Employee Code, Check In, Check Out , Date</strong>.</p>
                            </div>

                            <div class="text-muted mt-3">
                                <p><strong>Format Example:</strong></p>
                                <ul class="mb-0">
                                    <li>12, 08:00 PM, 17:00 PM, 2025-06-14</li>
                                    <li>19, 08:10 AM, 17:00 AM, 2025-06-14</li>
                                </ul>
                                <p class="mt-2">Make sure the employee code matches the one in the system.</p>
                            </div>

                            <div class="mt-3">
                                <a href="{{ asset(path: 'dist/sample.xlsx') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download mr-1"></i>
                                    Download Sample File
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
