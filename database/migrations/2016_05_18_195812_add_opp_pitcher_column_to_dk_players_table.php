<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOppPitcherColumnToDkPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dk_players', function ($table) {
            $table->string('opp_pitcher')->after('opp_team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dk_players', function ($table) {
            $table->dropColumn('opp_pitcher');
        });
    }
}
