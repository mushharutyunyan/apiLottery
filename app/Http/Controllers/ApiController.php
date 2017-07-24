<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Models\Jackpot;
use App\Models\SuperenaLotto;
use App\Models\FloridaLotto;
use App\Models\PowerBall;
use App\Models\OzLotto;
use App\Models\EuroJackpot;
use App\Models\UKLotto;
use App\Models\Lotto649;
use App\Models\AustraliaPowerball;
class ApiController extends Controller
{

    protected $alter_fields = '';
    public function jackpot(){
        $now = date('Y-m-d H:i:s');
        $providers = Jackpot::$providers;
        foreach($providers as $provider => $link) {
            $jackpot = Jackpot::where('provider', $provider)->where('date', '>', $now)->first();
//                $countdown = $this->countdown($jackpot->date);
            $data[] = array('n' => $jackpot->provider,
                'p' => $jackpot->prize,
                'd' => date("F d, Y G:i:s", strtotime($jackpot->date . "+3 hours")));
        }
        return response()->json($data);
    }

    public function results($provider){
        $client = new Client();
        $response_fields = array(
            'date' => 'draw_date',
            'prize' => 'prize',
            'numbers' => 'winning_numbers'
        );
        $providers = array(
            'superenalotto' => array(
                'link' => 'https://www.lotto.net/superenalotto/results',
                'class' => SuperenaLotto::class,
                'alter_fields' => array(
                    'jolly' => 'jolly',
                    'superstar' => 'superstar'
                )
            ),
            'floridalotto' => array(
                'link' => 'https://www.lotto.net/florida-lotto/numbers',
                'class' => FloridaLotto::class,
                'alter_fields' => array(
                    'lotto_xtra' => 'lotto-xtra'
                )
            ),
            'powerball' => array(
                'link' => 'https://www.lotto.net/powerball/numbers',
                'class' => PowerBall::class,
                'alter_fields' => array(
                    'powerball' => 'powerball',
                    'powerplay' => 'power-play'
                )
            ),
            'ozlotto' => array(
                'link' => 'https://www.lotto.net/oz-lotto/results',
                'class' => OzLotto::class,
                'alter_fields' => array(
                    'supp' => 'supplementary',
                    'supp2' => 'supplementary'
                )
            ),
            'eurojackpot' => array(
                'link' => 'https://www.lotto.net/eurojackpot/results',
                'class' => EuroJackpot::class,
                'alter_fields' => array(
                    'euro' => 'euro',
                    'euro2' => 'euro'
                )
            ),
            'uklotto' => array(
                'link' => 'https://www.lotto.net/uk-lotto/results',
                'class' => UKLotto::class,
                'alter_fields' => array(
                    'bonus' => 'bonus-ball',
                )
            ),
            'lotto649' => array(
                'link' => 'https://www.lotto.net/canada-lotto-6-49/numbers',
                'class' => Lotto649::class,
                'alter_fields' => array(
                    'bonus' => 'bonus-ball',
                )
            ),
            'australiapowerball' => array(
                'link' => 'https://www.lotto.net/australia-powerball/results',
                'class' => AustraliaPowerball::class,
                'alter_fields' => array(
                    'powerball' => 'powerball',
                )
            )
        );
        $j = 0;
        $info = $providers[$provider];
        $crawler = $client->request('GET', $info['link']);
        $last_jackpot = $crawler->filter('.results-big');
        $date = $last_jackpot->filter('.date')->text();
        $date = date("Y-m-d",strtotime($date));
        $this->alter_fields = $providers[$provider]['alter_fields'];
        if(!$info['class']::where('date',$date)->count()){
            $balls = $this->resultBalls($last_jackpot);
            $balls['date'] = $date;
            $balls['prize'] = trim($last_jackpot->filter('.jackpot')->filter('span')->text());
            $data[$j] = $balls;
            ++$j;
            $jackpots = $crawler->filter('.results-med')->each(function ($node) {
                $date = $node->filter('.date')->text();
                $date = date("Y-m-d",strtotime($date));
                $balls = $this->resultBalls($node);
                $balls['date'] = $date;
                $balls['prize'] = trim($node->filter('.jackpot')->filter('span')->text());
                return $balls;
            });
            foreach ($jackpots as $jackpot){
                if($info['class']::where('date',$date)->count()){
                    break;
                }
                $data[$j] = $jackpot;
                $j++;
            }
            foreach ($data as $key => $data_value){
                $info['class']::create($data_value);
            }
        }
        $data = $info['class']::orderBy('date','DESC')->take(10)->get();
        $result = array();
        $i = 0;
        foreach($data as $key => $value){
            foreach($response_fields as $db_field => $response_field){
                $result[$i][$response_field] = $value->$db_field;
            }
            foreach ($this->alter_fields as $field => $class){
                $result[$i][$field] = $value->$field;
            }
            $i++;
        }
        return response()->json($result);
    }
    
    private function resultBalls($jackpot_block){
        $balls = $jackpot_block->filter('.balls')->children('.ball')->each(function ($node) {
            $balls = array();
            $same_classes = array();
            foreach($this->alter_fields as $field => $class){
                if(!in_array($class,$same_classes)){
                    if(preg_match('/'.$class.'/',$node->attr('class'))){
                        $balls[$field] = $node->filter('span')->text();
                        $same_classes[] = $class;
                    }
                }
            }
            if(empty($balls)){
                $balls['ball'] = $node->filter('span')->text();
            }
            return $balls;
        });
        $i = 0;
        $numbers = array('numbers' => '');
        foreach($balls as $key => $ball){
            foreach($ball as $keyNumber => $ballValue){
                if($keyNumber == 'ball'){
                    if($i == 0){
                        $numbers['numbers'] .= $ballValue;
                    }else{
                        $numbers['numbers'] .= " ".$ballValue;
                    }
                }else{
                    foreach($this->alter_fields as $field => $class){
                        if($keyNumber == $field){
                            if(!empty($numbers[$keyNumber])){
                                $numbers[$keyNumber."2"] = $ballValue;
                                continue;
                            }else{
                                $numbers[$keyNumber] = $ballValue;
                                break;
                            }
                        }
                    }
                }
                $i++;
            }
        }
        return $numbers;
    }
    
    private function countdown($date){
        // Create two new DateTime-objects...
        $date1 = new \DateTime(date("c",strtotime($date)));
        $date2 = new \DateTime(date("c",time()));

        $diff = $date2->diff($date1);
        $hour = $diff->format('%a')*24 + $diff->format('%h');
        $minutes = $diff->format('%i');
        $seconds = $diff->format('%s');
        $countdown = $hour .":".$minutes. ":". $seconds;
        return $countdown;
    }


}
