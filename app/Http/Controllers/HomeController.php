<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
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
        $user = User::find(1);

// Creating a token without scopes...
        $token = $user->createToken('Token Name')->accessToken;
        print_R($token);die;
// Creating a token with scopes...
        $token = $user->createToken('My Token', ['place-orders'])->accessToken;

        $view = 'home';
        if(Auth::user()['role'] == User::ADMIN){
            $view = 'adminHome';
        }
        return view($view);
    }
}
