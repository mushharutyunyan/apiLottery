<?php

namespace App\Console\Commands;

use App\Models\CalifornaSuperLotto;
use App\Models\EuroMillions;
use App\Models\MegaMillion;
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
            'alter_fields' => array(
                'jolly' => 'jolly',
                'superstar' => 'superstar'
            )
        ),
        'megamillions' => array(
            'link' => 'https://www.lotto.net/mega-millions/numbers',
            'class' => MegaMillion::class,
            'alter_fields' => array(
                'mega' => 'mega-ball',
                'megaplier' => 'megaplier',
            )
        ),
        'euromillions' => array(
            'link' => 'https://www.lotto.net/euromillions/results',
            'class' => EuroMillions::class,
            'alter_fields' => array(
                'luckystar' => 'lucky-star',
                'luckystar2' => 'lucky-star',
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
        'californiasuperlotto' => array(
            'link' => 'https://www.lotto.net/california-super-lotto-plus/numbers',
            'class' => CalifornaSuperLotto::class,
            'alter_fields' => array(
                'mega' => 'mega-ball',
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
            'link' => 'https://www.onelotto.com/lottery-results/spanish-la-primitiva/draw-history?last_days=30&page=1&per_page=10',
            'class' => LaPrimitiva::class,
            'alter_fields' => array(
                'comp' => 'comp',
                'reimb' => 'reimb',
            )
        ),
        'elgordo' => array(
            'link' => 'https://www.onelotto.com/lottery-results/spanish-el-gordo/draw-history?last_days=30&page=1&per_page=10',
            'class' => ElGordo::class,
            'alter_fields' => array(
                'extra_number' => 'extra_number',
            )
        ),
        'bonolotto' => array(
            'link' => 'https://www.onelotto.com/lottery-results/spanish-bonoloto/draw-history?last_days=30&page=1&per_page=10',
            'class' => BonoLotto::class,
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
        foreach($providers as $provider){
            $j = 0;
            $this->provider = $provider;
            $crawler = $this->client->request('GET', $this->provider['link']);
            $this->alter_fields = $this->provider['alter_fields'];
            if($crawler->filter('.results-big')->count()){
                $last_jackpot = $crawler->filter('.results-big');
                $date = $last_jackpot->filter('.date')->text();
                $date = date("Y-m-d",strtotime($date));
                $update = false;
                if($this->provider['class']::where('date',$date)->count()){
                   if(!$this->provider['class']::where('prize','TBC')->count()){
                        continue;
                   }else{
                       $update = true;
                   }
                }

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
                $this->dataInsertResults(array($balls),$update);
                $this->dataInsertResults($jackpots,$update);
            }else{
                $this->spanish_lotto();
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
                if($this->provider['class']::where('date',$jackpot['date'])->count()){
                    if(!$this->provider['class']::where('prize','TBC')->count()){
                        break;
                    }
                }
                $data[$j] = $jackpot;
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
