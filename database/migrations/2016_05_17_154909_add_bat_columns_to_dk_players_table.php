<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatColumnsToDkPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dk_players', function ($table) {
            $table->string('lineup_bat')->after('upside_fpts_razzball');
            $table->decimal('fpts_bat', 5, 2)->after('lineup_bat');
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
            $table->dropColumn('lineup_bat');
            $table->dropColumn('fpts_bat');
        });
    }
}

