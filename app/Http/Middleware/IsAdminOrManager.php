<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class IsAdminOrManager
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
        $user = auth()->user();

        if (isset($user) && ( $user->isAdmin() || $user->isManager() || $user->isManager() )) {
            return $next($request);
        } else {
            return redirect('/dashboard')->with('error', 'Permission denined. You are not allowed to view this page.');
        }
    }
}
