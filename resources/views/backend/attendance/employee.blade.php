@extends('backend.layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/attendance.png') }}'); background-size: cover; background-position: center;">
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
                    <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>{{ __('dashboard.attendance_date') }}</label>
                                        <input type="date" id="getAttendanceDate" value="{{ Request::get('attendance_date') }}" required name="attendance_date" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3 d-flex align-items-end">
                                        <button class="btn btn-primary" type="submit" style="margin-right: 10px;">
                                            {{ __('dashboard.check') }}
                                        </button>
                                        <a href="{{ url('admin/attendance') }}" class="btn btn-success">
                                            {{ __('dashboard.reset') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @include('_message')

                    @if (!empty(Request::get('attendance_date')))
                    <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('dashboard.attendance_list') }}</h3>
                        </div>
                        <div class="card-body p-0" style="overflow: auto;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('dashboard.employee_id') }}</th>
                                        <th>{{ __('dashboard.employee_name') }}</th>
                                        <th>{{ __('dashboard.attendance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($getRecord) && !empty($getRecord->count()))
                                    @foreach ($getRecord as $value)
                                    @php
                                    $attendance_type = '';
                                    $getAttendance = $value->getAttendance($value->id, Request::get('attendance_date'));
                                    if(!empty($getAttendance->attendance_type)) {
                                        $attendance_type = $getAttendance->attendance_type;
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ $value->name }} {{ $value->last_name }}</td>
                                        <td>
                                            <label style="margin-right: 10px;">
                                                <input value="1" type="radio" {{ ($attendance_type == '1') ? 'checked' : ''}} id="{{ $value->id }}" class="SaveAttendance" name="attendance_{{ $value->id }}"> {{ __('dashboard.present') }}
                                            </label>
                                            <label style="margin-right: 10px;">
                                                <input value="2" type="radio" {{ ($attendance_type == '2') ? 'checked' : ''}} id="{{ $value->id }}" class="SaveAttendance" name="attendance_{{ $value->id }}"> {{ __('dashboard.late') }}
                                            </label>
                                            <label style="margin-right: 10px;">
                                                <input value="3" type="radio" {{ ($attendance_type == '3') ? 'checked' : ''}} id="{{ $value->id }}" class="SaveAttendance" name="attendance_{{ $value->id }}"> {{ __('dashboard.absent') }}
                                            </label>
                                            <label>
                                                <input value="4" type="radio" {{ ($attendance_type == '4') ? 'checked' : ''}} id="{{ $value->id }}" class="SaveAttendance" name="attendance_{{ $value->id }}"> {{ __('dashboard.half_day') }}
                                            </label>
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
<script src="{{ url('dist/js/attendance.js') }}"></script>
@endsection



