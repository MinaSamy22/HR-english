@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img//payroll3.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div id="accordion" class="w-100">
                        <div class="card card-light">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0">
                                    <a class="d-block" data-toggle="collapse" href="#collapseOne">
                                        {{ __('h_payroll.payroll_list') }}
                                    </a>
                                </h4>
                                <div class="ml-auto">
                                    <a href="{{ url('admin/payroll/add') }}"
                                        class="btn btn-primary text-white">{{ __('h_payroll.create_payroll') }}</a>
                                </div>
                            </div>
                            <div id="collapseOne" class="collapse " data-parent="#accordion">
                                <div class="card-body">

                                    ⚠️ <strong>{{ __('h_payroll.important_notice') }}</strong>
                                    {{ __('h_payroll.notice_text') }}<br><br>

                                    <strong>{{ __('h_payroll.clarification_title') }}</strong><br><br>

                                    • <strong>{{ __('h_payroll.bonus') }}</strong> – {{ __('h_payroll.bonus_desc') }}<br>

                                    • <strong>{{ __('h_payroll.deductions') }}</strong> –
                                    {{ __('h_payroll.deductions_desc') }}<br>

                                    • <strong>{{ __('h_payroll.taxes_insurance') }}</strong> –
                                    {{ __('h_payroll.taxes_insurance_desc') }}<br>

                                    • <strong>{{ __('h_payroll.vacation_balance') }}</strong> –
                                    {{ __('h_payroll.vacation_balance_desc') }}<br>

                                    • <strong>{{ __('h_payroll.net_pay') }}</strong> –
                                    {{ __('h_payroll.net_pay_desc') }}<br>
                                    <code>{{ __('h_payroll.net_pay_formula') }}</code>
                                </div>

                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <form method="get" action="">
            <div class="card-body">
                <div class="row">
                    <!-- First row with form fields -->
                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('h_payroll.employee_name') }}</label>
                        <input type="text" value="{{ Request::get('name') }}" name="name" class="form-control"
                            placeholder="{{ __('h_payroll.enter_name') }}"
                            style="background-color: rgba(255, 255, 255, 0.5); color: black; border: 1px solid rgba(255, 255, 255, 0.8);">
                    </div>

                    {{-- Branch Filter --}}
                    @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                        <div class="form-group col-md-2 col-sm-6">
                            <label>{{ __('h_employee.branch') }}</label>
                            <select name="filter_branch_id" class="form-control"
                                style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
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

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('h_payroll.payroll_type') }}</label>
                        <select name="payroll_type" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">{{ __('h_payroll.select_payroll_type') }}</option>
                            <option value="monthly" {{ Request::get('payroll_type') == 'monthly' ? 'selected' : '' }}>
                                {{ __('h_payroll.monthly') }}</option>
                            <option value="weekly" {{ Request::get('payroll_type') == 'weekly' ? 'selected' : '' }}>
                                {{ __('h_payroll.weekly') }}</option>
                            <option value="daily" {{ Request::get('payroll_type') == 'daily' ? 'selected' : '' }}>
                                {{ __('h_payroll.daily') }}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('h_payroll.month') }}</label>
                        <select name="month" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">{{ __('h_payroll.select_month') }}</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ Request::get('month') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('h_payroll.year') }}</label>
                        <select name="year" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">{{ __('h_payroll.select_year') }}</option>
                            @php
                                $currentYear = date('Y');
                                $endYear = $currentYear + 6;
                            @endphp
                            @for ($i = $currentYear; $i <= $endYear; $i++)
                                <option value="{{ $i }}" {{ Request::get('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Action buttons in a separate row -->
                    <div class="col-12">
                        <div class="form-group d-flex justify-content-between align-items-center" style="margin-top: 20px;">
                            <!-- Left side: Search & Reset -->
                            <div>
                                <button class="btn btn-primary rounded-pill" type="submit"
                                    title="{{ __('h_payroll.search') }}">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ url('admin/payroll') }}" class="btn btn-success rounded-pill ms-2"
                                    title="{{ __('h_payroll.reset') }}">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>

                            <!-- Right side: Excel & PDF -->
                            <div>
                                <a class="btn btn-success rounded-pill me-2"
                                    href="{{ url('admin/payroll_export?name=' . Request::get('name') . '&month=' . Request::get('month') . '&year=' . Request::get('year') . '&payroll_type=' . Request::get('payroll_type') . '&filter_branch_id=' . Request::get('filter_branch_id')) }}">
                                    <i class="fas fa-file-excel"></i> {{ __('h_payroll.excel') }}
                                </a>
                                <a href="{{ route('payrolls.exportPdf', Request::all()) }}"
                                    class="btn btn-danger rounded-pill">
                                    <i class="fas fa-file-pdf"></i> {{ __('h_payroll.pdf') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @include('_message')

        <div class="card"
            style="background-color: rgba(255, 255, 255, 0.7); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-2 mb-md-0">{{ __('h_payroll.payroll_list') }}</h3>
                <div class="ml-auto">
                    <button class="btn btn-danger" id="deleteSelected">{{ __('h_payroll.delete_selection') }}</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>{{ __('h_payroll.employee_id') }}</th>
                            <th>{{ __('h_payroll.employee_name') }}</th>
                            <th>{{ __('h_payroll.basic_salary') }}</th>
                            <th>{{ __('h_payroll.bonus') }}</th>
                            <th>{{ __('h_payroll.deductions') }}</th>
                            <th>{{ __('h_payroll.attendance_deduction') }}</th>
                            <th>{{ __('h_payroll.taxes_insurance') }}</th>
                            <th>{{ __('h_payroll.is_insure') }}</th>
                            <th>{{ __('h_payroll.vacation_balance') }}</th>
                            <th>{{ __('h_payroll.net_pay') }}</th>
                            <th>{{ __('h_payroll.payroll_type') }}</th>
                            <th>{{ __('h_payroll.pay_date') }}</th>
                            <th>{{ __('h_payroll.month') }}</th>
                            <th>{{ __('h_payroll.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($getRecord as $value)
                            <tr>
                                <td><input type="checkbox" class="payrollCheckbox" value="{{ $value->id }}"></td>
                                <td>{{ $value->employee_id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->basic_salary }}</td>
                                <td>{{ $value->bounas }}</td>
                                <td>{{ $value->deductions }}</td>
                                <td>{{ $value->attendance_deduction }}</td>
                                <td>{{ $value->taxes }}</td>
                                <td class="text-center">
                                    @if ($value->is_insured == 1)
                                        <span title="مؤمن عليه" style="color: green; font-size: 18px;">✅</span>
                                    @else
                                        <span title="غير مؤمن عليه" style="color: red; font-size: 18px;">❌</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($value->payroll_type == 'monthly')
                                        {{ $value->rest_vacancy }} {{ __('h_payroll.day') }}
                                    @else
                                        <span style="color: red; font-size: 18px;">❌</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($value->net_pay < 0)
                                        0
                                    @else
                                        {{ $value->net_pay }}
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge badge-pill
                                      @if ($value->payroll_type == 'daily') badge-warning
                                      @elseif($value->payroll_type == 'weekly') badge-info
                                      @elseif($value->payroll_type == 'monthly') badge-primary
                                      @else badge-secondary @endif">
                                        {{ __('h_payroll.payroll_types.' . strtolower($value->payroll_type)) }}
                                    </span>
                                </td>

                                <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                <td>{{ date('m', strtotime($value->start_date)) }}</td>
                                <td>
                                    <a href="{{ url('admin/payroll/edit/' . $value->id) }}"
                                        class="btn btn-primary rounded-pill" title="{{ __('h_payroll.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                data-id="{{ $value->id }}" title="{{ __('h_payroll.delete') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 pr-3">
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
                    url: "{{ url('admin/payroll/delete') }}/" + deleteId,
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
        $('.payrollCheckbox:checked').each(function() {
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
                    url: "{{ url('admin/payrolls/delete-multiple') }}",
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Remove selected rows with fade effect
                        $('.payrollCheckbox:checked').each(function() {
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
        $('.payrollCheckbox').prop('checked', this.checked);
    });

    // Update select all when individual checkboxes change
    $(document).on('change', '.payrollCheckbox', function() {
        if ($('.payrollCheckbox:checked').length === $('.payrollCheckbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });
</script>

@endsection
