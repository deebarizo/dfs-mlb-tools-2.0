<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParseDkActualLineupPlayersTest extends TestCase {

	/** @test */
    public function submits() {

       	$this->visit('/admin/parsers/dk_actual_lineup_players');
        $this->press('Submit');
    }

}