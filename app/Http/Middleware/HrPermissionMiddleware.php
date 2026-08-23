<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check() || Auth::user()->is_role != 1) {
            return redirect('/')->with('error', 'Access denied.');
        }

        // If no specific permission parameter was passed, allow request
        if (empty($permissions)) {
            return $next($request);
        }

        // Check if user has ANY of the specified permissions
        foreach ($permissions as $permission) {
            if (hr_can($permission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['error' => 'Access denied: You do not have permission to perform this action.'], 403);
        }

        return redirect()->route('dashboard')->with('error', __('dashboard.access_denied') ?? 'Access denied: You do not have permission to access this page.');
    }
}
