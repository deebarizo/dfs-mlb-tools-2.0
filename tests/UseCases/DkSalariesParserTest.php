<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\UseCase;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;

class DkSalariesParserTest extends TestCase {

    use DatabaseTransactions;

    private function setUpPlayers() {

        factory(Player::class)->create([
        
            'team_id' => 4,
            'name_dk' => 'John Doe'
        ]);   
    }

    private $csvFiles = [

        // note the formatting of csv file
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes

        'valid' => [

            'newPlayerName' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,Clayton Kershaw (6694453),Clayton Kershaw,6694453,13500,NYM@LAD 10:10PM ET,LAD,"
            ],

            'existingPlayerName' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,John Doe (6777888),John Doe,6777888,13500,NYM@LAD 10:10PM ET,LAD,"
            ]
        ],

        'invalid' => [

            'numericPositionField' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,9000,Clayton Kershaw (6694453),Clayton Kershaw,6694453,13500,NYM@LAD 10:10PM ET,LAD,"
            ],

            'numericNameField' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,Clayton Kershaw (6694453),9000,6694453,13500,NYM@LAD 10:10PM ET,LAD,"
            ],

            'notNumericSalaryField' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,Clayton Kershaw (6694453),Clayton Kershaw,6694453,Bob,NYM@LAD 10:10PM ET,LAD,"
            ],    

            'teamNotFound' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,Clayton Kershaw (6694453),Clayton Kershaw,6694453,13500,NYM@LAD 10:10PM ET,XYZ,"
            ],

            'oppTeamNotFound' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,Clayton Kershaw (6694453),Clayton Kershaw,6694453,13500,XYZ@LAD 10:10PM ET,LAD,"
            ],    

            'notNumericDkIdField' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,SP,Clayton Kershaw (6694453),Clayton Kershaw,Bob,13500,NYM@LAD 10:10PM ET,LAD,"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

    /** @test */
    public function validates_csv_with_number_in_position_field() { 

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['numericPositionField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The CSV format has changed. The position field has numbers.');
    }

    /** @test */
    public function validates_csv_with_number_in_name_field() { 

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['numericNameField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The CSV format has changed. The name field has numbers.');
    }


    /** @test */
    public function validates_csv_with_non_number_in_name_field() { 

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['notNumericSalaryField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The CSV format has changed. The salary field has non-numbers.');
    }

	/** @test */
    public function validates_csv_with_team_name_not_in_the_database() { 

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['teamNotFound']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

    	$this->assertContains($results->message, 'The DraftKings team name, <strong>XYZ</strong>, does not exist in the database.');
    }

    /** @test */
    public function validates_csv_with_opp_team_name_not_in_the_database() { 

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['oppTeamNotFound']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The DraftKings opposing team name, <strong>XYZ</strong>, does not exist in the database.');
    }

    /** @test */
    public function validates_csv_with_non_number_in_dk_id_field() { 

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['notNumericDkIdField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $this->assertContains($results->message, 'The CSV format has changed. The DK id field has non-numbers.');
    }

    /** @test */
    public function saves_new_player() { 

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['newPlayerName']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $playerPools = PlayerPool::where('date', '2016-01-01')->get();

        $this->assertCount(1, $playerPools);

        $players = Player::where('name_dk', 'Clayton Kershaw')->get();

        $this->assertCount(1, $players);
    }

    /** @test */
    public function saves_new_player_with_same_name_as_existing_player() { 

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['existingPlayerName']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $playerPools = PlayerPool::where('date', '2016-01-01')->get();

        $this->assertCount(1, $playerPools);

        $players = Player::where('name_dk', 'John Doe')->get();

        $this->assertCount(2, $players);
    }

    /** @test */
    public function saves_salary() { 

        $this->setUpPlayers();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['newPlayerName']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01', 'DK', 'All Day');

        $dkSalary = DkSalary::all()[0];

        $this->assertContains((string)$dkSalary->dk_id, '6694453');
        $this->assertContains((string)$dkSalary->team_id, '1');
        $this->assertContains((string)$dkSalary->opp_team_id, '4');
        $this->assertContains($dkSalary->position, 'SP');
        $this->assertContains((string)$dkSalary->salary, '13500');
    }

}