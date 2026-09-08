@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('dist/img/overtime.jpg') }}'); background-size: cover; background-position: center;">

        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_early_leave.page_title') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/early-leave') }}">{{ __('h_early_leave.breadcrumb_early_leave') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_early_leave.edit_btn') }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header" style="background-color: #acacac;">
                                <h3 class="card-title">{{ __('h_early_leave.edit_btn') }}</h3>
                            </div>

                            <form class="form-horizontal" method="post"
                                action="{{ url('admin/early-leave/edit/' . $getRecord->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="card-body">

                                    {{-- Employee Name (Non-editable) --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.employee_name') }}
                                        </label>
                                        <div class="col-sm-10 pt-2">
                                            <strong class="text-dark">
                                                {{ $getRecord->user->name ?? ($getRecord->employee->name ?? '') }}
                                            </strong>
                                        </div>
                                    </div>

                                    {{-- Request Date --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.request_date') }}
                                            <span style="color: red;">{{ __('h_early_leave.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="date"
                                                value="{{ \Carbon\Carbon::parse($getRecord->request_date)->format('Y-m-d') }}"
                                                name="request_date" class="form-control" required>
                                        </div>
                                    </div>

                                    {{-- Requested Leave Time --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            {{ __('h_early_leave.leave_time') }}
                                            <span style="color: red;">{{ __('h_early_leave.required_field') }}</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="time"
                                                value="{{ $getRecord->requested_leave_time }}"
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
                                                required>{{ $getRecord->reason }}</textarea>
                                        </div>
                                    </div>



                                </div><!-- /.card-body -->

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
