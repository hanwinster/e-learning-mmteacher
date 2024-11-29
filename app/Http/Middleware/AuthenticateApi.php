<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateApi 
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
        if ( ! auth()->check()) {
            return response()->json(['message' => 'Bearer Token was expired. Please login again!'], 403);
        }
        return $next($request);
    }
}
