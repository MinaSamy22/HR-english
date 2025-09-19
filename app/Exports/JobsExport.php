<?php
namespace App\Exports;
use App\Models\Job;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Request;

class JobsExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings{


public function collection(){
    $request = Request::all();
    return Job::getRecord($request);
}
protected $index = 0;
public function map($user):array{
    $cretedAtFormat = date('d-m-Y', strtotime($user->created_at));

return[ // data that will take in excel
    ++$this->index,
    $user->id,
    $user->job_title,
    $user->min_salary,
    $user->max_salary,
    $user->department_name,
    $user->branch_name,
    $cretedAtFormat

];

}
public function headings():array{  //this is the names that will show in the head of excel sheet
return [
    'S. No',
    'ID',
    'Job Title',
    'Min Salary',
    'Max Salary',
    'Department Name',
    'Branch',
    'Create At',



];
}

}

