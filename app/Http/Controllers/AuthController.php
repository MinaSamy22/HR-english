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

return redirect('/')->with('success', __('auth.registered_successfully'));







    }


public function login_post(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Debug: Log the credentials (remove password for security)
    \Log::info('Login attempt for email: ' . $credentials['email']);

    // Admin attempt FIRST
    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        \Log::info('Admin login successful');
        return redirect()->route('admin.landing');
    }

    // HR attempt BEFORE Employee (since they're in the same table)
    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::guard('web')->user();

        \Log::info('Web guard login successful for user ID: ' . $user->id);
        \Log::info('User is_role value: ' . $user->is_role);

        if ($user->is_role == '1' || $user->is_role == 1) {
            session([
                'company_id' => $user->company_id,
                'branch_id' => $user->branch_id
            ]);
            \Log::info('HR user redirected to dashboard');
            return redirect()->route('dashboard');
        }

        // If not HR, check if they're an employee (is_role == 0)
        if ($user->is_role == '0' || $user->is_role == 0) {
            // Switch to employee guard for consistency
            Auth::guard('web')->logout();
            Auth::guard('employee')->login($user);

            session([
                'company_id' => $user->company_id,
                'branch_id' => $user->branch_id,
                'employee_id' => $user->id
            ]);

            \Log::info('Employee user redirected to employee dashboard');
            return redirect()->route('employee.home');
        }

        Auth::guard('web')->logout();
        return back()->with('error', 'Invalid user role.');
    }

    \Log::warning('Login failed for email: ' . $credentials['email']);
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
return redirect('/')->with('success', __('auth.admin_registered_successfully'));
    }

    public function adminLanding(Request $request)
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

        // Filter Year configuration (2026 to 2030)
        $currentYear = (int) \Carbon\Carbon::now()->year;
        $selectedYear = (int) $request->get('year', $currentYear);
        $availableYears = range(2026, 2030);

        $months = [];
        $monthlyEmployees = [];
        $monthlyCompanies = [];

        for ($m = 1; $m <= 12; $m++) {
            $date = \Carbon\Carbon::createFromDate($selectedYear, $m, 1);
            $months[] = $date->format('M');

            $empCount = User::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->count();
            $monthlyEmployees[] = $empCount;

            $compCount = Company::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->count();
            $monthlyCompanies[] = $compCount;
        }

        $data['selectedYear'] = $selectedYear;
        $data['availableYears'] = $availableYears;
        $data['monthlyGrowth'] = [
            'labels' => $months,
            'employees' => $monthlyEmployees,
            'companies' => $monthlyCompanies,
        ];

        return view('admins.home.admin_home', $data);
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

return redirect('/')->with('success', __('auth.logged_out_successfully'));
}


}
