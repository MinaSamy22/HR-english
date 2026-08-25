@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('h_jobs.jobs') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-end" style="text-align: right;">


<form action="{{ url('admin/jobs_export') }}" method="get">
    <input type="hidden" name="job_title" value="{{ Request()->job_title }}">
    <input type="hidden" name="filter_branch_id" value="{{ Request()->filter_branch_id }}">
    <!-- Include other search parameters as hidden fields -->
    <input type="hidden" name="start_date" value="{{ Request()->start_date }}">
    <input type="hidden" name="end_date" value="{{ Request()->end_date }}">
    <input type="hidden" name="per_page" value="{{ Request()->per_page }}">

    <button type="submit" class="btn btn-success">
        <i class="fas fa-file-excel"></i> {{ __('h_jobs.export') }}
    </button>
</form>
                    <br>

                    <a href="{{ url('admin/jobs/add') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-user-plus"></i> {{ __('h_jobs.add_jobs_btn') }}
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
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_jobs.search_jobs') }}</h3>
                            </div>

<form method="get" action="">
    <div class="card-body">
        <div class="row">
            {{-- here the searching options --}}
            {{-- name and value I put the name el mktop fl database --}}
            {{-- md3 for the size of the label md2 small --}}

            <div class="form-group col-md-2">
                <label>{{ __('h_jobs.job_title') }}</label>
                <input type="text" value="{{ Request()->job_title }}" name="job_title" class="form-control" placeholder="{{ __('h_jobs.name_placeholder') }}">
            </div>

            {{-- 🆕 Simple Branch Filter --}}
            @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
            <div class="form-group col-md-2">
                <label>{{ __('h_employee.branch') }}</label>
                <select name="filter_branch_id" class="form-control">
                    <option value="">{{ __('h_employee.all') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ Request()->filter_branch_id == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="form-group col-md-3 d-flex align-items-end">
                <button class="btn btn-primary rounded-pill" type="submit" style="margin-right: 10px;" title="{{ __('h_jobs.search') }}">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ url('admin/jobs') }}" class="btn btn-success rounded-pill" title="{{ __('h_jobs.reset') }}">
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
                                <div class="d-flex align-items-center">
                                    <h3 class="card-title mb-2 mb-md-0 me-3">{{ __('h_jobs.jobs_list') }}</h3>
                                    <form method="get" action="" class="mb-0"
                                        style="margin-left: 15px; margin-right: 15px;">
                                        <!-- 🔧 FIX: Preserve ALL existing search parameters -->
                                        <input type="hidden" name="job_title" value="{{ Request()->job_title }}">
                                        <input type="hidden" name="filter_branch_id"
                                            value="{{ Request()->filter_branch_id }}">
                                        <input type="hidden" name="start_date" value="{{ Request()->start_date }}">
                                        <input type="hidden" name="end_date" value="{{ Request()->end_date }}">

                                        <select name="per_page" class="form-select" onchange="this.form.submit()"
                                            style="min-width: 60px; width: 60px;">
                                            <option value="5" {{ Request()->per_page == 5 ? 'selected' : '' }}>5
                                            </option>
                                            <option value="10" {{ Request()->per_page == 10 ? 'selected' : '' }}>10
                                            </option>
                                            <option value="25" {{ Request()->per_page == 25 ? 'selected' : '' }}>25
                                            </option>
                                            <option value="50" {{ Request()->per_page == 50 ? 'selected' : '' }}>50
                                            </option>
                                            <option value="100" {{ Request()->per_page == 100 ? 'selected' : '' }}>100
                                            </option>
                                            <option value="all" {{ Request()->per_page == 'all' ? 'selected' : '' }}>
                                                {{ __('h_employee.all') }}</option>
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('h_jobs.id') }}</th>
                                                <th>{{ __('h_jobs.job_title') }}</th>
                                                <th>{{ __('h_jobs.min_salary') }}</th>
                                                <th>{{ __('h_jobs.max_salary') }}</th>
                                                <th>{{ __('h_jobs.department_name') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_jobs.created_at') }}</th>
                                                <th>{{ __('h_jobs.action') }}</th>{{-- buttons of crud inside it --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($getRecord as $value )  {{-- forelse insted of foreach it found with col spam and empty for writing not found if not found --}}
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->job_title }}</td>
                                                <td>{{ $value->min_salary }}</td>
                                                <td>{{ $value->max_salary }}</td>
                                                <td>{{ $value->department_name ?? __('h_jobs.na') }}</td>
                                                <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                <td>{{ $value->created_at }}</td>

                                                <td>
                                                    <a href="{{ url('admin/jobs/view/' .$value->id) }}" class="btn btn-info rounded-pill" title="{{ __('h_jobs.view_btn') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ url('admin/jobs/edit/' .$value->id) }}" class="btn btn-primary rounded-pill" title="{{ __('h_jobs.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                     <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                            data-id="{{ $value->id }}" title="{{ __('h_jobs.delete') }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="100%">{{ __('h_jobs.not_found') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination removed as requested --}}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const deleteTranslations = {
        delete: "{{ __('dashboard.delete') }}",
        confirmation: "{{ __('dashboard.delete_confirmation') }}",
        cancel: "{{ __('dashboard.cancel') }}",
        deleted: "{{ __('dashboard.deleted') }}!",
        success: "{{ __('dashboard.delete_success') }}",
        error: "{{ __('dashboard.error') }}",
        failed: "{{ __('dashboard.delete_failed') }}",
        deleteUrl: "{{ url('admin/jobs/delete') }}"
    };
</script>

<script src="{{ asset('dist/js/jobs.js') }}?v=1"></script>
@endsection


@endsection
