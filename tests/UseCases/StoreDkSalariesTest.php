<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\UseCases\StoreDkSalaries;

class StoreDkSalariesTest extends TestCase {

	/** @test */
    public function validates_csv_with_a_team_name_not_in_the_database() { 

    	// time to create a model factory
    	// use a test csv file

		if (($handle = fopen('files/dk_salaries/2016-04-04.csv', 'r')) !== false) {
			
			$rowCount = 0;

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($rowCount != 0) { // skip first row because it contains the table names
				
				    $players[$rowCount] = array(

				    	'position' => $row[0],
				       	'playerNameDk' => $row[1],
				       	'salary' => $row[2],
				       	'teamNameDk' => $row[5]
				    );

				    $gameInfo = $row[3];
				    $gameInfo = preg_replace("/(\w+@\w+)(\s)(.*)/", "$1", $gameInfo);
				    $gameInfo = preg_replace("/@/", "", $gameInfo);
				    $players[$rowCount]['oppTeamNameDk'] = preg_replace("/".$players[$rowCount]['teamNameDk']."/", "", $gameInfo);

				    # $teamExists = Team::where('name_dk', $player[$row]['teamNameDk'])->count();

				    /* if (!$teamExists) {

						$this->message = 'The DraftKings team name, <strong>'.$player[$row]['abbr_dk'].'</strong>, does not exist in the database.'; 

						return $this;
				    }	*/			    

				}

				ddAll($player);

				$rowCount++;
			}
		} 

        $storeDkSalaries = new StoreDkSalaries; 
        
        $message = $storeDkSalaries->perform('files/dk_salaries/2016-04-04.csv');

    	$this->assertContains($message, 'The DraftKings team name, <strong>XYZ</strong>, does not exist in the database.');
    }

	/** @test */
    public function parses_second_line_of_dk_salaries_csv() { // not first line because it contains the table names

        # $this->assertContains('SP', $position);
    }

}