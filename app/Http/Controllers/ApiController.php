<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;

use App\Models\Jackpot;
use App\Models\SuperenaLotto;
use App\Models\FloridaLotto;
use App\Models\PowerBall;
use App\Models\OzLotto;
use App\Models\EuroJackpot;
use App\Models\UKLotto;
use App\Models\Lotto649;
use App\Models\AustraliaPowerball;
use App\Models\LaPrimitiva;
use App\Models\ElGordo;
use App\Models\BonoLotto;
use App\Console\Commands\ResultJackpot;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function jackpot(){
        $user = Auth::user();
        if($user->id != 2){// for Maor
            if(!$user->count_requests){
                return response()->json(['error' => 'Requests count is over']);
            }
            $user->count_requests = $user->count_requests - 1;
            $user->save();
        }
        $now = date('Y-m-d H:i:s');
        $providers = Jackpot::$providers;
        foreach($providers as $provider => $link) {
            $jackpot = Jackpot::where('provider', $provider)->orderBy('date', 'DESC')->first();
//                $countdown = $this->countdown($jackpot->date);
            $data[] = array('n' => $jackpot->provider,
                'p' => $jackpot->prize,
                'd' => date("F d, Y G:i:s", strtotime($jackpot->date . "+3 hours")));
        }
        return response()->json($data);
    }

    public function results($provider,$last = null){
        $user = Auth::user();
        if($user->id != 2){// for Maor
            if(!$user->count_requests){
                return response()->json(['error' => 'Requests count is over']);
            }
            $user->count_requests = $user->count_requests - 1;
            $user->save();
        }
        $rows = 10;
        $response_fields = array(
            'date' => 'draw_date',
            'prize' => 'prize',
            'numbers' => 'winning_numbers'
        );
        $providers = ResultJackpot::$providers;
        $data = $providers[$provider]['class']::orderBy('date','DESC')->take($rows)->get();
        $result = array();
        $i = 0;
        foreach($data as $key => $value){
            foreach($response_fields as $db_field => $response_field){
                $result[$i][$response_field] = $value->$db_field;
            }
            foreach ($providers[$provider]['alter_fields'] as $field => $class){
                $result[$i][$field] = $value->$field;
            }
            $i++;
        }
        return response()->json($result);
    }
    public function lastResult(){
        $user = Auth::user();
        if($user->id != 2){// for Maor
            if(!$user->count_requests){
                return response()->json(['error' => 'Requests count is over']);
            }
            $user->count_requests = $user->count_requests - 1;
            $user->save();
        }
        $rows = 1;
        $providers = ResultJackpot::$providers;
        $response_fields = array(
            'date' => 'draw_date',
            'prize' => 'prize',
            'numbers' => 'winning_numbers'
        );
        $result = array();
        $j = 0;
        foreach($providers as $key => $provider){
            $data = $provider['class']::orderBy('date','DESC')->take($rows)->get();
            $i = 0;
            $result[$j][$i]['provider'] = $key;
            foreach($data as $key => $value){
                foreach($response_fields as $db_field => $response_field){
                    $result[$j][$i][$response_field] = $value->$db_field;
                }
                foreach ($provider['alter_fields'] as $field => $class){
                    $result[$j][$i]['winning_numbers'] .= " " . $value->$field;
                }
                $i++;
            }
            $j++;
        }
        return response()->json($result);
    }

    public function info(){
        $infos = Info::all();
        return response()->json($infos);
    }
}
