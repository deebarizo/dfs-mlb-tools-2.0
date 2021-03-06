<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\UseCases\UseCase;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class ParseDkPlayersTest extends TestCase {

	/** @test */
    public function submits_dk_players_csv() {

       	$this->visit('/admin/parsers/dk_players');
        $this->select('DK', 'site');
        $this->select('All Day', 'time-period');
       	$this->type('2016-03-31', 'date');
        $this->type('DKSalaries.csv', 'csv')
             ->attach('/files/dk_players/', 'csv');
        $this->press('Submit');
    }

    /** @test */
    public function gets_csv_file() {

        $fileDirectory = 'test_folder/';

        $useCase = new UseCase;

        $csvFile = $useCase->uploadCsvFileForDkParsers('All Day', 'DK', '2016-01-01', $fileDirectory, $request = '');

        $this->assertContains($csvFile, 'test_folder/2016-01-01-all-day-dk.csv');
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/admin/parsers/dk_players', [

            'date' => '',
            'csv' => ''
        ]);

        $this->assertSessionHasErrors(['date', 'csv']);

        // I don't need to test the redirect because Taylor Otwell has already tested the form request class. I'm using that class for validation and the class automatically redirects back to the page with an $errors object. Plus, when I try to test the redirect, it doesn't work.
    }

    /** @test */
    public function validates_successful_input() {

        $this->call('POST', '/admin/parsers/dk_players', [

            'date' => '2016-04-02',
            'csv' => 'Test.csv'
        ]);

        $this->assertRedirectedTo('/admin/parsers/dk_players');

        $this->followRedirects();

        $this->see('Success!');
    }  

}