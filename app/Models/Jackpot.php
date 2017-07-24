<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jackpot extends Model
{
    protected $fillable = ['provider','prize','date'];

    public static $providers = array(
        'PowerBall' => 'https://www.lotto.net/powerball/numbers',
        'MegaMillions' => 'https://www.lotto.net/mega-millions/numbers',
        'EuroMillions' => 'https://www.lotto.net/euromillions/results',
        'SuperEnaLotto' => 'https://www.lotto.net/superenalotto/results',
        'EuroJackpot' => 'https://www.lotto.net/eurojackpot/results',
        'FloridaLotto' => 'https://www.lotto.net/florida-lotto/numbers',
        'CaliforniaSuperLotto' => 'https://www.lotto.net/california-super-lotto-plus/numbers',
        'OzLotto' => 'https://www.lotto.net/oz-lotto/results',
        'U.K.Lotto' => 'https://www.lotto.net/uk-lotto/results',
        'Lotto649' => 'https://www.lotto.net/canada-lotto-6-49/numbers',
        'AustraliaPowerBall' => 'https://www.lotto.net/australia-powerball/results',
        'LaPrimitiva' => 'https://www.thelotter.com/lottery-tickets/spain-la-primitiva/?player=0',
        'ElGordo' => 'https://www.thelotter.com/lottery-tickets/spain-el-gordo/?player=0',
        'BonoLoto' => 'https://www.thelotter.com/lottery-tickets/spain-bonoloto/?player=0'
    );
}
