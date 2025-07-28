@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Employees</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6" style="text-align: right;">


                        <form action="{{ url('admin/employees_export') }}" method="get">
                            <!-- Include other search parameters as hidden fields -->
                            <input type="hidden" name="id" value="{{ Request()->id }}">
                            <input type="hidden" name="name" value="{{ Request()->name }}">

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </form>

                        <br>
                        <a href="{{ url('admin/employees/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> Add Employee
                        </a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <section class="col-md-12">
                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">Search Employees</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>ID</label>
                                            <input type="text" name="id" class="form-control"
                                                value="{{ Request()->id }}" placeholder="ID">
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>Employee Name</label>
                                            <input type="text" value="{{ Request()->name }}" name="name"
                                                class="form-control" placeholder="Name">
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <!-- تأكد إن Font Awesome مضاف في <head> -->
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;" title="Search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/employees') }}" class="btn btn-success rounded-pill"
                                                title="Reset">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="card-title mb-2 mb-md-0">Employees List</h3>
                                <div class="ml-auto">


                                     <a href="{{ url('admin/employees/import') }}" class="btn btn-success mb-0">
                <i class="fas fa-file-excel"></i> Import
            </a>


                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile Mac Address</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->macaddress }}</td>
                                                    <td>{{ !empty($value->is_role) ? 'HR' : 'Employee' }}</td>
                                                    <td>
                                                        <a href="{{ url('admin/employees/view/' . $value->id) }}"
                                                            class="btn btn-info rounded-pill " title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('admin/employees/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill " title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('admin/employees/delete/' . $value->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete?')"
                                                            class="btn btn-danger rounded-pill" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Not Found..</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end p-3">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>

                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
