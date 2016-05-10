<?php namespace App\UseCases;

use DkSalariesParser;

use App\Team;
use App\Player;
use App\DkSalary;

trait DkSalariesParser {

	public function parseDkSalaries($csvFile, $date) {

		if (($handle = fopen($csvFile, 'r')) !== false) {
			
			$i = 0; // index

			$this->players = [];

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($i > 7) { 
				
				    $this->players[$i] = array( 

				    	'position' => $row[15],
				       	'nameDk' => convertAccentLettersToEnglish($row[17]),
				       	'idDk' => $row[18],
				       	'salary' => $row[19],
				       	'teamNameDk' => $row[21]
				    );

				    if (is_numeric($this->players[$i]['position'])) {

						$this->message = 'The CSV format has changed. The position field has numbers.'; 

						return $this;				    	
				    }

				    if (is_numeric($this->players[$i]['nameDk'])) {

						$this->message = 'The CSV format has changed. The name field has numbers.'; 

						return $this;				    	
				    }

				    if (!is_numeric($this->players[$i]['idDk'])) {

						$this->message = 'The CSV format has changed. The DK id field has non-numbers.'; 

						return $this;				    	
				    }

				    if (!is_numeric($this->players[$i]['salary'])) {

						$this->message = 'The CSV format has changed. The salary field has non-numbers.'; 

						return $this;				    	
				    }

				    $gameInfo = $row[20];
				    $gameInfo = preg_replace("/(\w+@\w+)(\s)(.*)/", "$1", $gameInfo);
				    $gameInfo = preg_replace("/@/", "", $gameInfo);
				    $this->players[$i]['oppTeamNameDk'] = preg_replace("/".$this->players[$i]['teamNameDk']."/", "", $gameInfo);

				    $teams = [

				    	[	
				    		'key' => 'teamNameDk',
				    		'phrase' => ' '
			    		],

			    		[
			    			'key' => 'oppTeamNameDk',
			    			'phrase' => ' opposing '
			    		]
			    	];

				    foreach ($teams as $team) {

					    $teamExists = Team::where('name_dk', $this->players[$i][$team['key']])->count();

					    if (!$teamExists) {

							$this->message = 'The DraftKings'.$team['phrase'].'team name, <strong>'.$this->players[$i][$team['key']].'</strong>, does not exist in the database.'; 

							return $this;
					    }	
				    }
				}

				$i++;
			}
		} 

		$this->save($date);

		return $this;	
	}

	private function save($date) {

		foreach ($this->players as $player) {

			$teamId = Team::where('name_dk', $player['teamNameDk'])->pluck('id')[0];

			$playerExists = Player::where('name_dk', $player['nameDk'])->where('team_id', $teamId)->count();

			if (!$playerExists) {

				$ePlayer = new Player;

				$ePlayer->team_id = $teamId;
				$ePlayer->name_dk = $player['nameDk'];

				$ePlayer->save();
			}
		}

		$this->message = 'Success!';
	}

}