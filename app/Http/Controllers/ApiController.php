<?php

namespace App\Http\Controllers;

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
class ApiController extends Controller
{
    public function jackpot(){
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
        $rows = 10;
        if($last){
            $rows = 1;
        }
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
        if($last){
            return $result;
        }
        return response()->json($result);
    }
    public function lastResult($provider){
        return response()->json($this->results($provider,1));
    }
}
