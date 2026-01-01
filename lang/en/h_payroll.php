<?php

return [
    // Page Title and Headers
    'payroll_list' => 'Payroll List',
    'create_payroll' => 'Create Payroll',

    // Important Notice
    'important_notice' => 'Important:',
    'notice_text' => 'Before creating payroll, make sure your company policy is configured correctly. Pay special attention to working days and official holidays, as they directly affect attendance deductions.',

    // Clarification Section
    'clarification_title' => 'Clarification of the Payroll:',
    'bonus_desc' => 'Calculated based on the company\'s bonus policy for extra hours in company policy.',
    'deductions_desc' => 'Includes manual deductions, penalties, or extra vacation days taken beyond the balance.',
    'taxes_insurance_desc' => 'Automatically deducted based on the configured percentages locate in the tax and insurance.',
    'vacation_balance_desc' => 'Determined by the company\'s policy (e.g., 21 or 30 days per year).',
    'net_pay_desc' => 'Calculated as:',
    'net_pay_formula' => 'Net Pay = Basic Salary - (Taxes + Insurance + Deductions + Attendance Deductions) + Bonus',

    // Form Fields
    'employee_name' => 'Employee Name',
    'enter_name' => 'Enter Name',
    'payroll_type' => 'Payroll Type',
    'select_payroll_type' => 'Select Payroll Type',
    'monthly' => 'Monthly',
    'weekly' => 'Weekly',
    'daily' => 'Daily',
    'month' => 'Month',
    'select_month' => 'Select Month',
    'year' => 'Year',
    'select_year' => 'Select Year',

    // Buttons
    'search' => 'Search',
    'reset' => 'Reset',
    'excel' => 'Excel',
    'pdf' => 'PDF',
    'delete_selection' => 'Delete Selection',
    'edit' => 'Edit',
    'delete' => 'Delete',

    // Table Headers
    'employee_id' => 'Employee ID',
    'basic_salary' => 'Basic Salary',
    'bonus' => 'Bonus',
    'deductions' => 'Deductions',
    'attendance_deduction' => 'Attendance Deduction',
    'taxes_insurance' => 'Taxes/Insurance',
    'vacation_balance' => 'Vacation Balance',
    'net_pay' => 'Net Pay',
    'pay_date' => 'Pay Date',
    'action' => 'Action',
    'day' => 'day',

    // Messages
    'no_row_selected' => 'No row selected.',
    'delete_confirmation' => 'Are you sure you want to delete the selection?',
    'delete_single_confirmation' => 'Are you sure you want to delete?',
    'error_occurred' => 'An error occurred. Please try again.',

    // Controller Messages
    'payroll_registered' => 'Payrolls successfully registered.',
    'payroll_updated' => 'Successfully updated.',
    'record_deleted' => 'Record successfully deleted.',
    'selected_deleted' => 'Selected Payroll deleted successfully.',
    'no_payroll_selected' => 'No Payroll selected.',

    'payroll_period_starts_before_hire_date' => 'Payroll period starts before hire date',
    'overlapping_payroll_exists_for_period' => 'Overlapping payroll exists for period',
    'payroll_generation_failed_for_following_employees' => 'Payroll generation failed for the following employees:',
    'note_payroll_successfully_generated_for_other_employees' => 'Note: Payroll was successfully generated for other employees.',
    'generated_for' => 'Generated for',
    'is_insure' =>'Is insured?',
    'yes'=>'yes',
    'no'=> 'no',

    // NEW: Message Templates for proper formatting
    'success_message' => ':payroll_message<br><br>:generated_for:<br>:employee_list',
    'error_message' => ':failed_message<br><br>:error_list',
    'mixed_message' => ':payroll_message<br><br>:generated_for:<br>:employee_list<br><br>:failed_message<br><br>:error_list<br>:note_message',
    'employee_item' => '• :name',
    'error_item' => '• :error',

    'payroll_types' => [
        'daily'   => 'Daily',
        'weekly'  => 'Weekly',
        'monthly' => 'Monthly',
        'other'   => 'Other',
    ],

    'please_fix_errors_before_proceeding' => 'Please fix the above errors before proceeding.',

     'months' => [
        1  => 'January',
        2  => 'February',
        3  => 'March',
        4  => 'April',
        5  => 'May',
        6  => 'June',
        7  => 'July',
        8  => 'August',
        9  => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ],

    'total_allowance' => 'Total Allowances',

    'select_payroll_type' => 'Please select a payroll type.',
    'daily_exceeds_limit' => 'Daily payroll cannot exceed 31 days.',
    'weekly_exceeds_limit' => 'Weekly payroll cannot exceed 7 days.',
    'monthly_minimum_days' => 'Monthly payroll must exceed 7 days.',
    'monthly_exceeds_limit' => 'Monthly payroll cannot exceed 31 days.',

];
