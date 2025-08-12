<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyAccountController extends Controller
{
    public function my_account(Request $request)
    {
        $data['getRecord'] = User::where('company_id', Auth::user()->company_id)
            ->where('id', Auth::user()->id)
            ->first();

        if (!$data['getRecord']) {
            return redirect()->back()->with('error', 'User not found or does not belong to your company.');
        }
        return view('backend.my-account.update', $data);
    }


    public function edit_update(Request $request)
    {
        // Validate input
        $use = $request->validate([
            'email'                 => 'required|unique:users,email,' . Auth::user()->id,
            'phone_number'          => 'nullable|numeric',
            'current_password'      => 'nullable', // Optional field
        ]);

        // Find the user by company_id and id to ensure they're from the correct company
        $use = User::where('company_id', Auth::user()->company_id)
            ->where('id', Auth::user()->id)
            ->first();
        if (!$use) {
            return redirect()->back()->with('error', 'User not found or does not belong to your company.');
        }

        // Update user details
        $use->name                    = trim($request->name);
        $use->email                   = trim($request->email);

        // Update phone number if provided
        if (!empty($request->phone_number)) {
            $use->phone_number        = trim($request->phone_number);
        }

        // Update password if the current password is provided and is correct
        if (!empty($request->password)) {
            if (Hash::check($request->current_password, $use->password)) {
                $use->password = Hash::make($request->password); // Save the new password securely
            } else {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
        }

        $use->save();

        return redirect('admin/my_account')->with('success', __('E_myaccount.controller message'));
    }
}
