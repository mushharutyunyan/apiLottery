<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class GetUserFromToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $data = $request->all();
        if (! User::where('api_token',$data['token'])->count()) {
            return response()->json(['error' => 'token_not_provided'],400);
        }
        $user = User::where('api_token',$data['token'])->first();
        print_r($user);
        dd("asdasd");
        if (! $user) {
            return response()->json(['error' => 'user_not_found'], 404);
        }

        if($user->id != 2){// for Maor
            if(!$user->count_requests){
                return response()->json(['error' => 'Requests count is over']);
            }
            $user->count_requests = $user->count_requests - 1;
            $user->save();
        }

        return $next($request);
    }
}
