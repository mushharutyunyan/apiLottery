<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
class PlansController extends Controller
{
    public function index(){
        $plans = Plan::all();
        return view('plan.index',['plans' => $plans]);
    }

    public function create(){
        return view('plan.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'amount' => 'required|numeric',
            'calls' => 'required|numeric',
        ]);
        $data = $request->all();
        if(isset($data['main'])){
            Plan::where('id','>',0)->update(array('main' => 0));
            $data['main'] = 1;
        }else{
            $data['main'] = 0;
        }
        Plan::create($data);
        return redirect('/plan')->with(['status' => 'Plan has been created successfully']);
    }

    public function edit($id){
        $plan = Plan::where('id',$id)->first();
        return view('plan.edit',['plan' => $plan]);
    }

    public function update(Request $request,$id){
        $this->validate($request, [
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'amount' => 'required|numeric',
            'calls' => 'required|numeric',
        ]);
        $data = $request->all();
        unset($data['_token']);
        unset($data['_method']);
        $data['main'] = 0;
        if(isset($data['main'])){
            Plan::where('id','>',0)->update(array('main' => 0));
            $data['main'] = 1;
        }
        Plan::where('id',$id)->update($data);
        return redirect('/plan')->with(['status' => 'Plan has been updated successfully']);
    }

    public function makeMain($id){
        Plan::where('id','>',0)->update(array('main' => 0));
        Plan::where('id',$id)->update(array('main' => 1));
        return redirect('/plan')->with(['status' => 'Plan has been updated successfully']);
    }

    public function destroy($id)
    {
        Plan::where('id',$id)->delete();
        return redirect('/plan')->with(['status' => 'Plan deleted successfully']);
    }
}
