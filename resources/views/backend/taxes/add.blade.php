@extends('backend.layouts.app')
@section('content')

<link rel="stylesheet" href="{{ url('dist/css/payrollcreate.css') }}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/additional.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Taxes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Add</a></li>
                            <li class="breadcrumb-item active">Tax </li>
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
                                <h3 class="card-title"> Add Tax </h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/taxes/add') }}" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                <div class="card-body">





                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Employee Name <span style="color: red;">*</span></label>
                                        <div class="col-sm-6">
                                            <div class="checkbox-box">
                                                <div class="checkbox-item">
                                                    <input type="checkbox" id="select-all">
                                                    <label for="select-all">Select All</label>
                                                </div>

                                                @foreach ($getEmployees as $employee)
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                            id="employee-{{ $employee->id }}" class="employee-checkbox">
                                                        <label for="employee-{{ $employee->id }}">{{ $employee->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>







                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Code <span style="color: red;">
                                                *</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('code') }}" name="code"
                                                class="form-control" required placeholder="Enter Code">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Name <span style="color: red;">
                                                *</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') }}" name="name"
                                                class="form-control" required placeholder="Enter Name">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Percentage <span style="color: red;">
                                                *</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('percent') }}" name="percent"
                                                class="form-control" required placeholder="Enter Percent">
                                        </div>
                                    </div>








                                </div>


                                <div class="card-footer">
                                    <a href="{{ url('admin/taxes') }}" class="btn btn-default float-left">Back</a>
                                    {{-- float for the place of the button --}}
                                    <button type="submit" class="btn btn-primary float-right">Submit</button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ url('dist/js/tax.js') }}"></script>
    <script src="{{ url('dist/js/taxcreate.js') }}"></script>

@endsection
