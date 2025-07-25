<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Mail;
use App\Mail\ForgotPasswordMail;

use Illuminate\Support\Str;

class AuthController extends Controller
{



    public function index(Request $request)
    {
        return view('login');
    }




    public function register(Request $request)
    {
        return view('admins.companies.company_register');
    }



    //function to save register in datebase with validation
    public function register_post(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required_with:password|same:password|min:6',
            'company_name' => 'required',
            'country' => 'required',
            'address' => 'required',
        ]);
        // Save company
        $company = new Company;
        $company->name = $request->company_name;
        $company->country = $request->country;
        $company->address = $request->address;
        $company->save();

        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(50);
        $user->company_id = $company->id; // this line is for company


        $user->save();

        return redirect('/')->with('success', 'Register successfully..');

    }


public function login_post(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Admin attempt
    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('admin.landing');
    }

    // HR attempt
    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::guard('web')->user();

        if ($user->is_role == '1') {
            session([
                'company_id' => $user->company_id,
                'branch_id' => $user->branch_id // ✅ Save branch_id for filtering
            ]);
            $request->session()->save();
            return redirect()->route('dashboard');
        }

        Auth::guard('web')->logout();
        return back()->with('error', 'Only HR users can log in.');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->onlyInput('email');
}


    // Show the admin registration form
    public function adminRegister()
    {
        return view('admins.admin.admin_register');
    }

    // Handle the admin registration form submission
    public function adminRegisterPost(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' will check if password_confirmation matches
        ]);


        // Create the new admin
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password); // Encrypt the password
        $admin->save();

        // Redirect to login page with success message
        return redirect('/')->with('success', 'Admin registered successfully.');
    }

    public function adminLanding()
    {
        $data['getEmployeeCount'] = User::count();
        $data['totalCompanies'] = Company::count();
        $data['totalAdmins'] = Admin::count();
        $data['totalHRs'] = User::where('is_role', 1)->count(); // 1 is hr and 0 is employee

        // Get number of employees per company for the barchart of admin
        $data['employeesByCompany'] = Company::withCount('users')
            ->get()
            ->map(function ($company) {
                return [
                    'company' => $company->name,
                    'employees' => $company->users_count
                ];
            })
            ->toArray();
        return view('admin_home', $data);
    }


    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
}


}
