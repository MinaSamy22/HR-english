<?php

return [
    // Page Title and Headers
    'payroll_list' => 'تنخواہوں کی فہرست',
    'create_payroll' => 'تنخواہ بنائیں',

    // Important Notice
    'important_notice' => 'اہم:',
    'notice_text' => 'تنخواہ بنانے سے پہلے، یقینی بنائیں کہ آپ کی کمپنی کی پالیسی درست طریقے سے تشکیل دی گئی ہے۔ خاص طور پر کام کے دنوں اور سرکاری چھٹیوں پر توجہ دیں کیونکہ یہ براہ راست حاضری کی کٹوتیوں پر اثرانداز ہوتے ہیں۔',

    // Clarification Section
    'clarification_title' => 'تنخواہ کی وضاحت:',
    'bonus_desc' => 'کمپنی کی پالیسی کے مطابق اضافی گھنٹوں پر بونس کے حساب سے۔',
    'deductions_desc' => 'اس میں دستی کٹوتیاں، جرمانے، یا دستیاب بیلنس سے زیادہ لی گئی چھٹیاں شامل ہیں۔',
    'taxes_insurance_desc' => 'ٹیکس اور انشورنس میں مقرر کردہ فیصد کے مطابق خودکار کٹوتی۔',
    'vacation_balance_desc' => 'کمپنی کی پالیسی کے مطابق (مثال کے طور پر سالانہ 21 یا 30 دن)۔',
    'net_pay_desc' => 'حساب اس طرح کیا جاتا ہے:',
    'net_pay_formula' => 'خالص تنخواہ = بنیادی تنخواہ - (ٹیکس + انشورنس + کٹوتیاں + حاضری کی کٹوتیاں) + بونس',

    // Form Fields
    'employee_name' => 'ملازم کا نام',
    'enter_name' => 'نام درج کریں',
    'payroll_type' => 'تنخواہ کی قسم',
    'select_payroll_type' => 'تنخواہ کی قسم منتخب کریں',
    'monthly' => 'ماہانہ',
    'weekly' => 'ہفتہ وار',
    'daily' => 'یومیہ',
    'month' => 'مہینہ',
    'select_month' => 'مہینہ منتخب کریں',
    'year' => 'سال',
    'select_year' => 'سال منتخب کریں',

    // Buttons
    'search' => 'تلاش',
    'reset' => 'ری سیٹ',
    'excel' => 'ایکسسل',
    'pdf' => 'پی ڈی ایف',
    'delete_selection' => 'منتخب کو حذف کریں',
    'edit' => 'ترمیم کریں',
    'delete' => 'حذف کریں',

    // Table Headers
    'employee_id' => 'ملازم آئی ڈی',
    'basic_salary' => 'بنیادی تنخواہ',
    'bonus' => 'بونس',
    'deductions' => 'کٹوتیاں',
    'attendance_deduction' => 'حاضری کی کٹوتی',
    'taxes_insurance' => 'ٹیکس/انشورنس',
    'vacation_balance' => 'چھٹیوں کا بیلنس',
    'net_pay' => 'خالص تنخواہ',
    'pay_date' => 'ادائیگی کی تاریخ',
    'action' => 'عمل',
    'day' => 'دن',

    // Messages
    'no_row_selected' => 'کوئی قطار منتخب نہیں کی گئی۔',
    'delete_confirmation' => 'کیا آپ واقعی منتخب اندراجات کو حذف کرنا چاہتے ہیں؟',
    'delete_single_confirmation' => 'کیا آپ واقعی حذف کرنا چاہتے ہیں؟',
    'error_occurred' => 'ایک خرابی پیش آگئی۔ براہ کرم دوبارہ کوشش کریں۔',

    // Controller Messages
    'payroll_registered' => 'تنخواہیں کامیابی سے درج ہو گئیں۔',
    'payroll_updated' => 'کامیابی سے اپ ڈیٹ ہو گیا۔',
    'record_deleted' => 'ریکارڈ کامیابی سے حذف ہو گیا۔',
    'selected_deleted' => 'منتخب شدہ تنخواہیں کامیابی سے حذف ہو گئیں۔',
    'no_payroll_selected' => 'کوئی تنخواہ منتخب نہیں کی گئی۔',

    'payroll_period_starts_before_hire_date' => 'تنخواہ کی مدت ملازمت شروع ہونے کی تاریخ سے پہلے ہے۔',
    'overlapping_payroll_exists_for_period' => 'اس مدت کے لئے پہلے سے ایک تنخواہ موجود ہے۔',
    'payroll_generation_failed_for_following_employees' => 'مندرجہ ذیل ملازمین کے لئے تنخواہ کی تیاری ناکام رہی:',
    'note_payroll_successfully_generated_for_other_employees' => 'نوٹ: دیگر ملازمین کے لئے تنخواہ کامیابی سے تیار ہو گئی۔',
    'generated_for' => 'تیار کی گئی برائے',
    'is_insure' =>'کیا بیمہ شدہ ہے؟',
    'yes'=>'ہاں',
    'no'=> 'نہیں',

    // NEW: Message Templates for proper formatting
    'success_message' => ':payroll_message<br><br>:generated_for:<br>:employee_list',
    'error_message' => ':failed_message<br><br>:error_list',
    'mixed_message' => ':payroll_message<br><br>:generated_for:<br>:employee_list<br><br>:failed_message<br><br>:error_list<br>:note_message',
    'employee_item' => '• :name',
    'error_item' => '• :error',

    'payroll_types' => [
        'daily'   => 'یومیہ',
        'weekly'  => 'ہفتہ وار',
        'monthly' => 'ماہانہ',
        'other'   => 'دیگر',
    ],

    'please_fix_errors_before_proceeding' => 'آگے بڑھنے سے پہلے براہ کرم مذکورہ بالا غلطیوں کو درست کریں۔',

    'months' => [
        1  => 'جنوری',
        2  => 'فروری',
        3  => 'مارچ',
        4  => 'اپریل',
        5  => 'مئی',
        6  => 'جون',
        7  => 'جولائی',
        8  => 'اگست',
        9  => 'ستمبر',
        10 => 'اکتوبر',
        11 => 'نومبر',
        12 => 'دسمبر',
    ],

    'total_allowance' => 'کل الاؤنسز',

];
