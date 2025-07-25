<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated using the 'admin' guard
        if (!Auth::guard('admin')->check()) {
            return redirect('/')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
