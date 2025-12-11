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
                            <div class="card-header">
                                <h3 class="card-title">{{ __('dashboard.attendance_list') }}</h3>
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
                                                <td colspan="5">{{ __('dashboard.record_not_found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <br>
                                {{-- <div style="padding: 10px; float:right;">
                                {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                            </div> --}}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
@endsection
