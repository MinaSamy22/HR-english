@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/payroll3.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div id="accordion" class="w-100">
                        <div class="card card-light">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0">
                                    <a class="d-block" data-toggle="collapse" href="#collapseOne">
                                        {{ __('h_payments.payment_list') }}
                                    </a>
                                </h4>
                                <div class="ml-auto">
                                    <a href="{{ url('admin/payments/create') }}" class="btn btn-primary text-white">
                                        {{ __('h_payments.create_payment') }}
                                    </a>
                                </div>
                            </div>
                            <div id="collapseOne" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    {{-- ⚠️ <strong>{{ __('h_payments.important_notice') }}</strong>
                                    {{ __('h_payments.notice_text') }}<br><br> --}}

                                    <strong>{{ __('h_payments.clarification_title') }}</strong><br><br>

                                    • <strong>{{ __('h_payments.net_pay') }}</strong> – {{ __('h_payments.net_pay_desc') }}<br>

                                    • <strong>{{ __('h_payments.paid_amount') }}</strong> – {{ __('h_payments.paid_amount_desc') }}<br>

                                    • <strong>{{ __('h_payments.remaining_amount') }}</strong> – {{ __('h_payments.remaining_amount_desc') }}<br>

                                    • <strong>{{ __('h_payments.payment_status') }}</strong> – {{ __('h_payments.payment_status_desc') }}<br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('_message')


        <!-- Filter Form -->
        <form method="get" action="">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('h_payments.employee_name') }}</label>
                        <input type="text" value="{{ Request::get('name') }}" name="name" class="form-control"
                            placeholder="{{ __('h_payroll.enter_name') }}"
                            style="background-color: rgba(255, 255, 255, 0.5); color: black; border: 1px solid rgba(255, 255, 255, 0.8);">
                    </div>

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
                        <label>{{ __('h_payroll.month') }}</label>
                        <select name="month" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">

                            <option value="">{{ __('h_payroll.select_month') }}</option>

                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ Request::get('month') == $i ? 'selected' : '' }}>
                                    {{ __('h_payroll.months.' . $i) }}
                                </option>
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

                    <!-- Action buttons -->
                    <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                        <button class="btn btn-primary rounded-pill" type="submit"
                            title="{{ __('h_payroll.search') }}">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ url('admin/salary-payment') }}" class="btn btn-success rounded-pill ms-2"
                            title="{{ __('h_payroll.reset') }}">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>


        <!-- Payments Table -->
        <div class="card"
            style="background-color: rgba(255, 255, 255, 0.7); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-2 mb-md-0">{{ __('h_payments.payment_list') }}</h3>
                <div class="ml-auto">
                    <button class="btn btn-danger" id="deleteSelected">
                        {{ __('h_payments.delete_selection') }}
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>{{ __('h_payments.employee_name') }}</th>
                            {{-- Net Pay --}}
            <th class="text-primary">
                {{ __('h_payroll.net_pay') }}
            </th>

            {{-- Paid --}}
            <th class="text-success">
                {{ __('h_payments.paid_amount') }}
            </th>

            {{-- Remaining --}}
            <th class="text-danger">
                {{ __('h_payments.remaining_amount') }}
            </th>
                            <th>{{ __('h_payments.payment_date') }}</th>
                            <th>{{ __('h_payments.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($getRecord as $key => $payment)
                            <tr>
                                <td><input type="checkbox" class="paymentCheckbox" value="{{ $payment->id }}"></td>
                                <td>{{ $payment->employee->name ?? 'N/A' }}</td>
                                {{-- Net Pay --}}
                <td class="text-primary font-weight-bold">
                    {{ $payment->total_amount < 0 ? 0 : number_format($payment->total_amount, 2) }}
                </td>

                {{-- Paid Amount --}}
                <td class="text-success font-weight-bold">
                    {{ number_format($payment->paid_amount, 2) }}
                </td>

                {{-- Remaining Amount --}}
                <td style="font-weight: bold;"
                    class="{{ $payment->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($payment->remaining_amount, 2) }}
                </td>
                                <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                        data-id="{{ $payment->id }}" title="{{ __('h_payments.delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('dashboard.record_not_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 pr-3">
                {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
            </div>
        </div>
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
                    url: "{{ url('admin/payments/delete') }}/" + deleteId,
                    type: 'GET',
                    success: function () {
                        $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();

                        Swal.fire({
                            title: "{{ __('dashboard.deleted') }}!",
                            text: "{{ __('h_payments.payment_deleted_successfully') }}",
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
        $('.paymentCheckbox:checked').each(function() {
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
                    url: "{{ url('admin/payments/delete-multiple') }}",
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('.paymentCheckbox:checked').each(function() {
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

    // Select all functionality
    $('#selectAll').change(function() {
        $('.paymentCheckbox').prop('checked', this.checked);
    });

    // Update select all when individual checkboxes change
    $(document).on('change', '.paymentCheckbox', function() {
        if ($('.paymentCheckbox:checked').length === $('.paymentCheckbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });
</script>
@endsection
