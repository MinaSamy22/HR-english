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
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_insurance.insurance') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "><a
                                href="{{ url('admin/insurance') }}">{{ __('h_insurance.insurance') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_insurance.add') }}</li>
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
                                <h3 class="card-title">{{ __('h_insurance.add_insurance') }}</h3>
                            </div>



                            <form class="form-horizontal" method="post" action="{{ url('admin/insurance/add') }}"
                                enctype="multipart/form-data" id="addForm">

                                {{ csrf_field() }}
                                <div class="card-body">

                                    @if ($errors->has('employee_ids'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('employee_ids') }}
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_insurance.employee_name') }} <span style="color: red;">*</span>
                                        </label>

                                        <div class="col-sm-6">
                                            <div id="employee-list"
                                                style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">

                                                <!-- Select All -->
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" id="select-all" class="form-check-input">
                                                    <label for="select-all" class="form-check-label">
                                                        {{ __('h_insurance.select_all') }}
                                                    </label>
                                                </div>

                                                <!-- Employees -->
                                                @foreach ($getEmployees as $employee)
                                                    <div class="form-check mb-2 employee-item">
                                                        <input type="checkbox" name="employee_ids[]"
                                                            value="{{ $employee->id }}" id="employee-{{ $employee->id }}"
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
                                        <label class="col-sm-2 col-form-lable">{{ __('h_insurance.code') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('code') }}" name="code"
                                                class="form-control" required
                                                placeholder="{{ __('h_insurance.enter_code') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_insurance.name') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') }}" name="name"
                                                class="form-control" required
                                                placeholder="{{ __('h_insurance.enter_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_tax.percentage') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('i_percent') }}" name="i_percent"
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
                                                    {{ old('apply_to_payroll') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="apply_yes">{{ __('h_insurance.yes') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="apply_to_payroll"
                                                    id="apply_no" value="0"
                                                    {{ old('apply_to_payroll', '0') == '0' ? 'checked' : '' }}>
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

        {{-- BASIC SALARY --}}
        <div class="form-check mb-2">
            <input type="hidden" name="from_basic" value="0">

            <input class="form-check-input insurance-source"
                   type="checkbox"
                   name="from_basic"
                   value="1"
                   data-target="basic_percent"
                   id="from_basic">

            <label class="form-check-label" for="from_basic">
                {{ __('h_insurance.basic_salary') }}
            </label>

            <input type="number"
                   step="0.01"
                   min="0"
                   name="basic_percent"
                   class="form-control mt-2 d-none insurance-input"
                   id="basic_percent"
                   placeholder="{{ __('h_insurance.enter_percent') }}">
        </div>

        {{-- TRANSPORTATION --}}
        <div class="form-check mb-2">
            <input type="hidden" name="from_transportation" value="0">

            <input class="form-check-input insurance-source"
                   type="checkbox"
                   name="from_transportation"
                   value="1"
                   data-target="transportation_percent"
                   id="from_transportation">

            <label class="form-check-label" for="from_transportation">
                {{ __('h_insurance.transportation_allowance') }}
            </label>

            <input type="number"
                   step="0.01"
                   min="0"
                   name="transportation_percent"
                   class="form-control mt-2 d-none insurance-input"
                   id="transportation_percent">
        </div>

        {{-- HOUSING --}}
        <div class="form-check mb-2">
            <input type="hidden" name="from_housing" value="0">

            <input class="form-check-input insurance-source"
                   type="checkbox"
                   name="from_housing"
                   value="1"
                   data-target="housing_percent"
                   id="from_housing">

            <label class="form-check-label" for="from_housing">
                {{ __('h_insurance.housing_allowance') }}
            </label>

            <input type="number"
                   step="0.01"
                   min="0"
                   name="housing_percent"
                   class="form-control mt-2 d-none insurance-input"
                   id="housing_percent">
        </div>

        {{-- OTHER ALLOWANCES --}}
        <div class="form-check mb-2">
            <input type="hidden" name="from_other_allowances" value="0">

            <input class="form-check-input insurance-source"
                   type="checkbox"
                   name="from_other_allowances"
                   value="1"
                   data-target="other_allowances_percent"
                   id="from_other_allowances">

            <label class="form-check-label" for="from_other_allowances">
                {{ __('h_insurance.other_allowances') }}
            </label>

            <input type="number"
                   step="0.01"
                   min="0"
                   name="other_allowances_percent"
                   class="form-control mt-2 d-none insurance-input"
                   id="other_allowances_percent">
        </div>

        <small class="text-danger d-none" id="insurance-percent-error">
            {{ __('h_insurance.percent_must_equal_total') }}
        </small>

        <div class="mt-2">
            <small class="text-info">
                {{ __('h_insurance.remaining_percent') }}:
                <strong>
                    <span id="insurance-remaining-percent">0</span> %
                </strong>
            </small>
        </div>

    </div>
</div>



                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/insurance') }}"
                                        class="btn btn-default float-left">{{ __('h_insurance.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" id="submitBtn"
                                        class="btn btn-primary float-right">{{ __('h_insurance.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ url('dist/js/insurance.js') }}?v=1"></script>

    <script>
        // Prevent double submit on add form
        const addForm = document.getElementById('addForm');
        const submitBtn = document.getElementById('submitBtn');
        let isSubmitting = false;

        addForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('h_insurance.submit') }}...';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
        });

        // Re-enable button if user goes back
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __('h_insurance.submit') }}';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        });
    </script>
@endsection
