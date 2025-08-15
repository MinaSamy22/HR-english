<?php

namespace App\Services;
use App\Models\User;
use App\Models\VacationRequest;
use App\Models\Resignation;
use App\Models\Payroll;
use App\Enums\VacationType;
use Carbon\Carbon;


class EmployeeService
{
    protected $employee;

    public function __construct(User $employee)
    {
        $this->employee = $employee;
    }

    public function getUser(){
        $totalUsed = $this->getVacations()->sum('total');
        $vacationLimit = $this->employee->company->attendanceSetting->vacation_balance;
        $this->employee->total_used = $totalUsed;
        $this->employee->remaing = $vacationLimit - $totalUsed;
        return $this->employee;
    }

    public function getVacations($from = null, $to = null)
    {
        if (!$from || !$to) {
            $year = now()->year;
            $from = $from ?? "$year-01-01";
            $to   = $to ?? "$year-12-31";
        }
        
        $vacations = $this->employee->vacations()
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereDate('start_date', '<=', $to)
                    ->whereDate('end_date', '>=', $from);
            })
            ->get();

        return $vacations;
    }

    public function getAttendances(){
        return $this->employee->attendances;
    }

    public function getSalaries($year = null, $month = null)
    {
        // Default to last month
        $date = Carbon::createFromDate($year ?? '2000', $month ?? '01', 1);

        $from = $date->copy()->startOfMonth()->toDateString();
        $to = $date->copy()->endOfMonth()->toDateString();

        return $this->employee
            ->payrolls()
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('start_date', [$from, $to])
                    ->orWhereBetween('end_date', [$from, $to])
                    ->orWhere(function ($query) use ($from, $to) {
                        $query->where('start_date', '<=', $from)
                                ->where('end_date', '>=', $to);
                    });
            })
            ->get();
    }



    public function getVacationRequests($from = null ,$to = null){
        if (!$from || !$to) {
            $year = now()->year;
            $from = $from ?? "$year-01-01";
            $to   = $to ?? "$year-12-31";
        }
        
        $requests = $this->employee->vacationRequests()
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereDate('start_date', '<=', $to)
                    ->whereDate('end_date', '>=', $from);
            })
            ->get();

        return $requests;
    }

    
    public function vacationRequest(array $data){
        return $this->employee->vacationRequests()->create($data);
    }
    
    public function deleteVacationRequest($id){
        if($vacationRequest = VacationRequest::where('status',VacationType::PENDING->value)->find($id)){
            $vacationRequest->delete();
            return true;
        }
        return false;
    }
    
    public function getResignationRequests($from = null ,$to = null){
        return $this->employee->resignations;
    }
    
    public function resignationRequest($data){
        return $this->employee->resignations()->create($data);
    }
    
    public function deleteResignationRequest($id){
        if($resignationRequest = Resignation::where('status',VacationType::PENDING->value)->find($id)){
            $resignationRequest->delete();
            return true;
        }
        return false;
    }
    public function getNews(){
        return $this->employee->company->news()->whereDate('news_date', '>=', now()->subDays(30))
                  ->orderBy('news_date', 'desc')->get();
    }

}
