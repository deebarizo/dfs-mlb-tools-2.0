<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\PlayerPool;
use App\Team;
use App\DkPlayer;

use DB;

class LineupsController extends Controller {

	public function createLineup($playerPoolId) {

		$playerPool = PlayerPool::find($playerPoolId);

		$titleAndHeadingPhrase = 'Create Lineup';

		$titleTag = $playerPool->date.' - '.$playerPool->time_period.' - '.$titleAndHeadingPhrase.' | ';
		$h2Tag = $playerPool->date.' - '.$playerPool->time_period.' - '.$titleAndHeadingPhrase;

		$dkPlayers = DkPlayer::with('team')
								->with('opp_team')
								->with('player')
								->where('player_pool_id', $playerPoolId)
								->where('fpts_razzball', '!=', 0)->get();

		$positions = ['SP', 'SP', '1B', '2B', '3B', 'SS', 'OF', 'OF', 'OF'];

		return view('player_pools/create_lineup', compact('titleTag', 'h2Tag', 'dkPlayers', 'positions'));
	}
	
}