<?php

namespace App\Http\Middleware;

use Closure;

class ForceApi
{
    /**
     * Handle an incoming request. Prepare request before processing
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
