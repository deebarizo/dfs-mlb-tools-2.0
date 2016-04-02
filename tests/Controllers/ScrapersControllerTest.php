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

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/scrapers/dk_salaries', [

            'date' => '',
            'csv' => ''
        ]);

        $this->assertSessionHasErrors(['date', 'csv']);

        // I don't need to test the redirect because Taylor Otwell has already tested the form request class. I'm using that class for validation and the class automatically redirects back to the page with an $errors object. Plus, when I try to test the redirect, it doesn't work.
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/scrapers/dk_salaries', [

            'date' => '2016-04-02',
            'csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/scrapers/dk_salaries');

        $this->followRedirects();

        $this->see('Success!');
    }  

}