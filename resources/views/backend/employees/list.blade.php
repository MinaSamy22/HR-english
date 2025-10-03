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
                        <h1>{{ __('h_employee.employees') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-end" style="text-align: right;">

                        <form action="{{ url('admin/employees_export') }}" method="get">
                            <!-- Include other search parameters as hidden fields -->
                            <input type="hidden" name="id" value="{{ Request()->id }}">
                            <input type="hidden" name="name" value="{{ Request()->name }}">
                            <input type="hidden" name="per_page" value="{{ Request()->per_page }}">

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> {{ __('h_employee.export') }}
                            </button>
                        </form>

                        <br>
                        <a href="{{ url('admin/employees/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('h_employee.add_employee') }}
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
                                <h3 class="card-title">{{ __('h_employee.search_employees') }}</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('h_employee.id') }}</label>
                                            <input type="text" name="id" class="form-control"
                                                value="{{ Request()->id }}" placeholder="{{ __('h_employee.id') }}">
                                        </div>
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('h_employee.employee_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name"
                                                class="form-control" placeholder="{{ __('h_employee.name') }}">
                                        </div>
                                        <!-- 🆕 NEW: Branch Name Dropdown Filter - Only for main branch users -->
                                        @if (session('h_employee.branch') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
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
                                                style="margin-right: 10px;" title="{{ __('h_employee.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/employees') }}" class="btn btn-success rounded-pill"
                                                title="{{ __('h_employee.reset') }}">
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
                                <div class="d-flex align-items-center">
                                    <h3 class="card-title mb-2 mb-md-0 me-3">{{ __('h_employee.employees_list') }}</h3>
                                    <form method="get" action="" class="mb-0"
                                        style="margin-left: 15px; margin-right: 15px;">
                                        <!-- 🔧 FIX: Preserve ALL existing search parameters -->
                                        <input type="hidden" name="id" value="{{ Request()->id }}">
                                        <input type="hidden" name="name" value="{{ Request()->name }}">
                                        <input type="hidden" name="filter_branch_id"
                                            value="{{ Request()->filter_branch_id }}">

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
                                <div class="ml-auto">
                                    <a href="{{ url('admin/employees/import') }}" class="btn btn-success mb-0">
                                        <i class="fas fa-file-excel"></i> {{ __('h_employee.import') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('h_employee.id') }}</th>
                                                <th>{{ __('h_employee.name') }}</th>
                                                <th>{{ __('h_employee.email') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_employee.role') }}</th>
                                                <th>{{ __('h_employee.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                <tr>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                    <td>{{ !empty($value->is_role) ? __('h_employee.hrs') : __('h_employee.employee') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('admin/employees/view/' . $value->id) }}"
                                                            class="btn btn-info rounded-pill "
                                                            title="{{ __('h_employee.view') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('admin/employees/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill "
                                                            title="{{ __('h_employee.edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
        class="btn btn-danger rounded-pill delete-btn"
        data-id="{{ $value->id }}"
        data-url="{{ route('employees_delete', $value->id) }}"
        title="{{ __('dashboard.delete') }}">
    <i class="fas fa-trash-alt"></i>
</button>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        {{ __('h_employee.not_found') }}</td>
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
    window.deleteEmployeeTranslations = {
        title: "{{ __('dashboard.delete') }}",
        text: "{{ __('h_employee.delete_confirmation') }}",
        confirm: "{{ __('dashboard.delete') }}",
        cancel: "{{ __('dashboard.cancel') }}",
        deleted: "{{ __('dashboard.deleted') }}!",
        success: "{{ __('dashboard.delete_success') }}",
        error: "{{ __('dashboard.error') }}",
        failed: "{{ __('dashboard.delete_failed') }}"
    };
</script>
<script src="{{ asset('dist/js/employee.js') }}?v=4"></script>
@endsection

@endsection
