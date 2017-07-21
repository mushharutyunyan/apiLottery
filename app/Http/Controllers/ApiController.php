<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Models\Jackpot;
use App\Models\Result;
class ApiController extends Controller
{
    protected $providers = array('PowerBall' => 'https://www.lotto.net/powerball/numbers',
                                 'MegaMillions' => 'https://www.lotto.net/mega-millions/numbers',
                                 'EuroMillions' => 'https://www.lotto.net/euromillions/results');

    public function jackpot(){
        $client = new Client();
        $data = [];
        foreach($this->providers as $provider => $link){
            $now = date('Y-m-d H:i:s');
            if(Jackpot::where('provider',$provider)->where('date','>',$now)->count()){
                $jackpot = Jackpot::where('provider',$provider)->where('date','>',$now)->first();
//                $countdown = $this->countdown($jackpot->date);
                $data[] = array('n' => $jackpot->provider,
                                'p' => $jackpot->prize,
                                'd' => date("F d, Y G:i:s",strtotime($jackpot->date . "+3 hours")));
                continue;
            }
            $crawler = $client->request('GET', $link);
            $current_jackpot = $crawler->filter('.sidebar-right')->children('.current');
            $current_link = $current_jackpot->filter('a')->attr('href');
            $crawler = $client->request('GET', 'https://www.lotto.net'.$current_link);
            $date = $crawler->filter('#dLottoSingleLineContainer')->attr('data-brand-draw-date');


            $prize = $crawler->filter('.lotto-prize')->text();
//            $countdown = $this->countdown($date);
            $data[] = array('n' => $provider,
                            'p' => $prize,
                            'd' => date("F d, Y G:i:s",strtotime($date . "+3 hours")));
            Jackpot::create(array('provider' => $provider,
                                  'prize' => $prize,
                                  'date' => date('Y-m-d H:i:s', strtotime($date))));
        }
        return response()->json($data);
    }

    public function results(){
        $client = new Client();
        $providers = array( 'superenalotto' => 'https://www.lotto.net/superenalotto/results',
                            'floridalotto' => 'https://www.lotto.net/florida-lotto/numbers');
        $other_balls = array('jolly','superstar');
        foreach($providers as $provider => $link){
            $crawler = $client->request('GET', $link);
            $last_jackpot = $crawler->filter('.results-big');
            $date = $last_jackpot->filter('.date')->text();
            $balls = $last_jackpot->filter('.balls')->children('.ball')->each(function ($node) {
                if(preg_match('/jolly/',$node->attr('class'))){
                    $balls['jolly'] = $node->filter('span')->text();
                }elseif(preg_match('/superstar/',$node->attr('class'))){
                    $balls['superstar'] = $node->filter('span')->text();
                }elseif(preg_match('/lotto-xtra/',$node->attr('class'))){
                    $balls['lotto_xtra'] = $node->filter('span')->text();
                }else{
                    $balls['ball'] = $node->filter('span')->text();
                }
                return $balls;
            });
            $date = date("Y-m-d",strtotime($date));
            $numbers = '';
            $jolly = '';
            $superstar = '';
            $lotto_xtra = '';
            $i = 0;
            foreach($balls as $key => $ball){
                foreach($ball as $keyNumber => $ballValue){
                    if($keyNumber == 'ball'){
                        if($i == 0){
                            $numbers .= $ballValue;
                        }else{
                            $numbers .= " ".$ballValue;
                        }
                    }elseif ($keyNumber == 'jolly'){
                        if(!empty($jolly)){
                            $jolly .= " ".$ballValue;
                        }else{
                            $jolly .= $ballValue;
                        }
                    }elseif ($keyNumber == 'superstar'){
                        if(!empty($superstar)){
                            $superstar .= " ".$ballValue;
                        }else{
                            $superstar .= $ballValue;
                        }
                    }elseif ($keyNumber == 'lotto_xtra'){
                        if(!empty($lotto_extra)){
                            $lotto_xtra .= " ".$ballValue;
                        }else{
                            $lotto_xtra .= $ballValue;
                        }
                    }
                    $i++;
                }
            }
            if(!Result::where('provider',$provider)->where('date',$date)->count()){
                Result::create(array(
                    'provider' => $provider,
                    'date' => $date,
                    'jolly' => $jolly,
                    'superstar' => $superstar,
                    'lotto_xtra' => $lotto_xtra,
                    'numbers' => $numbers
                ));
            }
            $data[] = array(
                'provider' => $provider,
                'date' => $date,
                'jolly' => $jolly,
                'superstar' => $superstar,
                'lotto_xtra' => $lotto_xtra,
                'numbers' => $numbers
            );
        }
        return response()->json($data);
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
