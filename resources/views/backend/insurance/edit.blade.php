@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_insurance.insurance') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "><a
                                href="{{ url('admin/insurance') }}">{{ __('h_insurance.insurance') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_insurance.edit') }}</li>
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
                                <h3 class="card-title">{{ __('h_insurance.edit_insurance') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post"
                                action="{{ route('insurance_update', $getRecord->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <!-- Payment Rules Banner -->
                                    <div class="card-body">
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>{{ __('h_payments.note_title') }}</strong>
                                            <ul class="mb-0 mt-2" style="list-style: none; padding-right: 0;">
                                                <li>{{ __('h_insurance.update_code_first') }}</li>
                                            </ul>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('h_tax.employee_name') }}
                                            </label>

                                            <div class="col-sm-10 pt-2">
                                                <strong class="text-dark">
                                                    {{ $getRecord->employee->name }}
                                                </strong>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-lable">{{ __('h_insurance.insurance_name') }}
                                                <span style="color: red;">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{ $getRecord->name }}" name="name"
                                                    class="form-control" required
                                                    placeholder="{{ __('h_insurance.enter_insurance_name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-lable">{{ __('h_insurance.insurance_code') }}
                                                <span style="color: red;">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{ $getRecord->code }}" name="code"
                                                    class="form-control" required
                                                    placeholder="{{ __('h_insurance.enter_insurance_code') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-lable">{{ __('h_tax.percentage') }} <span
                                                    style="color: red;">{{ __('h_tax.required') }}</span></label>
                                            <div class="col-sm-10">
                                                <input type="number" value="{{ $getRecord->i_percent }}" name="i_percent"
                                                    class="form-control" required
                                                    placeholder="{{ __('h_insurance.enter_percent') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-lable">{{ __('h_insurance.apply_to_payroll') }}</label>
                                            <div class="col-sm-10">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="apply_to_payroll"
                                                        id="apply_yes" value="1"
                                                        {{ $getRecord->apply_to_payroll == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="apply_yes">{{ __('h_insurance.yes') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="apply_to_payroll"
                                                        id="apply_no" value="0"
                                                        {{ $getRecord->apply_to_payroll == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="apply_no">{{ __('h_insurance.no') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">
                                                {{ __('h_insurance.deduct_from') }}
                                            </label>

                                            <div class="col-sm-10">

                                                {{-- BASIC --}}
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="from_basic" value="0">

                                                    <input class="form-check-input insurance-source" type="checkbox"
                                                        name="from_basic" value="1" data-target="basic_percent"
                                                        id="from_basic" {{ $getRecord->from_basic ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="from_basic">
                                                        {{ __('h_insurance.basic_salary') }}
                                                    </label>

                                                    <input type="number" step="0.01" min="0" name="basic_percent"
                                                        class="form-control mt-2 insurance-input {{ $getRecord->from_basic ? '' : 'd-none' }}"
                                                        id="basic_percent" value="{{ $getRecord->basic_percent }}">
                                                </div>

                                                {{-- TRANSPORTATION --}}
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="from_transportation" value="0">

                                                    <input class="form-check-input insurance-source" type="checkbox"
                                                        name="from_transportation" value="1"
                                                        data-target="transportation_percent" id="from_transportation"
                                                        {{ $getRecord->from_transportation ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="from_transportation">
                                                        {{ __('h_insurance.transportation_allowance') }}
                                                    </label>

                                                    <input type="number" step="0.01" min="0"
                                                        name="transportation_percent"
                                                        class="form-control mt-2 insurance-input {{ $getRecord->from_transportation ? '' : 'd-none' }}"
                                                        id="transportation_percent"
                                                        value="{{ $getRecord->transportation_percent }}">
                                                </div>

                                                {{-- HOUSING --}}
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="from_housing" value="0">

                                                    <input class="form-check-input insurance-source" type="checkbox"
                                                        name="from_housing" value="1" data-target="housing_percent"
                                                        id="from_housing" {{ $getRecord->from_housing ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="from_housing">
                                                        {{ __('h_insurance.housing_allowance') }}
                                                    </label>

                                                    <input type="number" step="0.01" min="0"
                                                        name="housing_percent"
                                                        class="form-control mt-2 insurance-input {{ $getRecord->from_housing ? '' : 'd-none' }}"
                                                        id="housing_percent" value="{{ $getRecord->housing_percent }}">
                                                </div>

                                                {{-- OTHER --}}
                                                <div class="form-check mb-2">
                                                    <input type="hidden" name="from_other_allowances" value="0">

                                                    <input class="form-check-input insurance-source" type="checkbox"
                                                        name="from_other_allowances" value="1"
                                                        data-target="other_allowances_percent" id="from_other_allowances"
                                                        {{ $getRecord->from_other_allowances ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="from_other_allowances">
                                                        {{ __('h_insurance.other_allowances') }}
                                                    </label>

                                                    <input type="number" step="0.01" min="0"
                                                        name="other_allowances_percent"
                                                        class="form-control mt-2 insurance-input {{ $getRecord->from_other_allowances ? '' : 'd-none' }}"
                                                        id="other_allowances_percent"
                                                        value="{{ $getRecord->other_allowances_percent }}">
                                                </div>

                                                <small class="text-danger d-none" id="insurance-percent-error">
                                                    {{ __('h_insurance.percent_must_equal_total') }}
                                                </small>

                                                {{-- <div class="mt-2">
                                                    <small class="text-info">
                                                        {{ __('h_insurance.remaining_percent') }}:
                                                        <strong>
                                                            <span id="insurance-remaining-percent">0</span> %
                                                        </strong>
                                                    </small>
                                                </div> --}}

                                            </div>
                                        </div>



                                    </div>

                                    <div class="card-footer">
                                        <a href="{{ url('admin/insurance') }}"
                                            class="btn btn-default float-left">{{ __('h_insurance.back') }}</a>
                                        {{-- float for the place of the button --}}
                                        <button type="submit"
                                            class="btn btn-primary float-right">{{ __('h_insurance.update') }}</button>
                                    </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ url('dist/js/insurance.js') }}?v=1"></script>
@endsection
