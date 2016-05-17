<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\UseCase;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class BatProjectionsParserTest extends TestCase {

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
            'name_bat' => ''
        ]);   

        factory(DkPlayer::class)->create([
        
            'id' => 1,
            'player_pool_id' => 1,
            'player_id' => 1,
            'position' => '2B'
        ]); 

        factory(Player::class)->create([

        	'id' => 2,
            'team_id' => 1,
            'name_dk' => 'John Doe',
            'name_bat' => 'John R. Doe'
        ]);   

        factory(DkPlayer::class)->create([
        
            'id' => 2,
            'player_pool_id' => 1,
            'player_id' => 2,
            'position' => 'C'
        ]);    

        factory(Player::class)->create([

        	'id' => 3,
            'team_id' => 1,
            'name_dk' => 'Bob Jones',
            'name_bat' => ''
        ]);   

        factory(DkPlayer::class)->create([
        
            'id' => 3,
            'player_pool_id' => 1,
            'player_id' => 3,
            'position' => 'SP'
        ]);   
    }	

    private $csvFiles = [

        // note the formatting of csv file
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes

        'valid' => [

            'batNameMatchesWithNameBatColumn' => [

                'test.csv' => "name,position,team,lineup,fpts\nJohn R. Doe,C,WAS,N/C,5.24"
            ]
        ],

        'invalid' => [

            'invalidLineupField' => [

                'test.csv' => "name,position,team,lineup,fpts\nJohn R. Doe,C,WAS,bob,5.24"
            ], 

            'nonNumericFptsField' => [

            	'test.csv' => "name,position,team,lineup,fpts\nJohn R. Doe,C,WAS,3,bob"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

    /** @test */
    public function matches_bat_name_with_name_bat_column_instead_of_name_dk_column() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['batNameMatchesWithNameBatColumn']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseBatProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'Success!');
    }

    /** @test */
    public function validates_lineup_field_in_csv() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['invalidLineupField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseBatProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The lineup field is "bob". It should be "Live" or "Lst7".');
    }

    /** @test */
    public function validates_that_fpts_field_is_numeric() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericFptsField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseBatProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The fpts field is "bob". It should be a number.');                
    }

    /** @test */
    public function updates_bat_fields_of_dk_player() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['batNameMatchesWithNameBatColumn']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseBatProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'Success!');

        $dkPlayer = DkPlayer::find(2);

        $this->assertContains((string)$dkPlayer->lineup_bat, 'N/C');
        $this->assertContains((string)$dkPlayer->fpts_bat, '5.24');
    }

}