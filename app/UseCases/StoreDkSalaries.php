<?php namespace App\UseCases;

use App\Team;
use App\Player;
use App\DkSalary;

class StoreDkSalaries {

	public function perform($csvFile, $date) {

		$this->parseCsvFile($csvFile);

		if ($this->message === 'Csv file was parsed succesfully.') {

			$this->save($date);
		}

		return $this;		
	}

	public function parseCsvFile($csvFile) {

		if (($handle = fopen($csvFile, 'r')) !== false) {
			
			$i = 0; // index

			$this->players = [];

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($i != 0) { // do not parse first row because it contains the table names
				
					// $i - 1 to start at zero index since we skip first row because it contains the table names
				    $this->players[$i] = array( 

				    	'position' => $row[0],
				       	'nameDk' => convertAccentLettersToEnglish($row[1]),
				       	'salary' => $row[2],
				       	'teamNameDk' => $row[5]
				    );

				    $gameInfo = $row[3];
				    $gameInfo = preg_replace("/(\w+@\w+)(\s)(.*)/", "$1", $gameInfo);
				    $gameInfo = preg_replace("/@/", "", $gameInfo);
				    $this->players[$i]['oppTeamNameDk'] = preg_replace("/".$this->players[$i]['teamNameDk']."/", "", $gameInfo);

				    $teamExists = Team::where('name_dk', $this->players[$i]['teamNameDk'])->count();

				    if (!$teamExists) {

						$this->message = 'The DraftKings team name, <strong>'.$this->players[$i]['teamNameDk'].'</strong>, does not exist in the database.'; 

						return $this;
				    }			    

				} else {

					$this->players[$i] = [];
				}

				$i++;
			}
		} 

		// deleting first row because it contains table names and setting array indexes to normal (start with 0 instead of 1)
		array_shift($this->players);

		$this->message = 'Csv file was parsed succesfully.';

		return $this;
	}

	public function save($date) {

		foreach ($this->players as $player) {
			
			$playerExists = Player::where('name_dk', $player['nameDk'])->where('team_id', $player['teamNameDk'])->count();

			if (!$playerExists) {

				$ePlayer = new Player;

				$ePlayer->team_id = Team::where('name_dk', $player['teamNameDk'])->pluck('id')[0];
				$ePlayer->name_dk = $player['nameDk'];

				$ePlayer->save();
			}

			$dkSalary = new dkSalary;

			$dkSalary->date = $date;
			$dkSalary->player_id = Player::where('name_dk', $player['nameDk'])->pluck('id')[0];
			$dkSalary->team_id = Team::where('name_dk', $player['teamNameDk'])->pluck('id')[0];
			$dkSalary->opp_team_id = Team::where('name_dk', $player['oppTeamNameDk'])->pluck('id')[0];
			$dkSalary->position = $player['position'];
			$dkSalary->salary = $player['salary'];

			$dkSalary->save();
		}

		$this->message = 'Success!';

		return $this;
	}

}