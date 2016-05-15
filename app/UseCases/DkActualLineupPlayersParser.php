<?php namespace App\UseCases;

ini_set('max_execution_time', 1200); // 1200 seconds = 20 minutes

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;
use App\DkActualLineup;
use App\DkActualLineupPlayer;

use DB;

trait DkActualLineupPlayersParser {

    public function parseDkActualLineupPlayers() {

    	$this->message = 'No errors.';

    	$dkActualLineups = DkActualLineup::take(3000)->where('raw_text_players_parsed', 0)->get();

	  	foreach ($dkActualLineups as $dkActualLineup) {

	  		set_time_limit(120);

	  		$rawText = $dkActualLineup->raw_text_players;

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

		            $this->message = 'The DK actual lineup with the ID of '.$dkActualLineup->id.' does not have 10 players.';

		            return $this;
		        } 

		        $actualLineupPlayers = [];

		        foreach ($rawTextPlayers as $rawTextPlayer) {

		            $position = preg_replace("/^(\w+)(\s)(.+)/", "$1", $rawTextPlayer);

		            $name = preg_replace("/^(\w+)(\s)(.+)/", "$3", $rawTextPlayer);

		            $dkPlayer = DB::table('players')
		                            ->join('dk_players', 'dk_players.player_id', '=', 'players.id')
		                            ->select('*')
		                            ->where('players.name_dk', $name)
		                            ->where('dk_players.position', 'like', '%'.$position.'%')
		                            ->where('dk_players.player_pool_id', $dkActualLineup->player_pool_id)
		                            ->get();

		            if (count($dkPlayer) === 0) {

		                $this->message = 'The DK actual lineup with the ID of '.$dkActualLineup->id.' has a missing player in database: '.$rawTextPlayer.'.';

		                return $this;
		            }

		            $actualLineupPlayers[] = [

		                'position' => $position,
		                'dkPlayerId' => $dkPlayer[0]->id
		            ];
		        }

	            foreach ($actualLineupPlayers as $actualLineupPlayer) {
	                
	                $dkActualLineupPlayer = new DkActualLineupPlayer;

	                $dkActualLineupPlayer->dk_actual_lineup_id = $dkActualLineup->id;
	                $dkActualLineupPlayer->position = $actualLineupPlayer['position'];
	                $dkActualLineupPlayer->dk_player_id = $actualLineupPlayer['dkPlayerId'];

	                $dkActualLineupPlayer->save();
	            }
	  		}

            $dkActualLineup->update(['raw_text_players_parsed' => 1]);
	  	}

		if ($this->message !== 'No errors.') {

			return $this;
		}

    	$this->message = 'Success!';

    	return $this;
    }

}