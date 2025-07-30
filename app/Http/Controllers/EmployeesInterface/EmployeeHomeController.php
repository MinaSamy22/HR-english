<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeHomeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('employee')->user();

        // تقدر هنا تعرض بيانات الموظف أو attendance وغيره
        return view('EmployeeInterface.dashboard.list', compact('user'));
    }

     public function logout( $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/employee/login'); // أو أي صفحة تسجيل دخول عندك
    }
}
