<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function included\sendResponse;

class checkDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $deviceIdFromRequest = $request->header('deviceserial');

            if (empty($request->user()->macaddress)) {
                // Save the first device serial as the macaddress
                $request->user()->update([
                    'macaddress' => $deviceIdFromRequest,
                ]);
            } elseif ($deviceIdFromRequest !== $request->user()->macaddress) {
                return sendResponse([],'Unauthorized device', 0);
            }
        }

        return $next($request);
    }
}
