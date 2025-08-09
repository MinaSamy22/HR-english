<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\VacationRequest;
use App\Models\ExtraTimeRequest;
use App\Models\Resignation;
use App\Models\LateRemovalRequest;

class RequestController extends Controller
{
    public function index()
    {
        // Get pending requests
        $pendingVacations = VacationRequest::where('status', 'pending')->with('user')->get();
        $pendingExtraTimes = ExtraTimeRequest::where('status', 'pending')->with('user')->get();
        $pendingResignations = Resignation::where('status', 'pending')->with('user')->get();
        $pendingLateRemovals = LateRemovalRequest::where('status', 'pending')->with('user')->get();

        return view('backend.requests.pending', compact(
            'pendingVacations',
            'pendingExtraTimes',
            'pendingResignations',
            'pendingLateRemovals'
        ));
    }

    public function processed(Request $request)
    {
        // Get filter parameters
        $selectedMonth = $request->get('month');
        $searchName = $request->get('search_name');

        // Build processed requests queries with filters
        $processedVacationsQuery = VacationRequest::whereIn('status', ['accepted', 'rejected'])
            ->with('user')
            ->orderBy('updated_at', 'desc');

        $processedExtraTimesQuery = ExtraTimeRequest::whereIn('status', ['accepted', 'rejected'])
            ->with('user')
            ->orderBy('updated_at', 'desc');

        $processedResignationsQuery = Resignation::whereIn('status', ['accepted', 'rejected'])
            ->with('user')
            ->orderBy('updated_at', 'desc');

        $processedLateRemovalsQuery = LateRemovalRequest::whereIn('status', ['accepted', 'rejected'])
            ->with('user')
            ->orderBy('updated_at', 'desc');

        // Apply month filter if selected
        if ($selectedMonth) {
            $year = date('Y');
            $month = $selectedMonth;

            $processedVacationsQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);

            $processedExtraTimesQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);

            $processedResignationsQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);

            $processedLateRemovalsQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);
        }

        // Apply name search filter if provided
        if ($searchName) {
            $processedVacationsQuery->whereHas('user', function($query) use ($searchName) {
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            });

            $processedExtraTimesQuery->whereHas('user', function($query) use ($searchName) {
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            });

            $processedResignationsQuery->whereHas('user', function($query) use ($searchName) {
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            });

            $processedLateRemovalsQuery->whereHas('user', function($query) use ($searchName) {
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            });
        }

        // Execute queries
        $processedVacations = $processedVacationsQuery->get();
        $processedExtraTimes = $processedExtraTimesQuery->get();
        $processedResignations = $processedResignationsQuery->get();
        $processedLateRemovals = $processedLateRemovalsQuery->get();

        // Generate months list for filter dropdown
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('backend.requests.processed', compact(
            'processedVacations',
            'processedExtraTimes',
            'processedResignations',
            'processedLateRemovals',
            'months',
            'selectedMonth',
            'searchName'
        ));
    }

    public function accept($type, $id)
    {
        $model = $this->getModelInstance($type, $id);
        $model->status = 'accepted';
        $model->save();

        return back()->with('success', 'Request accepted successfully.');
    }

    public function reject($type, $id)
    {
        $model = $this->getModelInstance($type, $id);
        $model->status = 'rejected';
        $model->save();

        return back()->with('success', 'Request rejected successfully.');
    }

    private function getModelInstance($type, $id)
    {
        switch ($type) {
            case 'vacation':
                return VacationRequest::findOrFail($id);
            case 'extra_time':
                return ExtraTimeRequest::findOrFail($id);
            case 'resignation':
                return Resignation::findOrFail($id);
            case 'late_removal':
                return LateRemovalRequest::findOrFail($id);
            default:
                abort(404);
        }
    }
}
