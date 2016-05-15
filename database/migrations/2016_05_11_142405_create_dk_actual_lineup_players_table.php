<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDkActualLineupPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dk_actual_lineup_players', function($table)
        {
            $table->increments('id');
            $table->integer('dk_actual_lineup_id')->unsigned();
            $table->foreign('dk_actual_lineup_id')->references('id')->on('dk_actual_lineups');
            $table->string('position');
            $table->integer('dk_player_id')->unsigned();
            $table->foreign('dk_player_id')->references('id')->on('dk_players');
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
        Schema::dropIfExists('dk_actual_lineup_players');
    }
}
