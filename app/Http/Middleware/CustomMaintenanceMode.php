<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CustomMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (Setting::isMaintenanceMode()) {
            // Allow owner and admin users to bypass maintenance mode
            if ($request->user() && in_array($request->user()->role, ['owner', 'admin'])) {
                return $next($request);
            }

            // Show maintenance page to other users
            $settings = [
                'show_maintenance_below_loading' => Setting::get('show_maintenance_below_loading', '0')
            ];

            return response()->view('errors.maintenance', compact('settings'), 503);
        }

        return $next($request);
    }
}
