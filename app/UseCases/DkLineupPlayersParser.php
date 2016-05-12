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

    	$this->message = 'Success!';

    	return $this;
    }

}