<?php

return [
    'early_leave_requests' => 'طلبات الانصراف المبكر',
    'submit_new_request' => 'إرسال طلب جديد',
    'date' => 'التاريخ',
    'leave_time' => 'وقت الانصراف',
    'reason_optional' => 'السبب (اختياري)',
    'reason_placeholder' => 'اكتب السبب هنا...',
    'urgent_request' => 'طلب عاجل',
    'leave_time_selected' => 'الوقت المختار للانصراف',
    'date_label' => 'التاريخ المختار',
    'submit_request' => 'إرسال الطلب',
    'reset_form' => 'إعادة تعيين النموذج',
    'summary' => 'الملخص',
    'approved' => 'مقبول',
    'pending' => 'قيد الانتظار',
    'rejected' => 'مرفوض',
    'total' => 'الإجمالي',
    'history' => 'سجل الطلبات',
    'no_requests' => 'لا توجد طلبات.',
    'no_reason' => 'لم يتم ذكر سبب',
    'confirm_cancel' => 'هل أنت متأكد من إلغاء هذا الطلب؟',
    'notes' => 'ملاحظات',

    // 🟢 Notes text
    'note1' => 'يمكن لكل موظف إرسال طلب انصراف مبكر واحد فقط في نفس التاريخ.',
    'note2' => 'يجب تقديم الطلبات مسبقًا والموافقة عليها من المشرف.',
    'note3' => 'يُستخدم الطلب العاجل فقط في حالات الطوارئ.',

    'reason' => 'السبب',
    'status' => 'الحالة',
    'submitted_at' => 'تاريخ الإرسال',
    'action' => 'الإجراء',

    'request_sent_successfully' => 'تم إرسال الطلب بنجاح.',
    'request_cancelled_successfully' => 'تم إلغاء الطلب بنجاح.',

        'validation' => [
        'request_date_required' => 'تاريخ الطلب مطلوب.',
        'request_date_date'     => 'يجب أن يكون تاريخ الطلب تاريخًا صالحًا.',
        'request_date_unique'   => 'لقد أرسلت طلبًا لهذا التاريخ بالفعل.',
        'requested_leave_time_required' => 'وقت الانصراف مطلوب.',
        'requested_leave_time_date_format' => 'يجب أن يكون وقت الانصراف بالتنسيق ساعة:دقيقة.',
        'reason_required'       => 'يرجى تقديم سبب للطلب.',
        'reason_string'         => 'يجب أن يكون السبب نصًا صالحًا.',
        'reason_min'            => 'يجب أن يكون السبب على الأقل 4 أحرف.',
    ],

];
