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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://www.lottoapi.co/api/jackpot?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIsImlzcyI6Imh0dHA6Ly93d3cubG90dG9hcGkuY28iLCJpYXQiOjE1MDE2Nzc3NTYsImV4cCI6MTUwNTI3Nzc1NiwibmJmIjoxNTAxNjc3NzU2LCJqdGkiOiI0Z0IwRExncWpOYXA2YUozIn0.JsB3hjHTGx4vdVzDZBlGB11zyHeI5ia9Cbt0oZ1GPI4");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        print_r($server_output);die;
        if(empty(Auth::user()['api_token'])){
            $token = JWTAuth::fromUser(Auth::user());
            $user = Auth::user();
            $user->api_token = $token;
            $user->save();
        }
        if(session('paymentId')){
            $payment = Payment::where('paymentId',session('paymentId'))->first();
            return view('thankyou',['payment' => $payment]);
        }else{
            return view('home');
        }
    }

    public function plans()
    {
        $plans = Plan::all();
        return view('plans',['plans' => $plans]);
    }

    public function payments(){
        $payments = Payment::where('user_id',Auth::user()['id'])->where('status',Payment::SUCCESS)->get();
        return view('payments',['payments' => $payments]);
    }
}
