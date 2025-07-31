<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class EmployeeHomeController extends Controller
{
    public function index()
{
    $user = Auth::guard('employee')->user();

    $recentNews = News::where('company_id', $user->company_id)
                      ->orderBy('news_date', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->limit(4)
                      ->get();

    return view('EmployeeInterface.dashboard.list', compact('user', 'recentNews'));
}

     public function logout( $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/employee/login'); // أو أي صفحة تسجيل دخول عندك
    }
}
