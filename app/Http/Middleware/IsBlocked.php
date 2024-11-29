<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class IsBlocked
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

        if (isset($user) && $user->blocked) {
            Session::flush();
            return redirect('login')->withInput()->withErrors([
                'email' => 'This account is blocked.',
            ]);
        }

        return $next($request);
    }
}
