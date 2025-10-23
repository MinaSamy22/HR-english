<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EmployeeService;
use function included\sendResponse;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = ['email' => strtolower($request->email), 'password' => $request->password];

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return sendResponse([], 'please enter valid credentials', 0);
        }

        return sendResponse(['date'=>auth('api')->user(),
            'token' => $token], 'login successful', 1);

    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return sendResponse([], 'logout successful', 1);
    }

    public function user()
    {
        if (!Auth::guard('api')->check()) {
            return sendResponse([], 'unauthenticated', 0);
        }
        $employeeService = new EmployeeService(auth('api')->user()->load('company.attendanceSetting','get_department_single as department'));
        $user = $employeeService->getUser();
        return sendResponse($user, 'user data retrieved successfully', 1);
    }
}
