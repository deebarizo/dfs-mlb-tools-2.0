<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\UseCase;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class RazzballPitcherProjectionsParserTest extends TestCase {

    use DatabaseTransactions;

    private function setUpDatabase() {

        factory(PlayerPool::class)->create([
        
            'id' => 1,
            'date' => '2016-01-01',
            'time_period' => 'Early',
            'site' => 'DK'
        ]);

        factory(Player::class)->create([

        	'id' => 1,
            'team_id' => 1,
            'name_dk' => 'Masahiro Tanaka',
            'name_razzball' => ''
        ]);   

        factory(DkPlayer::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'player_id' => 1
        ]); 

        factory(Player::class)->create([

        	'id' => 2,
            'team_id' => 1,
            'name_dk' => 'John Doe',
            'name_razzball' => 'John R. Doe'
        ]);   

        factory(DkPlayer::class)->create([
        
            'id' => 2,
            'player_pool_id' => 1,
            'player_id' => 2
        ]);    
    }	

    private $csvFiles = [

        // note the formatting of csv file
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes

        'invalid' => [

            'razzballNameDoesNotMatchDkName' => [

                'test.csv' => "P,P,C,1B,2B,3B,SS,OF,OF,OF,,Instructions\n,,,,,,,,,,,1. Locate the player you want to select in the list below \n,,,,,,,,,,,2. Copy the ID of your player (you can use the Name + ID column or the ID column) \n,,,,,,,,,,,3. Paste the ID into the roster position desired \n,,,,,,,,,,,4. You must include an ID for each player; you cannot use just the player's name \n,,,,,,,,,,,5. You can create up to 500 lineups per file \n \n,,,,,,,,,,,Position,Name + ID, Name, ID, Salary,GameInfo,TeamAbbrev \n,,,,,,,,,,,9000,Clayton Kershaw (6694453),Clayton Kershaw,6694453,13500,NYM@LAD 10:10PM ET,LAD,"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

    /** @test */
    public function validates_csv_with_razzball_name_that_does_not_match_dk_name() { 

    	$this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['razzballNameDoesNotMatchDkName']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballPitcherProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The Razzball name, John Doe, does not match a DK name.');
    }

}