@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ url('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                    <h1 class="mb-0">{{ __('h_adminstration.home') }}</h1>

                    <a href="{{ url('admin/administration/add') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-user-plus"></i> {{ __('h_adminstration.add') }}
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
                                <h3 class="card-title">{{ __('h_adminstration.search') }}</h3>
                            </div>

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        {{-- here the searching options --}}
                                        {{-- name and value I put the name el mktop fl database --}}
                                        {{-- md3 for the size of the label md2 small --}}

                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('h_adminstration.administration_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder="{{ __('h_adminstration.search_name_placeholder') }}">
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit" style="margin-right: 10px;" title="{{ __('h_adminstration.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/administration') }}" class="btn btn-success rounded-pill" title="{{ __('h_adminstration.reset') }}">
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
                                <h3 class="card-title">{{ __('h_adminstration.administration_list') }}</h3>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('h_adminstration.administration_name') }}</th>
                                                <th>{{ __('h_adminstration.code') }}</th>
                                                <th>{{ __('h_adminstration.manager_name') }}</th>
                                                <th>{{ __('h_adminstration.action') }}</th>{{-- buttons of crud inside it --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($getRecord as $value )  {{-- forelse insted of foreach it found with col spam and empty for writing not found if not found --}}
                                            <tr>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->code }}</td>

                                                <td>
                                                    {{ $value->manager_name ?? __('h_adminstration.na') }}<!-- Display N/A if manager is null -->
                                                </td>

                                                <td>
                                                        <a href="{{ url('admin/administration/edit/' .$value->id) }}" class="btn btn-primary rounded-pill" title="{{ __('h_adminstration.edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('admin/administration/delete/' .$value->id) }}" onclick="return confirm('{{ __('h_adminstration.delete_confirmation') }}')" class="btn btn-danger rounded-pill" title="{{ __('h_adminstration.delete') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">{{ __('h_adminstration.not_found') }}</td>
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
