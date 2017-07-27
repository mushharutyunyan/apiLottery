<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Log;
class Cors
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
        Log::info('Request Ip: '.Request::ip());
        if(Request::ip() != '46.166.180.153' && Request::ip != '79.179.56.196'){
            return response()->json(false);
        }
        Log::info('Success Ip: '.Request::ip());
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
