<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $infos = '[
                {
                "name" : "Viking Lotto",
                "country" : "Europe",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 48,
                "main_drawn" : 6,
                "bonus_min" : 1,
                "bonus_max" : 8,
                "bonus_drawn" : 1,
                "same_balls" : "N",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Baloto",
                "country" : "Colombia",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 43,
                "main_drawn" : 5,
                "bonus_min" : 1,
                "bonus_max" : 16,
                "bonus_drawn" : 1,
                "same_balls" : "N",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Lotto",
                "country" : "Ireland",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 47,
                "main_drawn" : 6,
                "bonus_min" : 1,
                "bonus_max" : 47,
                "bonus_drawn" : 1,
                "same_balls" : "Y",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Powerball",
                "country" : "New Zealand",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 40,
                "main_drawn" : 6,
                "bonus_min" : 1,
                "bonus_max" : 10,
                "bonus_drawn" : 1,
                "same_balls" : "N",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Cash4Life",
                "country" : "United States",
                "state" : "Multi-state",
                "main_min" : 1,
                "main_max" : 60,
                "main_drawn" : 5,
                "bonus_min" : 1,
                "bonus_max" : 4,
                "bonus_drawn" : 1,
                "same_balls" : "N",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Lotto",
                "country" : "Australia",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 45,
                "main_drawn" : 6,
                "bonus_min" : 1,
                "bonus_max" : 45,
                "bonus_drawn" : 2,
                "same_balls" : "Y",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Lotto",
                "country" : "Finland",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 40,
                "main_drawn" : 7,
                "bonus_min" : 1,
                "bonus_max" : 40,
                "bonus_drawn" : 1,
                "same_balls" : "Y",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Lotto",
                "country" : "South Africa",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 52,
                "main_drawn" : 6,
                "bonus_min" : 1,
                "bonus_max" : 52,
                "bonus_drawn" : 1,
                "same_balls" : "Y",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Powerball",
                "country" : "South Africa",
                "state" : "Nationwide",
                "main_min" : 1,
                "main_max" : 45,
                "main_drawn" : 5,
                "bonus_min" : 1,
                "bonus_max" : 20,
                "bonus_drawn" : 1,
                "same_balls" : "N",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                },
                {
                "name" : "Ontario 49",
                "country" : "Canada",
                "state" : "Ontario",
                "main_min" : 1,
                "main_max" : 49,
                "main_drawn" : 6,
                "bonus_min" : 1,
                "bonus_max" : 49,
                "bonus_drawn" : 1,
                "same_balls" : "Y",
                "digits" : 0,
                "drawn" : 0,
                "is_option" : "N",
                "option_desc" : "-"
                }
        ]';
        $data_info = json_decode($infos);
        foreach($data_info as $info){
            \App\Models\Info::create(array(
                'name' => $info->name,
                'state' => $info->state,
                'country' => $info->country,
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
        //
    }
}
