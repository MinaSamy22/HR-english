@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                    <h1>{{ __('h_department.home') }}</h1>
                    <a href="{{ url('admin/department/add') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-user-plus"></i> {{ __('h_department.add') }}
                    </a>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <section class="col-md-12">
                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_department.search') }}</h3>
                            </div>

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        {{-- here the searching options --}}
                                        {{-- name and value I put the name el mktop fl database --}}
                                        {{-- md3 for the size of the label md2 small --}}

                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('h_department.department_name') }}</label>
                                            <input type="text" value="{{ Request()->department_name }}"
                                                name="department_name" class="form-control"
                                                placeholder="{{ __('h_department.search_name_placeholder') }}">
                                        </div>

                                        <!-- 🆕 NEW: Branch Name Dropdown Filter - Only for main branch users -->
                                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                            <div class="form-group col-md-2 col-sm-6">
                                                <label>{{ __('h_employee.branch') }}</label>
                                                <select name="filter_branch_id" class="form-control">
                                                    <option value="">{{ __('h_employee.all') }}</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ Request()->filter_branch_id == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                            @if ($branch->is_main == 1)
                                                                ({{ __('Main') }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;" title="{{ __('h_department.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/department') }}" class="btn btn-success rounded-pill"
                                                title="{{ __('h_department.reset') }}">
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
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_department.department_list') }}</h3>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('h_department.id') }}</th>
                                                <th>{{ __('h_department.department_name') }}</th>
                                                <th>{{ __('h_department.administration_name') }}</th>
                                                <th>{{ __('h_department.manager_name') }}</th>
                                                <th>{{ __('h_department.location') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_department.action') }}</th>{{-- buttons of crud inside it --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                {{-- forelse insted of foreach it found with col spam and empty for writing not found if not found --}}
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->department_name }}</td>
                                                    <td>
                                                        {{ $value->administration_name ?? __('h_department.na') }}<!-- 34an de tzhar lazm n3dl querry el model Display N/A if administration is null -->
                                                    </td>
                                                    <td>
                                                        {{ $value->manager_name ?? __('h_department.na') }}<!-- Display N/A if manager is null -->
                                                    </td>
                                                    <td>{{ $value->location }}</td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>


                                                    <td>
                                                        <a href="{{ url('admin/department/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill"
                                                            title="{{ __('h_department.edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                         <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                                data-id="{{ $value->id }}" title="{{ __('h_department.delete') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" class="text-center">
                                                        {{ __('h_department.not_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end p-3"> {{-- for pagination --}}
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


@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Pass translations & delete URL dynamically to JS
    const deleteTranslations = {
        delete: "{{ __('dashboard.delete') }}",
        confirmation: "{{ __('dashboard.delete_confirmation') }}",
        cancel: "{{ __('dashboard.cancel') }}",
        deleted: "{{ __('dashboard.deleted') }}!",
        success: "{{ __('dashboard.delete_success') }}",
        error: "{{ __('dashboard.error') }}",
        failed: "{{ __('dashboard.delete_failed') }}",
        deleteUrl: "{{ url('admin/department/delete') }}"
    };
</script>

<script src="{{ asset('dist/js/department.js') }}?v=1"></script>
@endsection
