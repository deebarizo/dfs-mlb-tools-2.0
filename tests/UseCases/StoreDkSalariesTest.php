<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\StoreDkSalaries;

class StoreDkSalariesTest extends TestCase {

	/** @test */
    public function parses_row_in_csv_excluding_first_row() { // note zero index has a player instead of the table names

		$structure = [

			// note the formatting
			// double quotes are needed to property show the new line (\n)
			// each field does not have any single quotes
		
		   'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,Max Scherzer,12300,Was@Atl 04:10PM ET,0,Was"
		];

		$root = vfsStream::setup('root', null, $structure);

		$this->assertTrue($root->hasChild('test.csv'));

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv');

    	$this->assertContains($results->players[0]['position'], 'SP'); 
    	$this->assertContains($results->players[0]['playerNameDk'], 'Max Scherzer'); 
    	$this->assertContains($results->players[0]['salary'], '12300'); 
    	$this->assertContains($results->players[0]['oppTeamNameDk'], 'Atl');
    	$this->assertContains($results->players[0]['teamNameDk'], 'Was'); 
    }

	/** @test */
    public function validates_csv_with_a_team_name_not_in_the_database() { 

		$structure = [

		   'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,Max Scherzer,12300,Was@Atl 04:10PM ET,0,XYZ"
		];

		$root = vfsStream::setup('root', null, $structure);

		$this->assertTrue($root->hasChild('test.csv'));

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv');

    	$this->assertContains($results->message, 'The DraftKings team name, <strong>XYZ</strong>, does not exist in the database.');
    }

}