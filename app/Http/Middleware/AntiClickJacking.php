<?php

namespace App\Http\Middleware;

use Closure;

class AntiClickjacking
{
    // ... existing code ...

    public function handle($request, Closure $next)
    {
        if ($request->is('login') || $request->is('forgot-password') || $request->is('home/*')) {
            // CSP untuk halaman yang membutuhkan keamanan tinggi
            $csp = "default-src 'self'; frame-ancestors 'none'; script-src 'self'; style-src 'self'; img-src 'self'; font-src 'self'; connect-src 'self'; object-src 'none'; media-src 'none'; child-src 'none'; form-action 'self'; base-uri 'self'; sandbox allow-forms allow-scripts allow-top-navigation allow-same-origin;";

            return $next($request)
                ->header('Content-Security-Policy', $csp);
        }

        if ($request->is('sitemap.xml')) {
            // CSP yang lebih longgar untuk sitemap
            $csp = "default-src 'self' http: https:; script-src 'self' 'unsafe-inline' http: https:; style-src 'self' 'unsafe-inline' http: https:; img-src 'self' http: https: data:; font-src 'self' http: https:; connect-src 'self' http: https: ws:; frame-ancestors 'self';";

            return $next($request)
                ->header('Content-Security-Policy', $csp);
        }

        return $next($request);
    }
}
