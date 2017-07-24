<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOzLottosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oz_lottos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('supp')->nullable();
            $table->string('supp2')->nullable();
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
        Schema::dropIfExists('oz_lottos');
    }
}
