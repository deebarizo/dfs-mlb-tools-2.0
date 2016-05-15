<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDkActualLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dk_actual_lineups', function($table)
        {
            $table->increments('id');
            $table->integer('player_pool_id')->unsigned();
            $table->foreign('player_pool_id')->references('id')->on('player_pools');
            $table->integer('rank');
            $table->string('user');
            $table->decimal('fpts', 6, 2);
            $table->text('raw_text_players');
            $table->boolean('raw_text_players_parsed');
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
        Schema::dropIfExists('dk_actual_lineups');
    }
}
