<?php namespace App\UseCases;

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

trait DkLineupsParser {

	public function parseDkLineups($csvFile, $date, $site, $timePeriod) {

        $playerPoolExists = PlayerPool::where('date', $date)
                         			  ->where('site', $site)
                                      ->where('time_period', $timePeriod)
                                      ->count();

        if (!$playerPoolExists) {

            $this->message = 'This player pool does not exist.';

            return $this;
        } 


    }

}