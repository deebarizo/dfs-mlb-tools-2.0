<?php namespace App\UseCases;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;

trait DkPlayersParser {

	public function parseDkPlayers($csvFile, $date, $site, $timePeriod) {

        $duplicatePlayerPoolExists = PlayerPool::where('date', $date)
                                               ->where('site', $site)
                                               ->where('time_period', $timePeriod)
                                               ->count();

        if ($duplicatePlayerPoolExists) {

            $this->message = 'This player pool has already been parsed.';

            return $this;
        } 

		if (($handle = fopen($csvFile, 'r')) !== false) {
			
			$i = 0; // index

			$this->dkPlayers = [];

			while (($row = fgetcsv($handle, 5000, ',')) !== false) {
				
				if ($i > 7) { 
				
				    $this->dkPlayers[$i] = array( 

				    	'position' => $row[11],
				       	'nameDk' => convertAccentLettersToEnglish($row[13]),
				       	'dkId' => $row[14],
				       	'salary' => $row[15],
				       	'teamNameDk' => $row[17]
				    );

				    if (is_numeric($this->dkPlayers[$i]['position'])) {

						$this->message = 'The CSV format has changed. The position field has numbers.'; 

						return $this;				    	
				    }

				    if (is_numeric($this->dkPlayers[$i]['nameDk'])) {

						$this->message = 'The CSV format has changed. The name field has numbers.'; 

						return $this;				    	
				    }

				    if (!is_numeric($this->dkPlayers[$i]['dkId'])) {

						$this->message = 'The CSV format has changed. The DK id field has non-numbers.'; 

						return $this;				    	
				    }

				    if (!is_numeric($this->dkPlayers[$i]['salary'])) {

						$this->message = 'The CSV format has changed. The salary field has non-numbers.'; 

						return $this;				    	
				    }

				    if ($this->dkPlayers[$i]['position'] === 'RP') {

				    	$this->dkPlayers[$i]['position'] = 'SP';
				    }

				    $gameInfo = $row[16];
				    $gameInfo = preg_replace("/(\w+@\w+)(\s)(.*)/", "$1", $gameInfo);
				    $gameInfo = preg_replace("/@/", "", $gameInfo);
				    $this->dkPlayers[$i]['oppTeamNameDk'] = preg_replace("/".$this->dkPlayers[$i]['teamNameDk']."/", "", $gameInfo);

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

					    $teamExists = Team::where('name_dk', $this->dkPlayers[$i][$team['key']])->count();

					    if (!$teamExists) {

							$this->message = 'The DraftKings'.$team['phrase'].'team name, <strong>'.$this->dkPlayers[$i][$team['key']].'</strong>, does not exist in the database.'; 

							return $this;
					    }	
				    }
				}

				$i++;
			}
		} 

		$this->saveDkPlayers($date, $site, $timePeriod);

		return $this;	
	}

	private function saveDkPlayers($date, $site, $timePeriod) {

		$playerPool = new PlayerPool;

		$playerPool->date = $date;
		$playerPool->time_period = $timePeriod;
		$playerPool->site = $site;
		$playerPool->buy_in = 0;

		$playerPool->save();

		foreach ($this->dkPlayers as $dkPlayer) {

			$teamId = Team::where('name_dk', $dkPlayer['teamNameDk'])->pluck('id')[0];

			$playerExists = Player::where('name_dk', $dkPlayer['nameDk'])->where('team_id', $teamId)->count();

			if (!$playerExists) {

				$player = new Player;

				$player->team_id = $teamId;
				$player->name_dk = $dkPlayer['nameDk'];

				$player->save();
			}

			$eDkPlayer = new DkPlayer;

			$eDkPlayer->player_pool_id = $playerPool->id;
			$eDkPlayer->player_id = Player::where('name_dk', $dkPlayer['nameDk'])->pluck('id')[0];
			$eDkPlayer->dk_id = $dkPlayer['dkId'];
			$eDkPlayer->team_id = $teamId;
			$eDkPlayer->opp_team_id = Team::where('name_dk', $dkPlayer['oppTeamNameDk'])->pluck('id')[0];
			$eDkPlayer->position = $dkPlayer['position'];
			$eDkPlayer->salary = $dkPlayer['salary'];

			$eDkPlayer->save();
		}

		$this->message = 'Success!';
	}

}