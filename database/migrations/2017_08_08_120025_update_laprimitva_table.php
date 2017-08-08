<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLaprimitvaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('la_primitivas', function (Blueprint $table) {
            $table->dropColumn('extra_number')->nullable();
            $table->integer('comp')->after('numbers')->nullable();
            $table->integer('reimb')->after('comp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('la_primitivas', function (Blueprint $table) {
            $table->integer('extra_number')->after('numbers')->nullable();
            $table->dropColumn('comp');
            $table->dropColumn('reimb');
        });
    }
}
