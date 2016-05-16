<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Player;

class UpdatePlayerTest extends TestCase {

    use DatabaseTransactions;

    private function setUpDatabase() {

        factory(Player::class)->create([
        
            'id' => 1,
            'team_id' => 10,
            'name_dk' => 'Chris Sale',
            'name_razzball' => '',
            'name_bat' => ''
        ]);
    }

    /** @test */
    public function updates_player() {

        $this->setUpDatabase();

        $this->call('POST', '/players/1/update', [

            'name_dk' => 'Chris Sale',
            'name_razzball' => 'bob',
            'name_bat' => 'bob2',
            'team-id' => 1
        ]);

        $this->assertRedirectedTo('/players/1/edit');

        $this->followRedirects();

        $this->see('Success!');

        $player = Player::find(1);

        $this->assertContains($player->name_dk, 'Chris Sale');
        $this->assertContains($player->name_razzball, 'bob');
        $this->assertContains($player->name_bat, 'bob2');
        $this->assertContains((string)$player->team_id, '1');
    }

}