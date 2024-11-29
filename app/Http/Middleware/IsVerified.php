<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsVerified
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

        if (isset($user) && $user->verified != 1) {
            Auth::logout();

            return redirect('/')->with('status', 'Your account must be verified. Please verify your account with the verification code that we sent to your email or mobile phone.');
        }

        return $next($request);
    }
}
