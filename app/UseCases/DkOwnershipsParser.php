<?php namespace App\UseCases;

ini_set('max_execution_time', 1200); // 1200 seconds = 20 minutes

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkSalary;
use App\ActualLineup;
use App\ActualLineupPlayer;

use DB;

trait DkOwnershipsParser {

	public function parseDkOwnerships() {

		$playerPoolIds = DB::table('actual_lineups')
							->select('player_pool_id')
							->groupBy('player_pool_id')
							->lists('player_pool_id');

		foreach ($playerPoolIds as $playerPoolId) {

			$numOfActualLineups = ActualLineup::where('player_pool_id', $playerPoolId)->count();

			$dkSalaries = DkSalary::where('ownerships_parsed', 0)->where('player_pool_id', $playerPoolId)->get();

			foreach ($dkSalaries as $dkSalary) {
				
				set_time_limit(60);

				$player = Player::where('id', $dkSalary->player_id)->get();

	            $numOfLineupsWithPlayer = DB::table('player_pools')
	                                        ->join('actual_lineups', 'actual_lineups.player_pool_id', '=', 'player_pools.id')
	                                        ->join('actual_lineup_players', 'actual_lineup_players.actual_lineup_id', '=', 'actual_lineups.id')
	                                        ->where('player_pools.id', $playerPoolId)
	                                        ->where('actual_lineup_players.dk_salary_id', $dkSalary->id)
	                                        ->count();

	            if ($numOfLineupsWithPlayer > 0) {

	                $ownership = numFormat($numOfLineupsWithPlayer / $numOfActualLineups * 100, 1);

	                $dkSalary->update(['ownership' => $ownership, 'ownership_of_first_position' => $ownership]);
	            }

	            if (strpos($dkSalary->position, '/') !== false) {

	                $positions = [];

	                $positions['first'] = preg_replace("/(\w+)(\/)(\w+)/", "$1", $dkSalary->position);
	                $positions['second'] = preg_replace("/(\w+)(\/)(\w+)/", "$3", $dkSalary->position);

	                foreach ($positions as $key => $position) {

	                    $numOfLineupsWithPlayer = DB::table('player_pools')
	                                                ->join('actual_lineups', 'actual_lineups.player_pool_id', '=', 'player_pools.id')
	                                                ->join('actual_lineup_players', 'actual_lineup_players.actual_lineup_id', '=', 'actual_lineups.id')
	                                                ->where('player_pools.id', $playerPoolId)
	                                                ->where('actual_lineup_players.dk_salary_id', $dkSalary->id)
	                                                ->where('actual_lineup_players.position', $position)
	                                                ->count();

	                    if ($numOfLineupsWithPlayer > 0) {

	                        $ownership = numFormat($numOfLineupsWithPlayer / $numOfActualLineups * 100, 1);

	                        $dkSalary->update(['ownership_of_'.$key.'_position' => $ownership]);
	                    }                    
	                }
	            }

	            $dkSalary->update(['ownerships_parsed' => 1]);
			}
		}

		DB::table('actual_lineup_players')->delete();

		$this->message = 'Success!';

		return $this;
	}

}