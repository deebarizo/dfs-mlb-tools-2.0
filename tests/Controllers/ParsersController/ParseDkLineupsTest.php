<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

class ParseDkLineupsTest extends TestCase {

    use DatabaseTransactions;

	/** @test */
    public function submits_dk_lineups_csv() {

       	$this->visit('/admin/parsers/dk_lineups');
        $this->select('All Day', 'time-period');
       	$this->type('2016-01-01', 'date');
        $this->type('DKLineups.csv', 'csv')
             ->attach('/files/dk_lineups/', 'csv');
        $this->press('Submit');
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/admin/parsers/dk_lineups', [

            'date' => '',
            'csv' => ''
        ]);

        $this->assertSessionHasErrors(['date', 'csv']);

        // I don't need to test the redirect because Taylor Otwell has already tested the form request class. I'm using that class for validation and the class automatically redirects back to the page with an $errors object. Plus, when I try to test the redirect, it doesn't work.
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/admin/parsers/dk_lineups', [

            'date' => '2016-01-01',
            'csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/admin/parsers/dk_lineups');

        $this->followRedirects();

        $this->see('Success!');
    }  

}