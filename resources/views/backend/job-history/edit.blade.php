@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('h_job_history.job_history') }}</h1>
                    </div><!-- /.col -->
                    <div class="">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('h_job_history.edit_job_history') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_job_history.job_history') }}</li>
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
                                <h3 class="card-title">{{ __('h_job_history.edit_history') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ route('history_update',$getRecord->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.employee_name') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ $getRecord->employee_name }}" name="employee_name"
                                                class="form-control" required placeholder="{{ __('h_job_history.enter_employee_name') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.start_date') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ $getRecord->start_date }}" name="start_date"
                                                class="form-control" required placeholder="{{ __('h_job_history.enter_start_date') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.end_date') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ $getRecord->end_date }}" name="end_date"
                                                class="form-control" required placeholder="{{ __('h_job_history.enter_end_date') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.job_title') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="job_id" required>
                                                @foreach($getJobs as $value_job)
                                                <option {{ ($value_job->id == $getRecord->job_id) ? 'selected' : '' }} value="{{ $value_job->id }}">{{ $value_job->job_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-lable">{{ __('h_job_history.department_name') }} <span style="color: red;">{{ __('h_job_history.required') }}</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="department_id" required>
                                                @foreach($getDepartments as $value_department)
                                                <option {{ ($value_department->id == $getRecord->department_id) ? 'selected' : '' }} value="{{ $value_department->id }}">{{ $value_department->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/job_history') }}" class="btn btn-default float-left">{{ __('h_job_history.back') }}</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">{{ __('h_job_history.update') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
