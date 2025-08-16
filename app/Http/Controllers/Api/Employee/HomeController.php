<?php

namespace App\Http\Controllers\Api\Employee;

use Illuminate\Http\Request;
use App\Services\EmployeeService;
use function included\sendResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $employeeService;

    public function __construct()
    {
        if(auth('api')->check()){
            $this->employeeService = new EmployeeService(auth('api')->user()->load('company.attendanceSetting'));
        }
    }

    public function salary(Request $request)
    {
        $salaries = $this->employeeService->getSalaries($request->year,$request->month);

        return sendResponse($salaries,'');
    }

    public function news(Request $request)
    {
        $news = $this->employeeService->getNews();

        return sendResponse(NewsResource::collection($news),'');
    }

    public function checkIn(){
        $attendance = $this->employeeService->checkIn();
        return sendResponse($attendance['data'],$attendance['msg'],$attendance['status']);
    }

    public function checkOut(){
        $attendance = $this->employeeService->checkOut();
        return sendResponse($attendance['data'],$attendance['msg'],$attendance['status']);
    }

    public function attendance(){
        $attendance = $this->employeeService->getAttendances();

        return sendResponse($attendance['data'],$attendance['msg'],$attendance['status']);

    }


}
