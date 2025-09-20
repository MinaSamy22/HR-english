@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('h_insurance.insurance') }}</h1>
                    </div><!-- /.col -->
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_insurance.edit') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_insurance.insurance') }}</li>
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
                                <h3 class="card-title">{{ __('h_insurance.edit_insurance') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ route('insurance_update',$getRecord->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_insurance.insurance_name') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->name }}" name="name"
                                                class="form-control" required placeholder="{{ __('h_insurance.enter_insurance_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_insurance.insurance_code') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->code }}" name="code"
                                                class="form-control" required placeholder="{{ __('h_insurance.enter_insurance_code') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_insurance.percentage') }} <span style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ $getRecord->percent }}" name="percent"
                                                class="form-control" required placeholder="{{ __('h_insurance.enter_percent') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
    <label class="col-sm-2 col-form-lable">{{ __('h_insurance.apply_to_payroll') }}</label>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="apply_to_payroll" id="apply_yes"
                value="1" {{ $getRecord->apply_to_payroll == 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="apply_yes">{{ __('h_insurance.yes') }}</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="apply_to_payroll" id="apply_no"
                value="0" {{ $getRecord->apply_to_payroll == 0 ? 'checked' : '' }}>
            <label class="form-check-label" for="apply_no">{{ __('h_insurance.no') }}</label>
        </div>
    </div>
</div>


                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/insurance') }}" class="btn btn-default float-left">{{ __('h_insurance.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_insurance.update') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
