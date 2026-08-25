<?php
namespace App\Exports;

use App\Models\User;
use App\Models\HrPermission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Request;
use Carbon\Carbon;

class EmployeesExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings
{
    public function collection()
    {
        $request = Request::all();
        return User::getAllRecordsForExport($request);
    }

    protected $index = 0;

    public function map($user): array
    {
        // Salary type text
        $salaryType = '';
        if ($user->salary_type == 1) {
            $salaryType = 'Monthly Salary';
        } elseif ($user->salary_type == 2) {
            $salaryType = 'Weekly Wage';
        } elseif ($user->salary_type == 3) {
            $salaryType = 'Daily Wage';
        }

        // Nationality
        $nationality = '';
        if ($user->nationality == 'foreign') {
            $nationality = 'Foreign';
        } elseif ($user->nationality == 'local') {
            $nationality = 'Local';
        } elseif (!empty($user->nationality)) {
            $nationality = ucfirst($user->nationality);
        }

        // Biometric
        $freeBiometric = '';
        if ($user->is_biometric === 1) {
            $freeBiometric = 'Yes';
        } elseif ($user->is_biometric === 0) {
            $freeBiometric = 'No';
        }

        // HR Permissions
        $permissions = '';
        if (!empty($user->is_role) && $user->is_role == 1) {
            $hrPermission = HrPermission::where('user_id', $user->id)
                ->where('company_id', session('company_id'))
                ->first();

            if ($hrPermission) {
                $perms = is_array($hrPermission->permissions) 
                    ? $hrPermission->permissions 
                    : json_decode($hrPermission->permissions, true);
                if (is_array($perms)) {
                    $permissions = implode(', ', $perms);
                }
            }
        }

        return [
            ++$this->index,
            $user->id,
            $user->name,
            $user->email,
            $user->phone_number ?? '',
            $user->macaddress ?? '',
            $user->birth_date ? date('d-m-Y', strtotime($user->birth_date)) : '',
            $user->hire_date ? date('d-m-Y', strtotime($user->hire_date)) : '',
            $user->get_job_single->job_title ?? '',
            $user->get_manager_single->name ?? '',
            $user->get_department_single->department_name ?? '',
            $user->branch_name ?? ($user->branch->name ?? 'Main Branch'),
            (!empty($user->is_role) && $user->is_role == 1) ? 'HR' : 'Employee',
            $salaryType,
            $user->salary ?? '',
            $user->housing_allowance ?? '',
            $user->transportation_allowance ?? '',
            $user->other_allowances ?? '',
            $nationality,
            $user->country_code ?? '',
            $user->residency_number ?? '',
            $user->residency_expiry ? date('d-m-Y', strtotime($user->residency_expiry)) : '',
            $user->residency_job ?? '',
            $user->passport_number ?? '',
            $user->passport_expiry ? date('d-m-Y', strtotime($user->passport_expiry)) : '',
            $user->iban ?? '',
            $user->shift_count !== null ? $user->shift_count : '',
            $user->work_start_time ? Carbon::parse($user->work_start_time)->format('h:i A') : '',
            $user->work_end_time ? Carbon::parse($user->work_end_time)->format('h:i A') : '',
            $user->second_work_start_time ? Carbon::parse($user->second_work_start_time)->format('h:i A') : '',
            $user->second_work_end_time ? Carbon::parse($user->second_work_end_time)->format('h:i A') : '',
            $user->checkin_early_minutes ?? '',
            $freeBiometric,
            $permissions,
            $user->created_at ? date('d-m-Y h:i A', strtotime($user->created_at)) : '',
            $user->updated_at ? date('d-m-Y h:i A', strtotime($user->updated_at)) : '',
        ];
    }

    public function headings(): array
    {
        return [
            'S. No',
            'ID',
            'Name',
            'Email',
            'Phone Number',
            'MAC Address',
            'Birth Date',
            'Hire Date',
            'Job Title',
            'Manager',
            'Department',
            'Branch',
            'Role',
            'Salary Type',
            'Salary',
            'Housing Allowance',
            'Transportation Allowance',
            'Other Allowances',
            'Nationality',
            'Country Code',
            'Residency Number',
            'Residency Expiry',
            'Residency Job',
            'Passport Number',
            'Passport Expiry',
            'IBAN',
            'Shift Count',
            'Shift 1 Start Time',
            'Shift 1 End Time',
            'Shift 2 Start Time',
            'Shift 2 End Time',
            'Early Check-in Minutes',
            'Free Biometric',
            'HR Permissions',
            'Created At',
            'Updated At',
        ];
    }
}

















