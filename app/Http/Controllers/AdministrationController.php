<?php
namespace App\Http\Controllers;

use App\Models\Administration;
use App\Models\Manager;
use Illuminate\Http\Request;


class AdministrationController extends Controller
{


public function index(Request $request)
{
    $getRecord = Administration::getRecord($request);

    // Get branches for filter dropdown
    $branches = \DB::table('branches')
        ->where('company_id', session('company_id'))
        ->select('id', 'name', 'is_main')
        ->orderBy('name')
        ->get();

    // Get managers for filter dropdown
    $managers = \DB::table('managers')
        ->where('company_id', session('company_id'))
        ->select('id', 'name')
        ->orderBy('name')
        ->get();     //for reterving managers data from database and retrive model logic
    return view('backend.administration.list', compact('getRecord', 'branches', 'managers'));

}

public function add(Request $request)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

   // ✅ Always show all company data (no branch restriction)
    $data['getManagers'] = Manager::where('company_id', $company_id)->get();


    return view('backend.administration.add', $data);
}

public function add_post(Request $request){         //for validation logic ///// any post logic must put in it the validation before saving in data base

// dd($request->all());
    $administration                        = request()->validate([
    'name'                             => 'required',
    'code'                             => 'required',
    'manager_id'                       => 'required',

]);

$administration                       = new Administration;
$administration->name                 = trim ($request->name);
$administration->code                 = trim ($request->code);
$administration->manager_id           = trim ($request->manager_id);
$administration->company_id           = session('company_id'); // Important!

 // Handle company/branch assignment
    $administration->company_id = session('company_id');
    if (session()->has('branch_id')) {
        $administration->branch_id = session('branch_id');
    }
$administration->save();

return redirect('admin/administration')->with('success', __('h_adminstration.administration_added'));

}


public function edit($id)
{
    $company_id = session('company_id');
    $branch_id = session('branch_id');

    $data['getRecord'] = Administration::find($id);

    // if ($branch_id !== null) {
    //     $data['getManagers'] = Manager::where('branch_id', $branch_id)->get();
    // } else {
    //     $data['getManagers'] = Manager::where('company_id', $company_id)
    //                                   ->whereNull('branch_id')
    //                                   ->get();
    // }
// ✅ Always show all company data (no branch restriction)
    $data['getManagers'] = Manager::where('company_id', $company_id)->get();


    return view('backend.administration.edit', $data);
}


public function edit_update ($id, Request $request){

    $administration = Administration::find($id);

// $administration                    = new administration; de lma bt3ml add bs

$administration->name                 = trim ($request->name);
$administration->code                 = trim ($request->code);
$administration->manager_id           = trim ($request->manager_id);

$administration->save();

return redirect('admin/administration')->with('success', __('h_adminstration.administration_updated'));
}


public function delete($id){
    $recordDelete = Administration::find($id);
    $recordDelete->delete();
    // return redirect()->back()->with('error', 'Record successfully deleted');
    return redirect()->back()->with('error', __('h_adminstration.record_deleted'));

}



}
