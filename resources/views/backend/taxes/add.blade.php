@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ url('dist/css/payrollcreate.css') }}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/additional.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_tax.taxes') }}</h1>

                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_tax.add') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_tax.taxes') }}</li>
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
                                <h3 class="card-title">{{ __('h_tax.add_tax') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/taxes/add') }}"
                                enctype="multipart/form-data">

                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_tax.employee_name') }} <span
                                                style="color: red;">{{ __('h_tax.required') }}</span></label>
                                        <div class="col-sm-6">
    <div id="employee-list"
         style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">

        <!-- Select All -->
        <div class="form-check mb-2">
            <input type="checkbox" id="select-all" class="form-check-input">
            <label for="select-all" class="form-check-label">
                {{ __('h_tax.select_all') }}
            </label>
        </div>

        <!-- Employees -->
        @foreach ($getEmployees as $employee)
            <div class="form-check mb-2 employee-item">
                <input type="checkbox" name="employee_ids[]"
                       value="{{ $employee->id }}"
                       id="employee-{{ $employee->id }}"
                       class="form-check-input employee-checkbox">
                <label for="employee-{{ $employee->id }}" class="form-check-label">
                    {{ $employee->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_tax.code') }} <span
                                                style="color: red;">{{ __('h_tax.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('code') }}" name="code"
                                                class="form-control" required placeholder="{{ __('h_tax.enter_code') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_tax.name') }} <span
                                                style="color: red;">{{ __('h_tax.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') }}" name="name"
                                                class="form-control" required placeholder="{{ __('h_tax.enter_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_tax.percentage') }} <span
                                                style="color: red;">{{ __('h_tax.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('percent') }}" name="percent"
                                                class="form-control" required
                                                placeholder="{{ __('h_tax.enter_percent') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_tax.apply_to_payroll') }}</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="apply_to_payroll"
                                                    id="apply_yes" value="1">
                                                <label class="form-check-label"
                                                    for="apply_yes">{{ __('h_tax.yes') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="apply_to_payroll"
                                                    id="apply_no" value="0" checked>
                                                <label class="form-check-label" for="apply_no">{{ __('h_tax.no') }}</label>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/taxes') }}"
                                        class="btn btn-default float-left">{{ __('h_tax.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit"
                                        class="btn btn-primary float-right">{{ __('h_tax.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ url('dist/js/tax.js') }}"></script>
@endsection
