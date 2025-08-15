<?php

use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;

if (!function_exists('getPendingRequestsCount')) {
    function getPendingRequestsCount()
    {
        // Get current company ID from session
        $companyId = session('company_id');

        // If no company ID in session, return 0
        if (!$companyId) {
            return 0;
        }

        // Count pending vacation requests for current company
        $vacationCount = VacationRequest::where('status', 'pending')
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->count();

        // Count pending extra time requests for current company
        $extraTimeCount = ExtraTimeRequest::where('status', 'pending')
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->count();

        // Count pending resignation requests for current company
        $resignationCount = Resignation::where('status', 'pending')
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->count();

        // Count pending late removal requests for current company
        $lateRemovalCount = LateRemovalRequest::where('status', 'pending')
            ->whereHas('user', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->count();

        return $vacationCount + $extraTimeCount + $resignationCount + $lateRemovalCount;
    }
}
