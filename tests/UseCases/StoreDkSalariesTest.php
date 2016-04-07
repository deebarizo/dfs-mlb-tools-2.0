<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\StoreDkSalaries;

use App\Team;
use App\Player;
use App\DkSalary;

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

    private function setUpPlayers() {

        factory(Player::class)->create([
        
            'team_id' => 1,
            'name_dk' => 'John Doe'
        ]);   
    }

    private $csvFiles = [

        // note the formatting of csv file
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes

        'valid' => [

            'newPlayerName' => [

                'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,Max Scherzer,12300,Was@Atl 04:10PM ET,0,Was"
            ],

            'existingPlayerName' => [

                'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,John Doe,12300,Was@Atl 04:10PM ET,0,Was"
            ]
        ],

        'invalid' => [

            'newPlayerName' => [

                'test.csv' => "Position,Name,Salary,GameInfo,AvgPointsPerGame,teamAbbrev\nSP,Max Scherzer,12300,Was@Atl 04:10PM ET,0,XYZ"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

	/** @test */
    public function parses_row_in_csv_excluding_first_row() { // note zero index has a player instead of the table names

    	$this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['newPlayerName']);

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

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['newPlayerName']);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->parseCsvFile($root->url().'/test.csv');

    	$this->assertContains($results->message, 'The DraftKings team name, <strong>XYZ</strong>, does not exist in the database.');
    }

    /** @test */
    public function saves_new_player() { 

        $this->setUpTeams();

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['newPlayerName']);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv', '2016-04-04');

        $players = Player::where('name_dk', 'Max Scherzer')->get();

        $this->assertCount(1, $players);
    }

    /** @test */
    public function saves_new_player_with_same_name_as_existing_player() { 

        $this->setUpTeams();

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['existingPlayerName']);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv', '2016-04-04');

        $players = Player::where('name_dk', 'John Doe')->where('team_id', 2)->get();

        $this->assertCount(1, $players);
    }

    /** @test */
    public function saves_salary() { 

        $this->setUpTeams();

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['newPlayerName']);

        $storeDkSalaries = new StoreDkSalaries; 
        
        $results = $storeDkSalaries->perform($root->url().'/test.csv', '2016-04-04');

        $dkSalary = DkSalary::where('date', '2016-04-04')->first();

        $this->assertContains($dkSalary->date, '2016-04-04');
        $this->assertContains((string)$dkSalary->team_id, '2');
        $this->assertContains((string)$dkSalary->opp_team_id, '1');
        $this->assertContains($dkSalary->position, 'SP');
        $this->assertContains((string)$dkSalary->salary, '12300');
    }

}