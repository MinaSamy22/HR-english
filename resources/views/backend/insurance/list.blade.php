@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/additional.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('h_insurance.insurance') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-right">
                        <a href="{{ url('admin/insurance/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('h_insurance.add_insurance') }}
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
                                <h3 class="card-title">{{ __('h_insurance.search_insurance') }}</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_insurance.insurance_code') }}</label>
                                            <input type="text" name="code" class="form-control"
                                                value="{{ Request()->code }}" placeholder="{{ __('h_insurance.code') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_insurance.insurance_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name"
                                                class="form-control" placeholder="{{ __('h_insurance.name') }}">
                                        </div>

                                        {{-- 🆕 Branch Filter - Only show if user is in main branch or has no branch --}}
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

                                        <div class="form-group col-md-3 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;" title="{{ __('h_insurance.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/insurance') }}" class="btn btn-success rounded-pill"
                                                title="{{ __('h_insurance.reset') }}">
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <form method="POST" action="{{ route('insurances.toggleCompanyInsurance') }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $isInsuranceApplied ? 'danger' : 'success' }}">
                                            {{ $isInsuranceApplied ? __('h_insurance.dont_apply_insurance_payroll') : __('h_insurance.apply_insurance_payroll') }}
                                        </button>
                                    </form>
                                </div>

                                <h3 class="card-title text-center flex-grow-1 text-center">
                                    {{ __('h_insurance.insurance_list') }}</h3>

                                <div>
                                    <button class="btn btn-danger"
                                        id="deleteSelected">{{ __('h_insurance.delete_selected') }}</button>
                                </div>
                            </div>


                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>{{ __('h_insurance.table_headers.code') }}</th>
                                                <th>{{ __('h_insurance.table_headers.insurance_name') }}</th>
                                                <th>{{ __('h_insurance.table_headers.employee_name') }}</th>
                                                <th>{{ __('h_tax.apply_to_payroll') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>

                                                <th>{{ __('h_insurance.table_headers.percentage') }}</th>
                                                <th>{{ __('h_insurance.table_headers.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                <tr>
                                                    <td><input type="checkbox" class="insuranceCheckbox"
                                                            value="{{ $value->id }}"></td>
                                                    <td>{{ $value->code }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->employee_name }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $value->apply_to_payroll == 1 ? 'badge-success' : 'badge-warning' }}">
                                                            {{ $value->apply_to_payroll == 1 ? __('h_insurance.yes') : __('h_insurance.no') }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>

                                                    <td>{{ $value->percent }}%</td>
                                                    <td>
                                                        <a href="{{ url('admin/insurance/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill"
                                                            title="{{ __('h_insurance.edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-danger rounded-pill delete-btn"
                                                            data-id="{{ $value->id }}"
                                                            title="{{ __('h_insurance.delete') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">{{ __('h_insurance.not_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div style="padding: 10px; float:right;">
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
                        url: "{{ url('admin/insurance/delete') }}/" + deleteId,
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

        // Bulk delete functionality with SweetAlert2
        $('#deleteSelected').click(function() {
            var selectedIds = [];
            $('.insuranceCheckbox:checked').each(function() {
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
                        url: "{{ url('admin/insurance/delete-multiple') }}",
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Remove selected rows with fade effect
                            $('.insuranceCheckbox:checked').each(function() {
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
            $('.insuranceCheckbox').prop('checked', this.checked);
        });

        // Update select all when individual checkboxes change
        $(document).on('change', '.insuranceCheckbox', function() {
            if ($('.insuranceCheckbox:checked').length === $('.insuranceCheckbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    </script>
@endsection
