@extends('EmployeeInterface.layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <h1 class="m-0">{{ __('E_attendance.my_attendance') }}</h1>

            <ol class="breadcrumb mb-0 mt-2 mt-sm-0">
                <li class="breadcrumb-item">
                    <a href="home" class="text">{{ __('Calender.home') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ __('E_attendance.attendance') }}
                </li>
            </ol>

        </div>
    </div>
</div>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Work Time Cards -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sign-in-alt mr-2"></i>{{ __('E_attendance.work_start_time') }}
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-success">
                                <i class="fas fa-play-circle"></i>
                                <span id="workStartTime">
                                    @if($employee->work_start_time)
                                        {{ \Carbon\Carbon::parse($employee->work_start_time)->format('h:i A') }}
                                    @else
                                        09:00 AM
                                    @endif
                                </span>
                            </h3>
                            <p class="text-muted">{{ __('E_attendance.daily_work_begins') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sign-out-alt mr-2"></i>{{ __('E_attendance.work_end_time') }}
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-danger">
                                <i class="fas fa-stop-circle"></i>
                                <span id="workEndTime">
                                    @if($employee->work_end_time)
                                        {{ \Carbon\Carbon::parse($employee->work_end_time)->format('h:i A') }}
                                    @else
                                        05:00 PM
                                    @endif
                                </span>
                            </h3>
                            <p class="text-muted">{{ __('E_attendance.daily_work_ends') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-search mr-2"></i>{{ __('E_attendance.search_attendance_records') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('employee.attendance') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_from">{{ __('E_attendance.from_date') }}</label>
                                            <input type="date"
                                                   name="date_from"
                                                   id="date_from"
                                                   class="form-control"
                                                   value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_to">{{ __('E_attendance.to_date') }}</label>
                                            <input type="date"
                                                   name="date_to"
                                                   id="date_to"
                                                   class="form-control"
                                                   value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">{{ __('E_attendance.status') }}</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('E_attendance.all_status') }}</option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('E_attendance.present') }}</option>
                                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('E_attendance.late') }}</option>
                                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>{{ __('E_attendance.absent') }}</option>
                                                <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>{{ __('E_attendance.half_day') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search mr-1"></i>{{ __('E_attendance.search') }}
                                        </button>
                                        <a href="{{ route('employee.attendance') }}" class="btn btn-secondary ml-2">
                                            <i class="fas fa-refresh mr-1"></i>{{ __('E_attendance.reset') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-check mr-2"></i>{{ __('E_attendance.attendance_records') }}
                            </h3>
                            <div>
                                <span class="badge badge-info">
                                    {{ __('E_attendance.total_records') }}: {{ $getRecord->total() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($getRecord->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead">
                                            <tr>
                                                <th>#</th>
                                                <th>
                                                    <i class="fas fa-calendar mr-1"></i>{{ __('E_attendance.date') }}
                                                </th>
                                                <th>
                                                    <i class="fas fa-info-circle mr-1"></i>{{ __('E_attendance.status') }}
                                                </th>
                                                <th>
                                                    <i class="fas fa-clock mr-1"></i>{{ __('E_attendance.time_in') }}
                                                </th>
                                                <th>
                                                    <i class="fas fa-clock mr-1"></i>{{ __('E_attendance.time_out') }}
                                                </th>
                                                {{-- <th>
                                                    <i class="fas fa-hourglass-half mr-1"></i>{{ __('E_attendance.hours') }}
                                                </th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getRecord as $index => $record)
                                                <tr>
                                                    <td>{{ $getRecord->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong>{{ date('d M Y', strtotime($record->attendance_date)) }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ date('l', strtotime($record->attendance_date)) }}</small>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusClass = '';
                                                            $statusText = '';
                                                            $statusIcon = '';

                                                            switch($record->attendance_type) {
                                                                case 1:
                                                                    $statusClass = 'badge-success';
                                                                    $statusText = __('E_attendance.present');
                                                                    $statusIcon = 'fa-check-circle';
                                                                    break;
                                                                case 2:
                                                                    $statusClass = 'badge-warning';
                                                                    $statusText = __('E_attendance.late');
                                                                    $statusIcon = 'fa-exclamation-triangle';
                                                                    break;
                                                                case 3:
                                                                    $statusClass = 'badge-danger';
                                                                    $statusText = __('E_attendance.absent');
                                                                    $statusIcon = 'fa-times-circle';
                                                                    break;
                                                                case 4:
                                                                    $statusClass = 'badge-info';
                                                                    $statusText = __('E_attendance.half_day');
                                                                    $statusIcon = 'fa-clock';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'badge-secondary';
                                                                    $statusText = __('E_attendance.unknown');
                                                                    $statusIcon = 'fa-question';
                                                            }
                                                        @endphp

                                                        <span class="badge {{ $statusClass }} badge-lg">
                                                            <i class="fas {{ $statusIcon }} mr-1"></i>{{ $statusText }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($record->check_in)
                                                            <span class="text-success">
                                                                <i class="fas fa-sign-in-alt mr-1"></i>
                                                                {{ date('h:i A', strtotime($record->check_in)) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">--</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($record->check_out)
                                                            <span class="text-danger">
                                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                                {{ date('h:i A', strtotime($record->check_out)) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">--</span>
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        @if($record->time_in && $record->time_out)
                                                            @php
                                                                $timeIn = \Carbon\Carbon::parse($record->time_in);
                                                                $timeOut = \Carbon\Carbon::parse($record->time_out);
                                                                $totalHours = $timeOut->diff($timeIn);
                                                            @endphp
                                                            <span class="badge badge-primary">
                                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                                {{ $totalHours->format('%H:%I') }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">--</span>
                                                        @endif
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $getRecord->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">{{ __('E_attendance.no_records_found') }}</h4>
                                    <p class="text-muted">{{ __('E_attendance.try_adjusting_criteria') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@endsection
