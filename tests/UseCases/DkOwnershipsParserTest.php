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

class DkOwnershipsParserTest extends TestCase {

	use DatabaseTransactions;

    private function setUpPlayerPool() {

        factory(PlayerPool::class)->create([
        
            'id' => 1,
            'date' => '2016-01-01',
            'time_period' => 'All Day',
            'site' => 'DK'
        ]);
    }

    private function setUpPlayersAndDkSalaries() {

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

    private function setUpActualLineups() {

        factory(ActualLineup::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'rank' => 1,
            'user' => 'bob',
            'fpts' => 200.00,
            'raw_text_players' => 'bob',
            'raw_text_players_parsed' => 1
        ]);

        factory(ActualLineup::class)->create([
        
            'id' => 2,
            'player_pool_id' => 1,
            'rank' => 2,
            'user' => 'bob2',
            'fpts' => 199.00,
            'raw_text_players' => 'bob2',
            'raw_text_players_parsed' => 1
        ]);

        factory(ActualLineup::class)->create([
        
            'id' => 3,
            'player_pool_id' => 1,
            'rank' => 3,
            'user' => 'bob3',
            'fpts' => 198.00,
            'raw_text_players' => 'bob3',
            'raw_text_players_parsed' => 1
        ]);
    }

    private function setUpActualLineupPlayers() {

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 1,
            'actual_lineup_id' => 1,
            'position' => 'P',
            'dk_salary_id' => 1 
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 2,
            'actual_lineup_id' => 1,
            'position' => 'P',
            'dk_salary_id' => 2 
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 3,
            'actual_lineup_id' => 1,
            'position' => 'C',
            'dk_salary_id' => 3 
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 4,
            'actual_lineup_id' => 1,
            'position' => '1B',
            'dk_salary_id' => 4
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 5,
            'actual_lineup_id' => 1,
            'position' => '2B',
            'dk_salary_id' => 5
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 6,
            'actual_lineup_id' => 1,
            'position' => '3B',
            'dk_salary_id' => 6
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 7,
            'actual_lineup_id' => 1,
            'position' => 'SS',
            'dk_salary_id' => 7
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 8,
            'actual_lineup_id' => 1,
            'position' => 'OF',
            'dk_salary_id' => 8
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 9,
            'actual_lineup_id' => 1,
            'position' => 'OF',
            'dk_salary_id' => 9
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 10,
            'actual_lineup_id' => 1,
            'position' => 'OF',
            'dk_salary_id' => 10
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 11,
            'actual_lineup_id' => 2,
            'position' => 'P',
            'dk_salary_id' => 11
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 12,
            'actual_lineup_id' => 2,
            'position' => 'P',
            'dk_salary_id' => 2 
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 13,
            'actual_lineup_id' => 2,
            'position' => 'C',
            'dk_salary_id' => 3
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 14,
            'actual_lineup_id' => 2,
            'position' => '1B',
            'dk_salary_id' => 4
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 15,
            'actual_lineup_id' => 2,
            'position' => '2B',
            'dk_salary_id' => 5
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 16,
            'actual_lineup_id' => 2,
            'position' => '3B',
            'dk_salary_id' => 7
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 17,
            'actual_lineup_id' => 2,
            'position' => 'SS',
            'dk_salary_id' => 12
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 18,
            'actual_lineup_id' => 2,
            'position' => 'OF',
            'dk_salary_id' => 8
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 19,
            'actual_lineup_id' => 2,
            'position' => 'OF',
            'dk_salary_id' => 9
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 20,
            'actual_lineup_id' => 2,
            'position' => 'OF',
            'dk_salary_id' => 10
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 21,
            'actual_lineup_id' => 3,
            'position' => 'P',
            'dk_salary_id' => 11
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 22,
            'actual_lineup_id' => 3,
            'position' => 'P',
            'dk_salary_id' => 2
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 23,
            'actual_lineup_id' => 3,
            'position' => 'C',
            'dk_salary_id' => 3
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 24,
            'actual_lineup_id' => 3,
            'position' => '1B',
            'dk_salary_id' => 4
        ]);    

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 25,
            'actual_lineup_id' => 3,
            'position' => '2B',
            'dk_salary_id' => 5
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 26,
            'actual_lineup_id' => 3,
            'position' => '3B',
            'dk_salary_id' => 7
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 27,
            'actual_lineup_id' => 3,
            'position' => 'SS',
            'dk_salary_id' => 12
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 28,
            'actual_lineup_id' => 3,
            'position' => 'OF',
            'dk_salary_id' => 8
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 29,
            'actual_lineup_id' => 3,
            'position' => 'OF',
            'dk_salary_id' => 13
        ]);

        factory(ActualLineupPlayer::class)->create([
        
            'id' => 30,
            'actual_lineup_id' => 3,
            'position' => 'OF',
            'dk_salary_id' => 10
        ]);    
    }

	/** @test */
	public function saves_ownerships() {

		$this->setUpPlayerPool(); $this->setUpPlayersAndDkSalaries(); $this->setUpActualLineups(); $this->setUpActualLineupPlayers();

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkOwnerships();

        $this->assertContains($results->message, 'Success!');

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 1)
                            ->where('ownership', 33.3)
                            ->where('ownership_of_first_position', 33.3)
                            ->where('ownership_of_second_position', 0.0)
                            ->get();

        $this->assertCount(1, $dkSalaries);
        $this->assertContains((string)$dkSalaries[0]->ownerships_parsed, '1');

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 3)
                            ->where('ownership', 100.0)
                            ->where('ownership_of_first_position', 100.0)
                            ->where('ownership_of_second_position', 0.0)
                            ->get();

        $this->assertCount(1, $dkSalaries);
        $this->assertContains((string)$dkSalaries[0]->ownerships_parsed, '1');

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 9)
                            ->where('ownership', 66.7)
                            ->where('ownership_of_first_position', 66.7)
                            ->where('ownership_of_second_position', 0.0)
                            ->get();

        $this->assertCount(1, $dkSalaries);
        $this->assertContains((string)$dkSalaries[0]->ownerships_parsed, '1');

        $dkSalaries = DkSalary::where('player_pool_id', 1)
                            ->where('player_id', 7)
                            ->where('ownership', 100.0)
                            ->where('ownership_of_first_position', 66.7)
                            ->where('ownership_of_second_position', 33.3)
                            ->get();

        $this->assertCount(1, $dkSalaries);
        $this->assertContains((string)$dkSalaries[0]->ownerships_parsed, '1');

        $numOfActualLineupPlayers = ActualLineupPlayer::count();

        $this->assertContains((string)$numOfActualLineupPlayers, '0');
	} 

}