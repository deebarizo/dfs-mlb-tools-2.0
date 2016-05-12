<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRawTextPlayersToActualLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actual_lineups', function ($table) {
            $table->text('raw_text_players')->after('fpts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actual_lineups', function ($table) {
            $table->dropColumn('raw_text_players');
        });
    }
}
