<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameActualLineupPlayersTableToDkActualLineupPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('actual_lineup_players', 'dk_actual_lineup_players');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('dk_actual_lineup_players', 'actual_lineup_players');
    }
}
