<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameRazzballAndNameBatToPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function ($table) {
            $table->string('name_razzball')->after('name_dk');
            $table->string('name_bat')->after('name_razzball');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function ($table) {
            $table->dropColumn('name_razzball');
            $table->dropColumn('name_bat');
        });
    }
}
