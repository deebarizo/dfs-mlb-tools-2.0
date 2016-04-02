<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamsControllerTest extends TestCase {

	/** @test */
    public function sees_team_names() {

        $this->visit('/teams')
             ->see('NYM')
             ->see('Tex');
    }

   	/** @test */
    public function sees_team_name() {

        $this->visit('/teams/7')
             ->see('Oak');
    }

}
