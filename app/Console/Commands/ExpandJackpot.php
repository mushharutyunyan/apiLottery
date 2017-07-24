<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Jackpot;
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
        $client = new Client();
        $data = [];
        $now = date('Y-m-d H:i:s');
        $providers = Jackpot::$providers;
        foreach($providers as $provider => $link){
            if(Jackpot::where('provider',$provider)->where('date','>',$now)->count()){
                $jackpot = Jackpot::where('provider',$provider)->where('date','>',$now)->first();
                continue;
            }
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
                $prize = $crawler->filter('.lotto-prize')->text();
            }else{
                $canonical_source_content = $crawler->filter('meta[name="canonical_source"]')->attr('content');
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
                $prize_data = explode(" ",$drawData->jackpotText);
                $prize = $prize_data[0].$this->convertPrize(str_replace(",","",$prize_data[1]));
            }

            Jackpot::create(array('provider' => $provider,
                'prize' => $prize,
                'date' => date('Y-m-d H:i:s', strtotime($date))));
        }
    }

    private function convertPrize($n){
        if($n>1000000000000) return round(($n/1000000000000),1).'T';
        else if($n>1000000000) return round(($n/1000000000),1).'B';
        else if($n>1000000) return round(($n/1000000),1).'M';
        else if($n>1000) return round(($n/1000),1);

        return number_format($n);
    }
}
