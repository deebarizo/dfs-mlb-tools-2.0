<?php namespace App\Http\Controllers;

use App\PlayerPool;
use App\Team;

use DB;

class PlayerPoolsController extends Controller {

	public function showPlayerPool($id) {

		$playerPool = PlayerPool::find($id);

		$titleTag = $playerPool->date.' - '.$playerPool->time_period.' - Player Pool | ';
		$h2Tag = $playerPool->date.' - '.$playerPool->time_period.' - Player Pool';

		$dkPlayers = DB::table('dk_players')
						->select('players.name_dk',
								 'teams.name_dk as team_name_dk',
								 'dk_players.opp_team_id',
								 'dk_players.position',
								 'lineup_bat',
								 'percent_start_razzball',
								 'lineup_razzball',
								 'fpts_bat',
								 'fpts_razzball',
								 'upside_fpts_razzball',
								 'salary',
								 'dk_players.id',
								 'dk_players.player_pool_id',
								 'dk_players.player_id')
						->join('players', 'players.id', '=', 'dk_players.player_id')
						->join('player_pools', 'player_pools.id', '=', 'dk_players.player_pool_id')
						->join('teams', 'teams.id', '=', 'dk_players.team_id')
						->where('player_pools.id', $id)
						->get();

		$teams = Team::all();

		foreach ($dkPlayers as $dkPlayer) {
			
			foreach ($teams as $team) {
				
				if ($dkPlayer->opp_team_id === $team->id) {

					$dkPlayer->opp_team_name_dk = $team->name_dk;

					break;
				}
			}
		}

		$teams = DB::table('teams')
					->select('teams.name_dk')
					->join('dk_players', 'dk_players.team_id', '=', 'teams.id')
					->where('dk_players.player_pool_id', $id)
					->groupBy('teams.id')
					->lists('teams.name_dk');

		sort($teams);

		return view('player_pools/index', compact('titleTag', 'h2Tag', 'teams', 'dkPlayers'));
	}

	public function showPlayerPools() {

		$titleTag = 'Player Pools | ';

		$playerPools = PlayerPool::take(50)->orderBy('date', 'desc')->get();

		foreach ($playerPools as $playerPool) {
			
			$playerPool->time_period_in_url = preg_replace('/ /', '_', strtolower($playerPool->time_period));
			$playerPool->site_in_url = strtolower($playerPool->site);
		}

		return view('pages/player_pools', compact('titleTag', 'playerPools'));
	}

}