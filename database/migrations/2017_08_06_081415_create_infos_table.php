<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $infos = '[{
                      "name": "Mega Millions",
                      "country": "United States",
                      "state": "Multi-state",
                      "main_min": 1,
                      "main_max": 75,
                      "main_drawn": 5,
                      "bonus_min": 1,
                      "bonus_max": 15,
                      "bonus_drawn": 1,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "Y",
                      "option_desc": "Megaplier"
                    },
                    {
                      "name": "Powerball",
                      "country": "United States",
                      "state": "Multi-state",
                      "main_min": 1,
                      "main_max": 69,
                      "main_drawn": 5,
                      "bonus_min": 1,
                      "bonus_max": 26,
                      "bonus_drawn": 1,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "Y",
                      "option_desc": "Power Play"
                    },
                    {
                      "name": "SuperEnalotto",
                      "country": "Italy",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 90,
                      "main_drawn": 6,
                      "bonus_min": 1,
                      "bonus_max": 90,
                      "bonus_drawn": 1,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "Y",
                      "option_desc": "Superstar"
                    },
                    {
                      "name": "EuroMillions",
                      "country": "United Kingdom",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 50,
                      "main_drawn": 5,
                      "bonus_min": 1,
                      "bonus_max": 12,
                      "bonus_drawn": 2,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "EuroJackpot",
                      "country": "Europe",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 50,
                      "main_drawn": 5,
                      "bonus_min": 1,
                      "bonus_max": 10,
                      "bonus_drawn": 2,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "Oz Lotto",
                      "country": "Australia",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 45,
                      "main_drawn": 7,
                      "bonus_min": 1,
                      "bonus_max": 45,
                      "bonus_drawn": 2,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                    
                      "name": "UK Lotto",
                      "country": "United Kingdom",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 59,
                      "main_drawn": 6,
                      "bonus_min": 1,
                      "bonus_max": 59,
                      "bonus_drawn": 1,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "Powerball",
                      "country": "Australia",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 40,
                      "main_drawn": 6,
                      "bonus_min": 1,
                      "bonus_max": 20,
                      "bonus_drawn": 1,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "Lotto 6/49",
                      "country": "Canada",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 49,
                      "main_drawn": 6,
                      "bonus_min": 1,
                      "bonus_max": 49,
                      "bonus_drawn": 1,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "La Primitiva",
                      "country": "Spain",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 49,
                      "main_drawn": 6,
                      "bonus_min": 1,
                      "bonus_max": 49,
                      "bonus_drawn": 1,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "El Gordo",
                      "country": "Spain",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 54,
                      "main_drawn": 5,
                      "bonus_min": 0,
                      "bonus_max": 9,
                      "bonus_drawn": 1,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "BonoLoto",
                      "country": "Spain",
                      "state": "Nationwide",
                      "main_min": 1,
                      "main_max": 49,
                      "main_drawn": 6,
                      "bonus_min": 1,
                      "bonus_max": 49,
                      "bonus_drawn": 1,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "Y",
                      "option_desc": "Reintegro"
                    },
                    {
                      "name": "Florida Lotto",
                      "country": "United States",
                      "state": "Florida",
                      "main_min": 1,
                      "main_max": 53,
                      "main_drawn": 6,
                      "bonus_min": 0,
                      "bonus_max": 0,
                      "bonus_drawn": 0,
                      "same_balls": "Y",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    },
                    {
                      "name": "SuperLotto Plus",
                      "country": "United States",
                      "state": "California",
                      "main_min": 1,
                      "main_max": 47,
                      "main_drawn": 5,
                      "bonus_min": 1,
                      "bonus_max": 27,
                      "bonus_drawn": 1,
                      "same_balls": "N",
                      "digits": 0,
                      "drawn": 0,
                      "is_option": "N",
                      "option_desc": "-"
                    }]';
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('state')->nullable();
            $table->integer('main_min')->nullable();
            $table->integer('main_max')->nullable();
            $table->integer('main_drawn')->nullable();
            $table->integer('bonus_min')->nullable();
            $table->integer('bonus_max')->nullable();
            $table->integer('bonus_drawn')->nullable();
            $table->string('same_balls')->nullable();
            $table->integer('digits')->nullable();
            $table->integer('drawn')->nullable();
            $table->string('is_option')->nullable();
            $table->string('option_desc')->nullable();
            $table->timestamps();
        });

        $data_info = json_decode($infos);
        foreach($data_info as $info){
            \App\Models\Info::create(array(
               'name' => $info->name,
               'state' => $info->state,
               'main_min' => $info->main_min,
               'main_max' => $info->main_max,
               'main_drawn' => $info->main_drawn,
               'bonus_min' => $info->bonus_min,
               'bonus_max' => $info->bonus_max,
               'bonus_drawn' => $info->bonus_drawn,
               'same_balls' => $info->same_balls,
               'digits' => $info->digits,
               'drawn' => $info->drawn,
               'is_option' => $info->is_option,
               'option_desc' => $info->option_desc,
            ));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infos');
    }
}
