<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsRawTextPlayersParsedToActualLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actual_lineups', function ($table) {
            $table->boolean('raw_text_players_parsed')->after('raw_text_players');
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
            $table->dropColumn('raw_text_players_parsed');
        });
    }
}
