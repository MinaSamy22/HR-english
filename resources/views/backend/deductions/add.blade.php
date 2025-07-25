@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/deduction.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Deduction</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Add</a></li>
                            <li class="breadcrumb-item active">Deduction </li>
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
                                <h3 class="card-title"> Add Deduction </h3>
                            </div>
                            <form class="form-horizontal" method="post" accept="{{ url('admin/deductions/add') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">





                                    {{-- the first spam for the red message
the secound spam that not closed any thing you write in the place of close whill appear in red mess
value = old for not rebeating the input  --}}



                                    <div class="form-group row"> {{-- for make selection --}}
                                        <label class="col-sm-2 col-form-lable"> Employee Name
                                                </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="employee_id" required> {{-- b7ot el esm el f database bta3t hna --}}
                                                <option value=""> Select Employee Name </option>
                                                @foreach ($getUsers as $value_users)
                                                    <option value="{{ $value_users->id }}"> {{ $value_users->name }}
                                                    </option> {{-- name of users table --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Deduction Reason <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('deduction_type') }}"
                                                name="deduction_type" class="form-control" placeholder="Enter Reason">
                                        </div>
                                    </div>




                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Money Deduction <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            <input type="number" value="{{ old('money_deduction') }}"
                                                name="money_deduction" class="form-control" placeholder="Enter money of deduction">
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-lable"> Date <span style="color: red;">
                                            </span></label>
                                        <div class="col-sm-10">
                                            <input type="datetime-local" value="{{ old('created_at') }}" name="created_at"
                                                class="form-control">
                                        </div>
                                    </div>






                                </div>


                                <div class="card-footer">
                                    <a href="{{ url('admin/deductions') }}" class="btn btn-default float-left">Back</a>
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
@endsection
