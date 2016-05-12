<?php namespace App\UseCases;

ini_set('max_execution_time', 1200); // 1200 seconds = 20 minutes

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

use DB;

trait DkLineupPlayersParser {

    public function parseDkLineupPlayers() {

    	$this->message = 'No errors.';

    	$actualLineups = ActualLineup::take(3000)->where('raw_text_players_parsed', 0)->get();

	  	foreach ($actualLineups as $actualLineup) {

	  		set_time_limit(120);

	  		$rawText = $actualLineup->raw_text_players;

	  		if ($rawText != '') {

		        $rawText = preg_replace("/\sP\s/", "|P ", $rawText);
		        $rawText = preg_replace("/\sC\s/", "|C ", $rawText);
		        $rawText = preg_replace("/\s1B\s/", "|1B ", $rawText);
		        $rawText = preg_replace("/\s2B\s/", "|2B ", $rawText);
		        $rawText = preg_replace("/\s3B\s/", "|3B ", $rawText);
		        $rawText = preg_replace("/\sSS\s/", "|SS ", $rawText);
		        $rawText = preg_replace("/\sOF\s/", "|OF ", $rawText);

		        $rawTextPlayers = explode('|', $rawText);

		        if (count($rawTextPlayers) !== 10) {

		            $this->message = 'The actual lineup with the ID of '.$actualLineup->id.' does not have 10 players.';

		            return $this;
		        } 

		        $lineupPlayers = [];

		        foreach ($rawTextPlayers as $rawTextPlayer) {

		            $position = preg_replace("/^(\w+)(\s)(.+)/", "$1", $rawTextPlayer);

		            $name = preg_replace("/^(\w+)(\s)(.+)/", "$3", $rawTextPlayer);

		            $dkSalary = DB::table('players')
		                            ->join('dk_salaries', 'dk_salaries.player_id', '=', 'players.id')
		                            ->select('*')
		                            ->where('players.name_dk', $name)
		                            ->where('dk_salaries.position', 'like', '%'.$position.'%')
		                            ->where('dk_salaries.player_pool_id', $actualLineup->player_pool_id)
		                            ->get();

		            if (count($dkSalary) === 0) {

		                $this->message = 'The actual lineup with the ID of '.$actualLineup->id.' has a missing player in database: '.$rawTextPlayer.'.';

		                return $this;
		            }

		            $lineupPlayers[] = [

		                'position' => $position,
		                'dkSalaryId' => $dkSalary[0]->id
		            ];
		        }

	            foreach ($lineupPlayers as $lineupPlayer) {
	                
	                $actualLineupPlayer = new ActualLineupPlayer;

	                $actualLineupPlayer->actual_lineup_id = $actualLineup->id;
	                $actualLineupPlayer->position = $lineupPlayer['position'];
	                $actualLineupPlayer->dk_salary_id = $lineupPlayer['dkSalaryId'];

	                $actualLineupPlayer->save();
	            }
	  		}

            $actualLineup->update(['raw_text_players_parsed' => 1]);
	  	}

		if ($this->message !== 'No errors.') {

			return $this;
		}

    	$this->message = 'Success!';

    	return $this;
    }

}