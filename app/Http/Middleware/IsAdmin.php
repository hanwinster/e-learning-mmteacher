<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class IsAdmin
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

        if (isset($user) && !$user->isAdmin()) {
            return redirect('/')->withAlert('Permission denined');
        }

        return $next($request);
    }
}
