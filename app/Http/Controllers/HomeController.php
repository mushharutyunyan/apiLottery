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
        $token = $user->createToken('My Token', ['place-orders'])->accessToken;

        $view = 'home';
        if(Auth::user()['role'] == User::ADMIN){
            $view = 'adminHome';
        }
        return view($view);
    }
}
