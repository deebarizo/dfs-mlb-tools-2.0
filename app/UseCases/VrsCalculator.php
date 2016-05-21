<?php namespace App\UseCases;

use DB;

trait VrsCalculator {

	public function calculateVrs($dkPlayers, $playerPoolId) {

		$teamsWithLineup = DB::table('dk_players')
								->select('teams.name_dk')
								->join('teams', 'teams.id', '=', 'dk_players.team_id')
								->where('player_pool_id', $playerPoolId)
								->where(function($query) {
									$query->where('dk_players.lineup_bat', 1)
											->orWhere('dk_players.lineup_bat', 2);
								})
								->groupBy('teams.name_dk')
								->orderBy('teams.name_dk', 'asc')
								->lists('teams.name_dk');

		foreach ($dkPlayers as $dkPlayer) {

			foreach ($teamsWithLineup as $teamWithLineup) {

				if ($teamWithLineup === $dkPlayer->team_name_dk && 
					$dkPlayer->position !== 'SP' &&
					$dkPlayer->position !== 'RP' &&
					is_numeric($dkPlayer->lineup_bat) === false) {

					$dkPlayer->percent_start_razzball = 0;
					$dkPlayer->fpts_razzball = numFormat(0, 2);
					$dkPlayer->upside_fpts_razzball = numFormat(0, 2);
					$dkPlayer->fpts_bat = numFormat(0, 2);

					$dkPlayer->mPts = numFormat(0, 2);
					$dkPlayer->muPts = numFormat(0, 2);

					$dkPlayer->bVr = numFormat(0, 2);
					$dkPlayer->rVr = numFormat(0, 2);
					$dkPlayer->ruVr = numFormat(0, 2);
					$dkPlayer->mVr = numFormat(0, 2);
					$dkPlayer->muVr = numFormat(0, 2);

					break;
				}
			}

			$dkPlayer->mPts = numFormat(($dkPlayer->fpts_razzball + $dkPlayer->fpts_bat) / 2, 2);

			if ($dkPlayer->position === 'SP' || $dkPlayer->position === 'RP') {

				$dkPlayer->muPts = numFormat(0, 2);

				$dkPlayer->ruVr = numFormat(0, 2);

				$dkPlayer->muVr = numFormat(0, 2);
			
			} else {

				$dkPlayer->muPts = numFormat(($dkPlayer->upside_fpts_razzball + $dkPlayer->fpts_bat) / 2, 2);

				$dkPlayer->ruVr = numFormat($dkPlayer->upside_fpts_razzball / ($dkPlayer->salary / 1000), 2);

				$dkPlayer->muVr = numFormat(($dkPlayer->fpts_bat + $dkPlayer->upside_fpts_razzball) / ($dkPlayer->salary / 1000) / 2, 2);
			}


			$dkPlayer->bVr = numFormat($dkPlayer->fpts_bat / ($dkPlayer->salary / 1000), 2);
			$dkPlayer->rVr = numFormat($dkPlayer->fpts_razzball / ($dkPlayer->salary / 1000), 2);
			
			$dkPlayer->mVr = numFormat(($dkPlayer->fpts_bat + $dkPlayer->fpts_razzball) / ($dkPlayer->salary / 1000) / 2, 2);
		}

		return $dkPlayers;
	}

}