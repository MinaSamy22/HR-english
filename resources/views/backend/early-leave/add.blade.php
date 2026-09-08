@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('dist/img/overtime.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_early_leave.page_title') }}</h1>

                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('h_early_leave.breadcrumb_add') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_early_leave.breadcrumb_early_leave') }}</li>
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
                            <div class="card-header" style="background-color: #acacac;">
                                <h3 class="card-title">{{ __('h_early_leave.add_btn') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/early-leave/add') }}"
                                enctype="multipart/form-data" id="addForm">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    {{-- Employee --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.employee_name') }}
                                            <span style="color: red;">{{ __('h_early_leave.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="employee_id" required>
                                                <option value="">{{ __('h_early_leave.select_employee') }}</option>
                                                @foreach ($getUsers as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('employee_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Request Date --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.request_date') }}
                                            <span style="color: red;">{{ __('h_early_leave.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('request_date') }}" name="request_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    {{-- Requested Leave Time --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.leave_time') }}
                                            <span style="color: red;">{{ __('h_early_leave.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="time" value="{{ old('requested_leave_time') }}"
                                                name="requested_leave_time" class="form-control" required>
                                        </div>
                                    </div>

                                    {{-- Reason --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.reason') }}
                                            <span style="color: red;">{{ __('h_early_leave.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea name="reason" class="form-control" rows="3"
                                                placeholder="{{ __('h_early_leave.reason_placeholder') }}"
                                                required>{{ old('reason') }}</textarea>
                                        </div>
                                    </div>



                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/early-leave') }}"
                                        class="btn btn-default float-left">{{ __('h_early_leave.back_btn') }}</a>
                                    <button type="submit" id="submitBtn" class="btn btn-white float-right"
                                        style="background-color: #acacac;">{{ __('h_early_leave.submit_btn') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
