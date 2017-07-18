<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Models\Jackpot;
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
                            'd' => date("F d, Y G:i:s",strtotime($date)));
            Jackpot::create(array('provider' => $provider,
                                  'prize' => $prize,
                                  'date' => date('Y-m-d H:i:s', strtotime($date . "+3 hours"))));
        }
        return response()->json($data);
    }

    public function datetime(){
        echo date("Y-m-d H:i:s");
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
