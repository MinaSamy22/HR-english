<?php

namespace App\Http\Controllers\Api\Employee;

use Illuminate\Http\Request;
use App\Services\EmployeeService;
use function included\sendResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\VacationResource;
use App\Http\Requests\VacationRequest;
use Carbon\Carbon;
use App\Enums\VacationType;

class VacationController extends Controller
{
    protected $employeeService;

    public function __construct()
    {
        if(auth('api')->check()){
            $this->employeeService = new EmployeeService(auth('api')->user()->load('company.attendanceSetting'));
        }
    }

    public function index(Request $request)
    {
        $vacations = $this->employeeService->getVacations($request->from,$request->to);

        return sendResponse(VacationResource::collection($vacations),'');
    }

    public function vacationRequests(Request $request)
    {
        $vacations = $this->employeeService->getVacationRequests($request->from,$request->to);

        return sendResponse(VacationResource::collection($vacations),'');
    }

    public function store(VacationRequest $request){
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $hasVacation = $this->employeeService->getUser()->vacations()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        $hasRequest = $this->employeeService->getUser()->vacationRequests()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->where('status','!=',  VacationType::REJECTED->value)
            ->exists();

        if ($hasVacation || $hasRequest) {
            return sendResponse([], 'You already have a vacation or request overlapping with the selected dates.', 0);
        }


        $totalDays = $endDate->diffInDays($startDate) + 1;
        $employee = $this->employeeService->getUser();

        $vacationLimit = $employee->vacation_balance ?? 0;

        $totalUsed = $this->employeeService->getVacations()->sum('total');

        $remainingBalance = max(0, $vacationLimit - $totalUsed);

        if ($remainingBalance <= 0) 
        {
            return sendResponse([],'You have exhausted your allowed vacation balance. ',0);
        }

        if ($totalDays > $remainingBalance) {
            return sendResponse([],"You are trying to request $totalDays days, but the remaining balance is only $remainingBalance days. Vacation request denied.",0);
        }
        $data =[
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason'=>$request->reason,
            'vacation_type'=>$request->vacation_type,
            'status'=>VacationType::PENDING->value,
            // 'company_id'=>$employee->company_id,
        ];

        $vacationRequest = $this->employeeService->vacationRequest($data);
        return sendResponse(VacationResource::make($vacationRequest),'');
    }

    public function destroy($id){
        if($this->employeeService->deleteVacationRequest($id)){
            return sendResponse([],'deleted success',1);
        }
        return sendResponse([],'not found or the status not pending',0);
    }

}
