@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('h_manager.managers') }}</h1>
                    </div><!-- /.col -->
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_manager.edit') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_manager.managers') }}</li>
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
                                <h3 class="card-title">{{ __('h_manager.edit_managers') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ route('manager_update',$getRecord->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.name') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->name }}" name="name"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.email') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="email" value="{{ $getRecord->email }}" name="email"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_email') }}">
                                            <span style="color:red">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.phone_number') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->phone_number }}" name="phone_number"
                                                class="form-control" placeholder="{{ __('h_manager.enter_phone_number') }}">
                                            <span style="color:red">{{ $errors->first('phone_number') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.hire_date') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ $getRecord->hire_date }}" name="hire_date"
                                                class="form-control" required placeholder="{{ __('h_manager.date_format_placeholder') }}">
                                            <span style="color:red">{{ $errors->first('hire_date') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.salary') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->salary }}" name="salary"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_salary') }}">
                                            <span style="color:red">{{ $errors->first('salary') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.commission_pct') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->commission_pct }}" name="commission_pct"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_commission_pct') }}">
                                            <span style="color:red">{{ $errors->first('commission_pct') }}</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/manager') }}" class="btn btn-default float-left">{{ __('h_manager.back') }}</a>
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_manager.update') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
