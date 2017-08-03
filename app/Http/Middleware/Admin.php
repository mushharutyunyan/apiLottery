<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Auth;
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->user()['role'] != User::ADMIN) {
            return redirect('/');
        }
        return $next($request);
    }
}
