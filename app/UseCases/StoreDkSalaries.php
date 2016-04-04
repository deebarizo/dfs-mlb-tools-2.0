<?php namespace App\UseCases;

use App\Team;

class StoreDkSalaries {

	public function perform($csvFile) {

		$this->parseCsvFile($csvFile);

		if ($this->message === 'Success!') {

			$this->save();
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
				       	'playerNameDk' => convertAccentLettersToEnglish($row[1]),
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

		$this->message = 'Success!';

		return $this;
	}

	private function save() {

	/*    $playerId = Player::where('name', $player[$row]['name'])->pluck('id');

	    if (is_null($playerId)) {
			return 'The player name, <strong>'.$player[$row]['name'].'</strong>, does not exist in the database. You can add him <a target="_blank" href="http://dfstools.dev:8000/admin/nba/add_player">here</a>.'; 
	    } 

		return $this; */
	}

}