<?php
namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Models\Department;
use App\Models\HrPermission;
use App\Models\Job;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class EmployeeeController extends Controller
{
public function index(Request $request){
    $data['getRecord'] = User::getRecord($request);    //for reterving employees data from database and retrive model logic
    $data['branches'] = $this->getAvailableBranches(); // Add branches for dropdown
    return view('backend.employees.list',$data);
}

public function getAvailableBranches()
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $branchesQuery = \DB::table('branches')
        ->where('company_id', $company_id)
        ->select('id', 'name', 'is_main');

    // Apply same branch access logic as getRecord method
    if (!empty($branch_id)) {
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        if (!($currentBranch && $currentBranch->is_main == 1)) {
            // If current branch is not main, only show current branch
            $branchesQuery->where('id', $branch_id);
        }
    }

    return $branchesQuery->orderBy('name')->get();
}

public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

   // ✅ Always show all company data (no branch restriction)
    $data['getJobs']        = Job::where('company_id', $company_id)->get();
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();
    $data['getManagers'] = Manager::where('company_id', $company_id)->get();

    return view('backend.employees.add', $data);
}

public function add_post(Request $request)
{
    // Validate the incoming data
    $validated = $request->validate([
        'name'                  => 'required',
        'email'                 => 'required|unique:users',
        'hire_date'             => 'required',
        'birth_date'            => 'required',
        'job_id'                => 'required',
        'salary'                => 'required',
        'salary_type'           => 'required',
        'housing_allowance'     => 'nullable|numeric',
        'transportation_allowance'=> 'nullable|numeric',
        'other_allowances'      => 'nullable|numeric',

        'manager_id'            => 'required',
        'department_id'         => 'required',
        'is_role'               => 'required|in:0,1',
        'is_biometric'          => 'required|in:0,1',
        'work_start_time'       => 'required|date_format:H:i',
        'work_end_time'         => 'required|date_format:H:i',
        'checkin_early_minutes' => 'nullable|numeric|min:0',
        'shift_count'           => 'required|in:1,2',
        'second_work_start_time'=> 'nullable|date_format:H:i',
        'second_work_end_time'  => 'nullable|date_format:H:i',
        'attachment'            => 'nullable|file|mimes:pdf|max:2048',
        'macaddress'            => 'nullable|string|max:255',
        'password'              => 'required|min:6',

         // NEW
        'nationality'           => 'required|in:local,foreign',
        'country_code'          => 'required_if:nationality,foreign|nullable|string|max:10',
        'residency_expiry'      => 'required_if:nationality,foreign|nullable|date',
        'passport_number'       => 'required_if:nationality,foreign|nullable|string|max:50',
        'passport_expiry'       => 'required_if:nationality,foreign|nullable|date',
        'residency_number'      => 'required_if:nationality,foreign|nullable|string|max:50',
        'iban'                  => 'nullable|string|max:50',
        'residency_job'         => 'nullable|string|max:100',
    ]);

    // Create a new user
    $user                            = new User;
    $user->name                      = trim($request->name);
    $user->email                     = trim($request->email);
    $user->phone_number              = trim($request->phone_number);
    $user->hire_date                 = trim($request->hire_date);
    $user->birth_date                = trim($request->birth_date);
    $user->job_id                    = trim($request->job_id);
    $user->salary_type               = trim($request->salary_type);
    $user->salary                    = trim($request->salary);

    $user->housing_allowance         = $request->housing_allowance ;
    $user->transportation_allowance  = $request->transportation_allowance ;
    $user->other_allowances          = $request->other_allowances ;

    $user->macaddress                = trim($request->macaddress);

    $user->work_start_time           = trim($request->work_start_time);
    $user->work_end_time             = trim($request->work_end_time);

    $user->checkin_early_minutes     = trim($request->checkin_early_minutes);





    $user->shift_count                = $request->shift_count;
    if ($request->shift_count == 2) {
        $user->second_work_start_time = $request->second_work_start_time;
        $user->second_work_end_time   = $request->second_work_end_time;
    }

    $user->manager_id           = trim($request->manager_id);
    $user->department_id        = trim($request->department_id);
    $user->is_role              = $request->is_role;
    $user->is_biometric         = $request->is_biometric;

    //new
    $user->nationality       =     $request->nationality;
    $user->country_code      =     $request->country_code;
    $user->residency_expiry  =     $request->residency_expiry;
    $user->passport_number   =     $request->passport_number;
    $user->passport_expiry   =     $request->passport_expiry;
    $user->residency_number  =     $request->residency_number;
    $user->iban              =     $request->iban;
    $user->residency_job     =     $request->residency_job;


    $user->company_id        = session('company_id');

    // Password
    $user->password = bcrypt($request->password);

    // Handle company/branch assignment
    if (session()->has('branch_id')) {
        $user->branch_id = session('branch_id');
    }

    // Handle attachment
    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('../../HR-Uploads/shared_attachments/');
        $file->move($destinationPath, $filename);
        $user->attachment = $filename;
    }


    // Save the user to the database
    $user->save();

    // If HR (role = 1) then save permissions
