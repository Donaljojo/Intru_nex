<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnforceProductionDomain
{
    public function handle(Request $request, Closure $next)
    {
        $expectedHost = 'bug-free-space-invention-q7g47gp74xp9294r5-8000.app.github.dev';

        if ($request->getHost() !== $expectedHost || !$request->isSecure()) {
            return redirect()->secure($request->getRequestUri())->setHost($expectedHost);
        }

        return $next($request);
    }
}
