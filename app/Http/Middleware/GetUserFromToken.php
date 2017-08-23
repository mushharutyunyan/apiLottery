<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CallHistory;
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
        if (! $user) {
            return response()->json(['error' => 'user_not_found'], 404);
        }

        if($user->id != 7){// for Maor
            if(!$user->count_requests){
                return response()->json(['error' => 'Requests count is over']);
            }

            CallHistory::create(array(
                'user_id' => $user->id,
                'calls' => 1
            ));
            $user->count_requests = $user->count_requests - 1;
            $user->save();
        }

        return $next($request);
    }
}
