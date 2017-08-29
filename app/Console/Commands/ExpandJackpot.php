<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Jackpot;
use Log;
class ExpandJackpot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expand-jackpot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get expand jackpots info';

    protected $currencies = array(
        'CAD' => 'CA$',
        'AUD' => 'AU$',
        'COP' => 'COP',
        'NZD' => 'NZD',
        'R' => 'R',
        '£' => '£',
        '$' => '$',
        '€' => '€',
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
        Log::info('Expand jackpot command start '.date("Y-m-d H:i:s"));
        $client = new Client();
        $data = [];
        $now = date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i').":00"));
        $providers = Jackpot::$providers;
        foreach($providers as $provider => $link){


            $crawler = $client->request('GET', $link);
            if($crawler->filter('.sidebar-right')->count()){
                $current_jackpot = $crawler->filter('.sidebar-right')->children('.current');
                $current_link = 'https://www.lotto.net'.$current_jackpot->filter('a')->attr('href');
            }else{
                $current_link = $link;
            }
            $crawler = $client->request('GET', $current_link);
            if($crawler->filter('#dLottoSingleLineContainer')->count()){
                $date = $crawler->filter('#dLottoSingleLineContainer')->attr('data-brand-draw-date');
                $date = date('Y-m-d H:i:s', strtotime($date));
                $prize = $crawler->filter('.lotto-prize')->text();
            }else{
                $canonical_source_content = $crawler->filter('meta[name="canonical_source"]')->attr('content');
                if(!isset(explode('?lotteryid=',$canonical_source_content)[1])){
                    continue;
                }
                $lotteryId = explode('?lotteryid=',$canonical_source_content)[1];
                $data_string = json_encode(array(
                    'formType' => 0,
                    'lotteryId' => $lotteryId,
                    'lotteryType' => 0,
                    'numberOfLines' => 0
                ));
                $ch = curl_init('https://www.thelotter.com/__ajax/__play.asmx/getplaymodel');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, false); // --data-binary
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json;charset=UTF-8',
                        'accept-language: en-US,en;q=0.8')
                );
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0

                $result = curl_exec($ch);
                $result_data = json_decode($result);
                $drawData = $result_data->d->State->DrawData;
                $date = date('Y-m-d H:i:s',strtotime('+'.$drawData->secondsBeforeClose." seconds"));
                if($drawData->jackpotText == 'Not Published'){
                    $prize = $drawData->jackpotText;
                }else{
                    $prize_data = explode(" ",$drawData->jackpotText);
                    $prize = $this->currencies[$prize_data[0]].$this->convertPrize(str_replace(",","",$prize_data[1]));
                }
            }
            if(Jackpot::where('provider',$provider)->where('date','>=',$now)->where('prize',$prize)->where('prize','!=','Not Published')->count()){
                if(isset(Jackpot::$updated_providers[$provider])){
                    $old_jack = Jackpot::where('provider',$provider)->where('date','=',$now)->first();
                    if($old_jack){
                        $updated_provider_class = Jackpot::$updated_providers[$provider];
                        $date_ended_jackpot = date('Y-m-d',strtotime($now));
                        if(!$updated_provider_class::where('date',$date_ended_jackpot)->count()){
                            $updated_provider_class::create(array(
                                'date' => $date_ended_jackpot,
                                'prize' => $old_jack->prize
                            ));
                        }
                    }
                }

                continue;
            }

            $rounded = date('H:i:s', round(strtotime(date('H:i:s',strtotime($date)))/60)*60);
            if($rounded == '00:00:00'){
                $date = date("Y-m-d",strtotime($date . "+ 1 day"))." ".$rounded;
            }else{
                $date = date("Y-m-d",strtotime($date))." ".$rounded;
            }
            if($date == $now){
                continue;
            }
            if(Jackpot::where('provider',$provider)->where('date',$date)->where('prize','=','Not Published')->count()){
                $old_jackpot = Jackpot::where('provider',$provider)->where('date',$date)->where('prize','=','Not Published')->first();
                Log::info('Expand jackpot update row (provider - '.$provider.' , prize - '.$prize.') date - '.date('Y-m-d H:i:s'));
                Jackpot::where('id',$old_jackpot->id)->update(array(
                    'prize' => $prize,
                    'date' => $date
                    )
                );
            }else{
                Log::info('Expand jackpot insert row (provider - '.$provider.' , prize - '.$prize.') date - '.date('Y-m-d H:i:s'));
                Jackpot::create(array(
                    'provider' => $provider,
                    'prize' => $prize,
                    'date' => $date
                    )
                );
            }
        }
        Log::info('Expand jackpot command end '.date("Y-m-d H:i:s"));
    }

    private function convertPrize($n){
        if($n>1000000000000) return round(($n/1000000000000),1).'T';
        else if($n>1000000000) return round(($n/1000000000),1).'B';
        else if($n>=1000000) return round(($n/1000000),1).'M';
        else if($n>=1000) return round(($n/1000),1)."K";

        return number_format($n);
    }


}
