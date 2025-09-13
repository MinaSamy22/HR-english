<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\User;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
public function index(Request $request)
{
    $data['getRecord'] = Deduction::getRecord($request);

    // 🆕 Add branches for filter dropdown
    $data['branches'] = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.deductions.index', $data);
}

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

    return view('backend.deductions.add', $data);
}

    public function add_post(Request $request){         //for validation logic ///// any post logic must put in it the validation before saving in data base

    // dd($request->all());
        $deduction = request()->validate([

    'employee_id'         => 'required',
    'deduction_type'      => 'required',
    'money_deduction'     => 'required',
    'created_at'          => 'required',

    ]);

    $deduction                          = new Deduction;
    $deduction->employee_id             = trim ($request->employee_id);
    $deduction->deduction_type          = trim ($request->deduction_type);
    $deduction->money_deduction         = trim ($request->money_deduction);
    $deduction->created_at              = trim ($request->created_at);
    $deduction->company_id              = session('company_id'); // Important!
// Handle company/branch assignment
    $deduction->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $deduction->branch_id = session('branch_id');
    }
    $deduction->save();

    return redirect('admin/deductions')->with('success', __('h_deduction.add-message'));
    }


 // Method to delete a record
 public function delete($id){
    $recordDelete = Deduction::find($id);
    if ($recordDelete) {
        $recordDelete->delete();
        return redirect()->back()->with('success', __('h_deduction.delete-message'));
    } else {
        return redirect()->back()->with('error', 'Record not found.');
    }
}

public function deleteMultiple(Request $request)
{
    $ids = $request->input('ids');

    if (!$ids) {
        return response()->json(['success' => false, 'message' => 'No deduction selected.']);
    }

    Deduction::whereIn('id', $ids)->delete();

    return response()->json(['success' => true, 'message' => 'Selected deductions deleted successfully.']);
}

}
