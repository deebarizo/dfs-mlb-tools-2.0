<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Player;

class EditPlayerTest extends TestCase {

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
    public function submits_form() {

    	$this->setUpDatabase();

       	$this->visit('/players/1/edit');
        $this->see('Chris Sale');
        $this->see('<option value="28">Col</option>');
        $this->see('<option value="10" selected>CWS</option>');
       	$this->type('Chris Sale', 'name_dk');
       	$this->type('Chris Sale', 'name_razzball');
       	$this->type('Chris Sale', 'name_bat');
        $this->press('Submit');
    }

}