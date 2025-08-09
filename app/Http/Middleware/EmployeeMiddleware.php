<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if employee is authenticated via 'employee' guard
        if (!Auth::guard('employee')->check()) {
            return redirect('/')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
