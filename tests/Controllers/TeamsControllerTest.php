<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Team;

class TeamsControllerTest extends TestCase {

    use DatabaseTransactions;

    private function dbSetUp() {

        factory(Team::class)->create([
        
            'name_dk' => 'NYM'
        ]);

        factory(Team::class)->create([
        
            'name_dk' => 'Tex'
        ]);

        factory(Team::class)->create([
        
            'id' => 7,
            'name_dk' => 'Oak'
        ]);
    }

	/** @test */
    public function sees_team_names() {

        $this->dbSetUp();

        $this->visit('/teams')
             ->see('NYM')
             ->see('Tex');
    }

   	/** @test */
    public function sees_team_name() {

        $this->dbSetUp();

        $this->visit('/teams/7')
             ->see('Oak');
    }

}
