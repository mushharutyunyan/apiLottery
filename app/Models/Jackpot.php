<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jackpot extends Model
{
    protected $fillable = ['provider','prize','date'];

    public static $providers = array(
        'PowerBall' => 'https://www.thelotter.com/lottery-tickets/usa-powerball/',
        'MegaMillions' => 'https://www.thelotter.com/lottery-tickets/usa-megamillions/',
        'EuroMillions' => 'https://www.thelotter.com/lottery-tickets/euromillions',
        'SuperEnaLotto' => 'https://www.thelotter.com/lottery-tickets/italy-superenalotto',
        'EuroJackpot' => 'https://www.thelotter.com/lottery-tickets/eurojackpot',
        'FloridaLotto' => 'https://www.thelotter.com/lottery-tickets/florida-lotto',
        'CaliforniaSuperLotto' => 'https://www.thelotter.com/lottery-tickets/california-superlotto-plus',
        'OzLotto' => 'https://www.thelotter.com/lottery-tickets/australia-oz-lotto',
        'U.K.Lotto' => 'https://www.thelotter.com/lottery-tickets/uk-lotto',
        'Lotto649' => 'https://www.thelotter.com/lottery-tickets/canada-lotto-649',
        'AustraliaPowerBall' => 'https://www.thelotter.com/lottery-tickets/australia-powerball-lotto',
        'LaPrimitiva' => 'https://www.thelotter.com/lottery-tickets/spain-la-primitiva/?player=0',
        'ElGordo' => 'https://www.thelotter.com/lottery-tickets/spain-el-gordo/?player=0',
        'BonoLoto' => 'https://www.thelotter.com/lottery-tickets/spain-bonoloto/?player=0'
    );
}
