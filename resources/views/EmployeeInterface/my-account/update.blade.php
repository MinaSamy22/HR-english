@extends('EmployeeInterface.layouts.app')

@section('content')

<div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/my account.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
                <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">My Account Edit</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Setting</a></li>
                            <li class="breadcrumb-item active">My Account</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.Page Header -->

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">My Account</h3>
                            </div>

                            <!-- Form Start -->
                            <form class="form-horizontal" method="POST" action="{{ route('employee.my_account.update') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="card-body">
                                    <!-- Name -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label label-thin">Name <span style="color: red;">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" value="{{ $getRecord->name }}" name="name"
                                                   class="form-control" required placeholder="Enter name">
                                            <span style="color: red;">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label label-thin">Email <span style="color: red;">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="email" value="{{ $getRecord->email }}" name="email"
                                                   class="form-control" required placeholder="Enter email">
                                            <span style="color: red;">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label label-thin">Phone Number</label>
                                        <div class="col-sm-4">
                                            <input type="text" value="{{ $getRecord->phone_number }}" name="phone_number"
                                                   class="form-control" placeholder="Enter phone number">
                                            <span style="color: red;">{{ $errors->first('phone_number') }}</span>
                                        </div>
                                    </div>

                                    <!-- Current Password -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label label-thin">Current Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="current_password" class="form-control"
                                                   placeholder="Enter current password (required if changing password)">
                                            <span style="color: red;">{{ $errors->first('current_password') }}</span>
                                        </div>
                                    </div>

                                    <!-- New Password -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label label-thin">New Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="password" class="form-control"
                                                   placeholder="Enter new password">
                                            <small class="form-text text-muted">(Leave blank if you don't want to change password)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Footer -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right">Update</button>
                                </div>
                            </form>
                            <!-- /.Form End -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.Main Content -->
    </div>
@endsection
