<?php

namespace App\Console\Commands;

use App\Models\Cash4Life;
use App\Models\ColumbiaBaloto;
use App\Models\FinlandLotto;
use App\Models\FinlandVikingLotto;
use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Jackpot;
use Log;
class TheLotterXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:thelotter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TheLotter.com Xml';

    /**
     * The console scraping url.
     *
     * @var string
     */
    protected $url = 'https://www.thelotter.com/rss.xml';


    protected $jackpots = array(
        'PowerBall' => 'Australia - Powerball Lotto',
        'MegaMillions' => 'U.S. - Mega Millions',
        'EuroMillions' => 'Spain - EuroMillions',
        'SuperEnaLotto' => 'Italy - SuperEnalotto',
        'EuroJackpot' => 'Europe - EuroJackpot',
        'FloridaLotto' => 'https://www.thelotter.com/lottery-tickets/florida-lotto',
        'CaliforniaSuperLotto' => 'California - SuperLotto Plus',
        'OzLotto' => 'Australia - Oz Lotto',
        'U.K.Lotto' => 'U.K. - Lotto',
        'Lotto649' => 'Canada - Lotto 649',
        'AustraliaPowerBall' => 'Australia - Powerball Lotto',
        'LaPrimitiva' => 'Spain - La Primitiva',
        'ElGordo' => 'Spain - El Gordo',
        'BonoLoto' => 'Spain - BonoLoto',
        'FinlandVikingLotto' => 'Finland - Viking Lotto',
        'ColumbiaBaLoto' => 'Colombia - Baloto',
        'IrelandLotto' => 'Ireland - Lotto',
        'NewZealandPowerball' => 'New Zealand - Powerball',
        'Cash4Life' => 'New York - Cash4Life',
        'AustraliaSaturdayLotto' => 'Australia - Saturday Lotto',
        'FinlandLotto' => 'Finland - Lotto',
        'SouthAfricaLotto' => 'South Africa - Lotto',
        'SouthAfricaPowerball' => 'South Africa - Powerball',
        'Ontario49' => 'Ontario - Ontario 49',
    );

    private $updated_providers = array(
        'FinlandLotto' => FinlandLotto::class,
        'FinlandVikingLotto' => FinlandVikingLotto::class,
        'ColumbiaBaLoto' => ColumbiaBaloto::class,
        'Cash4Life' => Cash4Life::class
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
        Log::info('The Lotter command start '.date("Y-m-d H:i:s"));
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        $jackpots = array_flip($this->jackpots);
        $data = $crawler->filter('entry')->each(function ($node) use($jackpots) {
            $title = $node->filter('title')->text();
            if(isset($jackpots[$title])){
                $jackpot_name = $jackpots[$title];
                if(isset($this->updated_providers[$jackpot_name])){
                    $updated_class = $this->updated_providers[$jackpot_name];
                    $last_draw_date = explode(' GMT',$node->filter('last_draw_date')->text());
                    $draw_date = date("Y-m-d", strtotime(str_replace('/', '-', $last_draw_date[0])));
                    $numbers = explode(' + ',$node->filter('last_draw_results')->text());
                    $extra_number = $numbers[1];
                    $balls = str_replace(';',' ',$numbers[0]);
                    $prize_exp = explode(" ",$node->filter('next_draw_jackpot')->text());
                    $converUpdatedPrize = $this->convertUpdatedPrize($prize_exp[2]);
                    $prize = $prize_exp[0].$prize_exp[1].$converUpdatedPrize;
                    if($updated_class::where('date',$draw_date)->count()){
                        $updated_class::where('date',$draw_date)->update(array(
                            'prize' => $prize,
                            'numbers' => $balls,
                            'extra_number' => $extra_number
                        ));
                    }else{
                        $updated_class::create(array(
                            'prize' => $prize,
                            'date' => $draw_date,
                            'numbers' => $balls,
                            'extra_number' => $extra_number
                        ));
                    }
                }
                $prize_exp = explode(' ',$node->filter('next_draw_jackpot')->text());
                if(isset($prize_exp[2])){
                    $convertPrize = $this->convertPrize($prize_exp[2]);
                    $prize = $prize_exp[0].$prize_exp[1].$convertPrize;
                }else{
                    $prize = $node->filter('next_draw_jackpot')->text();
                }
                $next_draw_date = explode(' GMT',$node->filter('next_draw_close_date')->text());
                $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $next_draw_date[0])));
                return array(
                    'provider' => $jackpots[$title],
                    'prize' => $prize,
                    'date' => $date
                );
            }
        });
        foreach($data as $key => $value){
            if(!empty($value)){
                if(!Jackpot::where('provider',$value['provider'])->where('prize',$value['prize'])->where('date',$value['date'])->count()){
                    Jackpot::create($value);
                    Log::info('The Lotter create row provider - '.$value['provider'].', prize - '.$value['prize'].', date - '.$value['date'].' ('.date("Y-m-d H:i:s").')');
                }
            }
        }
        Log::info('The Lotter command end '.date("Y-m-d H:i:s"));
    }
    private function convertPrize($n){
        if($n == 'Million'){
            return 'M';
        }elseif($n == 'Billion'){
            return 'B';
        }else{
            return 'K';
        }
    }
    private function convertUpdatedPrize($n){
        if($n == 'Million'){
            return '.000.000';
        }elseif($n == 'Billion'){
            return '.000.000.000';
        }else{
            return '000';
        }
    }
}
