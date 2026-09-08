<?php

namespace App\Http\Controllers;

use App\Models\EarlyLeave;
use App\Models\User;
use Illuminate\Http\Request;

class HrEarlyLeaveController extends Controller
{
    public function index(Request $request)
    {
        $data['getRecord'] = EarlyLeave::getRecord($request);

        $data['branches'] = \DB::table('branches')
            ->where('company_id', session('company_id'))
            ->select('id', 'name', 'is_main')
            ->orderBy('name')
            ->get();

        return view('backend.early-leave.list', $data);
    }

    public function add(Request $request)
    {
        $company_id = session('company_id');
        $branch_id  = session('branch_id');

        if (empty($branch_id)) {
            $data['getUsers'] = User::where('company_id', $company_id)->get();
        } else {
            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            if ($currentBranch && $currentBranch->is_main == 1) {
                $data['getUsers'] = User::where('company_id', $company_id)->get();
            } else {
                $data['getUsers'] = User::where('branch_id', $branch_id)->get();
            }
        }

        return view('backend.early-leave.add', $data);
    }

    public function add_post(Request $request)
    {
        $request->validate([
            'employee_id'          => 'required',
            'request_date'         => 'required|date',
            'requested_leave_time' => 'required',
            'reason'               => 'required|string|max:500',
        ]);

        $record                        = new EarlyLeave;
        $record->employee_id           = trim($request->employee_id);
        $record->request_date          = trim($request->request_date);
        $record->requested_leave_time  = trim($request->requested_leave_time);
        $record->reason                = trim($request->reason);
        $record->company_id            = session('company_id');
        $record->branch_id             = session('branch_id');
        $record->created_by            = auth()->id();
        $record->save();

        return redirect('admin/early-leave')->with('success', __('h_early_leave.success_register'));
    }

    public function edit($id)
    {
        $data['getRecord'] = EarlyLeave::with('user')->findOrFail($id);

        return view('backend.early-leave.edit', $data);
    }

    public function edit_update($id, Request $request)
    {
        $request->validate([
            'request_date'         => 'required|date',
            'requested_leave_time' => 'required',
            'reason'               => 'required|string|max:500',
        ]);

        $record                        = EarlyLeave::findOrFail($id);
        $record->request_date          = trim($request->request_date);
        $record->requested_leave_time  = trim($request->requested_leave_time);
        $record->reason                = trim($request->reason);
        $record->updated_by            = auth()->id();
        $record->save();

        return redirect('admin/early-leave')->with('success', __('h_early_leave.success_updated'));
    }


    public function delete($id)
    {
        $record = EarlyLeave::find($id);
        if ($record) {
            $record->delete();
            return redirect()->back()->with('success', __('h_early_leave.success_deleted'));
        }
        return response()->json(['success' => false, 'message' => __('h_early_leave.no_record_selected')]);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return response()->json(['success' => false, 'message' => __('h_early_leave.no_record_selected')]);
        }

        EarlyLeave::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => __('h_early_leave.selected_deleted')]);
    }
}
