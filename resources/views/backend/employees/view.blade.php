@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">View Employees</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">View</a></li>
                            <li class="breadcrumb-item active">Employees </li>
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
                                <h3 class="card-title"> View Employees </h3>
                            </div>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="card-body">



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> ID <span style="color: red;">
                                                *</span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->id }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Name <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->name }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Email <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->email }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Phone Number <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->phone_number }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> Birth Date <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->birth_date ? date('d-m-Y', strtotime($getRecord->birth_date)) : 'Not Set' }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> Hire Date <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->hire_date ? date('d-m-Y', strtotime($getRecord->hire_date)) : 'Not Set' }}
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Job Name <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_job_single->job_title) ? $getRecord->get_job_single->job_title : '' }}
                                            {{-- to convert id of job to the job --}}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Salary Type <span
                                                style="color: red;"></span></label>
                                        <div class="col-sm-10 d-flex align-items-center">
                                            @if ($getRecord->salary_type == 1)
                                                Monthly salary
                                            @elseif($getRecord->salary_type == 2)
                                                Weekly wage
                                            @elseif($getRecord->salary_type == 3)
                                                Daily wage
                                            @else
                                                Not Set
                                            @endif
                                        </div>
                                    </div>




                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Salary <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->salary }}
                                        </div>
                                    </div>


<div class="form-group row">
    <label class="col-sm-2 col-form-lable">Salary <span style="color: red;"></span></label>
    <div class="col-sm-10">
        {{ $getRecord->salary }}
    </div>
</div>

@if($getRecord->attachment)
<div class="form-group row">
    <label class="col-sm-2 col-form-lable">Attachment</label>
    <div class="col-sm-10">
        <a href="{{ route('view.attachment', $getRecord->attachment) }}" target="_blank" class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> View PDF
        </a>
        <a href="{{ route('view.attachment', $getRecord->attachment) }}" download class="btn btn-success ml-2">
            <i class="fas fa-download"></i> Download
        </a>
    </div>
</div>
@endif

<div class="form-group row">
    <label class="col-sm-2 col-form-lable">Mobile Mac Address <span style="color: red;"></span></label>
    <div class="col-sm-10">
        {{ $getRecord->macaddress }}
    </div>
</div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Work Start Time </label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->work_start_time)
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_start_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">Not Set</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Work End Time </label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->work_end_time)
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $getRecord->work_end_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">Not Set</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> Free Biometric <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            @if ($getRecord->is_biometric === 1)
                                                Yes
                                            @elseif($getRecord->is_biometric === 0)
                                                No
                                            @else
                                                Not Set
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Manager Name <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_manager_single->name) ? $getRecord->get_manager_single->name : '' }}
                                            {{-- to convert id of job to the job --}}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Department Name <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->get_department_single->department_name) ? $getRecord->get_department_single->department_name : '' }}
                                            {{-- to convert id of job to the job --}}
                                        </div>
                                    </div>




                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Is Role <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ !empty($getRecord->is_role) ? 'HR' : 'Employees' }}
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> Created Date <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->created_at ? date('d-m-Y h:i A', strtotime($getRecord->created_at)) : 'Not Set' }}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> Updated Date <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            {{ $getRecord->updated_at ? date('d-m-Y h:i A', strtotime($getRecord->updated_at)) : 'Not Set' }}
                                        </div>
                                    </div>




                                </div>
                                <div class="card-footer">
                                    <a href="{{ url('admin/employees') }}" class="btn btn-default float-left">Back</a>
                                    {{-- float for the place of the button --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content-body -->

    </div>
@endsection
