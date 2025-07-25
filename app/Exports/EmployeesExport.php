<?php
namespace App\Exports;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Request;

class EmployeesExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings{


public function collection(){
    $request = Request::all();
    return User::getAllRecordsForExport($request);
}
protected $index = 0;
public function map($user):array{
    $cretedAtFormat = date('d-m-Y', strtotime($user->created_at));

return [
                'ID'            => $user->id,
                'Name'          => $user->name,
                'Email'         => $user->email,
                'Phone Number'  => $user->phone_number,
                'Job Name'      => $user->get_job_single->job_title ?? '',
                'Salary'        => $user->salary,
                'Start Time'    => $user->work_start_time ? \Carbon\Carbon::parse($user->work_start_time)->format('h:i A') : 'Not Set',
                'End Time'      => $user->work_end_time ? \Carbon\Carbon::parse($user->work_end_time)->format('h:i A') : 'Not Set',
                'Manager'       => $user->get_manager_single->name ?? '',
                'Department'    => $user->get_department_single->department_name ?? '',
                'Role'          => $user->is_role ? 'HR' : 'Employees',
                'Created At'    => date('d-m-Y h:i A', strtotime($user->created_at)),
                'Updated At'    => date('d-m-Y h:i A', strtotime($user->updated_at)),
            ];

}
public function headings():array{  //this is the names that will show in the head of excel sheet
return [
            'ID',
            'Name',
            'Email',
            'Phone Number',
            'Job Name',
            'Salary',
            'Start Time',
            'End Time',
            'Manager',
            'Department',
            'Role',
            'Created At',
            'Updated At',
        ];

}

}

















