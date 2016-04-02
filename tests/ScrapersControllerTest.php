<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScrapersControllerTest extends TestCase {

	/** @test */
    public function submits_dk_salaries_csv() {

       	$this->visit('/scrapers/dk_salaries');
       	$this->type('2016-03-31', 'date');
        $this->type('DKSalaries.csv', 'csv')
             ->attach('/files/dk_salaries/', 'csv');
        $this->press('Submit');
    }

}