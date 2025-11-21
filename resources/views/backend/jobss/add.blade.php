@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_jobs.jobs') }}</h1>

                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_jobs.add') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_jobs.jobs') }}</li>
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
                                <h3 class="card-title">{{ __('h_jobs.add_jobs') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/jobs/add') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.job_title') }} <span style="color: red;">{{ __('h_jobs.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('job_title') }}" name="job_title"
                                                class="form-control" required placeholder="{{ __('h_jobs.enter_job_title') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.min_salary') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('min_salary') }}" name="min_salary"
                                                class="form-control" placeholder="{{ __('h_jobs.enter_min_salary') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.max_salary') }} <span style="color: red;">{{ __('h_jobs.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('max_salary') }}" name="max_salary"
                                                class="form-control" required placeholder="{{ __('h_jobs.enter_max_salary') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.department_name') }} <span style="color: red;">{{ __('h_jobs.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="department_id" required>
                                                <option value="">{{ __('h_jobs.select_department') }}</option>
                                                @foreach($getDepartments as $value_department) {{-- $getDepartments is made in job controller at add function to link --}}
                                                <option value="{{ $value_department->id }}">{{ $value_department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/jobs') }}" class="btn btn-default float-left">{{ __('h_jobs.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_jobs.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
