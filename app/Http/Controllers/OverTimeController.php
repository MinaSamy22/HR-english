<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;

class OverTimeController extends Controller
{
    public function index(Request $request)
{
    $data['getRecord'] = Time::getRecord($request);

    // Add branches data like in your jobs controller
    $data['branches'] = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    return view('backend.bounas.list', $data);
}

    public function add(Request $request){
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    if (!empty($branch_id)) {
        $data['getUsers'] = User::where('branch_id', $branch_id)->get();
    } else {
        $data['getUsers'] = User::where('company_id', $company_id)->whereNull('branch_id')->get();
    }

    return view('backend.bounas.add', $data);

    }
    public function add_post(Request $request){         //for validation logic ///// any post logic must put in it the validation before saving in data base

    // dd($request->all());
        $bounas = request()->validate([

    'employee_id'         => 'required',
    'hours'               => 'required',
    'created_at'          => 'required',

    ]);

    $bounas                          = new Time; // time da esm elmodel
    $bounas->employee_id             = trim ($request->employee_id);
    $bounas->hours                   = trim ($request->hours);
    $bounas->created_at              = trim ($request->created_at);
    $bounas->company_id              = session('company_id'); // Important!
// Handle company/branch assignment
    $bounas->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $bounas->branch_id = session('branch_id');
    }
    $bounas->save();

return redirect('admin/bounas')->with('success', __('h_bounas.success_register'));
    }


    public function delete($id){
        $recordDelete = Time::find($id);
        if ($recordDelete) {
            $recordDelete->delete();
return redirect()->back()->with('success', __('h_bounas.success_deleted'));
        } else {
return response()->json(['success' => false, 'message' => __('h_bounas.no_time_selected')]);
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => 'No Time selected.']);
        }

        Time::whereIn('id', $ids)->delete();

return response()->json(['success' => true, 'message' => __('h_bounas.selected_deleted')]);    }

}
