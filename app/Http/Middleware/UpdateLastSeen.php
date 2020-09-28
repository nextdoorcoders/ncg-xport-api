<?php

namespace App\Http\Middleware;

use App\Models\Account\User;
use Closure;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user) {
            $user->touchLastSeen();
        }


        return $next($request);
    }
}
