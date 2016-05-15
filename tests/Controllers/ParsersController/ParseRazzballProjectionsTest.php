<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\UseCases\UseCase;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class ParseRazzballProjectionsTest extends TestCase {

    use DatabaseTransactions;

    private function setUpPlayerPool() {

        factory(PlayerPool::class)->create([
        
            'id' => 1,
            'date' => '2016-01-01',
            'time_period' => 'Early',
            'site' => 'DK'
        ]);

        factory(PlayerPool::class)->create([
        
            'id' => 2,
            'date' => '2016-01-01',
            'time_period' => 'Late',
            'site' => 'DK'
        ]);
    }

    /** @test */
    public function fetches_valid_player_pools() {

        $this->setUpPlayerPool();

        $useCase = new UseCase;

        $playerPools = $useCase->fetchPlayerPoolsForProjectionsParsers('2016-01-01');

        $this->assertContains((string)$playerPools[0]->id, '1');
        $this->assertContains((string)$playerPools[1]->id, '2');
    }

	/** @test */
    public function submits_both_razzball_projections_csv_files() {

    	$this->setUpPlayerPool();

       	$this->visit('/admin/parsers/razzball_projections');
        $this->type('razzball-pitchers.csv', 'pitchers-csv')
             ->attach('/files/razzball_projections/', 'pitchers-csv');
        $this->type('razzball-hitters.csv', 'hitters-csv')
             ->attach('/files/razzball_projections/', 'hitters-csv');
        $this->press('Submit');
    }



}