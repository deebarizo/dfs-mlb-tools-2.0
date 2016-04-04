<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\StoreDkSalaries;

use App\Team;
use App\Player;

class StoreDkSalariesTest extends TestCase {

    use DatabaseTransactions;

    private function setUpTeams() {

        factory(Team::class)->create([

            'id' => 1,
            'name_dk' => 'Atl'
        ]);

        factory(Team::class)->create([
        
            'id' => 2,
            'name_dk' => 'Was'
        ]);
    }

    private $validCsvFile = [

        // note the formatting
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes
    
       'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,Max Scherzer,12300,Was@Atl 04:10PM ET,0,Was"
    ];

    private $invalidCsvFile = [

        // note the formatting
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes
    
       'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,Max Scherzer,12300,Was@Atl 04:10PM ET,0,XYZ"
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

	/** @test */
    public function parses_row_in_csv_excluding_first_row() { // note zero index has a player instead of the table names

    	$this->setUpTeams();

        $root = $this->setUpCsvFile($this->validCsvFile);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->parseCsvFile($root->url().'/test.csv');

    	$this->assertContains($results->players[0]['position'], 'SP'); 
    	$this->assertContains($results->players[0]['nameDk'], 'Max Scherzer'); 
    	$this->assertContains($results->players[0]['salary'], '12300'); 
    	$this->assertContains($results->players[0]['oppTeamNameDk'], 'Atl');
    	$this->assertContains($results->players[0]['teamNameDk'], 'Was'); 
    }

	/** @test */
    public function validates_csv_with_a_team_name_not_in_the_database() { 

    	$this->setUpTeams();

        $root = $this->setUpCsvFile($this->invalidCsvFile);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->parseCsvFile($root->url().'/test.csv');

    	$this->assertContains($results->message, 'The DraftKings team name, <strong>XYZ</strong>, does not exist in the database.');
    }

    private function setUpPlayer() {

        factory(Player::class)->create([
        
            'team_id' => 1,
            'name_dk' => 'John Doe'
        ]);       
    }

    /** @test */
    public function saves_a_new_player() { 

        $this->setUpTeams();

        $this->setUpPlayer();

        $root = $this->setUpCsvFile($this->validCsvFile);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv');

        $players = Player::where('name_dk', 'Max Scherzer')->get();

        $this->assertCount(1, $players);
    }

    /** @test */
    public function saves_salaries() { 

        $this->setUpTeams();

        $this->setUpPlayer();

        $root = $this->setUpCsvFile($this->validCsvFile);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv');

        $dkPlayerPools = DkPlayerPool::where('date', '2016-04-04')->get();

        $this->assertCount(1, $dkPlayerPools);
    }

}