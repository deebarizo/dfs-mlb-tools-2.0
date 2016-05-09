<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDkIdColumnToDkSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dk_salaries', function ($table) {
            $table->integer('dk_id')->after('player_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dk_salaries', function ($table) {
            $table->dropColumn('dk_id');
        });
    }
}
