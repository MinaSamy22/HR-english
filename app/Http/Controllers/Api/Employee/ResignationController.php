<?php

namespace App\Http\Controllers\Api\Employee;

use Illuminate\Http\Request;
use App\Services\EmployeeService;
use function included\sendResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResignationResource;
use App\Http\Requests\ResignationRequest;
use Carbon\Carbon;
use App\Enums\VacationType;

class ResignationController extends Controller
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
        $vacations = $this->employeeService->getResignationRequests($request->from,$request->to);

        return sendResponse(ResignationResource::collection($vacations),'');
    }


    public function store(ResignationRequest $request){

        $employee = $this->employeeService->getUser();
        if($employee->resignation_date){
            return sendResponse([], 'You have already resigned.', 0);
        }
        if($request->resignation_date < $employee->hire_date){
            return sendResponse([], 'The resignation date is less than the hiring date.', 0);
        }
        $hasRequest = $employee->resignationRequests()->where('status','!=',VacationType::REJECTED->value)->exists();

        if ($hasRequest) {
            return sendResponse([], 'You already have a resignation request.', 0);
        }


        $data =[
            'resignation_date' => $request->resignation_date,
            'reason' => $request->reason,
            'status'=>VacationType::PENDING->value,
            'company_id'=>$employee->company_id,
        ];

        $resignationRequest = $this->employeeService->resignationRequest($data);
        return sendResponse(ResignationResource::make($resignationRequest),'');
    }

    public function destroy($id){
        if($this->employeeService->deleteResignationRequest($id)){
            return sendResponse([],'deleted success',1);
        }
        return sendResponse([],'not found or the status not pending',0);
    }

}
