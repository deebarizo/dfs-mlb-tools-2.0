<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRazzballColumnsToDkPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dk_players', function ($table) {
            $table->string('lineup_razzball')->after('ownerships_parsed');
            $table->integer('percent_start_razzball')->after('lineup_razzball');
            $table->decimal('fpts_razzball', 5, 2)->after('percent_start_razzball');
            $table->decimal('upside_fpts_razzball', 5, 2)->after('fpts_razzball');
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
            $table->dropColumn('lineup_razzball');
            $table->dropColumn('percent_start_razzball');
            $table->dropColumn('fpts_razzball');
            $table->dropColumn('upside_fpts_razzball');
        });
    }
}
