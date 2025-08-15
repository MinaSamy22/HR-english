@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('h_department.home') }}</h1>
                    </div><!-- /.col -->
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_department.breadcrumb_edit') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_department.breadcrumb_department') }}</li>
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
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_department.edit_department') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ route('department_update',$getRecord->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    {{-- the first spam for the red message
the secound spam that not closed any thing you write in the place of close whill appear in red mess
value = old for not rebeating the input  --}}

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.department_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->department_name }}" name="department_name"
                                                class="form-control" required placeholder="{{ __('h_department.enter_department_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.manager_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="manager_id" required>
                                                @foreach($getManagers as $value_manager)
                                                <option {{ ($value_manager->id == $getRecord->manager_id) ? 'selected' : '' }} value="{{ $value_manager->id }}">{{ $value_manager->name }}</option>  {{-- elnos al awlany fe el code da ms2ol 3n ezhar data al 2dema --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.administration_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="administration_id" required>
                                                @foreach($getAdministration as $value_administration)
                                                <option {{ ($value_administration->id == $getRecord->administration_id) ? 'selected' : '' }} value="{{ $value_administration->id }}">{{ $value_administration->name }}</option>  {{-- elnos al awlany fe el code da ms2ol 3n ezhar data al 2dema --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_department.location') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->location }}" name="location"
                                                class="form-control" required placeholder="{{ __('h_department.enter_location') }}">
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/department') }}" class="btn btn-default float-left">{{ __('h_department.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_department.update') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
