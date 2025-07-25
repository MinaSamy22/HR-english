@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/vacation.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Vacations</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6" style="text-align: right;">
                        <a href="{{ url('admin/vacations/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> Add Vacation
                        </a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <section class="col-md-12">
                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>Employee Name</label>
                                            <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder="Enter Name">
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit" style="margin-right: 10px;" title="Search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/vacations') }}" class="btn btn-success rounded-pill" title="Reset">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="card-title mb-2 mb-md-0">Vacation List</h3>
                                <div class="ml-auto">
                                    <button class="btn btn-danger" id="deleteSelected">Delete Selection</button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>ID</th>
                                                <th>Employee Name</th>
                                                <th>Vacation Type</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Total Days</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $vacation)
                                                <tr>
                                                    <td><input type="checkbox" class="vacationCheckbox" value="{{ $vacation->id }}"></td>
                                                    <td>{{ $vacation->id }}</td>
                                                    <td>{{ $vacation->name }}</td>
                                                    <td>{{ $vacation->vacation_type }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($vacation->start_date)) }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($vacation->end_date)) }}</td>
                                                    <td>{{ $vacation->total }}</td>

                                                    <td>
                                                        <a href="{{ url('admin/vacations/delete/' .$vacation->id) }}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger rounded-pill" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">No vacations found.</td>
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


<!-- Link to the new JavaScript file -->
<script src="{{ url('dist/js/vacation.js') }}"></script>


@endsection
