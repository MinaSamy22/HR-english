@extends('backend.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-file-import mr-2"></i> Import Employees</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/employees') }}">Employees</a></li>
                            <li class="breadcrumb-item active">Import Excel</li>
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
                                <h3 class="card-title"><i class="fas fa-upload mr-1"></i> Upload Excel File</h3>
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
                                            <i class="fas fa-file-excel text-success mr-1"></i> Choose Excel File
                                        </label>
                                        <input type="file" name="excel_file" class="form-control"
                                            accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            Required columns:
                                            <strong>
                                                ID, Name, Email, Phone Number, Birth Date, Hire Date, Job Title Id, Salary
                                                Type, Salary, Start Time, End Time, ManagerId, DepartmentID, Role
                                            </strong>
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer bg-light clearfix">
                                    <button type="submit" class="btn btn-primary float-left">
                                        <i class="fas fa-cloud-upload-alt mr-1"></i> Upload file
                                    </button>

                                    <a href="{{ url('admin/employees') }}" class="btn btn-secondary float-right"
                                        style="background-color: rgb(238, 237, 237)">
                                        <i class="fas fa-arrow-left mr-1"></i> Back
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="col-md-4">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Guidelines</h3>
                            </div>
                            <div class="card-body">
                                <div class="callout callout-info">
                                    <h5><i class="fas fa-check-circle"></i> Important Notes</h5>
                                    <p>The Excel file must include the following fields in this **exact order**:</p>
                                    <ul class="mb-1">
                                        <li>ID</li>
                                        <li>Name</li>
                                        <li>Email</li>
                                        <li>Phone Number</li>
                                        <li>Birth Date (dd/mm/yyyy)</li>
                                        <li>Hire Date (dd/mm/yyyy)</li>
                                        <li>Job Title ID</li>
                                        <li>Salary Type</li>
                                        <p class="mt-2 text-muted">Salary type should be 1 or 2 or 3.</p>
                                        <p class="mt-2 text-muted">1 => Monthly.</p>
                                        <p class="mt-2 text-muted">2 => Weekly.</p>
                                        <p class="mt-2 text-muted">2 => Daily.</p>
                                        <li>Salary</li>
                                        <li>Start Time (hh:mm AM/PM)</li>
                                        <li>End Time (hh:mm AM/PM)</li>
                                        <li>ManagerId</li>
                                        <li>DepartmentID</li>
                                        <li>Role (HR or Employee)</li>

                                    </ul>

                                </div>

                                <div class="mt-3">
                                    <a href="{{ asset('dist/sample_employee..xlsx') }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download mr-1"></i> Download Sample Template
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
