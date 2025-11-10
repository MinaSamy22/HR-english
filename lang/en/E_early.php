<?php


return [
    'early_leave_requests' => 'Early Leave Requests',
    'submit_new_request' => 'Submit New Request',
    'date' => 'Date',
    'leave_time' => 'Leave Time',
    'reason_optional' => 'Reason',
    'reason_placeholder' => 'Write your reason here...',
    'urgent_request' => 'Mark as Urgent',
    'leave_time_selected' => 'Leave Time Selected',
    'date_label' => 'Selected Date',
    'submit_request' => 'Submit Request',
    'reset_form' => 'Reset Form',
    'summary' => 'Summary',
    'approved' => 'Approved',
    'pending' => 'Pending',
    'rejected' => 'Rejected',
    'total' => 'Total',
    'history' => 'Request History',
    'no_requests' => 'No requests found.',
    'no_reason' => 'No reason provided',
    'confirm_cancel' => 'Are you sure you want to cancel this request?',
    'notes' => 'Notes',

    // 🟢 Notes text
    'note1' => 'Each employee can send only one early leave request per date.',
    'note2' => 'Requests must be submitted in advance and approved by your supervisor.',
    'note3' => 'Urgent requests should be used only for emergencies.',

    'reason' => 'Reason',
    'status' => 'Status',
    'submitted_at' => 'Submitted At',
    'action' => 'Action',

    'request_sent_successfully' => 'Request sent successfully.',
    'request_cancelled_successfully' => 'Request cancelled successfully.',

        'validation' => [
        'request_date_required' => 'The request date is required.',
        'request_date_date'     => 'The request date must be a valid date.',
        'request_date_unique'   => 'You have already sent a request for this date.',
        'requested_leave_time_required' => 'The leave time is required.',
        'requested_leave_time_date_format' => 'The leave time must be in the format HH:MM.',
        'reason_required'       => 'Please provide a reason for your request.',
        'reason_string'         => 'The reason must be a valid text.',
        'reason_min'            => 'The reason must be at least 4 characters.',
    ],


];
