<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreDkSalariesTest extends TestCase {

	/** @test */
    public function validates_csv_with_a_team_name_not_in_the_database() { 

    	// time to create a model factory
    	// use a test csv file


    }

	/** @test */
    public function parses_second_line_of_dk_salaries_csv() { // not first line because it contains the table names

        # $this->assertContains('SP', $position);
    }

}