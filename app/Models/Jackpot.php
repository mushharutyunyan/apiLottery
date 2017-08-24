<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jackpot extends Model
{
    protected $fillable = ['provider','prize','date'];

    public static $updated_providers = array(
        'FinlandLotto' => FinlandLotto::class,
        'FinlandVikingLotto' => FinlandVikingLotto::class,
        'ColumbiaBaLoto' => ColumbiaBaloto::class,
        'Cash4Life' => Cash4Life::class
    );

    public static $providers = array(
        'LaPrimitiva' => 'https://www.thelotter.com/lottery-tickets/spain-la-primitiva/?player=0',
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
        'ElGordo' => 'https://www.thelotter.com/lottery-tickets/spain-el-gordo/?player=0',
        'BonoLoto' => 'https://www.thelotter.com/lottery-tickets/spain-bonoloto/?player=0',
        'FinlandVikingLotto' => 'https://www.thelotter.com/lottery-tickets/finland-viking-lotto/?player=0',
        'ColumbiaBaLoto' => 'https://www.thelotter.com/lottery-tickets/colombia-baloto/?player=0',
        'IrelandLotto' => 'https://www.thelotter.com/lottery-tickets/ireland-lotto/?player=0',
        'NewZealandPowerball' => 'https://www.thelotter.com/lottery-tickets/new-zealand-powerball/?player=0',
        'Cash4Life' => 'https://www.thelotter.com/lottery-tickets/cash4life/?player=0',
        'AustraliaSaturdayLotto' => 'https://www.thelotter.com/lottery-tickets/australia-saturday-lotto/?player=0',
        'FinlandLotto' => 'https://www.thelotter.com/lottery-tickets/finland-lotto/?player=0',
        'SouthAfricaLotto' => 'https://www.thelotter.com/lottery-tickets/south-africa-lotto/?player=0',
        'SouthAfricaPowerball' => 'https://www.thelotter.com/lottery-tickets/south-africa-powerball/?player=0',
        'Ontario49' => 'https://www.thelotter.com/lottery-tickets/ontario-ontario-49/?player=0',
    );

    public static $providerClasses = array(
        'PowerBall' => PowerBall::class,
        'MegaMillions' => MegaMillion::class,
        'EuroMillions' => EuroMillions::class,
        'SuperEnaLotto' => SuperenaLotto::class,
        'EuroJackpot' => EuroJackpot::class,
        'FloridaLotto' => FloridaLotto::class,
        'CaliforniaSuperLotto' => CalifornaSuperLotto::class,
        'OzLotto' => OzLotto::class,
        'U.K.Lotto' => UKLotto::class,
        'Lotto649' => Lotto649::class,
        'AustraliaPowerBall' => AustraliaPowerball::class,
        'LaPrimitiva' => LaPrimitiva::class,
        'ElGordo' => ElGordo::class,
        'BonoLoto' => BonoLotto::class,
//        'FinlandVikingLotto' => ,
//        'ColumbiaBaLoto' => ,
        'IrelandLotto' => IrelandLotto::class,
        'NewZealandPowerball' => NewZealandPowerball::class,
//        'Cash4Life' => ,
        'AustraliaSaturdayLotto' => AustraliaSaturdayLotto::class,
//        'FinlandLotto' => ,
        'SouthAfricaLotto' => SouthAfricaLotto::class,
        'SouthAfricaPowerball' => SouthAfricaPowerball::class,
        'Ontario49' => Ontario49::class,
    );
}
