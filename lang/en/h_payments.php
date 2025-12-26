<?php

return [
    // Page Title
    'payments_list' => 'Salary Payments List',
    'payment' => 'Payment List',
    'payments' => 'Salary Payments',
    'add_payment' => 'Add Payment',

    // Important Notice
    'important_notice' => 'Important Notice',
    'notice_text' => 'This page displays the salary payment history for employees. Please ensure the accuracy of the data before adding any new payment.',
    'clarification_title' => 'Terminology Clarification:',

    // Terminology
    'net_pay' => 'Net Pay',
    'net_pay_desc' => 'The final salary amount after all deductions and additions have been applied',

    'paid_amount' => 'Paid Amount',
    'paid_amount_desc' => 'The total amount that has already been paid to the employee',

    'remaining_amount' => 'Remaining',
    'remaining_amount_desc' => 'The amount that is still unpaid (Net Pay - Paid Amount)',

    'payment_status' => 'Payment Status',
    'payment_status_desc' => 'Indicates whether the salary is fully paid, partially paid, or unpaid',

    // Form Fields
    'employee_code' => 'Employee Code',
    'employee_name' => 'Employee Name',
    'salary_month' => 'Salary Month',
    'paid_so_far' => 'Paid So Far',
    'remaining' => 'Remaining',
    'month' => 'Month',
    'year' => 'Year',

    // Placeholders
    'enter_code' => 'Enter employee code',
    'enter_name' => 'Enter employee name',
    'select_month' => 'Select month',
    'select_year' => 'Select year',

    // Buttons
    'search' => 'Search',
    'reset' => 'Reset',
    'excel' => 'Excel',
    'pdf' => 'PDF',
    'delete_selection' => 'Delete Selected',
    'view' => 'View',
    'delete' => 'Delete',

    // Payment Status
    'fully_paid' => 'Fully Paid',
    'partially_paid' => 'Partially Paid',
    'unpaid' => 'Unpaid',

    // Actions
    'action' => 'Actions',

    // Create / List
    'create_payment' => 'Create New Payment',
    'payment_list' => 'Payments List',
    'save_payments' => 'Pay',
    'no_payrolls_found' => 'No payrolls found',
    'select_filters_to_see_payrolls' => 'Please select branch, month, and year to view payrolls',
    'please_select_month_year' => 'Please select month and year',
    'please_select_at_least_one' => 'Please select at least one payroll',
    'please_select_payment_type' => 'Please select payment type (full or partial) for each selected payroll',

    // Payment fields
    'total_paid' => 'Total Paid',
    'amount_to_pay' => 'Amount to Pay',
    'payment_date' => 'Payment Date',
    'payment_status_label' => 'Payment Status',
    'fully_paid_badge' => 'Fully Paid',


    // Messages
    'payments_saved_successfully' => 'Payments saved successfully',
    'some_errors_occurred' => 'Some errors occurred',
    'error_saving_payments' => 'Error while saving payments',
    'payroll_not_found' => 'Payroll not found',
    'amount_exceeds_remaining' => 'Amount exceeds remaining balance',
    'payment_deleted_successfully' => 'Payment deleted successfully',
    'payments_deleted_successfully' => 'Payments deleted successfully',
     // New translations for success messages
    'skipped_fully_paid' => 'Skipped (Already Fully Paid)',
    'no_payments_processed' => 'No payments were processed',
    'all_payrolls_fully_paid' => 'All payrolls for this period are already fully paid',


    // Notes
    'note_title' => 'Important Note:',
    'note_all_paid' => '✓ If you click Pay without selection: all salaries will be paid in full.',
    'note_partial_paid' => '✓ If some employees are selected for partial payment: they will be partially paid and the rest will be paid in full.',
    'note_ignore_fully_paid' => '✓ When completing payments for partially paid employees: fully paid employees will be automatically ignored.',

    // Payment Options
    'partial_payment' => 'Partial Payment',
    'enter_amount' => 'Enter the amount to be paid',
    'full_remaining_amount_notice' => 'The full remaining amount will be paid',

    // Buttons & Status
    'all_salaries_paid' => 'All salaries have been paid',

    // Validation & Messages
    'partial_amount_required' => 'Please enter a valid amount for partial payment',

];
