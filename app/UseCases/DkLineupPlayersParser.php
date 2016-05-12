<?php namespace App\UseCases;

ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

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

		ActualLineup::where('raw_text_players_parsed', 0)->chunk(100, function($actualLineups) {

			set_time_limit(120);
		  	
		  	foreach ($actualLineups as $actualLineup) {

		  		$rawText = $actualLineup->raw_text_players;

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
		  	}
		});

		if ($this->message !== 'No errors.') {

			return $this;
		}

    	$this->message = 'Success!';

    	return $this;
    }

}