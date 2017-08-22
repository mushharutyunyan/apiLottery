<?php

namespace App\Console\Commands;

use App\Models\AustraliaSaturdayLotto;
use App\Models\CalifornaSuperLotto;
use App\Models\Cash4Life;
use App\Models\ColumbiaBaloto;
use App\Models\EuroMillions;
use App\Models\FinlandLotto;
use App\Models\FinlandVikingLotto;
use App\Models\IrelandLotto;
use App\Models\MegaMillion;
use App\Models\NewZealandPowerball;
use App\Models\Ontario49;
use App\Models\SouthAfricaLotto;
use App\Models\SouthAfricaPowerball;
use Illuminate\Console\Command;
use Goutte\Client;
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
use Symfony\Component\DomCrawler\Crawler;
use Log;
class ResultJackpot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:result-jackpot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get results jackpots';

    protected $alter_fields = '';
    protected $client;
    protected $provider;
    public static $providers = array(
        'superenalotto' => array(
            'link' => 'https://www.lotto.net/superenalotto/results',
            'class' => SuperenaLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'jolly' => 'jolly',
                'superstar' => 'superstar'
            )
        ),
        'megamillions' => array(
            'link' => 'https://www.lotto.net/mega-millions/numbers',
            'class' => MegaMillion::class,
            'spanish' => false,
            'alter_fields' => array(
                'mega' => 'mega-ball',
                'megaplier' => 'megaplier',
            )
        ),
        'euromillions' => array(
            'link' => 'https://www.lotto.net/euromillions/results',
            'class' => EuroMillions::class,
            'spanish' => false,
            'alter_fields' => array(
                'luckystar' => 'lucky-star',
                'luckystar2' => 'lucky-star',
            )
        ),
        'floridalotto' => array(
            'link' => 'https://www.lotto.net/florida-lotto/numbers',
            'class' => FloridaLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'lotto_xtra' => 'lotto-xtra'
            )
        ),
        'powerball' => array(
            'link' => 'https://www.lotto.net/powerball/numbers',
            'class' => PowerBall::class,
            'spanish' => false,
            'alter_fields' => array(
                'powerball' => 'powerball',
                'powerplay' => 'power-play'
            )
        ),
        'californiasuperlotto' => array(
            'link' => 'https://www.lotto.net/california-super-lotto-plus/numbers',
            'class' => CalifornaSuperLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'mega' => 'mega-ball',
            )
        ),
        'ozlotto' => array(
            'link' => 'https://www.lotto.net/oz-lotto/results',
            'class' => OzLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'supp' => 'supplementary',
                'supp2' => 'supplementary'
            )
        ),
        'eurojackpot' => array(
            'link' => 'https://www.lotto.net/eurojackpot/results',
            'class' => EuroJackpot::class,
            'spanish' => false,
            'alter_fields' => array(
                'euro' => 'euro',
                'euro2' => 'euro'
            )
        ),
        'uklotto' => array(
            'link' => 'https://www.lotto.net/uk-lotto/results',
            'class' => UKLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'bonus' => 'bonus-ball',
            )
        ),
        'lotto649' => array(
            'link' => 'https://www.lotto.net/canada-lotto-6-49/numbers',
            'class' => Lotto649::class,
            'spanish' => false,
            'alter_fields' => array(
                'bonus' => 'bonus-ball',
            )
        ),
        'australiapowerball' => array(
            'link' => 'https://www.lotto.net/australia-powerball/results',
            'class' => AustraliaPowerball::class,
            'spanish' => false,
            'alter_fields' => array(
                'powerball' => 'powerball',
            )
        ),
        'australiasaturdaylotto' => array(
            'link' => 'https://www.lotto.net/australia-saturday-lotto/results',
            'class' => AustraliaSaturdayLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'supp' => 'supplementary',
                'supp2' => 'supplementary'
            )
        ),
        'newzealandpowerball' => array(
            'link' => 'https://www.lotto.net/new-zealand-powerball/results',
            'class' => NewZealandPowerball::class,
            'spanish' => false,
            'alter_fields' => array(
                'bonus' => 'bonus-ball',
                'supp2' => 'powerball'
            )
        ),
        'southafricalotto' => array(
            'link' => 'https://www.lotto.net/south-africa-lotto/results',
            'class' => SouthAfricaLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'bonus' => 'bonus-ball'
            )
        ),
        'irelandlotto' => array(
            'link' => 'https://www.lottery.ie/dbg/results/view?game=lotto&draws=0',
            'class' => IrelandLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'bonus' => 'bonus'
            )
        ),
        'ontario49' => array(
            'link' => 'https://www.lottery.com/jackpots/can/ontario49',
            'class' => Ontario49::class,
            'spanish' => false,
            'alter_fields' => array(
                'bonus' => 'bonus'
            )
        ),
        'finlandlotto' => array(
            'link' => 'https://www.thelotter.com/lottery-results/finland-lotto/',
            'class' => FinlandLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'extra_number' => 'results-ball-bonus',
            )
        ),
        'finlandvikinglotto' => array(
            'link' => 'https://www.thelotter.com/lottery-results/finland-viking-lotto/',
            'class' => FinlandVikingLotto::class,
            'spanish' => false,
            'alter_fields' => array(
                'extra_number' => 'results-ball-additional',
            )
        ),
        'columbiabaloto' => array(
            'link' => 'https://www.thelotter.com/lottery-results/colombia-baloto/',
            'class' => ColumbiaBaloto::class,
            'spanish' => false,
            'alter_fields' => array(
                'extra_number' => 'results-ball-additional',
            )
        ),
        'cash4life' => array(
            'link' => 'https://www.thelotter.com/lottery-results/cash4life/',
            'class' => Cash4Life::class,
            'spanish' => false,
            'alter_fields' => array(
                'extra_number' => 'results-ball-additional',
            )
        ),
        'laprimitiva' => array(
            'link' => 'https://www.onelotto.com/lottery-results/spanish-la-primitiva/draw-history?last_days=30&page=1&per_page=10',
            'class' => LaPrimitiva::class,
            'spanish' => true,
            'alter_fields' => array(
                'comp' => 'comp',
                'reimb' => 'reimb',
            )
        ),
        'elgordo' => array(
            'link' => 'https://www.onelotto.com/lottery-results/spanish-el-gordo/draw-history?last_days=30&page=1&per_page=10',
            'class' => ElGordo::class,
            'spanish' => true,
            'alter_fields' => array(
                'extra_number' => 'extra_number',
            )
        ),
        'bonolotto' => array(
            'link' => 'https://www.onelotto.com/lottery-results/spanish-bonoloto/draw-history?last_days=30&page=1&per_page=10',
            'class' => BonoLotto::class,
            'spanish' => true,
            'alter_fields' => array(
                'comp' => 'comp',
                'reint' => 'reint',
            )
        )
    );
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $memory_size = memory_get_usage();
        echo $this->convert(memory_get_usage(true));
        Log::info('Result jackpot command start '.date("Y-m-d H:i:s"));
        $client = new Client();
        $providers = ResultJackpot::$providers;
        $this->client = $client;
        foreach($providers as $key => $provider){
            $j = 0;
            $this->provider = $provider;
            $this->alter_fields = $this->provider['alter_fields'];
            if($key == 'irelandlotto'){
                $this->irelandlotto();
            }elseif($key == 'ontario49'){
                $this->ontario();
            }else{
                $crawler = $this->client->request('GET', $this->provider['link']);
                if($crawler->filter('.results-big')->count()){
                    $last_jackpot = $crawler->filter('.results-big');
                    $date = $last_jackpot->filter('.date')->text();
                    $date = date("Y-m-d",strtotime($date));
                    $update = false;
                    $balls = $this->resultBalls($last_jackpot->filter('.balls')->children('.ball'));
                    $balls['prize'] = trim($last_jackpot->filter('.jackpot')->filter('span')->text());
                    if(SuperenaLotto::where('date',$date)->count()){
                       $last_row = $this->provider['class']::where('date',$date)->first();
                       if(!$this->provider['class']::where('prize','TBC')->count()){
                           if(!$this->provider['class']::where('date',$date)->count()){
                               continue;
                           }
                           if($last_row->prize != $balls['prize']){
                               $update = true;
                           }else{
                                continue;
                           }
                       }else{
                           $update = true;
                       }
                    }

                    $balls['date'] = $date;

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
                    $this->dataInsertResults(array($balls),$update);
                    $this->dataInsertResults($jackpots,$update);
                }else{
                    if($provider['spanish']){
                        $this->spanish_lotto();
                    }else{
                        $this->thelotter($crawler);
                    }
                }
            }


        }
        $memory_size = memory_get_usage();
        print_r($this->convert(memory_get_usage(true)));die;
    }
    private function spanish_lotto(){

        $ch = curl_init($this->provider['link']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'content-type:application/json',
                'server:cloudflare-nginx',
                'accept:application/json, text/plain, */*')
        );

        $result = curl_exec($ch);
        $data_days = json_decode($result);
        $results = array();
        foreach($data_days[0]->data as $key => $data){
            $balls = array();
            $date = date('Y-m-d',strtotime($data->drawDateLotteryTz));
            $all_balls = explode('|',$data->numbers);
            $count = 1;
            foreach($all_balls as $number){
                if($count > count($all_balls) - count($this->provider['alter_fields'])){// for specific balls
                    foreach($this->provider['alter_fields'] as $alter_field){
                        if(!array_key_exists($alter_field,$balls)){
                            $balls[$alter_field] = $number;
                            break;
                        }
                    }
                }else{
                    if(array_key_exists('numbers',$balls)){
                        $balls['numbers'] .= " ".$number;
                    }else{
                        $balls['numbers'] = $number;
                    }
                }
                $count++;
            }
            $balls['date'] = $date;
            $prize = "€".number_format($data->jackpot,0,".",",");
            $balls['prize'] = $prize;
            $results[] = $balls;
        }
        foreach($results as $jackpot){
            if($this->provider['class']::where('date',$jackpot['date'])->count()){
                break;
            }
            $this->provider['class']::create($jackpot);
        }
    }
    private function irelandlotto(){
        $crawler = $this->client->request('GET', $this->provider['link']);
        $balls = $crawler->filter('.matching-draw')->each(function ($node) {
            $numbers = '';
            $balls = array();
            $numbers = $node->filter('.draw-results')->first()->filter('.winning-numbers')->filter('.pick-number')->each(function ($number) {
                return $number->filter('.pick-number')->filter('input')->attr('value');
            });
            $balls['numbers'] = implode(" ",$numbers);
            foreach($this->alter_fields as $column => $field){
                $balls[$column] = $node->filter('.'.$field)->filter('input')->attr('value');
            }
            $date = $node->filter('h4')->text();
            $balls['date'] = date('Y-m-d',strtotime($date));
            $balls['prize'] = $node->filter('.jackpot')->filter('.prize')->text();
            return $balls;
        });
        $this->dataInsertResults($balls,false);
    }
    private function ontario(){
        $crawler = $this->client->request('GET', $this->provider['link']);
        $balls = $crawler->filter('.moreWinningNumbers')->each(function ($node) {
            $balls = array();
            $date = $node->filter('.dateCol')->text();
            $balls['date'] = date('Y-m-d',strtotime($date));
            $node->filter('.jackpotCol p')->each(function (Crawler $crawler) {
                foreach ($crawler as $node) {
                    $node->parentNode->removeChild($node);
                }
            });
            $prize_text = $node->filter('.jackpotCol')->text();
            $prize_exp = explode(' ',$prize_text);
            if(isset($prize_exp[1])){
                if(preg_match('/million/i',$prize_exp[1])){
                    $prize = $prize_exp[0].".000.000";
                }elseif(preg_match('/billion/i',$prize_exp[1])){
                    $prize = $prize_exp[0].".000.000.000";
                }
            }else{
                $prize = $prize_text;
            }
            $balls['prize'] = $prize;
            $winning_numbers = $node->filter('.lottery-item-winnumbers span')->each(function ($node) {
                return $node->text();
            });
            end($winning_numbers);
            $key = key($winning_numbers);
            foreach($this->alter_fields as $column => $field){
                $balls[$column] = $winning_numbers[$key];
            }
            unset($winning_numbers[$key]);
            $balls['numbers'] = implode(" ",$winning_numbers);
            return $balls;
        });
        $this->dataInsertResults($balls,false);
    }
    private function thelotter($crawler){
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
                            $prize = "€".number_format($prize,2,".",",");
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

    private function dataInsertResults($jackpots,$update){
        $j = 0;
        foreach ($jackpots as $jackpot){
            if(!empty($jackpot)){
                if($j <= 9){
                    if($this->provider['class']::where('date',$jackpot['date'])->count()){
                        $last_row = $this->provider['class']::where('date',$jackpot['date'])->first();
                        if(!$this->provider['class']::where('prize','TBC')->count()){
                            if($last_row->prize == $jackpot['prize']){
                                break;
                            }
                        }
                    }
                    $data[$j] = $jackpot;
                }
                $j++;
            }
        }
        if(!empty($data)){
            foreach ($data as $key => $data_value){
                if($update){
                    $this->provider['class']::where('date', $data_value['date'])->update($data_value);
                    Log::info('Result jackpot update row - '.json_encode($data_value)." date - ".date("Y-m-d H:i:s"));
                }else{
                    $this->provider['class']::create($data_value);
                    Log::info('Result jackpot insert row - '.json_encode($data_value)." date - ".date("Y-m-d H:i:s"));
                }
            }
        }
    }
    public function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

}
