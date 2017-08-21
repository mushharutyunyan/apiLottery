<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSouthAfricaPowerballsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('south_africa_powerballs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('powerball')->nullable();
            $table->string('numbers');
            $table->string('prize')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('south_africa_powerballs');
    }
}
