<?php namespace App\UseCases;

use DkSalariesParser;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;

trait DkSalariesParser {

	public function parseDkSalaries($csvFile, $date, $site, $timePeriod) {

        $duplicatePlayerPoolExists = PlayerPool::where('date', $date)
                                               ->where('site', $site)
                                               ->where('time_period', $timePeriod)
                                               ->count();

        if ($duplicatePlayerPoolExists) {

            $this->message = 'This slate has already been parsed.';

            return $this;
        } 

		if (($handle = fopen($csvFile, 'r')) !== false) {
			
			$i = 0; // index

			$this->players = [];

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($i > 7) { 
				
				    $this->players[$i] = array( 

				    	'position' => $row[11],
				       	'nameDk' => convertAccentLettersToEnglish($row[13]),
				       	'dkId' => $row[14],
				       	'salary' => $row[15],
				       	'teamNameDk' => $row[17]
				    );

				    if (is_numeric($this->players[$i]['position'])) {

						$this->message = 'The CSV format has changed. The position field has numbers.'; 

						return $this;				    	
				    }

				    if (is_numeric($this->players[$i]['nameDk'])) {

						$this->message = 'The CSV format has changed. The name field has numbers.'; 

						return $this;				    	
				    }

				    if (!is_numeric($this->players[$i]['dkId'])) {

						$this->message = 'The CSV format has changed. The DK id field has non-numbers.'; 

						return $this;				    	
				    }

				    if (!is_numeric($this->players[$i]['salary'])) {

						$this->message = 'The CSV format has changed. The salary field has non-numbers.'; 

						return $this;				    	
				    }

				    $gameInfo = $row[16];
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

		$this->save($date, $site, $timePeriod);

		return $this;	
	}

	private function save($date, $site, $timePeriod) {

		$playerPool = new PlayerPool;

		$playerPool->date = $date;
		$playerPool->time_period = $timePeriod;
		$playerPool->site = $site;
		$playerPool->buy_in = 0;

		$playerPool->save();

		foreach ($this->players as $player) {

			$teamId = Team::where('name_dk', $player['teamNameDk'])->pluck('id')[0];

			$playerExists = Player::where('name_dk', $player['nameDk'])->where('team_id', $teamId)->count();

			if (!$playerExists) {

				$ePlayer = new Player;

				$ePlayer->team_id = $teamId;
				$ePlayer->name_dk = $player['nameDk'];

				$ePlayer->save();
			}

			$dkSalary = new dkSalary;

			$dkSalary->player_pool_id = $playerPool->id;
			$dkSalary->player_id = Player::where('name_dk', $player['nameDk'])->pluck('id')[0];
			$dkSalary->dk_id = $player['dkId'];
			$dkSalary->team_id = $teamId;
			$dkSalary->opp_team_id = Team::where('name_dk', $player['oppTeamNameDk'])->pluck('id')[0];
			$dkSalary->position = $player['position'];
			$dkSalary->salary = $player['salary'];

			$dkSalary->save();
		}

		$this->message = 'Success!';
	}

}