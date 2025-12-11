<?php

namespace App\Http\Controllers;
use App\Models\Vacation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VacationController extends Controller
{
    // Method to display the list of vacations
public function index(Request $request)
{
    $company_id = session('company_id');
    $branch_id = $request->filter_branch_id ?? session('branch_id');

    // Base Query
    $baseQuery = \DB::table('vacations')
        ->join('users', 'users.id', '=', 'vacations.employee_id')
        ->leftJoin('branches', 'branches.id', '=', 'vacations.branch_id')
        ->where('vacations.company_id', $company_id)
        ->select(
            'vacations.*',
            'users.name',
            'users.vacation_balance',
            'branches.name as branch_name'
        );

    // Filter by name
    if (!empty($request->name)) {
        $baseQuery->where('users.name', 'like', '%' . $request->name . '%');
    }

    // Filter by branch
    if (!empty($request->filter_branch_id)) {
        $baseQuery->where('vacations.branch_id', $request->filter_branch_id);
    }

    // ================
    // 1) With Balance
    // ================
    $withBalance = (clone $baseQuery)
        ->where('users.vacation_balance', '>', 0)
        ->orderBy('vacations.id', 'desc')
        ->paginate(10, ['*'], 'withBalancePage');

    // ==================
    // 2) No Balance
    // ==================
    $noBalance = (clone $baseQuery)
        ->where(function ($q) {
            $q->where('users.vacation_balance', '=', 0)
              ->orWhereNull('users.vacation_balance');
        })
        ->orderBy('vacations.id', 'desc')
        ->paginate(10, ['*'], 'noBalancePage');

    // Branches list
    $branches = \DB::table('branches')
        ->where('company_id', $company_id)
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.vacations.index', compact(
        'withBalance',
        'noBalance',
        'branches'
    ));
}

    // Method to show the add vacation form
    public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    // If branch_id is null, show all users for the company
    if (empty($branch_id)) {
        $data['getUsers'] = User::where('company_id', $company_id)->get();
    } else {
        // Check if the current branch is the main branch
        $currentBranch = \DB::table('branches')
            ->where('id', $branch_id)
            ->select('is_main')
            ->first();

        // If it's the main branch (is_main == 1), show all users for the company
        if ($currentBranch && $currentBranch->is_main == 1) {
            $data['getUsers'] = User::where('company_id', $company_id)->get();
        } else {
            // Otherwise, filter by the specific branch_id
            $data['getUsers'] = User::where('branch_id', $branch_id)->get();
        }
    }

    return view('backend.vacations.add', $data);
}

// Method to handle the submission of the add vacation form
public function add_post(Request $request)
{
    $validatedData = $request->validate([
        'employee_id'    => 'required|exists:users,id',
        'vacation_type'  => 'required',
        'start_date'     => 'required|date',
        'end_date'       => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = Carbon::parse($request->start_date);
    $endDate   = Carbon::parse($request->end_date);

    $totalDays = $endDate->diffInDays($startDate) + 1;

    // Get employee
    $employee = User::find($request->employee_id);

    if (!$employee) {
        return back()->withErrors(['error' => 'Invalid employee.']);
    }

    // ✅ Get vacation balance from users table (default 25 if null)
    $vacationLimit = $employee->vacation_balance ?? 25;

    // Calculate already used days
    $totalUsed = Vacation::where('employee_id', $employee->id)->sum('total');

    // Remaining balance BEFORE this request
    $remainingBalance = max(0, $vacationLimit - $totalUsed);

    if ($remainingBalance <= 0) {
        return back()->withErrors(['error' => 'You have exhausted your allowed vacation balance.']);
    }

    if ($totalDays > $remainingBalance) {
        return back()->withErrors(['error' => "You are trying to request $totalDays days, but the remaining balance is only $remainingBalance days. Vacation request denied."]);
    }

    // ✅ Update remaining balance AFTER this request
    $remainingAfterVacation = $remainingBalance - $totalDays;

    // Save vacation request
    $vacation = new Vacation();
    $vacation->employee_id       = trim($request->employee_id);
    $vacation->vacation_type     = trim($request->vacation_type);
    $vacation->start_date        = $startDate;
    $vacation->end_date          = $endDate;
    $vacation->total             = $totalDays;
    $vacation->remaining_balance = $remainingAfterVacation; // 🔹 new column
    $vacation->company_id        = session('company_id');

    if (session()->has('branch_id')) {
        $vacation->branch_id = session('branch_id');
    }

    $vacation->save();

    // 🔹 (اختياري) تحديث رصيد الموظف في جدول المستخدمين
    // $employee->update(['vacation_balance' => $remainingAfterVacation]);

    return redirect('admin/vacations')->with('success', __('h_vacation.controller-add-message'));
}



    // Method to delete a vacation record
    public function delete($id){
        $recordDelete = Vacation::find($id);
        if ($recordDelete) {
            $recordDelete->delete();
            return redirect()->back()->with('success', __('h_vacation.controller-delete-message'));
        } else {
            return redirect()->back()->with('error', 'Record not found.');
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No vacations selected.']);
        }

        Vacation::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected vacations deleted successfully.']);
    }
}
