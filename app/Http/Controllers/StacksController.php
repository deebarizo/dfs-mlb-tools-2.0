<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\PlayerPool;

use DB;

class StacksController extends Controller {

	public function showStacks($playerPoolId) {

		$playerPool = PlayerPool::find($playerPoolId);

		$titleTag = $playerPool->date.' - '.$playerPool->time_period.' - Stacks | ';
		$h2Tag = $playerPool->date.' - '.$playerPool->time_period.' - Stacks';

		$teams = DB::table('dk_players')
					->select('teams.name_dk', 'teams.id')
					->join('player_pools', 'player_pools.id', '=', 'dk_players.player_pool_id')
					->join('teams', 'teams.id', '=', 'dk_players.team_id')
					->where('player_pools.id', $playerPoolId)
					->groupBy('teams.id')
					->orderBy('teams.id')
					->get();

		foreach ($teams as $team) {

			$lineupIsOut = DB::table('dk_players')
							->join('player_pools', 'player_pools.id', '=', 'dk_players.player_pool_id')
							->join('players', 'players.id', '=', 'dk_players.player_id')
							->join('teams', 'teams.id', '=', 'dk_players.team_id')
							->where('player_pools.id', $playerPoolId)
							->where('dk_players.team_id', $team->id)
							->where(function($query) {
								return $query->where('lineup_bat', 1)
												->orWhere('lineup_bat', 2);
							})
							->count();

			if (!$lineupIsOut) {

				$dkPlayers = DB::table('dk_players')
						->select(DB::raw('dk_players.player_id, 
										  dk_players.team_id,
										  teams.name_dk, 
										  (fpts_bat + upside_fpts_razzball) / 2 as muPts,
										  position'))
						->join('player_pools', 'player_pools.id', '=', 'dk_players.player_pool_id')
						->join('players', 'players.id', '=', 'dk_players.player_id')
						->join('teams', 'teams.id', '=', 'dk_players.team_id')
						->where('player_pools.id', $playerPoolId)
						->where('dk_players.team_id', $team->id)
						->where('fpts_bat', '!=', 0)
						->where('upside_fpts_razzball', '!=', 0)
						->where(function($query) {
							return $query->where('position', '!=', 'SP')
											->orWhere('position', '!=', 'RP');
						})
						->orderBy('muPts', 'desc')
						->get();
			}

			if ($lineupIsOut) {

				$dkPlayers = DB::table('dk_players')
						->select(DB::raw('dk_players.player_id, 
										  dk_players.team_id,
										  teams.name_dk, 
										  (fpts_bat + upside_fpts_razzball) / 2 as muPts,
										  position'))
						->join('player_pools', 'player_pools.id', '=', 'dk_players.player_pool_id')
						->join('players', 'players.id', '=', 'dk_players.player_id')
						->join('teams', 'teams.id', '=', 'dk_players.team_id')
						->where('player_pools.id', $playerPoolId)
						->where('dk_players.team_id', $team->id)
						->where('fpts_bat', '!=', 0)
						->where('upside_fpts_razzball', '!=', 0)
						->where('lineup_bat', '!=', '')
						->where('lineup_bat', '!=', 'N/C')
						->where(function($query) {
							return $query->where('position', '!=', 'SP')
											->orWhere('position', '!=', 'RP');
						})
						->orderBy('muPts', 'desc')
						->get();
			}

			$totalMuPts = 0;
			$takenPositions = [];

			foreach ($dkPlayers as $dkPlayer) {

				if (count($takenPositions) === 5) {

					break;
				}

				$positionTaken = false;
				
				foreach ($takenPositions as $takenPosition) {
					
					if ($dkPlayer->position === $takenPosition && $dkPlayer->position !== 'OF') {

						$positionTaken = true;

						break;
					}
				}

				if ($positionTaken) {

					$positionTaken = false;

					continue;
				}

				array_push($takenPositions, $dkPlayer->position);

				$totalMuPts += $dkPlayer->muPts;
			}

			$team->avgMuPts = numFormat($totalMuPts / 5, 2);

			$team->takenPositions = '';

			foreach ($takenPositions as $key => $takenPosition) {

				if ($key === 4) {

					$team->takenPositions .= $takenPosition;

					break;
				}
				
				$team->takenPositions .= $takenPosition.', ';
			}
		}

		return view('player_pools/show_stacks', compact('titleTag', 'h2Tag', 'playerPoolId', 'teams'));
	}

}