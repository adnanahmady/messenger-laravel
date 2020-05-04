<?php

return [
    /**
     * Access-Control-Allow-Origin parameter for response headers
     */
    'origin' => env('CORS_ALLOWED_ORIGIN', '*'),
    
    /**
     * Access-Control-Allow-Methods parameter for allowed request methods
     * such as PUT, PATCH, POST and e.q.
     */
    'methods' => env('CORS_ALLOWED_METHODS'),
    
    /**
     * Access-Control-Allow-Headers parameter for allowed request headers
     * this parameter is usually filled with request headers
     */
    'headers' => env('CORS_ALLOWED_HEADERS'),
    
    /**
     * Access-Control-Expose-Headers parameter for custom headers that needs to be 
     * present on responses to client
     * such as X-AUTHENTICATION, X-TOKEN and e.q.
     */
    'expose' => env('CORS_ALLOWED_EXPOSE_HEADERS'),
];