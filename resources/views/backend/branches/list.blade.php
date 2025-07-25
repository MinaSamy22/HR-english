@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Branches</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ url('admin/branches/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-code-branch"></i> Add Branch
                        </a>

                        <a href="{{ route('branches.transfer.form') }}" class="btn btn-warning rounded-pill">
                            <i class="fas fa-random"></i> Transfer Employees
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <section class="col-md-12">
                        @include('_message')

                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">Search Branch</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>Branch Name</label>
                                            <input type="text" value="{{ request()->get('name') }}" name="name"
                                                class="form-control" placeholder="Branch Name">
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill mr-2" type="submit" title="Search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/branches') }}" class="btn btn-success rounded-pill"
                                                title="Reset">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">Branch List</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Location</th>
                                                <th>Main Branch</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->location ?? 'N/A' }}</td>
                                                    <td>{{ $value->is_main ? 'Yes' : 'No' }}</td>
                                                    <td>
                                                        <a href="{{ url('admin/branches/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('admin/branches/delete/' . $value->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete this branch?')"
                                                            class="btn btn-danger rounded-pill" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" class="text-center">No branches found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end p-3">
                                    {!! $getRecord->appends(request()->except('page'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
