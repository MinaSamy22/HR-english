<?php
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Carbon\Carbon;

function getUnreadMessagesCount()
{
    if (Auth::guard('employee')->check()) {
        $employeeId = (string) Auth::guard('employee')->id();

        $messages = Message::whereJsonContains('recipient_ids', $employeeId)->get();

        $unreadCount = 0;
        foreach ($messages as $message) {
            $readBy = $message->read_by;
            if (!empty($readBy) && !is_array($readBy)) {
                $readBy = json_decode($readBy, true);
            }
            if (empty($readBy) || !array_key_exists($employeeId, $readBy)) {
                $unreadCount++;
            }
        }
        return $unreadCount;
    }
    return 0;
}

function getUnreadMessages($limit = 10)
{
    if (Auth::guard('employee')->check()) {
        $employeeId = (string) Auth::guard('employee')->id();

        $messages = Message::whereJsonContains('recipient_ids', $employeeId)
            ->latest()
            ->take($limit)
            ->get();

        $notifications = [];

        foreach ($messages as $msg) {
            $readBy = $msg->read_by;
            if (!empty($readBy) && !is_array($readBy)) {
                $readBy = json_decode($readBy, true);
            }

            // لو الرسالة غير مقروءة
            if (empty($readBy) || !array_key_exists($employeeId, $readBy)) {
                $notifications[] = [
                    'id' => $msg->id,
                    'icon' => 'fas fa-envelope',
                    'message' => $msg->subject,
                    'content' => $msg->content,
                    'date' => Carbon::parse($msg->created_at),
                    'url' => route('employee.messages.show', $msg->id), // لازم تعمل route للعرض
                ];
            }
        }

        return $notifications;
    }

    return [];
}
