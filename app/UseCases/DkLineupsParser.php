<?php namespace App\UseCases;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

trait DkLineupsParser {

	public function parseDkLineups($csvFile, $date, $site, $timePeriod) {

        $playerPool = PlayerPool::where('date', $date)
                         			  ->where('site', $site)
                                      ->where('time_period', $timePeriod)
                                      ->get();

        if (count($playerPool) === 0) {

            $this->message = 'This player pool does not exist.';

            return $this;
        } 

        $actualLineups = ActualLineup::where('player_pool_id', $playerPool[0]->id)->get();

        if (count($actualLineups) > 0) {

            $this->message = 'This player pool has already been parsed.';

            return $this;        	
        }
    }

}