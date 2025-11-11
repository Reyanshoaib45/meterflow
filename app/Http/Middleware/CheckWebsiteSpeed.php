<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckWebsiteSpeed
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $speed = Setting::getWebsiteSpeed();

        // Apply delay based on speed setting
        switch ($speed) {
            case 'low':
                sleep(5); // 5 seconds delay
                break;
            case 'medium':
                sleep(2); // 2 seconds delay
                break;
            case 'high':
            default:
                // No delay
                break;
        }

        return $next($request);
    }
}
