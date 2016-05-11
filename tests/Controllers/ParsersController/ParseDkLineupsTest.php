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

}