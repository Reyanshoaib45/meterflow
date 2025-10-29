<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CustomMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow the toggle route and the health check
        if ($request->is('maintenance/*') || $request->is('up')) {
            return $next($request);
        }

        // Skip for assets (css/js/images)
        if ($request->is('css/*') || $request->is('js/*') || $request->is('images/*') || $request->is('favicon.ico')) {
            return $next($request);
        }

        $enabled = Cache::get('custom_maintenance_enabled', false);
        if ($enabled) {
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
