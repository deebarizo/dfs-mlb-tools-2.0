<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\UseCase;

use App\PlayerPool;
use App\Player;
use App\DkPlayer;

class RazzballHitterProjectionsParserTest extends TestCase {

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
            'player_id' => 1,
            'position' => '2B'
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
            'player_id' => 2,
            'position' => 'SS'
        ]);    

        factory(Player::class)->create([

        	'id' => 3,
            'team_id' => 1,
            'name_dk' => 'Bob Jones',
            'name_razzball' => ''
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

            'razzballNameMatchesWithNameRazzballColumn' => [

                'test.csv' => "#,Name,B,Team,Pos,Date,GT,DH,Opp,Pitcher,% St,LU,PA,R,1B,2B,3B,HR,RBI,SB,BB+ HBP,PTS,UP PTS,DK $,$/PT,$/UP PT\n1,John R. Doe,R,MIA,OF,5/16,7,0,@PHI,JEickhoff(R),88,Lst7,4.3,0.65,0.43,0.17,0.01,0.33,0.83,0.04,0.63,9.9,19.4,5100,515.2,263.3"
            ]
        ],

        'invalid' => [

            'nonHitter' => [

                'test.csv' => "#,Name,B,Team,Pos,Date,GT,DH,Opp,Pitcher,% St,LU,PA,R,1B,2B,3B,HR,RBI,SB,BB+ HBP,PTS,UP PTS,DK $,$/PT,$/UP PT\n1,Bob Jones,R,MIA,OF,5/16,7,0,@PHI,JEickhoff(R),88,Lst7,4.3,0.65,0.43,0.17,0.01,0.33,0.83,0.04,0.63,9.9,19.4,5100,515.2,263.3"
            ],

            'invalidLineupField' => [

                'test.csv' => "#,Name,B,Team,Pos,Date,GT,DH,Opp,Pitcher,% St,LU,PA,R,1B,2B,3B,HR,RBI,SB,BB+ HBP,PTS,UP PTS,DK $,$/PT,$/UP PT\n1,Bob Jones,R,MIA,OF,5/16,7,0,@PHI,JEickhoff(R),88,bob,4.3,0.65,0.43,0.17,0.01,0.33,0.83,0.04,0.63,9.9,19.4,5100,515.2,263.3"
            ],

            'nonNumericPercentStartField' => [

                'test.csv' => "#,Name,B,Team,Pos,Date,GT,DH,Opp,Pitcher,% St,LU,PA,R,1B,2B,3B,HR,RBI,SB,BB+ HBP,PTS,UP PTS,DK $,$/PT,$/UP PT\n1,John R. Doe,R,MIA,OF,5/16,7,0,@PHI,JEickhoff(R),bob,Lst7,4.3,0.65,0.43,0.17,0.01,0.33,0.83,0.04,0.63,9.9,19.4,5100,515.2,263.3"
            ], 

            'nonNumericFptsField' => [

                'test.csv' => "#,Name,B,Team,Pos,Date,GT,DH,Opp,Pitcher,% St,LU,PA,R,1B,2B,3B,HR,RBI,SB,BB+ HBP,PTS,UP PTS,DK $,$/PT,$/UP PT\n1,John R. Doe,R,MIA,OF,5/16,7,0,@PHI,JEickhoff(R),88,Lst7,4.3,0.65,0.43,0.17,0.01,0.33,0.83,0.04,0.63,bob,19.4,5100,515.2,263.3"
            ],

            'nonNumericUpsideFptsField' => [

                'test.csv' => "#,Name,B,Team,Pos,Date,GT,DH,Opp,Pitcher,% St,LU,PA,R,1B,2B,3B,HR,RBI,SB,BB+ HBP,PTS,UP PTS,DK $,$/PT,$/UP PT\n1,John R. Doe,R,MIA,OF,5/16,7,0,@PHI,JEickhoff(R),88,Lst7,4.3,0.65,0.43,0.17,0.01,0.33,0.83,0.04,0.63,9.9,bob,5100,515.2,263.3"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

    /** @test */
    public function validates_csv_with_a_non_hitter() { 

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonHitter']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The Razzball hitter, Bob Jones, is not a DK hitter.');
    }

    /** @test */
    public function matches_razzball_name_with_name_razzball_column_instead_of_name_dk_column() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['razzballNameMatchesWithNameRazzballColumn']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'Success!');
    }

    /** @test */
    public function validates_lineup_field_in_csv() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['invalidLineupField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The lineup field is "bob". It should be "Live" or "Lst7".');
    }

    /** @test */
    public function validates_that_percent_start_field_is_numeric() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericPercentStartField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The percent start field is "bob". It should be a number.');                
    }

    /** @test */
    public function validates_that_fpts_field_is_numeric() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericFptsField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The fpts field is "bob". It should be a number.');                
    }

    /** @test */
    public function validates_that_upside_fpts_field_is_numeric() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericUpsideFptsField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The upside fpts field is "bob". It should be a number.');                
    }

    /** @test */
    public function updates_razzball_fields_of_dk_player() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['razzballNameMatchesWithNameRazzballColumn']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballHitterProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'Success!');

        $dkPlayer = DkPlayer::find(2);

        $this->assertContains((string)$dkPlayer->lineup_razzball, 'Lst7');
        $this->assertContains((string)$dkPlayer->percent_start_razzball, '88');
        $this->assertContains((string)$dkPlayer->fpts_razzball, '9.90');
        $this->assertContains((string)$dkPlayer->upside_fpts_razzball, '19.40');
    }

}