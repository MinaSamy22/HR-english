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
                        <h1>{{ __('h_job_history.job_history') }}</h1>
                    </div><!-- /.col -->
                   {{-- <div class="col-sm-6 text-end" style="text-align: right;">
                         <form action="{{ url('admin/jobhistory_export') }}" method="get"> <!-- excel export form -->
                            <input type="hidden" name="employee_name" value="{{ Request()->employee_name }}">
                            <input type="hidden" name="job_title" value="{{ Request()->job_title }}">
                            <!-- 🆕 Add branch filter to export -->
                            <input type="hidden" name="filter_branch_id" value="{{ Request()->filter_branch_id }}">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> {{ __('h_job_history.export') }}
                            </button>
                        </form>
                        <br>
                    </div><!-- /.col -->  --}}

                </div><!-- /.row -->
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
                                <h3 class="card-title">{{ __('h_job_history.search_job_history') }}</h3>
                            </div>

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_job_history.employee_name') }}</label>
                                            <input type="text" value="{{ Request()->employee_name }}"
                                                name="employee_name" class="form-control"
                                                placeholder="{{ __('h_job_history.name_placeholder') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_job_history.job_title') }}</label>
                                            <input type="text" value="{{ Request()->job_title }}" name="job_title"
                                                class="form-control"
                                                placeholder="{{ __('h_job_history.job_title_placeholder') }}">
                                        </div>

                                        <!-- 🆕 Branch Filter (Only for Main Branch Users) -->
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


                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;" title="{{ __('h_job_history.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/job_history') }}" class="btn btn-success rounded-pill"
                                                title="{{ __('h_job_history.reset') }}">
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
                                <h3 class="card-title">{{ __('h_job_history.job_history_list') }}</h3>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('dashboard.id') }}</th>
                                                <th>{{ __('h_job_history.employee_name') }}</th>

                                                <th>{{ __('h_job_history.job_title') }}</th>
                                                <th>{{ __('h_job_history.department_name') }}</th>
                                                <th>{{ __('E_resignation.resignation') }}</th>
                                                <th>{{ __('E_resignation.resignation_date') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_job_history.action') }}</th>{{-- buttons of crud inside it --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                {{-- forelse insted of foreach it found with col spam and empty for writing not found if not found --}}
                                                <tr>
                                                    <td>{{ $value->employee_id }}</td>
                                                    <td>{{ $value->employee_name }}</td>

                                                    <td>{{ $value->job_title }}</td>
                                                    <td>{{ $value->department_name }}</td>
                                                    <td>{{ $value->resignation_reason ?? '-' }}</td>
                                                    <td>{{ $value->resignation_date }}</td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                    <td>
                                                        <a href="{{ route('history.restore', $value->id) }}"
                                                            class="btn btn-success btn-sm rounded-pill restore-btn"
                                                            data-id="{{ $value->id }}"
                                                            title="{{ __('dashboard.restore_employee') }}">
                                                            <i class="fas fa-exchange-alt"></i>
                                                        </a>
                                                        <a href="{{ url('admin/job_history/view/' . $value->id) }}"
                                                            class="btn btn-info rounded-pill"
                                                            title="{{ __('h_manager.view') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const restoreButtons = document.querySelectorAll('.restore-btn');

                                                            restoreButtons.forEach(button => {
                                                                button.addEventListener('click', function(e) {
                                                                    e.preventDefault(); // منع الانتقال مباشرة
                                                                    const url = this.href;

                                                                    Swal.fire({
                                                                        title: "{{ __('dashboard.restore_employee') }}",
                                                                        text: "{{ __('dashboard.restore_employee_confirmation') }}",
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: '#28a745',
                                                                        cancelButtonColor: '#d33',
                                                                        confirmButtonText: "{{ __('dashboard.request_accepted') }}",
                                                                        cancelButtonText: "{{ __('dashboard.cancel') }}"
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            window.location.href = url;
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                </tr>
                                            @empty

                                                <tr>
                                                    <td colspan="100%">{{ __('h_job_history.not_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div style="padding: 10px; float:right;"> {{-- for pagination --}}
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

    @section('script')
        <script src="{{ asset('dist/js/job_history.js') }}?v=1"></script>
    @endsection

@endsection
