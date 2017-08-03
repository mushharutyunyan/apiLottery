<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use JWTAuth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(empty(Auth::user()['api_token'])){
            $token = JWTAuth::fromUser(Auth::user());
            $user = Auth::user();
            $user->api_token = $token;
            $user->save();
        }

        return view('home');
    }

    public function plans()
    {
        $plans = Plan::all();
        return view('plans',['plans' => $plans]);
    }

    public function payments(){
        $payments = Payment::where('user_id',Auth::user()['id'])->get();
        return view('payments',['payments' => $payments]);
    }
}
