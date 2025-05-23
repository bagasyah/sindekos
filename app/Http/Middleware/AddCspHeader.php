<?php

namespace App\Http\Middleware;

use Closure;

class AddCspHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Tentukan CSP yang ingin diterapkan
        $csp = "default-src 'self'; frame-ancestors 'none'; script-src 'self'; style-src 'self'; img-src 'self'; font-src 'self'; connect-src 'self'; object-src 'none'; media-src 'none'; child-src 'none'; form-action 'self'; base-uri 'self'; sandbox allow-forms allow-scripts allow-top-navigation allow-same-origin;";

        // Terapkan header CSP untuk semua permintaan
        return $next($request)
            ->header('Content-Security-Policy', $csp);
    }
}
