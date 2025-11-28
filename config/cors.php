<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin requests. This
    | configuration determines which domains, methods, and headers are
    | allowed to access your API.
    |
    */

    // Apply CORS only to API routes
    'paths' => ['api/*'],

    // Only allow the methods your frontend will use
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // Only allow your production frontend domain
    'allowed_origins' => ['https://positiveai.online'],

    // Optional: patterns for dynamic subdomains if needed
    'allowed_origins_patterns' => [],

    // Only allow headers your frontend will send
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],

    // Headers exposed to the frontend
    'exposed_headers' => ['Authorization'],

    // Preflight request cache duration in seconds
    'max_age' => 3600,

    // Allow cookies/credentials to be sent
    'supports_credentials' => true,

];
