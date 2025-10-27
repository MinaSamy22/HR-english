@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/attendance.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-7">
                        <h1>{{ __('dashboard.employee_attendance') }}</h1>
                        <h5>({{ __('dashboard.manually_if_no_machine_exists') }})</h5>
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
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>{{ __('dashboard.attendance_date') }}</label>
                                            <input type="date" id="getAttendanceDate"
                                                value="{{ Request::get('attendance_date') }}" required
                                                name="attendance_date" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3 d-flex align-items-end">
                                            <button class="btn btn-primary" type="submit" style="margin-right: 10px;">
                                                {{ __('dashboard.check') }}
                                            </button>
                                            <a href="{{ url('admin/attendance') }}" class="btn btn-success"
                                                style="margin-right: 10px;">
                                                {{ __('dashboard.reset') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        @if (!empty(Request::get('attendance_date')))
                            <div class="card"
                                style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('dashboard.attendance_list') }}</h3>
                                </div>
                                <div class="card-body p-0" style="overflow: auto;">
                                    <div class="card-body">
                                        <!-- Global Time Inputs -->
                                        <div class="row g-3 mb-4 align-items-end">
                                            <div class="col-md-2 col-sm-4">
                                                <label class="fw-bold">{{ __('dashboard.global_check_in') }}</label>
                                                <input type="time" id="globalCheckIn" class="form-control">
                                            </div>
                                            <div class="col-md-2 col-sm-4">
                                                <label class="fw-bold">{{ __('dashboard.global_check_out') }}</label>
                                                <input type="time" id="globalCheckOut" class="form-control">
                                            </div>
                                            <div class="col-md-auto col-sm-4">
                                                <button id="applyGlobalTime" class="btn btn-success">
                                                    <i class="fas fa-clock me-1"></i> {{ __('dashboard.apply_time') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>{{ __('dashboard.employee_id') }}</th>
                                            <th>{{ __('dashboard.employee_name') }}</th>
                                            <th>{{ __('dashboard.check_in') }}</th>
                                            <th>{{ __('dashboard.check_out') }}</th>
                                            <th>{{ __('dashboard.attendance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($getRecord) && !empty($getRecord->count()))
                                            @foreach ($getRecord as $value)
                                                @php
                                                    $attendance_type = '';
                                                    $check_in = '';
                                                    $check_out = '';
                                                    $getAttendance = $value->getAttendance(
                                                        $value->id,
                                                        Request::get('attendance_date'),
                                                    );
                                                    if (!empty($getAttendance)) {
                                                        $attendance_type = $getAttendance->attendance_type ?? '';
                                                        $check_in = $getAttendance->check_in ?? '';
                                                        $check_out = $getAttendance->check_out ?? '';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td><input type="checkbox" class="employee-select"
                                                            value="{{ $value->id }}"></td>
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                                    <td>
                                                        <input type="time" class="form-control check-in-input"
                                                            data-employee="{{ $value->id }}"
                                                            value="{{ $check_in }}">
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control check-out-input"
                                                            data-employee="{{ $value->id }}"
                                                            value="{{ $check_out }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control attendance-select"
                                                            data-employee="{{ $value->id }}">
                                                            <option value="">{{ __('dashboard.select_status') }}
                                                            </option>
                                                            <option value="1"
                                                                {{ $attendance_type == '1' ? 'selected' : '' }}>
                                                                {{ __('dashboard.present') }}</option>
                                                            <option value="2"
                                                                {{ $attendance_type == '2' ? 'selected' : '' }}>
                                                                {{ __('dashboard.late') }}</option>
                                                            <option value="3"
                                                                {{ $attendance_type == '3' ? 'selected' : '' }}>
                                                                {{ __('dashboard.absent') }}</option>
                                                            <option value="4"
                                                                {{ $attendance_type == '4' ? 'selected' : '' }}>
                                                                {{ __('dashboard.half_day') }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                </div>
                @endif
        </section>
    </div>
    </div>
    </section>
    </div>
    <!-- Link to the new JavaScript file -->

    <style>
        .attendance-select {
            transition: all 0.3s ease;
        }

        .attendance-select:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* Success state */
        .save-success {
            border: 2px solid #28a745 !important;
            background-color: #d4edda !important;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.4) !important;
        }

        /* Error state */
        .save-error {
            border: 2px solid #dc3545 !important;
            background-color: #f8d7da !important;
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.4) !important;
        }
    </style>

    <script src="{{ url('dist/js/attendance.js') }}?v=9"></script>
@endsection
