@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_department.home') }}</h1>

                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item "><a href="{{ url('admin/department') }}">{{ __('h_department.breadcrumb_department') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_department.breadcrumb_add') }}</li>
                        </ol>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_department.add_department') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/department/add') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    {{-- the first spam for the red message
the secound spam that not closed any thing you write in the place of close whill appear in red mess
value = old for not rebeating the input  --}}

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.department_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('department_name') }}" name="department_name"
                                                class="form-control" required placeholder="{{ __('h_department.enter_department_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.manager_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="manager_id" required> {{-- b7ot el esm el f database bta3t hna --}}
                                                <option value="">{{ __('h_department.select_manager_name') }}</option>
                                                @foreach($getManagers as $value_manager) {{-- $getManagers is made in employee controller at add function to link --}}
                                                <option value="{{ $value_manager->id }}">{{ $value_manager->name }}</option> {{-- name of manager table --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.administration_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="administration_id" required> {{-- b7ot el esm el f database bta3t hna --}}
                                                <option value="">{{ __('h_department.select_administration_name') }}</option>
                                                <option value="0">{{ __('h_department.null_option') }}</option>
                                                @foreach($getAdministration as $value_administration) {{-- $getAdministration is made in dep controller at add function to link --}}
                                                <option value="{{ $value_administration->id }}">{{ $value_administration->name }}</option> {{-- name of manager table --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.location') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('location') }}" name="location"
                                                class="form-control" placeholder="{{ __('h_department.enter_location') }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/department') }}" class="btn btn-default float-left">{{ __('h_department.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_department.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
