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

                'test.csv' => "#,Name,Team,Date,GT,DH,Opp,LU,W,L,IP,H,ER,K,BB+ HBP,ERA,WHIP,PTS,Salary,$/Pt\n1,Dee Barizo,NYA,5/15,1,0,CHA,Live,0.49,0.23,6.3,5.8,2.1,6.3,1.4,3.04,1.10,20.2,9200,455.4"
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

        $this->assertContains($results->message, 'The Razzball name, Dee Barizo, does not match a DK name.');
    }

}