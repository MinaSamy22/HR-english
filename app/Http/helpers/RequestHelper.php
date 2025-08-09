<?php

use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;

if (!function_exists('getPendingRequestsCount')) {
    function getPendingRequestsCount()
    {
        return VacationRequest::where('status', 'pending')->count()
            + ExtraTimeRequest::where('status', 'pending')->count()
            + Resignation::where('status', 'pending')->count()
            + LateRemovalRequest::where('status', 'pending')->count();
    }
}
