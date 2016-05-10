<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDkSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dk_salaries', function($table)
        {
            $table->increments('id');
            $table->integer('player_pool_id')->unsigned();
            $table->foreign('player_pool_id')->references('id')->on('player_pools');
            $table->integer('player_id')->unsigned();
            $table->foreign('player_id')->references('id')->on('players');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->integer('opp_team_id')->unsigned();
            $table->foreign('opp_team_id')->references('id')->on('teams');
            $table->string('position');
            $table->integer('salary');
            $table->date('created_at');
            $table->date('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dk_salaries');
    }
}