if ($request->is_role == 1) {
    HrPermission::create([
        'user_id' => $user->id,
        'company_id' => session('company_id'),
        'permissions' => json_encode($request->permissions ?? []),
    ]);
}

    return redirect('admin/employees')->with('success', __('h_employee.employee_registered'));
}




public function view($id){
    $data['getRecord'] = User::find($id);
    return view('backend.employees.view', $data);

}

public function edit($id)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $data['getRecord'] = User::find($id);

    // ✅ Always show all company data (no branch restriction)
    $data['getJobs']        = Job::where('company_id', $company_id)->get();
    $data['getDepartments'] = Department::where('company_id', $company_id)->get();
    $data['getManagers'] = Manager::where('company_id', $company_id)->get();

    return view('backend.employees.edit', $data);
}



public function edit_update($id, Request $request){

    $user = request()->validate([
        'email' => 'required|unique:users,email,'.$id,
        'is_biometric' => 'required|in:0,1', // Biometric validation
        'attachment' => 'nullable|file|mimes:pdf|max:2048', // Add attachment validation
    ]);

    // SAVE HR PERMISSIONS (VERY IMPORTANT)
    if ($request->is_role == 1) {
        \App\Models\HrPermission::updateOrCreate(
            [
                'user_id' => $id,
                'company_id' => session('company_id')
            ],
            [
                'permissions' => $request->permissions ?? []
            ]
        );
    } else {
        // If not HR remove permissions
        \App\Models\HrPermission::where('user_id', $id)
            ->where('company_id', session('company_id'))
            ->delete();
    }

    $user = User::find($id);

    // Handle file upload and replacement
    if ($request->hasFile('attachment')) {
        // Delete old attachment if exists
        if ($user->attachment) {
            $oldFilePath = public_path('../../HR-Uploads/shared_attachments/' . $user->attachment);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
        }

        // Upload new file
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('../../HR-Uploads/shared_attachments');

        $file->move($destinationPath, $filename);
        $user->attachment = $filename;
    }

    $user->name                      = trim($request->name);
    $user->email                     = trim($request->email);
    $user->phone_number              = trim($request->phone_number);
    $user->birth_date                = trim($request->birth_date);
    $user->hire_date                 = trim($request->hire_date);
    $user->job_id                    = trim($request->job_id);
    $user->salary_type               = trim($request->salary_type);
    $user->salary                    = trim($request->salary);
    $user->housing_allowance         = $request->housing_allowance ;
    $user->transportation_allowance  = $request->transportation_allowance ;
    $user->other_allowances          = $request->other_allowances ;

    $user->macaddress                = trim($request->macaddress);

    $user->work_start_time           = trim($request->work_start_time);
    $user->work_end_time             = trim($request->work_end_time);
    $user->checkin_early_minutes     = trim($request->checkin_early_minutes);

    $user->shift_count               = $request->shift_count;
    $user->second_work_start_time    = $request->second_work_start_time;
    $user->second_work_end_time      = $request->second_work_end_time;

    $user->is_biometric              = $request->is_biometric;

    //new
    $user->nationality       =     $request->nationality;
    $user->country_code      =     $request->country_code;
    $user->residency_expiry  =     $request->residency_expiry;
    $user->passport_number   =     $request->passport_number;
    $user->passport_expiry   =     $request->passport_expiry;
    $user->residency_number  =     $request->residency_number;
    $user->iban              =     $request->iban;
    $user->residency_job     =     $request->residency_job;



    $user->manager_id           = trim($request->manager_id);
    $user->department_id        = trim($request->department_id);
    $user->is_role              = $request->is_role;

    if ($request->filled('password')) {
    $user->password = bcrypt($request->password);
}
    $user->save();

return redirect('admin/employees')->with('success', __('h_employee.employee_updated'));
}


