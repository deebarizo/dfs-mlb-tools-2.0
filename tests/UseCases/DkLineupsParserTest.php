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

    private function setUpPlayers() {

        factory(Player::class)->create([
        
        	'id' => 1, 
            'team_id' => 1,
            'name_dk' => 'Mike Leake'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 1, 
        	'player_pool_id' => 1,
            'player_id' => 1,
            'position' => 'SP'
        ]);  

        factory(Player::class)->create([
        
        	'id' => 2, 
            'team_id' => 2,
            'name_dk' => 'Jacob deGrom'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 2, 
        	'player_pool_id' => 1,
            'player_id' => 2,
            'position' => 'SP'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 3, 
            'team_id' => 3,
            'name_dk' => 'Brian McCann'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 3, 
        	'player_pool_id' => 1,
            'player_id' => 3,
            'position' => 'C'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 4, 
            'team_id' => 4,
            'name_dk' => 'Hanley Ramirez'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 4, 
        	'player_pool_id' => 1,
            'player_id' => 4,
            'position' => '1B'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 5, 
            'team_id' => 5,
            'name_dk' => 'Robinson Cano'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 5, 
        	'player_pool_id' => 1,
            'player_id' => 5,
            'position' => '2B'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 6, 
            'team_id' => 6,
            'name_dk' => 'Matt Carpenter'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 6, 
        	'player_pool_id' => 1,
            'player_id' => 6,
            'position' => '3B'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 7, 
            'team_id' => 7,
            'name_dk' => 'Manny Machado'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 7, 
        	'player_pool_id' => 1,
            'player_id' => 7,
            'position' => '3B/SS'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 8, 
            'team_id' => 8,
            'name_dk' => 'Matt Holliday'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 8, 
        	'player_pool_id' => 1,
            'player_id' => 8,
            'position' => 'OF'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 9, 
            'team_id' => 9,
            'name_dk' => 'Mookie Betts'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 9, 
        	'player_pool_id' => 1,
            'player_id' => 9,
            'position' => 'OF'
        ]); 

        factory(Player::class)->create([
        
        	'id' => 10, 
            'team_id' => 10,
            'name_dk' => 'Yoenis Cespedes'
        ]);  

        factory(DkSalary::class)->create([
        
        	'id' => 10, 
        	'player_pool_id' => 1,
            'player_id' => 10,
            'position' => 'OF'
        ]); 

        factory(Player::class)->create([
        
            'id' => 11, 
            'team_id' => 11,
            'name_dk' => 'Jon Lester'
        ]);  

        factory(DkSalary::class)->create([
        
            'id' => 11, 
            'player_pool_id' => 1,
            'player_id' => 11,
            'position' => 'SP'
        ]); 

        factory(Player::class)->create([
        
            'id' => 12, 
            'team_id' => 12,
            'name_dk' => 'Jonathan Villar'
        ]);  

        factory(DkSalary::class)->create([
        
            'id' => 12, 
            'player_pool_id' => 1,
            'player_id' => 12,
            'position' => 'SS'
        ]); 

        factory(Player::class)->create([
        
            'id' => 13, 
            'team_id' => 13,
            'name_dk' => 'Bryce Harper'
        ]);  

        factory(DkSalary::class)->create([
        
            'id' => 13, 
            'player_pool_id' => 1,
            'player_id' => 13,
            'position' => 'OF'
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
            ],

            'missingPlayerInDatabase' => [

 	           'test.csv' => "Rank,EntryId,EntryName,TimeRemaining,Points,Lineup\n1,402195599,chrishrabe (1/2),0,201.75,P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Dee Barizo OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes"
            ]
        ],

        'multipleLineups' => [

            'test.csv' => "Rank,EntryId,EntryName,TimeRemaining,Points,Lineup\n1,402195599,chrishrabe (1/2),0,201.75,P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes\n2,402244112,fu69,0,196,P Jon Lester P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Manny Machado SS Jonathan Villar OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes\n25,402235946,jammer1333,18,179.4,P Jon Lester P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Manny Machado SS Jonathan Villar OF Matt Holliday OF Bryce Harper OF Yoenis Céspedes"
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

    /** @test */
    public function validates_csv_with_lineup_with_missing_player_in_database() { 

    	$this->setUpPlayerPool();

    	$this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['missingPlayerInDatabase']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The lineup with entry ID of 402195599 has a missing player in database: SS Dee Barizo');
    } 

    /** @test */
    public function saves_lineup() { 

    	$this->setUpPlayerPool();

    	$this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'Success!');

        $actualLineups = ActualLineup::where('player_pool_id', 1)->where('rank', 1)->where('user', 'chrishrabe')->where('fpts', 201.75)->get();

        $this->assertCount(1, $actualLineups);

        $actualLineupPlayers = ActualLineupPlayer::all();

        $this->assertCount(10, $actualLineupPlayers);
    }

    /** @test */
    public function saves_ownership() { 

        $this->setUpPlayerPool();

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['multipleLineups']);        

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkLineups($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'Success!');

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 1)
                            ->where('ownership', 33.3)
                            ->where('ownership_of_first_position', 33.3)
                            ->where('ownership_of_second_position', 0.0)
                            ->get();

        $this->assertCount(1, $dkSalaries);

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 3)
                            ->where('ownership', 100.0)
                            ->where('ownership_of_first_position', 100.0)
                            ->where('ownership_of_second_position', 0.0)
                            ->get();

        $this->assertCount(1, $dkSalaries);

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 9)
                            ->where('ownership', 66.7)
                            ->where('ownership_of_first_position', 66.7)
                            ->where('ownership_of_second_position', 0.0)
                            ->get();

        $this->assertCount(1, $dkSalaries);

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 7)
                            ->where('ownership', 100.0)
                            ->where('ownership_of_first_position', 66.7)
                            ->where('ownership_of_second_position', 33.3)
                            ->get();

        $this->assertCount(1, $dkSalaries);
    }

}