<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\UseCases\UseCase;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;
use App\DkActualLineup;
use App\DkActualLineupPlayer;

class DkActualLineupPlayersParserTest extends TestCase {

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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
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

        factory(DkPlayer::class)->create([
        
            'id' => 13, 
            'player_pool_id' => 1,
            'player_id' => 13,
            'position' => 'OF'
        ]); 
    }

    private function setUpDkActualLineupWithoutTenPlayers() {

        factory(DkActualLineup::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'rank' => 1,
            'user' => 'chrishrabe', 
            'fpts' => 201.75,
            'raw_text_players' => 'P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Yoenis Céspedes'
        ]);    	
    }

    private function setUpDkActualLineupWithMissingPlayerInDatabase() {

        factory(DkActualLineup::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'rank' => 1,
            'user' => 'chrishrabe', 
            'fpts' => 201.75,
            'raw_text_players' => 'P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Dee Barizo OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes'
        ]); 
    }

    private function setUpValidDkActualLineup() {

        factory(DkActualLineup::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'rank' => 1,
            'user' => 'chrishrabe', 
            'fpts' => 201.75,
            'raw_text_players' => 'P Mike Leake P Jacob deGrom C Brian McCann 1B Hanley Ramírez 2B Robinson Canó 3B Matt Carpenter SS Manny Machado OF Matt Holliday OF Mookie Betts OF Yoenis Céspedes'
        ]); 
    }

    /** @test */
    public function validates_dk_actual_lineup_that_does_not_have_ten_players() { 

    	$this->setUpPlayerPool(); $this->setUpPlayers();

    	$this->setUpDkActualLineupWithoutTenPlayers();

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkActualLineupPlayers();

        $this->assertContains($results->message, 'The DK actual lineup with the ID of 1 does not have 10 players.');
    }

    /** @test */
    public function validates_dk_actual_lineup_with_missing_player_in_database() { 

        $this->setUpPlayerPool(); $this->setUpPlayers();

        $this->setUpDkActualLineupWithMissingPlayerInDatabase();

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkActualLineupPlayers();

        $this->assertContains($results->message, 'The DK actual lineup with the ID of 1 has a missing player in database: SS Dee Barizo.');
    } 

    /** @test */
    public function saves_dk_actual_lineup_players() { 

        $this->setUpPlayerPool(); $this->setUpPlayers();

        $this->setUpValidDkActualLineup();

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkActualLineupPlayers();

        $this->assertContains($results->message, 'Success!');

        $actualLineupPlayers = DkActualLineupPlayer::all();

        $this->assertCount(10, $actualLineupPlayers);

        $actualLineupPlayers = DkActualLineupPlayer::where('dk_actual_lineup_id', 1)
                                                    ->where('position', 'SS')
                                                    ->where('dk_player_id', 7)
                                                    ->get();

        $this->assertCount(1, $actualLineupPlayers);

        $actualLineup = DkActualLineup::where('id', 1)
                                        ->where('raw_text_players_parsed', 1)
                                        ->get();

        $this->assertCount(1, $actualLineup);
    }

}