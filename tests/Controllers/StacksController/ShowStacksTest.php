<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class ShowStacksTest extends TestCase {

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
            'name_dk' => 'Carlos Santana'
        ]);  

        factory(DkPlayer::class)->create([
        
            'id' => 1, 
            'player_pool_id' => 1,
            'player_id' => 1,
            'position' => '1B',
            'fpts_bat' => 7.55,
            'upside_fpts_razzball' => 15.20
        ]);  

        factory(Player::class)->create([
        
            'id' => 2, 
            'team_id' => 2,
            'name_dk' => 'Jason Kipnis'
        ]);  

        factory(DkPlayer::class)->create([
        
            'id' => 2, 
            'player_pool_id' => 1,
            'player_id' => 2,
            'position' => '2B',
            'fpts_bat' => 7.83,
            'upside_fpts_razzball' => 14.80
        ]); 

        factory(Player::class)->create([
        
            'id' => 3, 
            'team_id' => 3,
            'name_dk' => 'Mike Napoli'
        ]);  

        factory(DkPlayer::class)->create([
        
            'id' => 3, 
            'player_pool_id' => 1,
            'player_id' => 3,
            'position' => '1B',
            'fpts_bat' => 7.41,
            'upside_fpts_razzball' => 14.70
        ]); 

        factory(Player::class)->create([
        
            'id' => 4, 
            'team_id' => 4,
            'name_dk' => 'Francisco Lindor'
        ]);  

        factory(DkPlayer::class)->create([
        
            'id' => 4, 
            'player_pool_id' => 1,
            'player_id' => 4,
            'position' => 'SS',
            'fpts_bat' => 6.89,
            'upside_fpts_razzball' => 14.30
        ]); 

        factory(Player::class)->create([
        
            'id' => 5, 
            'team_id' => 5,
            'name_dk' => 'Yan Gomes'
        ]);  

        factory(DkPlayer::class)->create([
        
            'id' => 5, 
            'player_pool_id' => 1,
            'player_id' => 5,
            'position' => 'C',
            'fpts_bat' => 6.46,
            'upside_fpts_razzball' => 13.80
        ]); 

        factory(Player::class)->create([
        
            'id' => 6, 
            'team_id' => 6,
            'name_dk' => 'Rajai Davis'

        ]);  

        factory(DkPlayer::class)->create([
        
            'id' => 6, 
            'player_pool_id' => 1,
            'player_id' => 6,
            'position' => 'OF',
            'fpts_bat' => 5.95,
            'upside_fpts_razzball' => 12.60
        ]); 
    }

	/** @test */
    public function submits_form() {

    	$this->setUpPlayerPool(); $this->setUpPlayers();

       	$this->visit('/player_pools/1/stacks');
        $this->see('LAD');
        $this->see('10.54');
    }

}