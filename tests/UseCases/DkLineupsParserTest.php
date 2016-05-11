<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\UseCase;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

class DkLineupsParserTest extends TestCase {

    use DatabaseTransactions;

    private function setUpPlayerPool() {

        factory(PlayerPool::class)->create([
        
            'id' => 1,
            'date' => '2016-01-01',
            'time_period' => 'All Day',
            'site' => 'DK'
        ]);
    }

    private $csvFiles = [

        'valid' => [

            'test.csv' => "Rank,EntryId,EntryName,TimeRemaining,Points,Lineup\n1,402195599,chrishrabe (1/2),0,201.75,P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes"
        ],

        'invalid' => [

            'nonNumericRankField' => [

                'test.csv' => "Rank,EntryId,EntryName,TimeRemaining,Points,Lineup\nbob,402195599,chrishrabe (1/2),0,201.75,P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes"
            ],

            'nonNumericFptsField' => [

                'test.csv' => "Rank,EntryId,EntryName,TimeRemaining,Points,Lineup\n1,402195599,chrishrabe (1/2),0,bob,P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes"
            ],

            'lineupNotTenPlayers' => [

 	           'test.csv' => "Rank,EntryId,EntryName,TimeRemaining,Points,Lineup\n1,402195599,chrishrabe (1/2),0,201.75,P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Yoenis Céspedes"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

    /** @test */
    public function validates_player_pool_that_does_not_exist() {

    	$this->setUpPlayerPool();

        $root = $root = $this->setUpCsvFile($this->csvFiles['valid']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-02', 'DK', 'All Day');

        $this->assertContains($results->message, 'This player pool does not exist.');
    }

    /** @test */
    public function validates_player_pool_that_has_already_been_parsed() {

        $this->setUpPlayerPool();

        factory(ActualLineup::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'user' => 'Bob', 
            'fpts' => '200.00'
        ]); 

        $root = $root = $this->setUpCsvFile($this->csvFiles['valid']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'This player pool has already been parsed.');
    }

    /** @test */
    public function validates_csv_with_non_number_in_rank_field() { 

    	$this->setUpPlayerPool();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericRankField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The rank field in the csv has a non-number.');
    }

    /** @test */
    public function validates_csv_with_non_number_in_fpts_field() { 

    	$this->setUpPlayerPool();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericFptsField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The fpts field in the csv has a non-number.');
    }

    /** @test */
    public function validates_csv_with_lineup_that_does_not_match_ten_players() { 

    	$this->setUpPlayerPool();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['lineupNotTenPlayers']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The lineup with entry ID of 402195599 does not have 10 players');
    }

}