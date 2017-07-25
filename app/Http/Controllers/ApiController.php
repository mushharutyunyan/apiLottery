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
use App\Models\LaPrimitiva;
use App\Models\ElGordo;
use Symfony\Component\DomCrawler\Crawler;
class ApiController extends Controller
{

    protected $alter_fields = '';
    protected $client;
    protected $provider;
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
            ),
            'laprimitiva' => array(
                'link' => 'https://www.thelotter.com/lottery-results/spain-la-primitiva/',
                'class' => LaPrimitiva::class,
                'alter_fields' => array(
                    'extra_number' => 'results-ball-bonus',
                )
            ),
            'elgordo' => array(
                'link' => 'https://www.thelotter.com/lottery-results/spain-el-gordo/',
                'class' => ElGordo::class,
                'alter_fields' => array(
                    'extra_number' => 'results-ball-additional',
                )
            ),
            'bonolotto' => array(
                'link' => 'https://www.thelotter.com/lottery-results/spain-bonoloto/',
                'class' => ElGordo::class,
                'alter_fields' => array(
                    'extra_number' => 'results-ball-bonus',
                )
            )
        );
        $j = 0;
        $info = $providers[$provider];
        $crawler = $client->request('GET', $info['link']);
        $this->alter_fields = $providers[$provider]['alter_fields'];
        $this->provider = $providers[$provider];
        $this->client = $client;
        if($crawler->filter('.results-big')->count()){
            $last_jackpot = $crawler->filter('.results-big');
            $date = $last_jackpot->filter('.date')->text();
            $date = date("Y-m-d",strtotime($date));
            if(!$info['class']::where('date',$date)->count()){
                $balls = $this->resultBalls($last_jackpot->filter('.balls')->children('.ball'));
                $balls['date'] = $date;
                $balls['prize'] = trim($last_jackpot->filter('.jackpot')->filter('span')->text());
                $data[$j] = $balls;
                ++$j;
                $jackpots = $crawler->filter('.results-med')->each(function ($node) {
                    $date = $node->filter('.date')->text();
                    $date = date("Y-m-d",strtotime($date));
                    $balls = $this->resultBalls($node->filter('.balls')->children('.ball'));
                    $balls['date'] = $date;
                    $balls['prize'] = trim($node->filter('.jackpot')->filter('span')->text());
                    return $balls;
                });
                $this->dataInsertResults($jackpots);
            }
        }else{
            $this->spanish_lotto($crawler);
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

    private function spanish_lotto($crawler){
        $jackpots = $crawler->filter('script')->each(function ($node) {
            if(!empty($node->text())){
                if(preg_match('/TL.tlGlobals/',$node->text())){
                    preg_match("/\((.*?)\)/s",$node->text(),$content);
                    if(isset($content[1])){
                        $data_script = json_decode($content[1]);
                        $lotteryRef = $data_script->Params->lotteryRef;
                        $data_string = json_encode(array(
                            'lotteryRef' => $lotteryRef
                        ));
                        $ch = curl_init('https://www.thelotter.com/__Ajax/__AsyncControls.asmx/GetDrawsValueNameList');
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                'Content-Type: application/json;charset=UTF-8',
                                'accept-language: en-US,en;q=0.8')
                        );
                        $result = curl_exec($ch);
                        $data_days = json_decode($result);
                        $count = 1;
                        $results = array();
                        foreach($data_days->d as $key => $data){
                            if($count == 11){
                                break;
                            }
                            $date = date("Y-m-d",strtotime(trim(explode("|",$data->DisplayText)[1])));
                            $crawler = $this->client->request('GET', $this->provider['link']."?DrawNumber=".$data->DrawRef);
                            $balls = $this->resultBalls($crawler->filter('.draw-result-item')->filter('.results-ball'));
                            $crawler = new Crawler($crawler->text());
                            $script_text = explode("new TheLotter\$JqGridControl",$crawler->text());
                            preg_match("/\[(.*?)\]/s",explode('data : ',$script_text[1])[1],$content);
                            $prize_json = str_replace("\r\n","",$content[0]);


                            preg_match_all("/\'LocalWinningAmount\'\:(.*?)\,/s",$prize_json,$content);
                            $prize = 0;
                            foreach($content[1] as $prizes){
                                if(is_numeric(trim($prizes)) && trim($prizes) > 0){
                                    $prize += (double)trim($prizes);
                                }
                            }
                            $prize = "&euro;".number_format($prize,2,".",",");
                            $balls['date'] = $date;
                            $balls['prize'] = $prize;
                            $results[] = $balls;
                            $count++;
                        }
                        return $results;
                    }
                }
            }
        });
        foreach($jackpots as $jackpot){
            if(!empty($jackpot)){
                foreach($jackpot as $key => $value){
                    if($this->provider['class']::where('date',$value['date'])->count()){
                        break;
                    }
                    $this->provider['class']::create($value);
                }
            }
        }
    }

    private function resultBalls($jackpot_block){
        $balls = $jackpot_block->each(function ($node) {
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



    private function dataInsertResults($jackpots){
        $j = 0;
        foreach ($jackpots as $jackpot){
            if(!empty($jackpot)){
                if($this->provider['class']::where('date',$jackpot['date'])->count()){
                    break;
                }
                $data[$j] = $jackpot;
                $j++;
            }
        }
        if(!empty($data)){
            foreach ($data as $key => $data_value){
                $this->provider['class']::create($data_value);
            }
        }
    }




}
