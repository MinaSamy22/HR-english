<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class checkDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if ($request->user() && $request->user()->macaddress) {
        //     $deviceIdFromRequest = $request->header('Device-ID');

        //     if ($deviceIdFromRequest !== $request->user()->macaddress) {
        //         return response()->json(['message' => 'Unauthorized device'], 401);
        //     }
        // }
        Log::info("message",$request->header());
        return $next($request);
    }
}
