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

use App\UseCases\UseCase;

class ParseDkLineupsTest extends TestCase {

    use DatabaseTransactions;

    private function setUpDatabase() {

        factory(PlayerPool::class)->create([
        
            'id' => 1,
            'date' => '2016-01-01',
            'time_period' => 'All Day',
            'site' => 'DK'
        ]);

        factory(PlayerPool::class)->create([
        
            'id' => 2,
            'date' => '2016-01-02',
            'time_period' => 'All Day',
            'site' => 'DK'
        ]);

        factory(PlayerPool::class)->create([
        
            'id' => 3,
            'date' => '2016-01-03',
            'time_period' => 'Late',
            'site' => 'DK'
        ]);

        factory(PlayerPool::class)->create([
        
            'id' => 4,
            'date' => '2016-01-03',
            'time_period' => 'Early',
            'site' => 'DK'
        ]);

        factory(PlayerPool::class)->create([
        
            'id' => 5,
            'date' => '2016-01-04',
            'time_period' => 'All Day',
            'site' => 'DK'
        ]);

        factory(ActualLineup::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'rank' => 1,
            'user' => 'capri',
            'fpts' => 248.80,
            'raw_text_players' => 'P James Shields P Clayton Kershaw C Yasmani Grandal 1B Matt Adams 2B Starlin Castro 3B Matt Carpenter SS Corey Seager OF Matt Holliday OF Stephen Piscotty OF Odubel Herrera',
            'raw_text_players_parsed' => 1
        ]);
    }

    /** @test */
    public function fetches_valid_player_pools() {

        $this->setUpDatabase();

        $useCase = new UseCase;

        $playerPools = $useCase->fetchValidPlayerPools();

        $this->assertContains((string)$playerPools[0]->id, '2');
        $this->assertContains((string)$playerPools[1]->id, '4');
        $this->assertContains((string)$playerPools[2]->id, '3');
        $this->assertContains((string)$playerPools[3]->id, '5');
    }

	/** @test */
    public function submits_dk_lineups_csv() {

        $this->setUpDatabase();

       	$this->visit('/admin/parsers/dk_lineups');
        $this->dontSee('<option value="1">DK, All Day, 2016-01-01</option>');
        $this->see('<option value="2">DK, All Day, 2016-01-02</option>');
        $this->select('3', 'player-pool');
        $this->type('DKLineups.csv', 'csv')
             ->attach('/files/dk_lineups/', 'csv');
        $this->press('Submit');
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/admin/parsers/dk_lineups', [

            'csv' => ''
        ]);

        $this->assertSessionHasErrors(['csv']);

        // I don't need to test the redirect because Taylor Otwell has already tested the form request class. I'm using that class for validation and the class automatically redirects back to the page with an $errors object. Plus, when I try to test the redirect, it doesn't work.
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/admin/parsers/dk_lineups', [

            'csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/admin/parsers/dk_lineups');

        $this->followRedirects();

        $this->see('Success!');
    }  

}