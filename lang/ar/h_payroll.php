<?php

return [
    // Page Title and Headers
    'payroll_list' => 'قائمة كشوف المرتبات',
    'create_payroll' => 'إنشاء كشف مرتبات',

    // Important Notice
    'important_notice' => 'مهم:',
    'notice_text' => 'قبل إنشاء كشف المرتبات، تأكد من تكوين سياسة الشركة بشكل صحيح. انتبه بشكل خاص لأيام العمل والعطلات الرسمية، حيث أنها تؤثر مباشرة على خصومات الحضور.',

    // Clarification Section
    'clarification_title' => 'توضيح كشف المرتبات:',
    'bonus_desc' => 'يتم حسابها بناءً على سياسة الشركة للمكافآت للساعات الإضافية في سياسة الشركة.',
    'deductions_desc' => 'تشمل الخصومات اليدوية والغرامات أو أيام الإجازة الإضافية المأخوذة خارج الرصيد.',
    'taxes_insurance_desc' => 'يتم خصمها تلقائياً بناءً على النسب المكونة الموجودة في الضرائب والتأمين.',
    'vacation_balance_desc' => 'يتم تحديدها بسياسة الشركة (مثلاً 21 أو 30 يوماً في السنة).',
    'net_pay_desc' => 'يتم حسابها كالتالي:',
    'net_pay_formula' => 'صافي الراتب = الراتب الأساسي - (الضرائب + التأمين + الخصومات + خصومات الحضور) + المكافآت',

    // Form Fields
    'employee_name' => 'اسم الموظف',
    'enter_name' => 'أدخل الاسم',
    'payroll_type' => 'نوع الرواتب',
    'select_payroll_type' => 'اختر نوع المرتب',
    'monthly' => 'شهري',
    'weekly' => 'أسبوعي',
    'daily' => 'يومي',
    'month' => 'الشهر',
    'select_month' => 'اختر الشهر',
    'year' => 'السنة',
    'select_year' => 'اختر السنة',

    // Buttons
    'search' => 'بحث',
    'reset' => 'إعادة تعيين',
    'excel' => 'إكسل',
    'pdf' => 'PDF',
    'delete_selection' => 'حذف المحدد',
    'edit' => 'تعديل',
    'delete' => 'حذف',

    // Table Headers
    'employee_id' => 'كود الموظف',
    'basic_salary' => 'الراتب الأساسي',
    'bonus' => 'المكافآت',
    'deductions' => 'الخصومات',
    'attendance_deduction' => 'خصم الحضور',
    'taxes_insurance' => 'الضرائب/التأمين',
    'vacation_balance' => 'رصيد الإجازات',
    'net_pay' => 'صافي الراتب',
    'pay_date' => 'تاريخ الدفع',
    'action' => 'الإجراءات',
    'day' => 'يوم',

    // Messages
    'no_row_selected' => 'لم يتم تحديد أي صف.',
    'delete_confirmation' => 'هل أنت متأكد من حذف المحدد؟',
    'delete_single_confirmation' => 'هل أنت متأكد من الحذف؟',
    'error_occurred' => 'حدث خطأ. يرجى المحاولة مرة أخرى.',

    // Controller Messages
    'payroll_registered' => 'تم تسجيل كشوف المرتبات بنجاح.',
    'payroll_updated' => 'تم التحديث بنجاح.',
    'record_deleted' => 'تم حذف السجل بنجاح.',
    'selected_deleted' => 'تم حذف كشوف المرتبات المحددة بنجاح.',
    'no_payroll_selected' => 'لم يتم تحديد كشف مرتبات.',
    'payroll_period_starts_before_hire_date' => 'فترة الراتب تبدأ قبل تاريخ التوظيف',
    'overlapping_payroll_exists_for_period' => 'يوجد راتب متداخل للفترة',
    'payroll_generation_failed_for_following_employees' => 'فشل في إنشاء الراتب للموظفين التاليين:',
    'note_payroll_successfully_generated_for_other_employees' => 'ملاحظة: تم إنشاء الراتب بنجاح للموظفين الآخرين.',
    'generated_for' => 'تم الإنشاء لـ',
    'is_insure' =>'مؤمن علية؟',
    'yes'=>'نعم',
    'no'=> 'لا',

     'payroll_types' => [
        'daily'   => 'يومي',
        'weekly'  => 'أسبوعي',
        'monthly' => 'شهري',
        'other'   => 'أخرى',
    ],
    'please_fix_errors_before_proceeding' => 'يرجى إصلاح الأخطاء أعلاه قبل المتابعة.',

     'months' => [
        1  => 'يناير',
        2  => 'فبراير',
        3  => 'مارس',
        4  => 'أبريل',
        5  => 'مايو',
        6  => 'يونيو',
        7  => 'يوليو',
        8  => 'أغسطس',
        9  => 'سبتمبر',
        10 => 'أكتوبر',
        11 => 'نوفمبر',
        12 => 'ديسمبر',
    ],

    'total_allowance' => 'إجمالي البدلات',

'select_payroll_type'   => 'يرجى اختيار نوع كشف الرواتب.',
'daily_exceeds_limit'   => 'كشف الرواتب اليومي لا يمكن أن يتجاوز 31 يومًا.',
'weekly_exceeds_limit'  => 'كشف الرواتب الأسبوعي لا يمكن أن يتجاوز 7 أيام.',
'monthly_minimum_days'  => 'كشف الرواتب الشهري يجب أن يتجاوز 7 أيام.',
'monthly_exceeds_limit' => 'كشف الرواتب الشهري لا يمكن أن يتجاوز 31 يومًا.',

];
