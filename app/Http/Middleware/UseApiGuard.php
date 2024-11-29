<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

/**
 * Optional Auth Guard for API
 * #CREDITS - https://laracasts.com/discuss/channels/general-discussion/optional-authentication-for-api?reply=374989
 */
class UseApiGuard
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Auth::shouldUse('api');

        return $next($request);
    }
}
