@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/overtime.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('h_early_leave.page_title') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-right">
                        <a href="{{ url('admin/early-leave/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('h_early_leave.add_btn') }}
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

                        {{-- Filter Card --}}
                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('h_early_leave.employee_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name"
                                                class="form-control" placeholder="{{ __('h_early_leave.name_placeholder') }}">
                                        </div>

                                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                            <div class="form-group col-md-2 col-sm-6">
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

                                        {{-- From Date --}}
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('dashboard.date') }}</label>
                                            <input type="date" name="from_date" value="{{ Request()->from_date }}"
                                                class="form-control">
                                        </div>

                                        {{-- To Date --}}
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('dashboard.to_date') }}</label>
                                            <input type="date" name="to_date" value="{{ Request()->to_date }}"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/early-leave') }}" class="btn btn-success rounded-pill">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </form>

                        </div>

                        @include('_message')

                        {{-- Table Card --}}
                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="card-title mb-2 mb-md-0">{{ __('h_early_leave.list_title') }}</h3>
                                <div class="ml-auto">
                                    <button class="btn btn-danger"
                                        id="deleteSelected">{{ __('h_early_leave.delete_selection_btn') }}</button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>{{ __('h_early_leave.table_employee_id') }}</th>
                                                <th>{{ __('h_early_leave.table_employee_name') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_early_leave.table_leave_time') }}</th>
                                                <th>{{ __('h_early_leave.table_date') }}</th>
                                                <th>{{ __('h_early_leave.table_reason') }}</th>
                                                <th>{{ __('h_early_leave.table_action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                <tr>
                                                    <td><input type="checkbox" class="earlyLeaveCheckbox"
                                                            value="{{ $value->id }}"></td>
                                                    <td>{{ $value->employee_id }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                    <td>{{ $value->requested_leave_time }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($value->request_date)) }}</td>
                                                    <td>{{ Str::limit($value->reason, 40) }}</td>
                                                    <td>
                                                        <a href="{{ url('admin/early-leave/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill"
                                                            title="{{ __('h_early_leave.edit_btn') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-danger rounded-pill delete-btn"
                                                            data-id="{{ $value->id }}"
                                                            title="{{ __('h_early_leave.delete_btn') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%">{{ __('h_early_leave.not_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end p-3">
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
        // Individual delete with SweetAlert2
        $(document).on('click', '.delete-btn', function() {
            let deleteId = $(this).data('id');

            Swal.fire({
                title: "{{ __('dashboard.delete') }}",
                text: "{{ __('dashboard.delete_confirmation') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "{{ __('dashboard.delete') }}",
                cancelButtonText: "{{ __('dashboard.cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/early-leave/delete') }}/" + deleteId,
                        type: 'GET',
                        success: function() {
                            $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr')
                                .fadeOut();

                            Swal.fire({
                                title: "{{ __('dashboard.deleted') }}!",
                                text: "{{ __('dashboard.delete_success') }}",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "{{ __('dashboard.error') }}",
                                text: "{{ __('dashboard.delete_failed') }}",
                                icon: "error",
                                confirmButtonText: "{{ __('dashboard.ok') }}"
                            });
                        }
                    });
                }
            });
        });

        // Bulk delete with SweetAlert2
        $('#deleteSelected').click(function() {
            var selectedIds = [];
            $('.earlyLeaveCheckbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    title: "{{ __('dashboard.no_selection') }}",
                    text: "{{ __('dashboard.select_items_first') }}",
                    icon: "warning",
                    confirmButtonText: "{{ __('dashboard.ok') }}"
                });
                return;
            }

            Swal.fire({
                title: "{{ __('dashboard.delete_selected') }}",
                text: "{{ __('dashboard.delete_selected_confirm') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "{{ __('dashboard.delete') }}",
                cancelButtonText: "{{ __('dashboard.cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/early-leave/delete-multiple') }}",
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('.earlyLeaveCheckbox:checked').each(function() {
                                $(this).closest('tr').fadeOut();
                            });

                            $('#selectAll').prop('checked', false);

                            Swal.fire({
                                title: "{{ __('dashboard.deleted') }}!",
                                text: "{{ __('dashboard.bulk_delete_success') }}",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: "{{ __('dashboard.error') }}",
                                text: "{{ __('dashboard.bulk_delete_failed') }}",
                                icon: "error",
                                confirmButtonText: "{{ __('dashboard.ok') }}"
                            });
                        }
                    });
                }
            });
        });

        // Select all
        $('#selectAll').change(function() {
            $('.earlyLeaveCheckbox').prop('checked', this.checked);
        });

        // Sync select-all checkbox
        $(document).on('change', '.earlyLeaveCheckbox', function() {
            if ($('.earlyLeaveCheckbox:checked').length === $('.earlyLeaveCheckbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    </script>
@endsection
