<?php

namespace AIBattle\Http\Middleware;

use AIBattle\User;
use Closure;

class AdminMiddleware
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
        if (!User::isAdmin())
            abort(403);

        return $next($request);
    }
}
