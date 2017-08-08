<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBonolottoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bono_lottos', function (Blueprint $table) {
            $table->dropColumn('extra_number')->nullable();
            $table->integer('comp')->after('numbers')->nullable();
            $table->integer('reint')->after('comp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bono_lottos', function (Blueprint $table) {
            $table->dropColumn('extra_number')->nullable();
            $table->integer('comp')->after('numbers')->nullable();
            $table->integer('reint')->after('comp')->nullable();
        });
    }
}
