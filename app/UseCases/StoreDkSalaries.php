<?php namespace App\UseCases;

class StoreDkSalaries {

	public static function perform($csvFile) {

		return (new static)->handle($csvFile);
	}

	private function handle($csvFile) {

		$this->parseCsvFile($csvFile);

		if ($this->message === 'Success!') {

			$this->save();
		}

		# dd($this->message);

		return $this->message;
	}

	private function parseCsvFile($csvFile) {

		if (($handle = fopen($csvFile, 'r')) !== false) {
			
			$rowCount = 0;

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($rowCount != 0) { // skip first row because it contains the table names
				
				    $player[$rowCount] = array(

				    	'position' => $row[0],
				       	'playerNameDk' => $row[1],
				       	'salary' => $row[2],
				       	'teamNameDk' => $row[5]
				    );

				    $gameInfo = $row[3];
				    $gameInfo = preg_replace("/(\w+@\w+)(\s)(.*)/", "$1", $gameInfo);
				    $gameInfo = preg_replace("/@/", "", $gameInfo);
				    $player[$rowCount]['oppTeamNameDk'] = preg_replace("/".$player[$rowCount]['teamNameDk']."/", "", $gameInfo);

				    $teamExists = Team::where('name_dk', $player[$row]['teamNameDk'])->count();

				    if (!$teamExists) {

						$this->message = 'The DraftKings team name, <strong>'.$player[$row]['abbr_dk'].'</strong>, does not exist in the database.'; 

						return $this;
				    }				    
				}

				$row++;
			}
		} 

		$this->message = 'Success!';

		return $this;
	}

	private function save() {

	    $playerId = Player::where('name', $player[$row]['name'])->pluck('id');

	    # dd($playerId);

	    if (is_null($playerId)) {
			return 'The player name, <strong>'.$player[$row]['name'].'</strong>, does not exist in the database. You can add him <a target="_blank" href="http://dfstools.dev:8000/admin/nba/add_player">here</a>.'; 
	    } 

		return $this;
	}

}