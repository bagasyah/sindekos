<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FrameHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('Access-Control-Allow-Origin', 'https://app.sandbox.midtrans.com');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN');
        
        return $response;
    }
}
