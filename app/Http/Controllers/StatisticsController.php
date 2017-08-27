<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;
use Prophecy\Call\Call;

class StatisticsController extends Controller
{
    public function index(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            if(!empty($data['start']) && !empty($data['end'])){
                if(strtotime($data['start']) > strtotime($data['end'])){
                    return redirect()->back()->withErrors(['error' => 'Start date cannot be greater than End date']);
                }

                $history = CallHistory::where('created_at','>=',$data['start'])->where('created_at','<=',$data['end'])->get();
            }else{
                if(!empty($data['start'])){
                    $history = CallHistory::where('created_at','>=',$data['start'])->get();
                }elseif(!empty($data['end'])){
                    $history = CallHistory::where('created_at','<=',$data['start'])->get();
                }
            }

            $start = $data['start'];
            $end = $data['end'];
        }else{
            $history = CallHistory::All();
            $start = '';
            $end = '';
        }
        dd("asdasd");
        $all = 0;
        $user_calls = array();
        $user_exist = array();
        foreach($history as $calls){
            $all += $calls->calls;
            if(in_array($calls->user->id,$user_exist)){
                $old_calls = $user_calls[$calls->user->id]['calls'];
                $user_calls[$calls->user->id]['calls'] = $calls->calls + $old_calls;
            }else{
                $user_calls[$calls->user->id] = array(
                    'user' => $calls->user->name,
                    'calls' => $calls->calls
                );
                $user_exist[] = $calls->user->id;
            }
        }
        return view('statistic.index',['user_calls' => $user_calls,'all' => $all,'start' => $start,'end' => $end]);
    }
}
