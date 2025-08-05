@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ url('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                    <h1 class="mb-0">Administration</h1>
                    
                    <a href="{{ url('admin/administration/add') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-user-plus"></i> Add Administration
                    </a>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <section class="col-md-12">
                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">Search Administration</h3>
                            </div>

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        {{-- here the searching options --}}
                                        {{-- name and value I put the name el mktop fl database --}}
                                        {{-- md3 for the size of the label md2 small --}}

                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>Administration Name </label>
                                            <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder=" Name">
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit" style="margin-right: 10px;" title="Search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/administration') }}" class="btn btn-success rounded-pill" title="Reset">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title"> Administration List </h3>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Administration Name</th>
                                                <th>Code</th>
                                                <th>Manager Name</th>
                                                <th>Action</th>{{-- buttons of crud inside it --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($getRecord as $value )  {{-- forelse insted of foreach it found with col spam and empty for writing not found if not found --}}
                                            <tr>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->code }}</td>

                                                <td>
                                                    {{ $value->manager_name ?? 'N/A' }}<!-- Display N/A if manager is null -->
                                                </td>

                                                <td>
                                                        <a href="{{ url('admin/administration/edit/' .$value->id) }}" class="btn btn-primary rounded-pill" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('admin/administration/delete/' .$value->id) }}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger rounded-pill" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="100%" class="text-center"> Not Found.. </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end p-3">   {{-- for pagination --}}
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
