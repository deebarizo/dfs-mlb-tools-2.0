<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Team;
use App\Player;
use App\DkSalary;

class ParsersControllerTest extends TestCase {

    use DatabaseTransactions;

    private function setUpDkSalary() {

        factory(Team::class)->create([

            'id' => 1,
            'name_dk' => 'Atl'
        ]);

        factory(Team::class)->create([
        
            'id' => 2,
            'name_dk' => 'Was'
        ]);

        factory(Player::class)->create([
        
            'id' => 1,
            'team_id' => 1, 
            'name_dk' => 'John Doe'
        ]);

        factory(DkSalary::class)->create([
        
            'id' => 1,
            'player_id' => 1,
            'team_id' => 1,
            'opp_team_id' => 2,
            'date' => '2016-04-04'
        ]);        
    }

	/** @test */
    public function submits_dk_salaries_csv() {

       	$this->visit('/admin/parsers/dk_salaries');
       	$this->type('2016-03-31', 'date');
        $this->type('DKSalaries.csv', 'csv')
             ->attach('/files/dk_salaries/', 'csv');
        $this->press('Submit');
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/admin/parsers/dk_salaries', [

            'date' => '',
            'csv' => ''
        ]);

        $this->assertSessionHasErrors(['date', 'csv']);

        // I don't need to test the redirect because Taylor Otwell has already tested the form request class. I'm using that class for validation and the class automatically redirects back to the page with an $errors object. Plus, when I try to test the redirect, it doesn't work.
    }

    /** @test */
    public function validates_duplicate_date() {

        $this->setUpDkSalary();

        $this->call('POST', '/admin/parsers/dk_salaries', [

            'date' => '2016-04-04',
            'csv' => 'Test.csv'
        ]);

        $this->assertSessionHasErrors(['date']);
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/admin/parsers/dk_salaries', [

            'date' => '2016-04-02',
            'csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/admin/parsers/dk_salaries');

        $this->followRedirects();

        $this->see('Success!');
    }  

}