<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualLineupPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actual_lineup_players', function($table)
        {
            $table->increments('id');
            $table->integer('actual_lineup_id')->unsigned();
            $table->foreign('actual_lineup_id')->references('id')->on('actual_lineups');
            $table->string('position');
            $table->integer('dk_salary_id')->unsigned();
            $table->foreign('dk_salary_id')->references('id')->on('dk_salaries');
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
        Schema::dropIfExists('actual_lineup_players');
    }
}
