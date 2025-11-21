@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_job_history.job_history') }}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_job_history.add') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_job_history.history') }}</li>
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
                                <h3 class="card-title">{{ __('h_job_history.add_history') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" accept="{{ url('admin/job-history/add') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">





                                    {{-- the first spam for the red message
the secound spam that not closed any thing you write in the place of close whill appear in red mess
value = old for not rebeating the input  --}}

                                   <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.employee_name') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('employee_name') }}" name="employee_name"
                                                class="form-control" required placeholder="{{ __('h_job_history.enter_employee_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.start_date') }}</label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('start_date') }}" name="start_date"
                                                class="form-control" placeholder="{{ __('h_job_history.enter_start_date') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.end_date') }}</label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('end_date') }}" name="end_date"
                                                class="form-control" placeholder="{{ __('h_job_history.enter_end_date') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.job_title') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="job_id" required>
                                                <option value="">{{ __('h_job_history.select_job_title') }}</option>
                                                @foreach($getJobs as $value_job) {{-- $getJobs is made in employee controller at add function to link --}}
                                                <option value="{{ $value_job->id }}">{{ $value_job->job_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.department_name') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="department_id" required>
                                                <option value="">{{ __('h_job_history.select_department') }}</option>
                                                @foreach($getDepartments as $value_department) {{-- $getDepartments is made in employee controller at add function to link --}}
                                                <option value="{{ $value_department->id }}">{{ $value_department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <a href="{{ url('admin/job_history') }}" class="btn btn-default float-left">{{ __('h_job_history.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_job_history.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
