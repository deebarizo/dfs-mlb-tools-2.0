<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\UseCase;

use App\Team;
use App\Player;
use App\DkSalary;

class DkSalariesParserTest extends TestCase {

    use DatabaseTransactions;

    private function setUpTeams() {

        factory(Team::class)->create([

            'id' => 4,
            'name_dk' => 'NYM'
        ]);

        factory(Team::class)->create([
        
            'id' => 1,
            'name_dk' => 'LAD'
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

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,Clayton Kershaw (6690258),Clayton Kershaw,6690258,14200,NYM@LAD 10:10PM ET,LAD,"
            ],

            'existingPlayerName' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,John Doe (6777888),John Doe,6777888,14200,NYM@LAD 10:10PM ET,LAD,"
            ]
        ],

        'invalid' => [

            'numericPositionField' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,9000,Clayton Kershaw (6690258),Clayton Kershaw,6690258,14200,NYM@LAD 10:10PM ET,LAD,"
            ],

            'numericNameField' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,Clayton Kershaw (6690258),9000,6690258,14200,NYM@LAD 10:10PM ET,LAD,"
            ],

            'notNumericSalaryField' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,Clayton Kershaw (6690258),Clayton Kershaw,6690258,Bob,NYM@LAD 10:10PM ET,LAD,"
            ],    

            'teamNotFound' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,Clayton Kershaw (6690258),Clayton Kershaw,6690258,14200,NYM@LAD 10:10PM ET,XYZ,"
            ],

            'oppTeamNotFound' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,Clayton Kershaw (6690258),Clayton Kershaw,6690258,14200,NYM@XYZ 10:10PM ET,LAD,"
            ],    

            'notNumericDkIdField' => [

                'test.csv' => "Entry ID,Contest Name,Contest ID,Entry Fee,P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n401303040,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,1. Column A lists all of your contest entries for this draftgroup\n401308302,MLB $300K Swing for the Fences [$50000 to 1st],24093060,$3,Corey Kluber (6690186),Jon Lester (6690113),Welington Castillo (6690620),Anthony Rizzo (6690648),Ben Zobrist (6690708),Hernan Perez (6690743),Luis Sardinas (6690761),Mike Aviles (6690819),Darin Mastroianni (6690953),Hunter Renfroe (6690932),,2. Your current lineup is listed next to each entry (blank for reservations)\n,,,,,,,,,,,,,,,3. You can change the lineup for an entry by entering the IDs of the players in the row next to that entry\n,,,,,,,,,,,,,,,4. Use data from the Name+ID column or the ID column; you cannot use just the player's name\n,,,,,,,,,,,,,,,5. For faster processing only include entries you are changing in the file you upload\n,,,,,,,,,,,,,,,\n,,,,,,,,,,,,,,,Position,Name + ID,Name,ID,Salary,Game Info,TeamAbbrev\n,,,,,,,,,,,,,,,SP,Clayton Kershaw (6690258),Clayton Kershaw,Bob,14200,NYM@LAD 10:10PM ET,LAD,"
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

        $this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['numericPositionField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01');

        $this->assertContains($results->message, 'The CSV format has changed. The position field has numbers.');
    }

    /** @test */
    public function validates_csv_with_number_in_name_field() { 

        $this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['numericNameField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01');

        $this->assertContains($results->message, 'The CSV format has changed. The name field has numbers.');
    }


    /** @test */
    public function validates_csv_with_non_number_in_name_field() { 

        $this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['notNumericSalaryField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01');

        $this->assertContains($results->message, 'The CSV format has changed. The salary field has non-numbers.');
    }

	/** @test */
    public function validates_csv_with_team_name_not_in_the_database() { 

    	$this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['teamNotFound']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01');

    	$this->assertContains($results->message, 'The DraftKings team name, <strong>XYZ</strong>, does not exist in the database.');
    }

    /** @test */
    public function validates_csv_with_opp_team_name_not_in_the_database() { 

        $this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['oppTeamNotFound']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01');

        $this->assertContains($results->message, 'The DraftKings opposing team name, <strong>NYMXYZ</strong>, does not exist in the database.');
    }

    /** @test */
    public function validates_csv_with_non_number_in_dk_id_field() { 

        $this->setUpTeams();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['notNumericDkIdField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseDkSalaries($root->url().'/test.csv', '2016-01-01');

        $this->assertContains($results->message, 'The CSV format has changed. The DK id field has non-numbers.');
    }

}