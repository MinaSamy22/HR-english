@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/vacation.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('h_vacation.vacations') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6" style="text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};">
                        <a href="{{ url('admin/vacations/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('h_vacation.add_vacation') }}
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

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('h_vacation.employee_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder="{{ __('h_vacation.enter_name') }}">
                                        </div>

                                        {{-- 🆕 Add Branch Filter (same as jobs) --}}
                                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                        <div class="form-group col-md-2 col-sm-6">
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

                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;" title="{{ __('h_vacation.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/vacations') }}" class="btn btn-success rounded-pill" title="{{ __('h_vacation.reset') }}">
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
                                <h3 class="card-title mb-2 mb-md-0">{{ __('h_vacation.vacation_list') }}</h3>
                                <div class="ml-auto">
                                    <button class="btn btn-danger" id="deleteSelected">{{ __('h_vacation.delete_selection') }}</button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>{{ __('h_vacation.id') }}</th>
                                                <th>{{ __('h_vacation.employee_name') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_vacation.vacation_type') }}</th>
                                                <th>{{ __('h_vacation.start_date') }}</th>
                                                <th>{{ __('h_vacation.end_date') }}</th>
                                                <th>{{ __('h_vacation.total_days') }}</th>
                                                <th>{{ __('h_vacation.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $vacation)
                                                <tr>
                                                    <td><input type="checkbox" class="vacationCheckbox" value="{{ $vacation->id }}"></td>
                                                    <td>{{ $vacation->id }}</td>
                                                    <td>{{ $vacation->name }}</td>
                                                    <td>{{ $vacation->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                    <td>{{ $vacation->vacation_type }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($vacation->start_date)) }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($vacation->end_date)) }}</td>
                                                    <td>{{ $vacation->total }}</td>

                                                    <td>
                                                        <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                                data-id="{{ $vacation->id }}" title="{{ __('h_vacation.delete') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">{{ __('h_vacation.no_vacations_found') }}</td>
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

    <!-- RTL Support for Arabic -->
    @if(app()->getLocale() == 'ar')
        <style>
            body {
                direction: rtl;
                text-align: right;
            }

            .float-sm-right {
                float: left !important;
            }

            .text-right {
                text-align: left !important;
            }

            .ml-auto {
                margin-left: 0 !important;
                margin-right: auto !important;
            }

            .mr-1, .mr-2 {
                margin-right: 0 !important;
                margin-left: 0.25rem;
            }

            .mr-2 {
                margin-left: 0.5rem !important;
            }
        </style>
    @endif

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Individual delete functionality with SweetAlert2
    $(document).on('click', '.delete-btn', function () {
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
                    url: "{{ url('admin/vacations/delete') }}/" + deleteId,
                    type: 'GET',
                    success: function () {
                        $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();

                        Swal.fire({
                            title: "{{ __('dashboard.deleted') }}!",
                            text: "{{ __('dashboard.delete_success') }}",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire({
                            title: "{{ __('dashboard.error') }}",
                            text: "{{ __('dashboard.delete_failed') }}",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    });

    // Bulk delete functionality with SweetAlert2
    $('#deleteSelected').click(function() {
        var selectedIds = [];
        $('.vacationCheckbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            Swal.fire({
                title: "{{ __('dashboard.no_selection') }}",
                text: "{{ __('dashboard.select_items_first') }}",
                icon: "warning",
                confirmButtonText: "OK"
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
                    url: "{{ url('admin/vacations/delete-multiple') }}",
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Remove selected rows with fade effect
                        $('.vacationCheckbox:checked').each(function() {
                            $(this).closest('tr').fadeOut();
                        });

                        // Uncheck select all
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
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    });

    // Select all functionality
    $('#selectAll').change(function() {
        $('.vacationCheckbox').prop('checked', this.checked);
    });

    // Update select all when individual checkboxes change
    $(document).on('change', '.vacationCheckbox', function() {
        if ($('.vacationCheckbox:checked').length === $('.vacationCheckbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });
</script>


@endsection
