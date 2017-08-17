<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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

    public function history(Request $request){
        $data = $request->all();
        $user = User::where('id',$data['id'])->first();
        $user->history;
        $payments = array();
        if($user->payment->count()){
            foreach ($user->payment as $payment){
                $calls = $payment->plan->calls;
                if($payment->calls){
                    $calls = $payment->calls;
                }
                $payments[] = array(
                    'plan' => $payment->plan->name,
                    'calls' => $calls,
                    'paymentId' => $payment->paymentId,
                    'cart' => $payment->cart,
                    'status' => Payment::$status[$payment->status]
                );
            }
        }
        $user->payments = $payments;
        return response()->json($user);
    }
}
