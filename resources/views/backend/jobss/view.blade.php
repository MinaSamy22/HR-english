@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h1 class="m-0 mt-3 mb-3">{{ __('h_jobs.view_jobs') }}</h1>


                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/jobs') }}">{{ __('h_jobs.jobs') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_jobs.view') }}</li>
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
                                <h3 class="card-title">{{ __('h_jobs.view_jobs') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.id') }} <span style="color: red;">{{ __('h_jobs.required') }}</span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->id }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.job_title') }}</label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->job_title }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.min_salary') }}</label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->min_salary }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.max_salary') }}</label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->max_salary }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.department_name') }}</label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_department_single->department_name) ? $getRecord->get_department_single->department_name : '' }} {{-- to convert id of department to the department name --}}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.created_date') }}</label>
                                        <div class="col-sm-10">
                                            {{ date('d-m-Y  H:i A', strtotime($getRecord->created_at)) }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_jobs.updated_date') }}</label>
                                        <div class="col-sm-10">
                                            {{ date('d-m-Y H:i A', strtotime($getRecord->updated_at)) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <a href="{{ url('admin/jobs') }}" class="btn btn-default float-left">{{ __('h_jobs.back') }}</a>
                                    {{-- float for the place of the button --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>   <!-- /.content-body -->
    </div>
@endsection
