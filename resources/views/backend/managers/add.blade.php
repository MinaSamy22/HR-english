@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_manager.managers') }}</h1>

                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item "><a href="{{ url('admin/manager') }}">{{ __('h_manager.managers') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_manager.add') }}</li>
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
                                <h3 class="card-title">{{ __('h_manager.add_managers') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/manager/add') }}"
                                enctype="multipart/form-data" id="addForm">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.name') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') }}" name="name"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.email') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="email" value="{{ old('email') }}" name="email"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_email') }}">
                                            <span style="color:red">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.phone_number') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('phone_number') }}" name="phone_number"
                                                class="form-control" placeholder="{{ __('h_manager.enter_phone_number') }}">
                                            <span style="color:red">{{ $errors->first('phone_number') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.hire_date') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('hire_date') }}" name="hire_date"
                                                class="form-control" required placeholder="{{ __('h_manager.date_format_placeholder') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.salary') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('salary') }}" name="salary"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_salary') }}">
                                            <span style="color:red">{{ $errors->first('salary') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_manager.commission_pct') }} <span style="color: red;">{{ __('h_manager.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('commission_pct') }}" name="commission_pct"
                                                class="form-control" required placeholder="{{ __('h_manager.enter_commission_pct') }}">
                                            <span style="color:red">{{ $errors->first('commission_pct') }}</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/manager') }}" class="btn btn-default float-left">{{ __('h_manager.back') }}</a>
                                    <button type="submit" id="submitBtn" class="btn btn-primary float-right">{{ __('h_manager.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('h_manager.submit') }}...';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
        });

        // Re-enable button if user goes back
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __('h_manager.submit') }}';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        });
    </script>
@endsection
