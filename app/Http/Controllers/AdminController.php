<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User; // Assuming admin is a user model
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show all admins
    public function manage()
    {
        // Retrieve all admins from the 'admins' table
        $admins = Admin::all();

        return view('admins.admin.index', compact('admins')); // Pass the data to the view
    }

    // Delete an admin from the 'admins' table
    public function destroy(Admin $admin)
    {
        if (auth('admin')->id() == $admin->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $admin->delete(); // Delete the admin from the database
        return back()->with('success', 'Admin deleted successfully!'); // Redirect back with success message
    }


}
