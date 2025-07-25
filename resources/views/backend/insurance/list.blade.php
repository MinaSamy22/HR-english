@extends('backend.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/additional.jpg') }}'); background-size: cover; background-position: center;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Insurance</h1>
                </div><!-- /.col -->
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ url('admin/insurance/add') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-user-plus"></i> Add Insurance
                    </a>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-md-12">
                    <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="card-header">
                            <h3 class="card-title">Search Insurance</h3>
                        </div>
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label>Insurance code</label>
                                        <input type="text" name="code" class="form-control" value="{{ Request()->code }}" placeholder=" Code">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Insurance Name</label>
                                        <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder=" Name">
                                    </div>
                                    <div class="form-group col-md-3 d-flex align-items-end">
                                        <button class="btn btn-primary rounded-pill" type="submit" style="margin-right: 10px;" title="Search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ url('admin/insurance') }}" class="btn btn-success rounded-pill" title="Reset">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @include('_message')
                    <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <form method="POST" action="{{ route('insurances.toggleCompanyInsurance') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $isInsuranceApplied ? 'danger' : 'success' }}">
                                        {{ $isInsuranceApplied ? 'Don\'t Apply Insurance to Payroll' : 'Apply Insurance to Payroll' }}
                                    </button>
                                </form>
                            </div>

                            <h3 class="card-title text-center flex-grow-1 text-center">Insurance List</h3>

                            <div>
                                <button class="btn btn-danger" id="deleteSelected">Delete Selection</button>
                            </div>
                        </div>


                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Employee Name</th>
                                            <th>Code</th>
                                            <th>Insurance Name</th>
                                            <th>Percentage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                        <tr>
                                            <td><input type="checkbox" class="insuranceCheckbox" value="{{ $value->id }}"></td>
                                            <td>{{ $value->employee_name }}</td>
                                            <td>{{ $value->code }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->percent }}%</td>
                                            <td>
                                                <a href="{{ url('admin/insurance/edit/' .$value->id) }}" class="btn btn-primary rounded-pill" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ url('admin/insurance/delete/' .$value->id) }}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger rounded-pill" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">Not Found..</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div style="padding: 10px; float:right;">
                                {{-- {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!} --}}
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </section>
</div>

<!-- Link to the new JavaScript file -->
<script src="{{ url('dist/js/insurance.js') }}"></script>

@endsection
