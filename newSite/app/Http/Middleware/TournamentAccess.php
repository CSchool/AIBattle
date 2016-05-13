<?php

namespace AIBattle\Http\Middleware;

use AIBattle\Tournament;
use Closure;

class TournamentAccess
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
        $tournament = Tournament::findOrFail($request->segment(2)); // get tournament id

        // TODO: added group visibility (group 'b2' see only 1,4,6 tournaments)
        if ($tournament->state == 'running')
            return $next($request);
        else
            abort(403);
    }
}
