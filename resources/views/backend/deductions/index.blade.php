@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/deduction.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('h_deduction.deductions') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-right">
                        <a href="{{ url('admin/deductions/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('h_deduction.add_deduction_btn') }}
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
                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">

                                        {{-- here the searching options --}}
                                        {{-- name and value I put the name el mktop fl database --}}
                                        {{-- md3 for the size of the label md2 small --}}

                                        {{-- <div class="form-group col-md-3">
                                            <label> ID </label>
                                            <input type="number" name="h_id" class="form-control" value="{{ Request()->h_id }}" placeholder="ID">
                                        </div> --}}

                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_deduction.employee_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name"
                                                class="form-control" placeholder="{{ __('h_deduction.enter_name') }}">
                                        </div>
                                        <!-- Branch Filter (Only for Main Branch Users) -->
                                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_employee.branch') }}</label>
                                            <select name="filter_branch_id" class="form-control">
                                                <option value="">{{ __('h_employee.all') }}</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                            {{ Request()->filter_branch_id == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif

                                        <div class="form-group col-md-3 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;" title="{{ __('h_deduction.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/deductions') }}" class="btn btn-success rounded-pill"
                                                title="{{ __('h_deduction.reset') }}">
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
                                <h3 class="card-title mb-2 mb-md-0">{{ __('h_deduction.deduction_list') }}</h3>
                                <div class="ml-auto">
                                    <button class="btn btn-danger" id="deleteSelected">{{ __('h_deduction.delete_selection') }}</button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>{{ __('h_deduction.employee_name') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_deduction.deduction_reason') }}</th>
                                                <th>{{ __('h_deduction.money') }}</th>
                                                <th>{{ __('h_deduction.deduction_date') }}</th>
                                                <th>{{ __('h_deduction.action') }}</th>{{-- buttons of crud inside it --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                {{-- forelse insted of foreach it found with col spam and empty for writing not found if not found --}}
                                                <tr>
                                                    <td><input type="checkbox" class="deductionCheckbox"
                                                            value="{{ $value->id }}"></td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                    <td>{{ $value->deduction_type }}</td>
                                                    <td>{{ $value->money_deduction }} {{ __('h_deduction.currency') }}
                                                    </td>
                                                    <td>{{ date('d-m-Y (h:i A)', strtotime($value->created_at)) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                                data-id="{{ $value->id }}" title="{{ __('h_deduction.delete') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">{{ __('h_deduction.not_found') }}</td>
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
                    url: "{{ url('admin/deductions/delete') }}/" + deleteId,
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
                                confirmButtonText: "{{ __('dashboard.ok') }}"
                        });
                    }
                });
            }
        });
    });

    // Bulk delete functionality with SweetAlert2
    $('#deleteSelected').click(function() {
        var selectedIds = [];
        $('.deductionCheckbox:checked').each(function() {
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
                    url: "{{ url('admin/deductions/delete-multiple') }}",
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Remove selected rows with fade effect
                        $('.deductionCheckbox:checked').each(function() {
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
                                confirmButtonText: "{{ __('dashboard.ok') }}"
                        });
                    }
                });
            }
        });
    });

    // Select all functionality
    $('#selectAll').change(function() {
        $('.deductionCheckbox').prop('checked', this.checked);
    });

    // Update select all when individual checkboxes change
    $(document).on('change', '.deductionCheckbox', function() {
        if ($('.deductionCheckbox:checked').length === $('.deductionCheckbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });
</script>


@endsection
