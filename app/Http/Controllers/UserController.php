<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class UserController extends Controller
{
    public function index(){
        $users = User::where('id','!=',Auth::user()['id'])->paginate(10);
        return view('user.index',['users' => $users]);
    }

    public function create(){
        return view('user.create');
    }
}
