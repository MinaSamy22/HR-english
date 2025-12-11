<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceImportController;
use App\Http\Controllers\AttendanceRulesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeesInterface\EarlyLeaveController;
use App\Http\Controllers\EmployeesInterface\EmployeeLateRemovalController;
use App\Http\Controllers\EmployeesInterface\EmployeePerformanceController;
use App\Http\Controllers\EmployeesInterface\ExtraTimeRequestController;
use App\Http\Controllers\EmployeesInterface\ResignationController;
use App\Http\Controllers\EmployeesInterface\EmployeeAttendanceController;
use App\Http\Controllers\EmployeesInterface\EmployeeCalendarController;
use App\Http\Controllers\EmployeesInterface\EmployeeHomeController;
use App\Http\Controllers\EmployeeeController;
use App\Http\Controllers\EmployeesInterface\EmployeeMyAccountController;
use App\Http\Controllers\EmployeesInterface\EmployeePayrollController;
use App\Http\Controllers\EmployeesInterface\EmployeeVacationController;
use App\Http\Controllers\FinancialAnalysisController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobHistoryController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OverTimeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\PerformanceCriteriaController;
use App\Http\Controllers\PerformanceEvaluationController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\LocationController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('lang/{lang}', function ($lang) {
    $available = ['en', 'ar', 'au']; // حط كل اللغات هنا
    if (in_array($lang, $available)) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return back();
});

