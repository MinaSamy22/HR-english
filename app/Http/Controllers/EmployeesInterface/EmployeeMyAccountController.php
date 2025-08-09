<?php

namespace App\Http\Controllers\EmployeesInterface;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeMyAccountController extends Controller
{
public function my_account(Request $request)
    {
        $user = Auth::guard('employee')->user();

        $data['getRecord'] = User::where('id', $user->id)
            ->where('company_id', $user->company_id)
            ->first();

        if (!$data['getRecord']) {
            return redirect()->back()->with('error', 'User not found or does not belong to your company.');
        }

        return view('EmployeeInterface.my-account.update', $data);
    }

    public function edit_update(Request $request)
    {
        $user = Auth::guard('employee')->user();

        // Validate input
        $request->validate([
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'phone_number'    => 'nullable|numeric',
            'current_password'=> 'nullable',
        ]);

        $record = User::where('id', $user->id)
            ->where('company_id', $user->company_id)
            ->first();

        if (!$record) {
            return redirect()->back()->with('error', 'User not found or does not belong to your company.');
        }

        $record->name = trim($request->name);
        $record->email = trim($request->email);

        if (!empty($request->phone_number)) {
            $record->phone_number = trim($request->phone_number);
        }

        // Update password if new one provided
        if (!empty($request->password)) {
            if (Hash::check($request->current_password, $record->password)) {
                $record->password = Hash::make($request->password);
            } else {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
        }

        $record->save();
        return redirect('employee/my_account')->with('success', 'My Account Successfully Updated!');

    }
}
