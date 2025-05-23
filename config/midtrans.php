<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-vqQ5YchRR_ImQZrz6V9axekq'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-zcfTw5iXDXJQzzJN'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    
    // Tambahan konfigurasi untuk callback URL
    'finish_url' => env('MIDTRANS_FINISH_URL', '/api/midtrans/finish'),
    'unfinish_url' => env('MIDTRANS_UNFINISH_URL', '/api/midtrans/unfinish'),
    'error_url' => env('MIDTRANS_ERROR_URL', '/api/midtrans/error'),
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL', '/api/midtrans/notification'),
    
    // Timeout dalam detik
    'sanitize_timeout' => env('MIDTRANS_SANITIZE_TIMEOUT', 2),
    'curl_timeout' => env('MIDTRANS_CURL_TIMEOUT', 30),
];

