@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('dist/img/attendance.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('dashboard.attendance_report') }}</h1>
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
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row align-items-end">
                                        <div class="form-group col-md-2">
                                            <label>{{ __('dashboard.employee_name') }}</label>
                                            <input type="text" value="{{ Request::get('employee_name') }}"
                                                name="employee_name" class="form-control"
                                                placeholder="{{ __('dashboard.enter_name') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>{{ __('dashboard.attendance_type') }}</label>
                                            <select class="form-control" name="attendance_type">
                                                <option value="">{{ __('dashboard.select') }}</option>
                                                <option {{ Request::get('attendance_type') == 1 ? 'selected' : '' }}
                                                    value="1">{{ __('dashboard.present') }}</option>
                                                <option {{ Request::get('attendance_type') == 2 ? 'selected' : '' }}
                                                    value="2">{{ __('dashboard.late') }}</option>
                                                <option {{ Request::get('attendance_type') == 3 ? 'selected' : '' }}
                                                    value="3">{{ __('dashboard.absent') }}</option>
                                                <option {{ Request::get('attendance_type') == 4 ? 'selected' : '' }}
                                                    value="4">{{ __('dashboard.half_day') }}</option>
                                            </select>
                                        </div>

                                        {{-- 🆕 Branch Filter --}}
                                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                            <div class="form-group col-md-2">
                                                <label>{{ __('h_employee.branch') }}</label>
                                                <select name="filter_branch_id" class="form-control">
                                                    <option value="">{{ __('h_employee.all') }}</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ Request()->filter_branch_id == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-2">
                                            <label>{{ __('dashboard.date') }}</label> 
                                            <input type="date" value="{{ Request()->start_date }}" name="start_date"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>{{ __('dashboard.to_date') }}</label>
                                            <input type="date" value="{{ Request()->end_date }}" name="end_date"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <div class="d-flex justify-content-between w-100">
                                                <div>
                                                    <button class="btn btn-primary rounded-pill" type="submit"
                                                        style="margin-right: 10px;" title="Search">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <a href="{{ url('admin/reports') }}"
                                                        class="btn btn-success rounded-pill" title="Reset">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </a>
                                                </div>
                                                <div>
                                                    <a href="{{ route('reports.exportPdf', Request::all()) }}"
                                                        class="btn btn-danger">
                                                        <i class="fas fa-file-pdf"></i> {{ __('dashboard.pdf') }}
                                                    </a>
                                                </div>
                                            </div>
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
                                    <h3 class="card-title mb-2 mb-md-0 me-3">{{ __('dashboard.attendance_list') }}</h3>
                                    <form method="get" action="" class="mb-0"
                                        style="margin-left: 15px; margin-right: 15px;">
                                        <!-- 🔧 FIX: Preserve ALL existing search parameters -->
                                        <input type="hidden" name="employee_name" value="{{ Request::get('employee_name') }}">
                                        <input type="hidden" name="attendance_type" value="{{ Request::get('attendance_type') }}">
                                        <input type="hidden" name="filter_branch_id"
                                            value="{{ Request()->filter_branch_id }}">
                                        <input type="hidden" name="start_date" value="{{ Request()->start_date }}">
                                        <input type="hidden" name="end_date" value="{{ Request()->end_date }}">

                                        <select name="per_page" class="form-select" onchange="this.form.submit()"
                                            style="min-width: 60px; width: 60px;">
                                            <option value="10" {{ (Request()->get('per_page', 10)) == 10 ? 'selected' : '' }}>10
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
                            <div class="card-body p-0" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('dashboard.employee_id') }}</th>
                                            <th>{{ __('dashboard.employee_name') }}</th>
                                            <th>{{ __('h_employee.branch') }}</th>
                                            <th>{{ __('dashboard.attendance') }}</th>
                                            <th>{{ __('dashboard.check_in') }}</th>
                                            <th>{{ __('dashboard.check_out') }}</th>
                                            <th>{{ __('dashboard.attendance_date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->employee_id }}</td>
                                                <td>{{ $value->employee_name }}</td>
                                                <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                <td>
                                                    @if ($value->attendance_type == 1)
                                                        {{ __('dashboard.present') }}
                                                    @elseif($value->attendance_type == 2)
                                                        {{ __('dashboard.late') }}
                                                    @elseif($value->attendance_type == 3)
                                                        {{ __('dashboard.absent') }}
                                                    @elseif($value->attendance_type == 4)
                                                        {{ __('dashboard.half_day') }}
                                                    @endif
                                                </td>

                                                <td>{{ $value->check_in ? date('h:i A', strtotime($value->check_in)) : '-' }}
                                                </td>
                                                <td>{{ $value->check_out ? date('h:i A', strtotime($value->check_out)) : '-' }}
                                                </td>

                                                <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                                                {{-- <td>{{ date('d-m-Y (h:i A)', strtotime($value->created_at)) }}</td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">{{ __('dashboard.record_not_found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{-- Pagination removed as requested --}}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
