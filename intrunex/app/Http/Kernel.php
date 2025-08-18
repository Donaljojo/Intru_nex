<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global middleware stack.
     */
    protected $middleware = [
        //\App\Http\Middleware\EnforceProductionDomain::class,
        'enforce.domain' => \App\Http\Middleware\EnforceProductionDomain::class,
    ];

    /**
     * Route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // Add session, CSRF, etc. if needed later
        ],

        'api' => [
            // Add API-specific middleware if needed
        ],
    ];

    /**
     * Route middleware.
     */
    protected $routeMiddleware = [
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
