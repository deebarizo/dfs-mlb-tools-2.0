<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actual_lineups', function($table)
        {
            $table->increments('id');
            $table->integer('player_pool_id')->unsigned();
            $table->foreign('player_pool_id')->references('id')->on('player_pools');
            $table->string('user');
            $table->decimal('fpts', 6, 2);
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
        Schema::dropIfExists('actual_lineups');
    }
}
