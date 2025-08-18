<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnforceProductionDomain
{
     public function handle(Request $request, Closure $next)
    {
        if (app()->environment('production') &&
            (!$request->isSecure() || $request->getHost() !== 'bug-free-space-invention-q7g47gp74xp9294r5-8000.app.github.dev')) {
            
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
   
}
