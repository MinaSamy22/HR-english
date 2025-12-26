@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ url('dist/css/payrollcreate.css') }}?v=4">

 <style>
        .payroll-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .payroll-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .payroll-card.selected {
            border: 2px solid #007bff;
            background: rgba(255, 255, 255, 0.98);
        }

        .employee-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .employee-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .salary-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .info-item {
            background: rgba(248, 249, 250, 0.9);
            padding: 8px;
            border-radius: 5px;
        }

        /* RTL Support for border */
        [dir="ltr"] .info-item {
            border-left: 3px solid #007bff;
        }

        [dir="rtl"] .info-item {
            border-right: 3px solid #007bff;
        }

        /* Default for when dir is not set */
        html:not([dir]) .info-item {
            border-left: 3px solid #007bff;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .payment-input-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px dashed #ddd;
            display: none;
        }

        .payment-input-section.active {
            display: block;
        }

        /* RTL Support for already-paid border */
        [dir="ltr"] .already-paid {
            background: rgba(76, 175, 80, 0.1);
            border-left-color: #4caf50;
        }

        [dir="rtl"] .already-paid {
            background: rgba(76, 175, 80, 0.1);
            border-right-color: #4caf50;
        }

        html:not([dir]) .already-paid {
            background: rgba(76, 175, 80, 0.1);
            border-left-color: #4caf50;
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .no-payrolls {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            color: #666;
        }

        .partial-payment-toggle {
            margin-bottom: 15px;
        }

        .partial-payment-checkbox {
            margin-right: 8px;
        }

        #payrollsSection {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
        }

        .payment-progress {
            background: rgba(255, 255, 255, 0.95);
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* RTL Support for payment-progress border */
        [dir="ltr"] .payment-progress.all-paid {
            border-right: 4px solid #2196F3;
        }

        [dir="rtl"] .payment-progress.all-paid {
            border-left: 4px solid #2196F3;
        }

        html:not([dir]) .payment-progress.all-paid {
            border-right: 4px solid #2196F3;
        }

        .progress-text {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .progress-badge {
            background: #f8f9fa;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: bold;
            color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        .payment-progress.all-paid .progress-badge {
            color: #2196F3;
            border-color: #2196F3;
        }
    </style>

    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/payroll3.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">

        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="card-title">{{ __('h_payments.create_payment') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{ url('admin/salary-payment') }}">{{ __('h_payments.payment') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_employee.add_breadcrumb') }}</li>
                    </ol>
                </div>
            </div>
        </div>


        <!-- Filter Section -->
        <div class="filter-section">
            <form id="filterForm">
                <div class="row">
                    @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                        <div class="form-group col-md-4">
                            <label>{{ __('h_employee.branch') }}</label>
                            <select name="filter_branch_id" id="filter_branch_id" class="form-control">
                                <option value="">{{ __('h_employee.all') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group col-md-4">
                        <label>{{ __('h_payroll.month') }}<span style="color: red;">*</span></label>
                        <select name="month" id="month" class="form-control" required>
                            <option value="">{{ __('h_payroll.select_month') }}</option>

                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                    {{ __('h_payroll.months.' . $i) }}
                                </option>
                            @endfor
                        </select>
                    </div>


                    <div class="form-group col-md-4">
                        <label>{{ __('h_payroll.year') }}<span style="color: red;">*</span></label>
                        <select name="year" id="year" class="form-control" required>
                            <option value="">{{ __('h_payroll.select_year') }}</option>
                            @php
                                $currentYear = date('Y');
                                $endYear = $currentYear + 1;
                                $startYear = $currentYear - 3;
                            @endphp
                            @for ($i = $startYear; $i <= $endYear; $i++)
                                <option value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <button type="button" id="searchPayrolls" class="btn btn-primary">
                    <i class="fas fa-search"></i> {{ __('h_payroll.search') }}
                </button>
            </form>
        </div>

        <!-- Payrolls Display Section -->
        <div id="payrollsSection" style="display: none;">
            <!-- Payment Progress Counter -->
            <div class="payment-progress" id="paymentProgress">
                <div class="progress-text">
                    <i class="fas fa-users"></i> {{ __('h_payments.payment_status_label') }}
                </div>
                <div class="progress-badge" id="progressBadge">
                    <span id="paidCount">0</span> / <span id="totalCount">0</span>
                </div>
            </div>


            <form method="post" action="{{ url('admin/payments/store') }}" id="paymentForm">
                @csrf

                <input type="hidden" name="selected_month" id="selected_month">
                <input type="hidden" name="selected_year" id="selected_year">
                <input type="hidden" name="selected_branch" id="selected_branch">

                <div id="payrollsList"></div>

                <div class="card mt-3">
                    <div class="card-body">
                        <button type="submit" id="submitPaymentBtn" class="btn btn-success btn-lg float-right">
                            <i class="fas fa-check-circle"></i> {{ __('h_payments.save_payments') }}
                        </button>
                        <a href="{{ url('admin/salary-payment') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> {{ __('dashboard.back') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Payment Rules Banner -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i>
                    <strong>{{ __('h_payments.note_title') }}</strong>
                    <ul class="mb-0 mt-2" style="list-style: none; padding-right: 0;">
                        <li>{{ __('h_payments.note_all_paid') }}</li>
                        <li>{{ __('h_payments.note_partial_paid') }}</li>
                        <li>{{ __('h_payments.note_ignore_fully_paid') }}</li>
                    </ul>

                </div>
            </div>
        </div>

        <div id="noPayrolls" class="no-payrolls" style="display: none;">
            <i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i>
            <h4>{{ __('h_payments.no_payrolls_found') }}</h4>
            <p>{{ __('h_payments.select_filters_to_see_payrolls') }}</p>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let totalEmployees = 0;
            let paidEmployees = 0;

            $('#searchPayrolls').on('click', function() {
                const branchId = $('#filter_branch_id').val();
                const month = $('#month').val();
                const year = $('#year').val();

                if (!month || !year) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('dashboard.warning') }}',
                        text: '{{ __('h_payments.please_select_month_year') }}'
                    });
                    return;
                }

                $('#selected_month').val(month);
                $('#selected_year').val(year);
                $('#selected_branch').val(branchId);

                $.ajax({
                    url: '{{ url('admin/payments/get-payrolls') }}',
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        if (response.success && response.payrolls.length > 0) {
                            displayPayrolls(response.payrolls);
                            $('#payrollsSection').show();
                            $('#noPayrolls').hide();
                        } else {
                            $('#payrollsSection').hide();
                            $('#noPayrolls').show();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('dashboard.error') }}',
                            text: '{{ __('dashboard.something_went_wrong') }}'
                        });
                    }
                });
            });

            function displayPayrolls(payrolls) {
                let html = '';
                totalEmployees = payrolls.length;
                paidEmployees = 0;

                payrolls.forEach(function(payroll) {
                    const totalPaid = parseInt(payroll.total_paid) || 0;
                    const remaining = payroll.net_pay - totalPaid;
                    const isPaidFull = remaining <= 0;

                    if (isPaidFull) {
                        paidEmployees++;
                    }

                    // Start payroll card
                    html += '<div class="payroll-card ' + (isPaidFull ? 'already-paid' : '') + '" data-payroll-id="' + payroll.id + '">';

                    // Employee info header
                    html += '<div class="employee-info">';
                    html += '<div>';
                    html += '<span class="employee-name">' + payroll.employee_name + '</span>';
                    html += '<span class="badge badge-info ml-2">' + payroll.payroll_type_label + '</span>';

                    if (isPaidFull) {
                        html += '<span class="badge badge-success ml-2">';
                        html += '<i class="fas fa-check"></i> {{ __('h_payments.fully_paid_badge') }}';
                        html += '</span>';
                    }

                    html += '</div>';
                    html += '<div>';

                    if (!isPaidFull) {
                        html += '<input type="checkbox" class="payroll-checkbox" data-payroll-id="' + payroll.id + '">';
                    }

                    html += '</div>';
                    html += '</div>';

                    // Salary info grid
                    html += '<div class="salary-info">';

                    html += '<div class="info-item">';
                    html += '<div class="info-label">{{ __('h_payroll.basic_salary') }}</div>';
                    html += '<div class="info-value">' + payroll.basic_salary + '</div>';
                    html += '</div>';

                    html += '<div class="info-item">';
                    html += '<div class="info-label">{{ __('h_payroll.net_pay') }}</div>';
                    html += '<div class="info-value" style="color: #4caf50;">' + payroll.net_pay + '</div>';
                    html += '</div>';

                    html += '<div class="info-item ' + (totalPaid > 0 ? 'already-paid' : '') + '">';
                    html += '<div class="info-label">{{ __('h_payments.total_paid') }}</div>';
                    html += '<div class="info-value" style="color: #2196f3;">' + totalPaid + '</div>';
                    html += '</div>';

                    html += '<div class="info-item">';
                    html += '<div class="info-label">{{ __('h_payments.remaining') }}</div>';
                    html += '<div class="info-value" style="color: ' + (remaining > 0 ? '#ff9800' : '#4caf50') + ';">' + remaining + '</div>';
                    html += '</div>';

                    html += '</div>';

                    // Payment input section (only if not fully paid)
                    if (!isPaidFull) {
                        html += '<div class="payment-input-section" id="payment-section-' + payroll.id + '">';

                        // Partial payment toggle
                        html += '<div class="partial-payment-toggle">';
                        html += '<label>';
                        html += '<input type="checkbox" class="partial-payment-checkbox" data-payroll="' + payroll.id + '">';
                        html += '<i class="fas fa-coins"></i> {{ __('h_payments.partial_payment') }}';
                        html += '</label>';
                        html += '</div>';

                        // Partial payment inputs
                        html += '<div class="partial-payment-inputs" id="partial-inputs-' + payroll.id + '" style="display: none;">';
                        html += '<div class="form-group">';
                        html += '<label>{{ __('h_payments.amount_to_pay') }} <span style="color: red;">*</span></label>';
                        html += '<input type="number" class="form-control payment-amount" data-payroll="' + payroll.id + '" min="0" max="' + remaining + '" placeholder="{{ __('h_payments.enter_amount') }}">';
                        html += '</div>';
                        html += '</div>';

                        // Full payment inputs
                        html += '<div class="full-payment-inputs" id="full-inputs-' + payroll.id + '">';
                        html += '<input type="hidden" class="full-amount-input" data-payroll="' + payroll.id + '" data-amount="' + remaining + '">';
                        html += '<div class="alert alert-info">';
                        html += '<i class="fas fa-info-circle"></i> {{ __('h_payments.full_remaining_amount_notice') }}: <strong>' + remaining + '</strong>';
                        html += '</div>';
                        html += '</div>';

                        // Payment date
                        html += '<div class="form-group">';
                        html += '<label>{{ __('h_payments.payment_date') }} <span style="color: red;">*</span></label>';
                        html += '<input type="date" class="form-control payment-date-input" data-payroll="' + payroll.id + '" value="{{ date('Y-m-d') }}">';
                        html += '</div>';

                        // Hidden inputs
                        html += '<input type="hidden" class="payroll-id-input" value="' + payroll.id + '">';
                        html += '<input type="hidden" class="employee-id-input" value="' + payroll.employee_id + '">';

                        html += '</div>';
                    }

                    html += '</div>';
                });

                $('#payrollsList').html(html);
                updateProgressCounter();
                attachEventHandlers();
            }

            function updateProgressCounter() {
                $('#paidCount').text(paidEmployees);
                $('#totalCount').text(totalEmployees);

                const progressDiv = $('#paymentProgress');
                const submitBtn = $('#submitPaymentBtn');

                if (paidEmployees === totalEmployees && totalEmployees > 0) {
                    progressDiv.addClass('all-paid');
                    submitBtn.prop('disabled', true).html('<i class="fas fa-check-circle"></i> {{ __('h_payments.all_salaries_paid') }}');
                } else {
                    progressDiv.removeClass('all-paid');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> {{ __('h_payments.save_payments') }}');
                }
            }

            function attachEventHandlers() {
                $('.payroll-checkbox').on('change', function() {
                    const payrollId = $(this).data('payroll-id');
                    const card = $(this).closest('.payroll-card');
                    const section = $('#payment-section-' + payrollId);

                    if ($(this).is(':checked')) {
                        card.addClass('selected');
                        section.addClass('active');
                    } else {
                        card.removeClass('selected');
                        section.removeClass('active');
                        $('.partial-payment-checkbox[data-payroll="' + payrollId + '"]').prop('checked', false);
                        $('#partial-inputs-' + payrollId).hide();
                        $('#full-inputs-' + payrollId).show();
                        $('.payment-amount[data-payroll="' + payrollId + '"]').prop('required', false).val('');
                    }
                });

                $('.partial-payment-checkbox').on('change', function() {
                    const payrollId = $(this).data('payroll');
                    const partialInputs = $('#partial-inputs-' + payrollId);
                    const fullInputs = $('#full-inputs-' + payrollId);
                    const amountInput = $('.payment-amount[data-payroll="' + payrollId + '"]');

                    if ($(this).is(':checked')) {
                        fullInputs.hide();
                        partialInputs.show();
                        amountInput.prop('required', true);
                    } else {
                        partialInputs.hide();
                        fullInputs.show();
                        amountInput.prop('required', false).val('');
                    }
                });
            }

            // NEW: Non-AJAX form submission with validation
            $('#paymentForm').on('submit', function(e) {
                const selectedPayrolls = $('.payroll-checkbox:checked');

                // Only validate if there are selected payrolls
                if (selectedPayrolls.length > 0) {
                    let isValid = true;
                    let errorMessage = '';

                    selectedPayrolls.each(function() {
                        const payrollId = $(this).data('payroll-id');
                        const isPartial = $('.partial-payment-checkbox[data-payroll="' + payrollId + '"]').is(':checked');

                        if (isPartial) {
                            const amount = $('.payment-amount[data-payroll="' + payrollId + '"]').val();
                            if (!amount || parseFloat(amount) <= 0) {
                                isValid = false;
                                errorMessage = '{{ __('h_payments.partial_amount_required') }}';
                                return false;
                            }
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: '{{ __('dashboard.warning') }}',
                            text: errorMessage
                        });
                        return false;
                    }

                    // Build form inputs for selected payrolls
                    selectedPayrolls.each(function(index) {
                        const payrollId = $(this).data('payroll-id');
                        const section = $('#payment-section-' + payrollId);
                        const isPartial = $('.partial-payment-checkbox[data-payroll="' + payrollId + '"]').is(':checked');

                        let amount;
                        if (isPartial) {
                            amount = $('.payment-amount[data-payroll="' + payrollId + '"]').val();
                        } else {
                            amount = $('.full-amount-input[data-payroll="' + payrollId + '"]').data('amount');
                        }

                        const paymentDate = $('.payment-date-input[data-payroll="' + payrollId + '"]').val();
                        const employeeId = section.find('.employee-id-input').val();

                        // Create hidden inputs for form submission
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'payments[' + index + '][payroll_id]',
                            value: payrollId
                        }).appendTo('#paymentForm');

                        $('<input>').attr({
                            type: 'hidden',
                            name: 'payments[' + index + '][employee_id]',
                            value: employeeId
                        }).appendTo('#paymentForm');

                        $('<input>').attr({
                            type: 'hidden',
                            name: 'payments[' + index + '][amount]',
                            value: amount
                        }).appendTo('#paymentForm');

                        $('<input>').attr({
                            type: 'hidden',
                            name: 'payments[' + index + '][payment_date]',
                            value: paymentDate
                        }).appendTo('#paymentForm');
                    });
                }

                // Let the form submit normally (no preventDefault here)
                return true;
            });
        });
    </script>
@endsection
