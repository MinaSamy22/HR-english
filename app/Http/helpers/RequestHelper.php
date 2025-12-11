<?php

use App\Models\EarlyLeaveRequest;
use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;



if (!function_exists('getPendingRequestsCount')) {
    function getPendingRequestsCount()
    {
        // Get current company ID and branch ID from session
        $companyId = session('company_id');
        $branchId = session('branch_id');

        // If no company ID in session, return 0
        if (!$companyId) {
            return 0;
        }

        // Determine filtering logic based on branch_id and is_main
        $showAllCompanyRequests = false;
        $filterBranchId = null;

        if ($branchId) {
            $currentBranch = \App\Models\Branch::find($branchId);
            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch - show all company requests
                $showAllCompanyRequests = true;
            } else {
                // Regular branch - show only this branch's requests
                $filterBranchId = $branchId;
            }
        } else {
            // No branch_id in session - show all company requests
            $showAllCompanyRequests = true;
        }

        // Create a closure for the user filtering logic
        $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
            if ($showAllCompanyRequests) {
                $query->where('company_id', $companyId);
            } else {
                $query->where('branch_id', $filterBranchId);
            }
        };

        //start counting the requests here ↓
        // Count pending vacation requests with consistent filtering
        $vacationCount = VacationRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count pending extra time requests with consistent filtering
        $extraTimeCount = ExtraTimeRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count pending resignation requests with consistent filtering
        $resignationCount = Resignation::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count pending late removal requests with consistent filtering
        $lateRemovalCount = LateRemovalRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count pending early leave requests with consistent filtering
        $earlyLeaveCount = EarlyLeaveRequest::where('status', 'pending')
            ->whereHas('user', $userFilterClosure)
            ->count();

        return $vacationCount + $extraTimeCount + $resignationCount + $lateRemovalCount + $earlyLeaveCount;
    }
}

if (!function_exists('getProcessedRequestsCount')) {
    function getProcessedRequestsCount()
    {
        // Get current company ID and branch ID from session
        $companyId = session('company_id');
        $branchId = session('branch_id');

        // If no company ID in session, return 0
        if (!$companyId) {
            return 0;
        }

        // Determine filtering logic based on branch_id and is_main
        $showAllCompanyRequests = false;
        $filterBranchId = null;

        if ($branchId) {
            $currentBranch = \App\Models\Branch::find($branchId);
            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch - show all company requests
                $showAllCompanyRequests = true;
            } else {
                // Regular branch - show only this branch's requests
                $filterBranchId = $branchId;
            }
        } else {
            // No branch_id in session - show all company requests
            $showAllCompanyRequests = true;
        }

        // Create a closure for the user filtering logic
        $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
            if ($showAllCompanyRequests) {
                $query->where('company_id', $companyId);
            } else {
                $query->where('branch_id', $filterBranchId);
            }
        };

        // Count unseen processed vacation requests
        $vacationCount = VacationRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count unseen processed extra time requests
        $extraTimeCount = ExtraTimeRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count unseen processed resignation requests
        $resignationCount = Resignation::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count unseen processed late removal requests
        $lateRemovalCount = LateRemovalRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->whereHas('user', $userFilterClosure)
            ->count();

        // Count unseen processed early leave requests
        $earlyLeaveCount = EarlyLeaveRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->whereHas('user', $userFilterClosure)
            ->count();

        return $vacationCount + $extraTimeCount + $resignationCount + $lateRemovalCount + $earlyLeaveCount;
    }
}

