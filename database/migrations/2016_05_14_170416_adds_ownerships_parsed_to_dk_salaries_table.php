<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsOwnershipsParsedToDkSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dk_salaries', function ($table) {
            $table->boolean('ownerships_parsed')->after('ownership_of_second_position');
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
            $table->dropColumn('ownerships_parsed');
        });
    }
}
