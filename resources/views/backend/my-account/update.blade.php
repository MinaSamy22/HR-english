@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/my account.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0"> {{ __('E_myaccount.My Account Edit') }} </h1>
                    </div>
                    <div class="">
                    <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"> {{ __('E_myaccount.Setting') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('E_myaccount.My Account') }}</li>
                            <!-- Dark Mode Toggle Button -->
                            <li class="breadcrumb-item">
                                <a class="dark-mode-toggle" role="button" style="cursor: pointer;">
                                    <i class="fa fa-moon" style="color: #908a8a;"></i>
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"> {{ __('E_myaccount.My Account') }} </h3>
                            </div>

                            {{-- da form el saf7a bdelo url 34an a7oto fl routes get and post --}}
                            <form class="form-horizontal" method="post" action="{{ url('admin/my_account/update') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    {{-- Name input --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable label-thin"> {{ __('E_myaccount.Name') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" value="{{ $getRecord->name }}" name="name"
                                                class="form-control" required placeholder="Enter name">
                                            <span style="color: red;"> {{ $errors->first('name') }} </span>
                                        </div>
                                    </div>

                                    {{-- Email input --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable label-thin"> {{ __('E_myaccount.Email') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="email" value="{{ $getRecord->email }}" name="email"
                                                class="form-control" required placeholder="Enter Email">
                                            <span style="color: red;"> {{ $errors->first('email') }} </span>
                                        </div>
                                    </div>

                                    {{-- Phone Number input --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable label-thin"> {{ __('E_myaccount.Phone Number') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" value="{{ $getRecord->phone_number }}" name="phone_number"
                                                class="form-control" required placeholder="Enter Phone Number">
                                            <span style="color: red;"> {{ $errors->first('phone_number') }} </span>
                                        </div>
                                    </div>

                                    {{-- Current Password input (required only if changing password) --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable label-thin">{{ __('E_myaccount.Current Password') }}</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="current_password" class="form-control"
                                                placeholder="Enter current password">
                                            <span style="color: red;"> {{ $errors->first('current_password') }} </span>
                                        </div>
                                    </div>

                                    {{-- Password input --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable label-thin"> {{ __('E_myaccount.New Password') }} </label>
                                        <div class="col-sm-4">
                                            <input type="password" value="" name="password" class="form-control"
                                                placeholder="Enter the new Password">
                                            <small class="form-text text-muted">{{ __('E_myaccount.(Leave blank if you dont want to change password)') }}</small>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('E_myaccount.Update') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