public function delete($id){
    $recordDelete = User::find($id);

    // Check if user exists
    if (!$recordDelete) {
        return redirect()->back()->with('error', 'User not found');
    }

    // Delete the attachment file if it exists
    if ($recordDelete->attachment) {
        $filePath = public_path('../../HR-Uploads/shared_attachments/' . $recordDelete->attachment);

        // Check if file exists and delete it
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete the user record
    $recordDelete->delete();

return redirect()->back()->with('success', __('h_employee.record_deleted'));
}

public function info(Request $request){
    $data['getRecord'] = User::getRecord($request);
    return view('backend.employees.info',$data);

}


    public function employees_export(Request $request)
{

    return Excel::download(new EmployeesExport, 'employees.xlsx');
}
public function showImportForm()
{
    return view('backend.employees.import');
}


public function importEmployees(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    try {
        $data = Excel::toArray([], $request->file('excel_file'));
        $rows = $data[0] ?? [];

        if (empty($rows)) {
            return back()->with('error', 'The Excel file appears to be empty.');
        }

        // Debug: Show the first few rows
        \Log::info('Excel Data:', ['first_row' => $rows[0] ?? 'No header', 'second_row' => $rows[1] ?? 'No data']);

        unset($rows[0]); // remove header

        $processedCount = 0;
        $errors = [];
        $companyId = auth()->user()->company_id ?? session('company_id') ?? 1;

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                // Debug: Log each row
                \Log::info("Processing Row $rowNumber:", $row);

                if (count($row) < 14) {
                    $errors[] = "Row $rowNumber: Insufficient columns (expected 14, got " . count($row) . ")";
                    continue;
                }

                // Clean and extract values
                $id           = trim($row[0]);
                $name         = trim($row[1]);
                $email        = trim($row[2]);
                $phone        = trim($row[3]);
                $birthDate    = trim($row[4]);
                $hireDate     = trim($row[5]);
                $jobId        = trim($row[6]);
                $salaryType   = trim($row[7]);
                $salary       = trim($row[8]);
                $startTime    = trim($row[9]);
                $endTime      = trim($row[10]);
                $managerId    = trim($row[11]);
                $departmentId = trim($row[12]);
                $role         = strtolower(trim($row[13]));

                // Debug: Log extracted values with types
                \Log::info("Row $rowNumber extracted:", [
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'birth_date' => $birthDate . ' (type: ' . gettype($birthDate) . ')',
                    'hire_date' => $hireDate . ' (type: ' . gettype($hireDate) . ')',
                    'job_id' => $jobId,
                    'salary_type' => $salaryType,
                    'salary' => $salary,
                    'manager_id' => $managerId,
                    'department_id' => $departmentId,
                    'role' => $role,
                    'validated_birth_date' => $validatedBirthDate ?? 'null',
                    'validated_hire_date' => $validatedHireDate ?? 'null'
                ]);

                // Basic validation
                if (empty($email) || empty($name)) {
                    $errors[] = "Row $rowNumber: Name and Email are required";
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row $rowNumber: Invalid email format ($email)";
                    continue;
                }

                if (!in_array($role, ['hr', 'employee'])) {
                    $errors[] = "Row $rowNumber: Role must be 'HR' or 'Employee' (got: '$role')";
                    continue;
                }

                // Parse Birth Date
                $validatedBirthDate = null;
                if ($birthDate) {
                    try {
                        // Try Excel date formats
                        if (is_numeric($birthDate)) {
                            // Excel serial date number
                            $validatedBirthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
                        } else {
                            // Try different date formats
                            $formats = ['d/m/Y', 'm/d/Y', 'Y-m-d', 'd-m-Y', 'm-d-Y'];
                            foreach ($formats as $format) {
                                try {
                                    $validatedBirthDate = Carbon::createFromFormat($format, $birthDate)->format('Y-m-d');
                                    break;
                                } catch (\Exception $e) {
                                    continue;
                                }
                            }
                        }

                        if (!$validatedBirthDate) {
                            $errors[] = "Row $rowNumber: Invalid birth date format ($birthDate). Try DD/MM/YYYY";
                            continue;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row $rowNumber: Invalid birth date format ($birthDate). Error: " . $e->getMessage();
                        continue;
                    }
                }

                // Parse Hire Date
                $validatedHireDate = null;
                if ($hireDate) {
                    try {
                        // Try Excel date formats
                        if (is_numeric($hireDate)) {
                            // Excel serial date number
                            $validatedHireDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($hireDate)->format('Y-m-d');
                        } else {
                            // Try different date formats
                            $formats = ['d/m/Y', 'm/d/Y', 'Y-m-d', 'd-m-Y', 'm-d-Y'];
                            foreach ($formats as $format) {
                                try {
                                    $validatedHireDate = Carbon::createFromFormat($format, $hireDate)->format('Y-m-d');
                                    break;
                                } catch (\Exception $e) {
                                    continue;
                                }
                            }
                        }

                        if (!$validatedHireDate) {
                            $errors[] = "Row $rowNumber: Invalid hire date format ($hireDate). Try DD/MM/YYYY";
                            continue;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row $rowNumber: Invalid hire date format ($hireDate). Error: " . $e->getMessage();
                        continue;
                    }
                }

                // Validate Salary Type
                $validatedSalaryType = null;
                if ($salaryType) {
                    if (is_numeric($salaryType) && $salaryType >= 0) {
                        $validatedSalaryType = (int)$salaryType;
                    } else {
                        $errors[] = "Row $rowNumber: Salary type must be a valid number (got: '$salaryType')";
                        continue;
                    }
                }

                // Validate Job ID
                $validatedJobId = null;
                if ($jobId) {
                    \Log::info("Processing Job ID for row $rowNumber:", ['raw_job_id' => $jobId, 'type' => gettype($jobId), 'empty' => empty($jobId)]);

                    if (is_numeric($jobId) && $jobId > 0) {
                        $validatedJobId = (int)$jobId;
                        \Log::info("Job ID validated successfully:", ['validated_job_id' => $validatedJobId]);
                    } else {
                        \Log::warning("Job ID validation failed:", ['job_id' => $jobId, 'is_numeric' => is_numeric($jobId)]);
                        $errors[] = "Row $rowNumber: Job ID must be a valid positive number (got: '$jobId')";
                        continue;
                    }
                } else {
                    \Log::info("Job ID is empty for row $rowNumber");
                }

                // Validate Manager ID
                $validatedManagerId = null;
                if ($managerId) {
                    if (is_numeric($managerId) && $managerId >= 0) {
                        $validatedManagerId = (int)$managerId;
                    } else {
                        $errors[] = "Row $rowNumber: Manager ID must be a valid number (got: '$managerId')";
                        continue;
                    }
                }

                // Validate Department ID
                $validatedDepartmentId = null;
                if ($departmentId) {
                    if (is_numeric($departmentId) && $departmentId >= 0) {
                        $validatedDepartmentId = (int)$departmentId;
                    } else {
                        $errors[] = "Row $rowNumber: Department ID must be a valid number (got: '$departmentId')";
                        continue;
                    }
                }

                // Simple time parsing
                $start = null;
                $end = null;

                if ($startTime) {
                    try {
                        $start = Carbon::parse($startTime)->format('H:i:s');
                    } catch (\Exception $e) {
                        \Log::warning("Time parsing failed for start time: $startTime");
                    }
                }

                if ($endTime) {
                    try {
                        $end = Carbon::parse($endTime)->format('H:i:s');
                    } catch (\Exception $e) {
                        \Log::warning("Time parsing failed for end time: $endTime");
                    }
                }

                // Clean salary
                $validatedSalary = null;
                if ($salary) {
                    $cleanSalary = preg_replace('/[^0-9.]/', '', $salary);
                    if (is_numeric($cleanSalary) && $cleanSalary >= 0) {
                        $validatedSalary = $cleanSalary;
                    }
                }

                // Prepare user data
                $userData = [
                    'name'            => $name,
                    'phone_number'    => $phone,
                    'birth_date'      => $validatedBirthDate,
                    'hire_date'       => $validatedHireDate,
                    'job_id'          => $validatedJobId,
                    'salary_type'     => $validatedSalaryType,
                    'salary'          => $validatedSalary,
                    'work_start_time' => $start,
                    'work_end_time'   => $end,
                    'manager_id'      => $validatedManagerId,
                    'department_id'   => $validatedDepartmentId,
                    'company_id'      => $companyId,
                    'is_role'         => $role === 'hr' ? 1 : 0,
                ];

                // Only set password for new users
                $existingUser = User::where('email', $email)->first();
                if (!$existingUser) {
                    $userData['password'] = bcrypt('12345678');
                }

                \Log::info("About to create/update user:", array_merge($userData, [
                    'validated_job_id_final' => $validatedJobId,
                    'job_id_in_userdata' => $userData['job_id'] ?? 'not_set'
                ]));

                // Create or update user
                $user = User::updateOrCreate(['email' => $email], $userData);
                \Log::info("User created/updated:", ['id' => $user->id, 'email' => $user->email]);

                $processedCount++;

            } catch (\Exception $e) {
                $errors[] = "Row $rowNumber: Unexpected error - " . $e->getMessage();
                \Log::error("Row processing failed:", ['row' => $rowNumber, 'error' => $e->getMessage()]);
            }
        }

$message = __('h_employee.employees_imported', ['count' => $processedCount]);

        if (!empty($errors)) {
            $errorMessage = implode("\n", array_slice($errors, 0, 10));
            if (count($errors) > 10) {
                $errorMessage .= "\n... and " . (count($errors) - 10) . " more errors.";
            }

            if ($processedCount > 0) {
                return back()->with('success', $message)->with('warning', $errorMessage);
            } else {
                return back()->with('error', "Import failed. Errors:\n" . $errorMessage);
            }
        }

        return back()->with('success', $message);

    } catch (\Exception $e) {
        \Log::error("Import failed completely:", ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to process the Excel file: ' . $e->getMessage());
    }
}



}
