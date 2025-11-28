<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Paths
    |--------------------------------------------------------------------------
    | Apply CORS ONLY to the API endpoints, not web routes.
    */
    'paths' => ['api/*'],


    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    | Only allow the methods your frontend will actually use.
    | This reduces the attack surface.
    */
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],


    /*
    |--------------------------------------------------------------------------
    | Allowed Origins (Production)
    |--------------------------------------------------------------------------
    | VERY IMPORTANT:
    | Only allow trusted frontend domains.
    | Do NOT allow localhost or wildcards in production deploys.
    |
    | If you need localhost for development,
    | add it ONLY in your local environment's cors.php.
    */
    'allowed_origins' => [
        'https://positiveai.online',     // main production frontend (React)
        'https://www.positiveai.online', // optional alias
    ],


    /*
    |--------------------------------------------------------------------------
    | Allowed Origin Patterns
    |--------------------------------------------------------------------------
    | Not needed for your production use case.
    */
    'allowed_origins_patterns' => [],


    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    | Allow Authorization header because you use Bearer tokens.
    */
    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
    ],


    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    | You can expose Authorization, but generally it's not needed.
    */
    'exposed_headers' => [],


    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    | Cache OPTIONS preflight for better performance.
    */
    'max_age' => 3600,


    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    | MUST BE FALSE for Bearer Token APIs.
    | If true, browser expects cookies & CSRF.
    | This is the #1 cause of failed authentication in APIs.
    */
    'supports_credentials' => false,

];
