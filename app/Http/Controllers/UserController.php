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

    public function edit($id){
        $user = User::where('id',$id)->first();
        return view('user.edit',['user' => $user]);
    }

    public function update(Request $request,$id){
        $this->validate($request, [
            'count_requests' => 'required|numeric',
        ]);
        $data = $request->all();
        User::where('id',$id)->update(array(
            'count_requests' => $data['count_requests']
        ));
        return redirect('/user')->with(['status' => 'User has been updated successfully']);
    }
}