if (!function_exists('getPendingNotifications')) {
    function getPendingNotifications($limit = 10)
    {
        // Get current company ID and branch ID from session
        $companyId = session('company_id');
        $branchId = session('branch_id');

        // If no company ID in session, return empty array
        if (!$companyId) {
            return [];
        }

        // Determine filtering logic based on branch_id and is_main
        $showAllCompanyRequests = false;
        $filterBranchId = null;

        if ($branchId) {
            $currentBranch = \App\Models\Branch::find($branchId);
            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch - show all company requests
                $showAllCompanyRequests = true;
            } else {
                // Regular branch - show only this branch's requests
                $filterBranchId = $branchId;
            }
        } else {
            // No branch_id in session - show all company requests
            $showAllCompanyRequests = true;
        }

        // Create a closure for the user filtering logic
        $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
            if ($showAllCompanyRequests) {
                $query->where('company_id', $companyId);
            } else {
                $query->where('branch_id', $filterBranchId);
            }
        };

        $notifications = [];

        // Get vacation requests
        $vacationRequests = VacationRequest::where('status', 'pending')
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($vacationRequests as $request) {
            // Translate vacation type
            $vacationType = __('dashboard.vacation_types.' . $request->vacation_type, [], null, $request->vacation_type);

            $notifications[] = [
                'type' => 'vacation',
                'id' => $request->id,
                'message' => __('dashboard.vacation_request', [
                    'name' => $request->user->name,
                    'type' => $vacationType
                ]),
                'date' => $request->created_at,
                'icon' => 'fas fa-calendar-alt',
                'color' => 'text-primary',
                'url' => route('Requests', $request->id)
            ];
        }

        // Get extra time requests
        $extraTimeRequests = ExtraTimeRequest::where('status', 'pending')
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($extraTimeRequests as $request) {
            $notifications[] = [
                'type' => 'extra_time',
                'id' => $request->id,
                'message' => __('dashboard.extra_time_request', [
                    'name' => $request->user->name,
                    'hours' => $request->hours
                ]),
                'date' => $request->created_at,
                'icon' => 'fas fa-clock',
                'color' => 'text-warning',
                'url' => route('Requests', $request->id)
            ];
        }

        // Get resignation requests
        $resignationRequests = Resignation::where('status', 'pending')
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($resignationRequests as $request) {
            $notifications[] = [
                'type' => 'resignation',
                'id' => $request->id,
                'message' => __('dashboard.resignation_request', [
                    'name' => $request->user->name
                ]),
                'date' => $request->created_at,
                'icon' => 'fas fa-user-times',
                'color' => 'text-danger',
                'url' => route('Requests', $request->id)
            ];
        }

        // Get late removal requests
        $lateRemovalRequests = LateRemovalRequest::where('status', 'pending')
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($lateRemovalRequests as $request) {
            $notifications[] = [
                'type' => 'late_removal',
                'id' => $request->id,
                'message' => __('dashboard.late_removal_request', [
                    'name' => $request->user->name,
                    'date' => \Carbon\Carbon::parse($request->day)->format('M d')
                ]),
                'date' => $request->created_at,
                'icon' => 'fas fa-user-clock',
                'color' => 'text-info',
                'url' => route('Requests', $request->id)
            ];
        }

        // Get early leave requests
        $earlyLeaveRequests = EarlyLeaveRequest::where('status', 'pending')
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($earlyLeaveRequests as $request) {
            $notifications[] = [
                'type' => 'early_leave',
                'id' => $request->id,
                'message' => __('dashboard.early_leave_request', [
                    'name' => $request->user->name,
                    'time' => \Carbon\Carbon::parse($request->requested_leave_time)->format('H:i')
                ]),
                'date' => $request->created_at,
                'icon' => 'fas fa-door-open',
                'color' => 'text-success',
                'url' => route('Requests', $request->id),
                'urgent' => $request->urgent_request
            ];
        }

        // Sort all notifications by date (newest first) and limit
        usort($notifications, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });

        return array_slice($notifications, 0, $limit);
    }
}

