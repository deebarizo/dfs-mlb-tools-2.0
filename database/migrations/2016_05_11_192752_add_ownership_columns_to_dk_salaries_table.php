<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnershipColumnsToDkSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dk_salaries', function ($table) {
            $table->decimal('ownership', 4, 1)->after('salary');
            $table->decimal('ownership_of_first_position', 4, 1)->after('ownership');
            $table->decimal('ownership_of_second_position', 4, 1)->after('ownership_of_first_position');
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
            $table->dropColumn('ownership');
            $table->dropColumn('ownership_of_first_position');
            $table->dropColumn('ownership_of_second_position');
        });
    }
}
