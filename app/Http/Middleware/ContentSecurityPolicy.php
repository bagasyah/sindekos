<?php

namespace App\Http\Middleware;

use Closure;

class ContentSecurityPolicy
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Menambahkan header CSP
        // Memperbarui kebijakan untuk mengizinkan sumber dari CDN dan menambahkan nonce
        $nonce = base64_encode(uniqid()); // Generate nonce
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'nonce-{$nonce}'; style-src 'self'; ");
        
        // Menyimpan nonce ke dalam request untuk digunakan di view
        $request->attributes->set('nonce', $nonce);

        return $response;
    }
}
