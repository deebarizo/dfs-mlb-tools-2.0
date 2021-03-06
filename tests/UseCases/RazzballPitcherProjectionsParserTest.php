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
            'player_id' => 1,
            'position' => 'SP'
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
            'position' => 'RP'
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
            'position' => 'SS'
        ]);   
    }	

    private $csvFiles = [

        // note the formatting of csv file
        // double quotes are needed to property show the new line (\n)
        // each field does not have any single quotes

    	'valid' => [

            'razzballNameMatchesWithNameRazzballColumn' => [

                'test.csv' => "#,Name,Team,Date,GT,DH,Opp,LU,W,L,IP,H,ER,K,BB+ HBP,ERA,WHIP,PTS,Salary,$/Pt\n1,John R. Doe,NYA,5/15,1,0,CHA,Live,0.49,0.23,6.3,5.8,2.1,6.3,1.4,3.04,1.10,20.2,9200,455.4"
            ]
    	],

        'invalid' => [

            'invalidLineupField' => [

                'test.csv' => "#,Name,Team,Date,GT,DH,Opp,LU,W,L,IP,H,ER,K,BB+ HBP,ERA,WHIP,PTS,Salary,$/Pt\n1,Masahiro Tanaka,NYA,5/15,1,0,CHA,bob,0.49,0.23,6.3,5.8,2.1,6.3,1.4,3.04,1.10,20.2,9200,455.4"
            ],

            'nonNumericFptsField' => [

                'test.csv' => "#,Name,Team,Date,GT,DH,Opp,LU,W,L,IP,H,ER,K,BB+ HBP,ERA,WHIP,PTS,Salary,$/Pt\n1,Masahiro Tanaka,NYA,5/15,1,0,CHA,Live,0.49,0.23,6.3,5.8,2.1,6.3,1.4,3.04,1.10,bob,9200,455.4"
            ]
        ]
    ];

    private function setUpCsvFile($csvFile) {

        $root = vfsStream::setup('root', null, $csvFile);

        $this->assertTrue($root->hasChild('test.csv'));

        return $root;
    }

    /** @test */
    public function matches_razzball_name_with_name_razzball_column_instead_of_name_dk_column() {

    	$this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['razzballNameMatchesWithNameRazzballColumn']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballPitcherProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'Success!');
    }

    /** @test */
    public function validates_lineup_field_in_csv() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['invalidLineupField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballPitcherProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The lineup field is "bob". It should be "Live" or "Lst7".');
    }

    /** @test */
    public function validates_that_fpts_field_is_numeric() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['invalid']['nonNumericFptsField']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballPitcherProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'The fpts field is "bob". It should be a number.');                
    }

    /** @test */
    public function updates_razzball_fields_of_dk_player() {

        $this->setUpDatabase();

        $root = $this->setUpCsvFile($this->csvFiles['valid']['razzballNameMatchesWithNameRazzballColumn']);

        $useCase = new UseCase; 
        
        $results = $useCase->parseRazzballPitcherProjections($root->url().'/test.csv', 1);

        $this->assertContains($results->message, 'Success!');

        $dkPlayer = DkPlayer::find(2);

        $this->assertContains((string)$dkPlayer->lineup_razzball, 'Live');
        $this->assertContains((string)$dkPlayer->fpts_razzball, '20.20');
    }

}