if (!function_exists('getProcessedNotifications')) {
    function getProcessedNotifications($limit = 10)
    {
        // Get current company ID and branch ID from session
        $companyId = session('company_id');
        $branchId = session('branch_id');

        // If no company ID in session, return empty array
        if (!$companyId) {
            return [];
        }

        // Determine filtering logic based on branch_id and is_main
        $showAllCompanyRequests = false;
        $filterBranchId = null;

        if ($branchId) {
            $currentBranch = \App\Models\Branch::find($branchId);
            if ($currentBranch && $currentBranch->is_main == 1) {
                // Main branch - show all company requests
                $showAllCompanyRequests = true;
            } else {
                // Regular branch - show only this branch's requests
                $filterBranchId = $branchId;
            }
        } else {
            // No branch_id in session - show all company requests
            $showAllCompanyRequests = true;
        }

        // Create a closure for the user filtering logic
        $userFilterClosure = function($query) use ($showAllCompanyRequests, $companyId, $filterBranchId) {
            if ($showAllCompanyRequests) {
                $query->where('company_id', $companyId);
            } else {
                $query->where('branch_id', $filterBranchId);
            }
        };

        $notifications = [];

        // Get processed vacation requests (unseen)
        $vacationRequests = VacationRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($vacationRequests as $request) {
            // Translate vacation type
            $vacationType = __('dashboard.vacation_types.' . $request->vacation_type, [], null, $request->vacation_type);

            // Get status message
            $statusMessage = $request->status == 'accepted'
                ? __('dashboard.request_accepted')
                : __('dashboard.request_rejected');

            $notifications[] = [
                'type' => 'vacation', // Changed from 'vacation_processed'
                'request_type' => 'vacation',
                'id' => $request->id,
                'status' => $request->status,
                'message' => __('dashboard.vacation_request_processed', [
                    'name' => $request->user->name,
                    'type' => $vacationType,
                    'status' => $statusMessage
                ]),
                'date' => $request->updated_at,
                'icon' => 'fas fa-calendar-alt',
                'color' => 'text-primary', // Base color for vacation (blue)
                'url' => route('processed-requests.show', ['type' => 'vacation', 'id' => $request->id])
            ];
        }

        // Get processed extra time requests (unseen)
        $extraTimeRequests = ExtraTimeRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($extraTimeRequests as $request) {
            $statusMessage = $request->status == 'accepted'
                ? __('dashboard.request_accepted')
                : __('dashboard.request_rejected');

            $notifications[] = [
                'type' => 'extra_time', // Changed from 'extra_time_processed'
                'request_type' => 'extra_time',
                'id' => $request->id,
                'status' => $request->status,
                'message' => __('dashboard.extra_time_request_processed', [
                    'name' => $request->user->name,
                    'hours' => $request->hours,
                    'status' => $statusMessage
                ]),
                'date' => $request->updated_at,
                'icon' => 'fas fa-clock',
                'color' => 'text-warning', // Base color for extra time (orange/yellow)
                'url' => route('processed-requests.show', ['type' => 'extra_time', 'id' => $request->id])
            ];
        }

        // Get processed resignation requests (unseen)
        $resignationRequests = Resignation::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($resignationRequests as $request) {
            $statusMessage = $request->status == 'accepted'
                ? __('dashboard.request_accepted')
                : __('dashboard.request_rejected');

            $notifications[] = [
                'type' => 'resignation', // Changed from 'resignation_processed'
                'request_type' => 'resignation',
                'id' => $request->id,
                'status' => $request->status,
                'message' => __('dashboard.resignation_request_processed', [
                    'name' => $request->user->name,
                    'status' => $statusMessage
                ]),
                'date' => $request->updated_at,
                'icon' => 'fas fa-user-times',
                'color' => 'text-danger', // Base color for resignation (red)
                'url' => route('processed-requests.show', ['type' => 'resignation', 'id' => $request->id])
            ];
        }

        // Get processed late removal requests (unseen)
        $lateRemovalRequests = LateRemovalRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($lateRemovalRequests as $request) {
            $statusMessage = $request->status == 'accepted'
                ? __('dashboard.request_accepted')
                : __('dashboard.request_rejected');

            $notifications[] = [
                'type' => 'late_removal', // Changed from 'late_removal_processed'
                'request_type' => 'late_removal',
                'id' => $request->id,
                'status' => $request->status,
                'message' => __('dashboard.late_removal_request_processed', [
                    'name' => $request->user->name,
                    'date' => \Carbon\Carbon::parse($request->day)->format('M d'),
                    'status' => $statusMessage
                ]),
                'date' => $request->updated_at,
                'icon' => 'fas fa-user-clock',
                'color' => 'text-secondary', // Base color for late removal (gray)
                'url' => route('processed-requests.show', ['type' => 'late_removal', 'id' => $request->id])
            ];
        }

// Get processed early leave requests (unseen)
        $earlyLeaveRequests = EarlyLeaveRequest::whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', 0)
            ->with('user')
            ->whereHas('user', $userFilterClosure)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        foreach ($earlyLeaveRequests as $request) {
            $statusMessage = $request->status == 'accepted'
                ? __('dashboard.request_accepted')
                : __('dashboard.request_rejected');

            $notifications[] = [
                'type' => 'early_leave',
                'request_type' => 'early_leave',
                'id' => $request->id,
                'status' => $request->status,
                'message' => __('dashboard.early_leave_request_processed', [
                    'name' => $request->user->name,
                    'time' => \Carbon\Carbon::parse($request->requested_leave_time)->format('H:i'),
                    'status' => $statusMessage
                ]),
                'date' => $request->updated_at,
                'icon' => 'fas fa-door-open',
                'color' => 'text-success',
                'url' => route('processed-requests.show', ['type' => 'early_leave', 'id' => $request->id])
            ];
        }


        // Sort all notifications by date (newest first) and limit
        usort($notifications, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });

        return array_slice($notifications, 0, $limit);
    }
}
