<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // HSTS (Strict-Transport-Security) - Force HTTPS for 2 years
        $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');

        // X-Frame-Options - Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options - Prevent MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection (legacy but still useful for older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy - Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy (formerly Feature-Policy)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content-Security-Policy (basic CSP - can be customized)
        // Note: This is a basic CSP. Adjust based on your needs.
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com https://fonts.bunny.net; " .
               "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://fonts.bunny.net; " .
               "font-src 'self' https://fonts.bunny.net https://cdnjs.cloudflare.com data:; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self' https://fonts.bunny.net; " .
               "frame-ancestors 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self'; " .
               "upgrade-insecure-requests;";
        
        $response->headers->set('Content-Security-Policy', $csp);

        // Cache-Control for static assets (set in web server config ideally)
        // But we can set it for responses here
        if ($request->is('*.css') || $request->is('*.js') || $request->is('*.jpg') || $request->is('*.png') || $request->is('*.gif') || $request->is('*.svg')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }

        return $response;
    }
}

