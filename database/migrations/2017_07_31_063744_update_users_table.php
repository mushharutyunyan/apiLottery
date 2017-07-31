<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role')->after('id')->default(2);
            $table->integer('count_requests')->after('password')->default(10);
            $table->tinyInteger('verified')->after('password')->default(0);
            $table->string('email_token')->after('verified')->nullable();
        });
        \App\Models\User::where('created_at','<',date("Y-m-d H:i:s"))->update(array(
            'role' => 1
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('count_requests');
            $table->dropColumn('verified');
            $table->dropColumn('email_token');
        });
    }
}