Route::get('/', [AuthController::class, 'index'])->name('start'); //index navigate to auth controller
Route::post('login_post', [AuthController::class, 'login_post'])->name('login_post');
Route::get('view/news/image/{filename}', [NewsController::class, 'viewImage'])->name('view.news.image');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//middlware 1 (HR interface)
Route::middleware('admin')->group(function () {

    //Navbar icons
    route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    route::get('admin/calendar', [CalendarController::class, 'index'])->name('calendar');
    route::get('admin/charts', [ChartsController::class, 'index'])->name('charts');
    route::get('admin/do', [TodoController::class, 'index'])->name('ToDo list');
    Route::post('admin/do', [ToDoController::class, 'store'])->name('tasks.store');
    Route::put('admin/do/{id}', [ToDoController::class, 'update'])->name('tasks.update');
    Route::delete('admin/do/{id}', [ToDoController::class, 'destroy'])->name('tasks.destroy');
    Route::delete('admin/do', [ToDoController::class, 'bulkDestroy'])->name('tasks.bulkDestroy');


    //employees
    route::get('admin/employees', [EmployeeeController::class, 'index'])->name('employees');
    route::get('admin/employees/add', [EmployeeeController::class, 'add'])->name('employees_add');
    route::post('admin/employees/add', [EmployeeeController::class, 'add_post'])->name('employees_add_post'); //post for save in database
    route::get('admin/employees/view/{id}', [EmployeeeController::class, 'view'])->name('employees_view');
    route::get('admin/employees/edit/{id}', [EmployeeeController::class, 'edit'])->name('employees_edit');
    route::post('admin/employees/update/{id}', [EmployeeeController::class, 'edit_update'])->name('employees_update');
    route::get('admin/employees/delete/{id}', [EmployeeeController::class, 'delete'])->name('employees_delete');
    route::get('admin/employee_info', [EmployeeeController::class, 'info'])->name('employee_info');
    route::get('admin/employees_export', [EmployeeeController::class, 'employees_export'])->name('employees_export');
    Route::get('admin/employees/import', [EmployeeeController::class, 'showImportForm'])->name('employees.import.form');
    Route::post('admin/import-employees', [EmployeeeController::class, 'importEmployees'])->name('admin.employees.import');
    Route::get('/view-attachment/{filename}', function ($filename) {
        $path = public_path('../../HR-Uploads/shared_attachments/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    })->name('view.attachment');

    //job   admin/jobs
    route::get('admin/jobs', [JobController::class, 'index'])->name('jobs');
    route::get('admin/jobs/add', [JobController::class, 'add'])->name('jobs_add');
    route::post('admin/jobs/add', [JobController::class, 'add_post'])->name('jobs_add_post'); //post for save in database
    route::get('admin/jobs/view/{id}', [JobController::class, 'view'])->name('jobs_view');
    route::get('admin/jobs/edit/{id}', [JobController::class, 'edit'])->name('jobs_edit');
    route::post('admin/jobs/update/{id}', [JobController::class, 'edit_update'])->name('jobs_update');
    route::get('admin/jobs/delete/{id}', [JobController::class, 'delete'])->name('jobs_delete');
    route::get('admin/jobs_export', [JobController::class, 'jobs_export'])->name('jobs_export');
    route::get('admin/job_info', [JobController::class, 'info'])->name('job_info');


    //job history  admin/job_history
    route::get('admin/job_history', [JobHistoryController::class, 'index'])->name('job_history');
    route::get('admin/job_history/add', [JobHistoryController::class, 'add'])->name('history_add');
    route::post('admin/job_history/add', [JobHistoryController::class, 'add_post'])->name('history_add_post'); //post for save in database
    route::get('admin/job_history/edit/{id}', [JobHistoryController::class, 'edit'])->name('history_edit');
    route::post('admin/job_history/update/{id}', [JobHistoryController::class, 'edit_update'])->name('history_update');
    route::get('admin/job_history/delete/{id}', [JobHistoryController::class, 'delete'])->name('history_delete');
    route::get('admin/jobhistory_export', [JobHistoryController::class, 'jobs_export'])->name('history_export'); //34an roue bdl url


    //managers    admin/manager
    route::get('admin/manager', [ManagerController::class, 'index'])->name('manager');
    route::get('admin/manager/add', [ManagerController::class, 'add'])->name('manager_add');
    route::post('admin/manager/add', [ManagerController::class, 'add_post'])->name('manager_add_post'); //post for save in database
    route::get('admin/manager/view/{id}', [ManagerController::class, 'view'])->name('manager_view');
    route::get('admin/manager/edit/{id}', [ManagerController::class, 'edit'])->name('manager_edit');
    route::post('admin/manager/update/{id}', [ManagerController::class, 'edit_update'])->name('manager_update');
    route::get('admin/manager/delete/{id}', [ManagerController::class, 'delete'])->name('manager_delete');

    route::get('admin/manager_info', [ManagerController::class, 'info'])->name('manager_info');


    //Administration   admin/administration
    Route::get('admin/administration', [AdministrationController::class, 'index'])->name('administration');
    route::get('admin/administration/add', [AdministrationController::class, 'add'])->name('administration_add');
    route::post('admin/administration/add', [AdministrationController::class, 'add_post'])->name('administration_add_post'); //post for save in database
    route::get('admin/administration/edit/{id}', [AdministrationController::class, 'edit'])->name('administration_edit');
    route::post('admin/administration/update/{id}', [AdministrationController::class, 'edit_update'])->name('administration_update');
    route::get('admin/administration/delete/{id}', [AdministrationController::class, 'delete'])->name('administration_delete');


    //department   admin/department
    route::get('admin/department', [DepartmentController::class, 'index'])->name('department');
    route::get('admin/department/add', [DepartmentController::class, 'add'])->name('department_add');
    route::post('admin/department/add', [DepartmentController::class, 'add_post'])->name('department_add_post'); //post for save in database
    route::get('admin/department/view/{id}', [DepartmentController::class, 'view'])->name('department_view');
    route::get('admin/department/edit/{id}', [DepartmentController::class, 'edit'])->name('department_edit');
    route::post('admin/department/update/{id}', [DepartmentController::class, 'edit_update'])->name('department_update');
    route::get('admin/department/delete/{id}', [DepartmentController::class, 'delete'])->name('department_delete');
    route::get('admin/department_export', [DepartmentController::class, 'department_export'])->name('department_export');

    route::get('admin/department_info', [DepartmentController::class, 'info'])->name('department_info');

    //Employee Requests   admin/Requests

    Route::get('admin/Requests', [RequestController::class, 'index'])->name('Requests');
    Route::get('admin/Requests/processed', [RequestController::class, 'processed'])->name('Requests.processed');
    Route::post('admin/Requests/accept/{type}/{id}', [RequestController::class, 'accept'])->name('Requests.accept');
    Route::post('admin/Requests/reject/{type}/{id}', [RequestController::class, 'reject'])->name('Requests.reject');
    Route::get('admin/Requests', [RequestController::class, 'index'])->name('Requests');
    Route::get('admin/Requests/processed', [RequestController::class, 'processed'])->name('Requests.processed');
    Route::post('admin/Requests/accept/{type}/{id}', [RequestController::class, 'accept'])->name('Requests.accept');
    Route::post('admin/Requests/reject/{type}/{id}', [RequestController::class, 'reject'])->name('Requests.reject');
    // Accept/Reject routes for employee interface
    Route::post('/admin/requests/{type}/{id}/accept', [RequestController::class, 'accept'])->name('requests.accept');
    Route::post('/admin/requests/{type}/{id}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::get('/admin/requests/processed', [RequestController::class, 'processed'])->name('requests.processed');
    // New routes for processed notifications
    Route::get('/admin/requests/processed/{type}/{id}', [RequestController::class, 'showProcessedRequest'])->name('processed-requests.show');
    Route::post('/admin/requests/mark-all-processed-seen', [RequestController::class, 'markAllProcessedAsSeen'])->name('requests.mark-all-processed-seen');


    //Attendance section
    Route::get('admin/Requests', [RequestController::class, 'index'])->name('Requests');
    Route::get('admin/Requests/processed', [RequestController::class, 'processed'])->name('Requests.processed');
    Route::post('admin/Requests/accept/{type}/{id}', [RequestController::class, 'accept'])->name('Requests.accept');
    Route::post('admin/Requests/reject/{type}/{id}', [RequestController::class, 'reject'])->name('Requests.reject');
    //Attendance section
    Route::get('admin/attendance', [AttendanceController::class, 'AttendanceEmployee'])->name('attendance.index');
    Route::post('admin/attendance/save', [AttendanceController::class, 'AttendanceEmployeeSubmit'])->name('attendance.submit');
    //its report
    Route::get('admin/reports', [AttendanceController::class, 'index'])->name('report');
    Route::get('admin/reports/export-pdf', [AttendanceController::class, 'exportPdf'])->name('reports.exportPdf');


    // Company policy section
    Route::get('admin/attendance-rule', [AttendanceRulesController::class, 'index'])->name('attendance-rule');
    Route::get('/attendance-rules', [AttendanceRulesController::class, 'index'])->name('attendance-rules.index');
    Route::post('admin/attendance-rule/save', [AttendanceRulesController::class, 'saveRules'])->name('attendance-rule.save');


    Route::post('/attendance-rules/upload-policy', [AttendanceRulesController::class, 'uploadPolicyPdf'])
    ->name('attendance-rules.upload-policy');

    Route::get('/company-policy/view/{file}', function($file) {
    $path = public_path('../../HR-Uploads/pdf_policy/' . $file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('company-policy.view');


    // holiday management
    Route::get('/attendance-rules/get-holidays', [AttendanceRulesController::class, 'getHolidays']);
    Route::post('admin/attendance-rule/add-holiday', [AttendanceRulesController::class, 'addHoliday'])->name('attendance-rule.add-holiday');
    Route::delete('admin/attendance-rule/delete-holiday', [AttendanceRulesController::class, 'deleteHoliday'])->name('attendance-rule.delete-holiday');
    Route::post('/attendance-rules/update-holidays', [AttendanceRulesController::class, 'updateHolidays'])->name('attendance.update-holidays');

    // edit late - FIXED
    Route::post('/attendance-rules/update-late-deduction', [AttendanceRulesController::class, 'updateLateDeduction'])->name('attendance-rules.update-late-deduction');
    // edit halfday - FIXED
    Route::post('/attendance-rules/update-half-day', [AttendanceRulesController::class, 'updateHalfDayDeduction'])->name('attendance-rules.update-half-day');
    Route::post('/attendance-rules/update-half-day', [AttendanceRulesController::class, 'updateHalfDayDeduction'])->name('attendance-rules.update-half-day');

    // work days edit
    Route::post('/attendance/update-employee-working-days', [AttendanceRulesController::class, 'updateEmployeeWorkingDays'])->name('attendance.update-employee-working-days');
    // work hours edit
    Route::post('/attendance-rules/update-work-hours', [AttendanceRulesController::class, 'updateWorkHoursPerDay'])->name('attendance.update-work-hours');
    Route::post('/attendance/update-employee-work-hours', [AttendanceRulesController::class, 'updateEmployeeWorkHours'])->name('attendance.update-employee-work-hours');


    Route::post('/attendance-rules/update-absent-threshold', [AttendanceRulesController::class, 'updateAbsentThreshold'])->name('attendance-rules.update-absent-threshold');
    //vacation balance assignment to employees
    Route::post('/attendance/update-employee-vacation-balance', [AttendanceRulesController::class, 'updateEmployeeVacationBalance'])->name('attendance.update-employee-vacation-balance');
    //bonus per hour assignment to employees
    Route::post('/attendance/update-employee-bonus-per-hour', [AttendanceRulesController::class, 'updateEmployeeBonusPerHour'])->name('attendance.update-employee-bonus-per-hour');

    // Add these routes to your existing attendance rules routes
    Route::post('/attendance-rules/update-late-threshold', [AttendanceRulesController::class, 'updateLateThreshold'])->name('attendance-rules.update-late-threshold');
    Route::post('/attendance-rules/update-half-day-threshold', [AttendanceRulesController::class, 'updateHalfDayThreshold'])->name('attendance-rules.update-half-day-threshold');
    Route::post('timezone/update',[AttendanceRulesController::class,'updateTimezone'])->name('timezone.update');


    //biometric excel
    Route::get('admin/biometer-excel', [AttendanceImportController::class, 'showForm'])->name('attendance.import.form');
    Route::post('/biometer-excel', [AttendanceImportController::class, 'import'])->name('attendance.import');

    //deductions    admin/deductions
    route::get('admin/deductions', [DeductionController::class, 'index'])->name('leaves');
    route::get('admin/deductions/delete/{id}', [DeductionController::class, 'delete'])->name('leaves_delete');
    route::get('admin/deductions/add', [DeductionController::class, 'add'])->name('leaves_add');
    route::post('admin/deductions/add', [DeductionController::class, 'add_post'])->name('leaves_add_post'); //post for save in database
    Route::post('admin/deductions/delete-multiple', [DeductionController::class, 'deleteMultiple']);

    //vacations    admin/vacations
    route::get('admin/vacations', [VacationController::class, 'index'])->name('vacations');
    route::get('admin/vacations/delete/{id}', [VacationController::class, 'delete'])->name('vacations_delete');
    route::get('admin/vacations/add', [VacationController::class, 'add'])->name('vacations_add');
    route::post('admin/vacations/add', [VacationController::class, 'add_post'])->name('vacations_add_post'); //post for save in database
    Route::post('admin/vacations/delete-multiple', [VacationController::class, 'deleteMultiple']);


    //OverTime      admin/bounas
    route::get('admin/bounas', [OverTimeController::class, 'index'])->name('bounas');
    route::get('admin/bounas/delete/{id}', [OverTimeController::class, 'delete'])->name('bounas_delete');
    route::get('admin/bounas/add', [OverTimeController::class, 'add'])->name('bounas_add');
    route::post('admin/bounas/add', [OverTimeController::class, 'add_post'])->name('bounas_add_post'); //post for save in database
    Route::post('admin/bounas/delete-multiple', [OverTimeController::class, 'deleteMultiple']);


    // payrolls    admin/payroll
    route::get('admin/payroll', [PayrollController::class, 'index'])->name('payroll');
    route::get('admin/payroll/add', [PayrollController::class, 'add'])->name('payroll_add');
    route::post('admin/payroll/add', [PayrollController::class, 'add_post'])->name('payroll_add_post'); //post for save in database
    route::get('admin/payroll/edit/{id}', [PayrollController::class, 'edit'])->name('payroll_edit');
    route::post('admin/payroll/update/{id}', [PayrollController::class, 'edit_update'])->name('payroll_update');
    route::get('admin/payroll/delete/{id}', [PayrollController::class, 'delete'])->name('payroll_delete');
    route::get('admin/payroll_export', [PayrollController::class, 'payrolls_export'])->name('payroll_export'); //34an route bdl url
    Route::get('admin/payrolls/export-pdf', [PayrollController::class, 'exportPdf'])->name('payrolls.exportPdf');
    Route::post('admin/payrolls/delete-multiple', [PayrollController::class, 'deleteMultiple']);
    // payslip
    Route::get('admin/payslip', [PayrollController::class, 'payslip'])->name('payslip');
    // PDF download payslip
    Route::post('admin/payslip/download-pdf', [PayrollController::class, 'downloadSinglePayslip'])->name('payslip.download.single');
    Route::post('admin/payslip/download-all-pdf', [PayrollController::class, 'downloadAllPayslips'])->name('payslip.download.all');


    // Taxes
    Route::get('admin/taxes', [TaxController::class, 'index'])->name('taxes');
    Route::get('admin/taxes/add', [TaxController::class, 'add'])->name('taxes_add');
    Route::post('admin/taxes/add', [TaxController::class, 'add_post'])->name('taxes_add_post');
    Route::get('admin/taxes/edit/{id}', [TaxController::class, 'edit'])->name('taxes_edit');
    Route::post('admin/taxes/update/{id}', [TaxController::class, 'edit_update'])->name('taxes_update');
    Route::get('admin/taxes/delete/{id}', [TaxController::class, 'delete'])->name('taxes_delete');
    Route::post('admin/taxes/delete-multiple', [TaxController::class, 'deleteMultiple']);
    Route::post('/admin/taxes/toggle-company-tax', [TaxController::class, 'toggleCompanyTax'])->name('taxes.toggleCompanyTax');


    // Insurance
    Route::get('admin/insurance', [InsuranceController::class, 'index'])->name('insurance');
    Route::get('admin/insurance/add', [InsuranceController::class, 'add'])->name('insurance_add');
    Route::post('admin/insurance/add', [InsuranceController::class, 'add_post'])->name('insurance_add_post');
    Route::get('admin/insurance/edit/{id}', [InsuranceController::class, 'edit'])->name('insurance_edit');
    Route::post('admin/insurance/update/{id}', [InsuranceController::class, 'edit_update'])->name('insurance_update');
    Route::get('admin/insurance/delete/{id}', [InsuranceController::class, 'delete'])->name('insurance_delete');
    Route::post('admin/insurance/delete-multiple', [InsuranceController::class, 'deleteMultiple']);
    Route::post('/admin/insurance/toggle-company-insurance', [InsuranceController::class, 'toggleCompanyInsurance'])->name('insurances.toggleCompanyInsurance');

    //Branches   admin/branches
    Route::get('admin/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('admin/branches/add', [BranchController::class, 'add'])->name('branches.add');
    Route::post('admin/branches/add', [BranchController::class, 'add_post'])->name('branches.add_post');
    Route::get('admin/branches/edit/{id}', [BranchController::class, 'edit'])->name('branches.edit');
    Route::post('admin/branches/edit/{id}', [BranchController::class, 'edit_update'])->name('branches.edit_update');
    Route::get('admin/branches/delete/{id}', [BranchController::class, 'delete'])->name('branches.delete');
    Route::get('/admin/branches/transfer', [BranchController::class, 'showTransferForm'])->name('branches.transfer.form');
    Route::post('/admin/branches/transfer', [BranchController::class, 'assignEmployee'])->name('branches.transfer');

    //Company News  admin/news
    Route::get('admin/news', [NewsController::class, 'index'])->name('news.index');

    Route::get('admin/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('admin/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('admin/news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('admin/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('admin/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('admin/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::get('admin/news/filter', [NewsController::class, 'filterNews'])->name('news.filter');
    Route::post('admin/news/{news}/toggle-status', [NewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::post('admin/news/bulk-delete', [NewsController::class, 'bulkDelete'])->name('news.bulk-delete');

    //my account   admin/my_account
    route::get('admin/my_account', [MyAccountController::class, 'my_account'])->name('my_account');
    route::post('admin/my_account/update', [MyAccountController::class, 'edit_update'])->name('my_account_update');
    //my account   admin/my_account
    route::get('admin/my_account', [MyAccountController::class, 'my_account'])->name('my_account');
    route::post('admin/my_account/update', [MyAccountController::class, 'edit_update'])->name('my_account_update');

    //Company information  admin/company-info..
    route::get('admin/company-info', [CompanyInfoController::class, 'index'])->name('company-info');
    route::post('admin/company-info/update', [CompanyInfoController::class, 'edit_update'])->name('company_info_update');
    Route::get('/view-logo/{filename}', function ($filename) {
        $path = public_path('../../HR-Uploads/company_logos/' . $filename);
        //Company information  admin/company-info..
        route::get('admin/company-info', [CompanyInfoController::class, 'index'])->name('company-info');
        route::post('admin/company-info/update', [CompanyInfoController::class, 'edit_update'])->name('company_info_update');
        Route::get('/view-logo/{filename}', function ($filename) {
            $path = public_path('../../HR-Uploads/company_logos/' . $filename);

            if (!file_exists($path)) {
                abort(404);
            }
            if (!file_exists($path)) {
                abort(404);
            }

            return response()->file($path);
        })->name('view.logo');
        return response()->file($path);
    })->name('view.logo');

    // Performance Management (your existing routes)
    Route::get('admin/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('admin/performance/create', [PerformanceController::class, 'create'])->name('performance.create');
    Route::post('admin/performance', [PerformanceController::class, 'store'])->name('performance.store');
    Route::get('admin/performance/{id}', [PerformanceController::class, 'show'])->name('performance.show');
    Route::get('admin/performance/{id}/edit', [PerformanceController::class, 'edit'])->name('performance.edit');
    Route::put('admin/performance/{id}', [PerformanceController::class, 'update'])->name('performance.update');
    Route::delete('admin/performance/{id}', [PerformanceController::class, 'destroy'])->name('performance.destroy');
    // Performance Management (your existing routes)
    Route::get('admin/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('admin/performance/create', [PerformanceController::class, 'create'])->name('performance.create');
    Route::post('admin/performance', [PerformanceController::class, 'store'])->name('performance.store');
    Route::get('admin/performance/{id}', [PerformanceController::class, 'show'])->name('performance.show');
    Route::get('admin/performance/{id}/edit', [PerformanceController::class, 'edit'])->name('performance.edit');
    Route::put('admin/performance/{id}', [PerformanceController::class, 'update'])->name('performance.update');
    Route::delete('admin/performance/{id}', [PerformanceController::class, 'destroy'])->name('performance.destroy');

    // Employee Performance Report (your existing route)
    Route::get('admin/performance/employee/{employeeId}/report', [PerformanceController::class, 'employeeReport'])->name('performance.employee-report');
    // Employee Performance Report (your existing route)
    Route::get('admin/performance/employee/{employeeId}/report', [PerformanceController::class, 'employeeReport'])->name('performance.employee-report');

    // NEW: Performance Criteria Management Routes
    Route::get('admin/performance-criteria', [PerformanceCriteriaController::class, 'index'])->name('performance-criteria.index');
    Route::get('admin/performance-criteria/create', [PerformanceCriteriaController::class, 'create'])->name('performance-criteria.create');
    Route::post('admin/performance-criteria', [PerformanceCriteriaController::class, 'store'])->name('performance-criteria.store');
    Route::get('admin/performance-criteria/{id}/edit', [PerformanceCriteriaController::class, 'edit'])->name('performance-criteria.edit');
    Route::put('admin/performance-criteria/{id}', [PerformanceCriteriaController::class, 'update'])->name('performance-criteria.update');
    Route::delete('admin/performance-criteria/{id}', [PerformanceCriteriaController::class, 'destroy'])->name('performance-criteria.destroy');
    Route::post('admin/performance-criteria/update-order', [PerformanceCriteriaController::class, 'updateOrder'])->name('performance-criteria.update-order');
    Route::resources(['admin/locations' => LocationController::class]);
    Route::post(('admin/locations/assign-employees'), [LocationController::class, 'assignEmployees'])->name('locations.assignEmployees');
    Route::get('admin/locations/{location}/employees', [LocationController::class, 'getAssignedEmployees'])->name('locations.getAssignedEmployees');


    // Make sure to import the controller at the top of your routes file:
    // NEW: Performance Criteria Management Routes
    Route::get('admin/performance-criteria', [PerformanceCriteriaController::class, 'index'])->name('performance-criteria.index');
    Route::get('admin/performance-criteria/create', [PerformanceCriteriaController::class, 'create'])->name('performance-criteria.create');
    Route::post('admin/performance-criteria', [PerformanceCriteriaController::class, 'store'])->name('performance-criteria.store');
    Route::get('admin/performance-criteria/{id}/edit', [PerformanceCriteriaController::class, 'edit'])->name('performance-criteria.edit');
    Route::put('admin/performance-criteria/{id}', [PerformanceCriteriaController::class, 'update'])->name('performance-criteria.update');
    Route::delete('admin/performance-criteria/{id}', [PerformanceCriteriaController::class, 'destroy'])->name('performance-criteria.destroy');
    Route::post('admin/performance-criteria/update-order', [PerformanceCriteriaController::class, 'updateOrder'])->name('performance-criteria.update-order');
    Route::resources(['admin/locations' => LocationController::class]);
    Route::post(('admin/locations/assign-employees'), [LocationController::class, 'assignEmployees'])->name('locations.assignEmployees');
    Route::get('admin/locations/{location}/employees', [LocationController::class, 'getAssignedEmployees'])->name('locations.getAssignedEmployees');


    // Messages routes
    Route::get('admin/messages', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('admin/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('admin/messages/store', [MessageController::class, 'store'])->name('messages.store');
    Route::get('admin/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::get('admin/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('admin/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Financial Analysis Routes
      Route::get('admin/financial-analysis', [FinancialAnalysisController::class, 'index'])
        ->name('financial.analysis');

});



//middlware 2 (Admin interface)
Route::middleware('SuperAdmin')->group(function () {

    Route::get('admin/landing', [AuthController::class, 'adminLanding'])->name('admin.landing');
    // Company management
    Route::get('admin/companies', [CompanyController::class, 'index'])->name('admin.companies');
    Route::delete('admin/companies/{company}', [CompanyController::class, 'destroy'])->name('admin.companies.destroy');
    // Admin management
    Route::get('admin/admins/manage', [AdminController::class, 'manage'])->name('admin.admins.manage');
    Route::delete('admin/admins/{admin}', [AdminController::class, 'destroy'])->name('admin.admins.destroy');
    // Display the admin registration page
    Route::get('admin/register', [AuthController::class, 'adminRegister'])->name('admin.register');
    Route::post('admin/register', [AuthController::class, 'adminRegisterPost'])->name('admin.register.post');
    // register new company
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'register_post'])->name('register_post');
});


//middlware 3 (Employee interface)
Route::middleware('employee')->group(function () {

    //dashboard   employee/home
    Route::get('employee/home', [EmployeeHomeController::class, 'index'])->name('employee.home');
    Route::get('employee/logout', [EmployeeHomeController::class, 'logout'])->name('employee.logout');

    // Route to serve news images
    Route::get('employee/news-image/{filename}', [EmployeeHomeController::class, 'viewNewsImage'])->name('employee.news.image');
    Route::get('employee/news/{news}', [EmployeeHomeController::class, 'show'])->name('Employeenews.show');
    Route::get('employee/calendar', [EmployeeCalendarController::class, 'index'])->name('employee.calendar');

    //my account   employee/my_account
    Route::get('/employee/my_account', [EmployeeMyAccountController::class, 'my_account'])->name('employee.my_account');
    Route::post('/employee/my_account/update', [EmployeeMyAccountController::class, 'edit_update'])->name('employee.my_account.update');

    //payroll
    Route::get('employee/payroll', [EmployeePayrollController::class, 'index'])->name('employee.payroll');
    Route::post('employee/payroll/download-pdf', [EmployeePayrollController::class, 'downloadSinglePayslip'])->name('employee.payslip.download.single');

    //attendance
    Route::get('employee/attendance', [EmployeeAttendanceController::class, 'index'])->name('employee.attendance');

    //Company Policy
    Route::get('employee/policys', [AttendanceRulesController::class, 'policy'])->name('employee.policy');

    //Performance
    Route::get('employee/performance', [EmployeePerformanceController::class, 'index'])->name('employee.performances.index');
    Route::get('employee/performance/{id}', [EmployeePerformanceController::class, 'show'])->name('employee.performances.show');


    // Vacation Routes
    Route::get('employee/vacation', [EmployeeVacationController::class, 'index'])->name('vacation.index');
    Route::post('employee/vacation', [EmployeeVacationController::class, 'store'])->name('employee.vacation.store');
    Route::get('employee/vacation/{id}', [EmployeeVacationController::class, 'show'])->name('vacation.show');
    Route::get('employee/vacation/{id}/edit', [EmployeeVacationController::class, 'edit'])->name('vacation.edit');
    Route::put('employee/vacation/{id}', [EmployeeVacationController::class, 'update'])->name('vacation.update');
    Route::delete('employee/vacation/{id}', [EmployeeVacationController::class, 'cancel'])->name('vacation.cancel');

    //Resignation
    Route::get('employee/resignation', [ResignationController::class, 'index'])->name('employee.resignation.index');
    Route::get('employee/resignation/create', [ResignationController::class, 'create'])->name('employee.resignation.create');
    Route::post('employee/resignation', [ResignationController::class, 'store'])->name('employee.resignation.store');
    Route::delete('employee/resignation/{id}', [ResignationController::class, 'destroy'])->name('employee.resignation.destroy');

    //extratime request
    Route::get('employee/extra', [ExtraTimeRequestController::class, 'index'])->name('employee.extra.index');
    Route::post('employee/extra', [ExtraTimeRequestController::class, 'store'])->name('employee.extra.store');
    Route::delete('extra/{id}', [ExtraTimeRequestController::class, 'destroy'])->name('employee.extra.destroy');

    //late request
    Route::get('employee/late', [EmployeeLateRemovalController::class, 'index'])->name('employee.late.index');
    Route::post('employee/late/request', [EmployeeLateRemovalController::class, 'store'])->name('employee.late.request');
    Route::post('employee/late-removal-request', [EmployeeLateRemovalController::class, 'store'])->name('employee.late.removal.request');
    Route::post('employee/late-removal/store', [EmployeeLateRemovalController::class, 'store'])->name('employee.late.removal.store');

    //Early leave request
    Route::get('employee/early-leave', [EarlyLeaveController::class, 'index'])->name('employee.early_leave.index');
    Route::post('employee/early-leave/store', [EarlyLeaveController::class, 'store'])->name('employee.early.store');
    Route::delete('employee/early-leave/cancel/{id}', [EarlyLeaveController::class, 'cancel'])->name('employee.early.destroy');


    // this to show each request page from notification
    Route::get('processed-requests/{type}/{id}', [RequestController::class, 'showProcessedRequest'])->name('processed-requests.show');

    // Messages routes for employees
    Route::get('employee/messages', [MessageController::class, 'employeeInbox'])->name('employee.messages.inbox');
    Route::get('employee/messages/{message}', [MessageController::class, 'employeeShow'])->name('employee.messages.show');
    Route::post('employee/messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('employee.messages.mark-read');



});


Route::get('logout', [AuthController::class, 'logout'])->name('logout');
