<?php

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

        return $vacationCount + $extraTimeCount + $resignationCount + $lateRemovalCount;
    }
}
