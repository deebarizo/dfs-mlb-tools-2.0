<?php namespace App\UseCases;

ini_set('max_execution_time', 1200); // 1200 seconds = 20 minutes

use App\Team;
use App\PlayerPool;
use App\Player;
use App\DkPlayer;
use App\DkActualLineup;
use App\DkActualLineupPlayer;

use DB;

trait DkOwnershipsParser {

	public function parseDkOwnerships() {

		$playerPoolIds = DB::table('dk_actual_lineups')
							->select('player_pool_id')
							->groupBy('player_pool_id')
							->lists('player_pool_id');

		foreach ($playerPoolIds as $playerPoolId) {

			$numOfDkActualLineups = DkActualLineup::where('player_pool_id', $playerPoolId)->count();

			$dkPlayers = DkPlayer::where('ownerships_parsed', 0)->where('player_pool_id', $playerPoolId)->get();

			foreach ($dkPlayers as $dkPlayer) {
				
				set_time_limit(60);

				$player = Player::where('id', $dkPlayer->player_id)->get();

	            $numOfLineupsWithDkPlayer = DB::table('player_pools')
	                                        ->join('dk_actual_lineups', 'dk_actual_lineups.player_pool_id', '=', 'player_pools.id')
	                                        ->join('dk_actual_lineup_players', 'dk_actual_lineup_players.dk_actual_lineup_id', '=', 'dk_actual_lineups.id')
	                                        ->where('player_pools.id', $playerPoolId)
	                                        ->where('dk_actual_lineup_players.dk_player_id', $dkPlayer->id)
	                                        ->count();

	            if ($numOfLineupsWithDkPlayer > 0) {

	                $ownership = numFormat($numOfLineupsWithDkPlayer / $numOfDkActualLineups * 100, 1);

	                $dkPlayer->update(['ownership' => $ownership, 'ownership_of_first_position' => $ownership]);
	            }

	            if (strpos($dkPlayer->position, '/') !== false) {

	                $positions = [];

	                $positions['first'] = preg_replace("/(\w+)(\/)(\w+)/", "$1", $dkPlayer->position);
	                $positions['second'] = preg_replace("/(\w+)(\/)(\w+)/", "$3", $dkPlayer->position);

	                foreach ($positions as $key => $position) {

	                    $numOfLineupsWithDkPlayer = DB::table('player_pools')
	                                                ->join('dk_actual_lineups', 'dk_actual_lineups.player_pool_id', '=', 'player_pools.id')
	                                                ->join('dk_actual_lineup_players', 'dk_actual_lineup_players.dk_actual_lineup_id', '=', 'dk_actual_lineups.id')
	                                                ->where('player_pools.id', $playerPoolId)
	                                                ->where('dk_actual_lineup_players.dk_player_id', $dkPlayer->id)
	                                                ->where('dk_actual_lineup_players.position', $position)
	                                                ->count();

	                    if ($numOfLineupsWithDkPlayer > 0) {

	                        $ownership = numFormat($numOfLineupsWithDkPlayer / $numOfDkActualLineups * 100, 1);

	                        $dkPlayer->update(['ownership_of_'.$key.'_position' => $ownership]);
	                    }                    
	                }
	            }

	            $dkPlayer->update(['ownerships_parsed' => 1]);
			}
		}

		DB::table('dk_actual_lineup_players')->delete();

		$this->message = 'Success!';

		return $this;
	}

}