@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/my account.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Company Information</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Setting</a></li>
                            <li class="breadcrumb-item active">Company Information</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card card-info shadow-lg">
                            <div class="card-header bg-gradient-info">
                                <h3 class="card-title text-white">
                                    <i class="fas fa-building mr-2"></i>
                                    Company Information
                                </h3>
                            </div>

                            <form class="form-horizontal" method="post" action="{{ url('admin/company-info/update') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="row">
                                        <!-- Left Column - Form Fields -->
                                        <div class="col-md-8">
                                            {{-- Company Name --}}
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label label-thin font-weight-bold">
                                                    <i class="fas fa-building text-info mr-1"></i>
                                                    Company Name <span style="color: red;">*</span>
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" value="{{ $getRecord->company->name }}"
                                                        name="company_name" class="form-control form-control-lg" required
                                                        placeholder="Enter company name">
                                                    <span style="color: red;">{{ $errors->first('company_name') }}</span>
                                                </div>
                                            </div>

                                            {{-- Address --}}
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label label-thin font-weight-bold">
                                                    <i class="fas fa-map-marker-alt text-info mr-1"></i>
                                                    Address
                                                </label>
                                                <div class="col-sm-9">
                                                    <textarea name="company_address" class="form-control" rows="3"
                                                        placeholder="Enter company address">{{ $getRecord->company->address }}</textarea>
                                                    <span style="color: red;">{{ $errors->first('company_address') }}</span>
                                                </div>
                                            </div>

                                            {{-- Country --}}
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label label-thin font-weight-bold">
                                                    <i class="fas fa-globe text-info mr-1"></i>
                                                    Country
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" value="{{ $getRecord->company->country }}"
                                                        name="company_country" class="form-control" placeholder="Enter country">
                                                    <span style="color: red;">{{ $errors->first('company_country') }}</span>
                                                </div>
                                            </div>

                                            {{-- Phone Number --}}
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label label-thin font-weight-bold">
                                                    <i class="fas fa-phone text-info mr-1"></i>
                                                    Phone Number
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" value="{{ $getRecord->company->phone_number }}"
                                                        name="company_phone" class="form-control" placeholder="Enter phone number">
                                                    <span style="color: red;">{{ $errors->first('company_phone') }}</span>
                                                </div>
                                            </div>

                                            {{-- Commercial Registration --}}
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label label-thin font-weight-bold">
                                                    <i class="fas fa-certificate text-info mr-1"></i>
                                                    Commercial Registration
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" value="{{ $getRecord->company->commercial_registration }}"
                                                        name="commercial_registration" class="form-control"
                                                        placeholder="Enter commercial registration">
                                                    <span style="color: red;">{{ $errors->first('commercial_registration') }}</span>
                                                </div>
                                            </div>

                                            {{-- Tax Card --}}
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label label-thin font-weight-bold">
                                                    <i class="fas fa-file-invoice-dollar text-info mr-1"></i>
                                                    Tax Card
                                                </label>
                                                <div class="col-sm-9">
                                                    <input type="text" value="{{ $getRecord->company->tax_card }}"
                                                        name="tax_card" class="form-control" placeholder="Enter tax card">
                                                    <span style="color: red;">{{ $errors->first('tax_card') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column - Company Logo -->
                                        <div class="col-md-4">
                                            <div class="logo-section">
                                                <div class="card card-outline card-info h-100">
                                                    <div class="card-header text-center bg-light">
                                                        <h5 class="mb-0">
                                                            <i class="fas fa-image text-info mr-2"></i>
                                                            Company Logo
                                                        </h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <!-- Logo Preview Area -->
                                                        <div class="logo-preview-container mb-3">
                                                            @if($getRecord->company->logo)
                                                                <div class="current-logo-wrapper">
                                                                    <img src="{{ Auth::user()->company->logo_url }}"
                                                                         alt="Company Logo"
                                                                         class="company-logo-preview img-fluid rounded shadow-sm"
                                                                         style="max-width: 200px; max-height: 150px; border: 3px solid #17a2b8;">
                                                                    <p class="text-muted small mt-2">
                                                                        <i class="fas fa-check-circle text-success mr-1"></i>
                                                                        Current Logo
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="no-logo-placeholder">
                                                                    <div class="placeholder-box d-flex align-items-center justify-content-center"
                                                                         style="width: 200px; height: 150px; border: 2px dashed #dee2e6; background-color: #f8f9fa; margin: 0 auto;">
                                                                        <div class="text-center">
                                                                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                                                            <p class="text-muted mt-2 mb-0">No Logo</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- File Upload Section -->
                                                        <div class="upload-section">
                                                            <div class="custom-file">
                                                                <input type="file" name="company_logo" class="custom-file-input"
                                                                       id="logoUpload" accept="image/*">
                                                                <label class="custom-file-label" for="logoUpload">
                                                                    Choose logo file...
                                                                </label>
                                                            </div>
                                                            <span style="color: red;">{{ $errors->first('company_logo') }}</span>

                                                            <div class="upload-info mt-2">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-info-circle mr-1"></i>
                                                                    Supported formats: JPG, PNG, GIF
                                                                </small>
                                                                <br>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-weight-hanging mr-1"></i>
                                                                    Maximum size: 2MB
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer bg-light">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary btn-md px-4">
                                                <i class="fas fa-save mr-2"></i>
                                                Update Information
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .logo-section {
            position: sticky;
            top: 20px;
        }

        .company-logo-preview {
            transition: transform 0.3s ease;
        }

        .company-logo-preview:hover {
            transform: scale(1.05);
        }

        .placeholder-box {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .placeholder-box:hover {
            background-color: #e9ecef;
            border-color: #17a2b8;
        }

        .custom-file-label::after {
            content: "Browse";
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .upload-info {
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #17a2b8;
        }

        .current-logo-wrapper {
            position: relative;
        }

        .label-thin {
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .logo-section {
                position: static;
                margin-top: 20px;
            }
        }
    </style>

    <script>
        // Update file input label when file is selected
        document.getElementById('logoUpload').addEventListener('change', function(e) {
            var fileName = e.target.files[0]?.name || 'Choose logo file...';
            var label = document.querySelector('label[for="logoUpload"]');
            label.textContent = fileName;
        });
    </script>
@endsection
