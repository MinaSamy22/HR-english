<?php

use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;

if (!function_exists('getEmployeeNotificationsCount')) {
    function getEmployeeNotificationsCount()
    {
        // Get current authenticated user ID
        $userId = auth()->id();

        // If no user authenticated, return 0
        if (!$userId) {
            return 0;
        }

        // Count unseen accepted/rejected vacation requests
        $vacationCount = VacationRequest::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->count();

        // Count unseen accepted/rejected extra time requests
        $extraTimeCount = ExtraTimeRequest::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->count();

        // Count unseen accepted/rejected resignation requests
        $resignationCount = Resignation::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->count();

        // Count unseen accepted/rejected late removal requests
        $lateRemovalCount = LateRemovalRequest::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->count();

        return $vacationCount + $extraTimeCount + $resignationCount + $lateRemovalCount;
    }
}

if (!function_exists('getEmployeeNotifications')) {
    function getEmployeeNotifications($limit = 10)
    {
        // Get current authenticated user ID
        $userId = auth()->id();

        // If no user authenticated, return empty array
        if (!$userId) {
            return [];
        }

        $notifications = [];

        // Get vacation requests that are accepted/rejected
        $vacationRequests = VacationRequest::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($vacationRequests as $request) {
            // Translate vacation type
            $vacationType = __('dashboard.vacation_types.' . $request->vacation_type, [], null, $request->vacation_type);

            $isAccepted = $request->status === 'accepted';

            $notifications[] = [
                'type' => 'vacation',
                'id' => $request->id,
                'table' => 'vacation_requests',
                'message' => $isAccepted
                    ? __('Your :type vacation request has been <strong>accepted</strong>', ['type' => $vacationType])
                    : __('Your :type vacation request has been <strong>rejected</strong>', ['type' => $vacationType]),
                'date' => $request->updated_at,
                'icon' => $isAccepted ? 'fas fa-calendar-check' : 'fas fa-calendar-times',
                'color' => $isAccepted ? 'text-success' : 'text-danger',
                'is_seen' => $request->is_seen,
                'status' => $request->status,
                'url' => route('employee.requests.show', ['type' => 'vacation', 'id' => $request->id])
            ];
        }

        // Get extra time requests that are accepted/rejected
        $extraTimeRequests = ExtraTimeRequest::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($extraTimeRequests as $request) {
            $isAccepted = $request->status === 'accepted';

            $notifications[] = [
                'type' => 'extra_time',
                'id' => $request->id,
                'table' => 'extra_time_requests',
                'message' => $isAccepted
                    ? __('Your overtime request for :hours hours has been <strong>accepted</strong>', ['hours' => $request->hours])
                    : __('Your overtime request for :hours hours has been <strong>rejected</strong>', ['hours' => $request->hours]),
                'date' => $request->updated_at,
                'icon' => $isAccepted ? 'fas fa-clock' : 'fas fa-clock',
                'color' => $isAccepted ? 'text-success' : 'text-danger',
                'is_seen' => $request->is_seen,
                'status' => $request->status,
                'url' => route('employee.requests.show', ['type' => 'extra_time', 'id' => $request->id])
            ];
        }

        // Get resignation requests that are accepted/rejected
        $resignationRequests = Resignation::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($resignationRequests as $request) {
            $isAccepted = $request->status === 'accepted';

            $notifications[] = [
                'type' => 'resignation',
                'id' => $request->id,
                'table' => 'resignations',
                'message' => $isAccepted
                    ? __('Your resignation request has been <strong>accepted</strong>')
                    : __('Your resignation request has been <strong>rejected</strong>'),
                'date' => $request->updated_at,
                'icon' => $isAccepted ? 'fas fa-user-check' : 'fas fa-user-times',
                'color' => $isAccepted ? 'text-success' : 'text-danger',
                'is_seen' => $request->is_seen,
                'status' => $request->status,
                'url' => route('employee.requests.show', ['type' => 'resignation', 'id' => $request->id])
            ];
        }

        // Get late removal requests that are accepted/rejected
        $lateRemovalRequests = LateRemovalRequest::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($lateRemovalRequests as $request) {
            $isAccepted = $request->status === 'accepted';

            $notifications[] = [
                'type' => 'late_removal',
                'id' => $request->id,
                'table' => 'late_removal_requests',
                'message' => $isAccepted
                    ? __('Your late removal request for :date has been <strong>accepted</strong>', ['date' => \Carbon\Carbon::parse($request->day)->format('M d')])
                    : __('Your late removal request for :date has been <strong>rejected</strong>', ['date' => \Carbon\Carbon::parse($request->day)->format('M d')]),
                'date' => $request->updated_at,
                'icon' => $isAccepted ? 'fas fa-user-clock' : 'fas fa-user-clock',
                'color' => $isAccepted ? 'text-success' : 'text-danger',
                'is_seen' => $request->is_seen,
                'status' => $request->status,
                'url' => route('employee.requests.show', ['type' => 'late_removal', 'id' => $request->id])
            ];
        }

        // Sort all notifications by date (newest first) and limit
        usort($notifications, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });

        return array_slice($notifications, 0, $limit);
    }
}
