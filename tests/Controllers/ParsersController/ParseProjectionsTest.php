<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\UseCases\UseCase;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class ParseProjectionsTest extends TestCase {

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
    public function submits_three_projections_csv_files() {

    	$this->setUpPlayerPool();

       	$this->visit('/admin/parsers/projections');
        $this->type('razzball-pitchers.csv', 'razzball-pitchers-csv')
             ->attach('/files/projections/', 'razzball-pitchers-csv');
        $this->type('razzball-hitters.csv', 'razzball-hitters-csv')
             ->attach('/files/projections/', 'razzball-hitters-csv');
        $this->type('bat.csv', 'bat-csv')
             ->attach('/files/projections/', 'bat-csv');
        $this->press('Submit');
    }

    /** @test */
    public function uploads_csv_files() {

        $fileDirectory = 'test_folder/';

        $useCase = new UseCase;

        $csvFile = $useCase->uploadCsvFileForProjectionsParser($playerPoolId = 1, 'Razzball', 'pitchers', $fileDirectory, $request = '');

        $this->assertContains($csvFile, 'test_folder/player-pool-id-1-razzball-pitchers.csv');

        $csvFile = $useCase->uploadCsvFileForProjectionsParser($playerPoolId = 1, 'Razzball', 'hitters', $fileDirectory, $request = '');

        $this->assertContains($csvFile, 'test_folder/player-pool-id-1-razzball-hitters.csv');

        $csvFile = $useCase->uploadCsvFileForProjectionsParser($playerPoolId = 1, 'BAT', 'N/A', $fileDirectory, $request = '');

        $this->assertContains($csvFile, 'test_folder/player-pool-id-1-bat.csv');
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/admin/parsers/projections', [

            'player-pool-id' => '',
            'razzball-pitchers-csv' => '',
            'razzball-hitters-csv' => '',
            'bat-csv' => ''
        ]);

        $this->assertSessionHasErrors(['player-pool-id', 'razzball-pitchers-csv', 'razzball-hitters-csv', 'bat-csv']);
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/admin/parsers/projections', [

            'player-pool-id' => 1,
            'razzball-pitchers-csv' => 'Test.csv',
            'razzball-hitters-csv' => '',
            'bat-csv' => ''
        ]);

        $this->assertRedirectedTo('/admin/parsers/projections');

        $this->followRedirects();

        $this->see('Success!');

        $this->call('POST', '/admin/parsers/projections', [

            'player-pool-id' => 1,
            'razzball-pitchers-csv' => '',
            'razzball-hitters-csv' => 'Test.csv',
            'bat-csv' => ''
        ]);

        $this->assertRedirectedTo('/admin/parsers/projections');

        $this->followRedirects();

        $this->see('Success!');

        $this->call('POST', '/admin/parsers/projections', [

            'player-pool-id' => 1,
            'razzball-pitchers-csv' => '',
            'razzball-hitters-csv' => '',
            'bat-csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/admin/parsers/projections');

        $this->followRedirects();

        $this->see('Success!');
    }  

}