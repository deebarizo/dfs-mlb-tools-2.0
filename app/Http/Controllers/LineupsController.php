<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\PlayerPool;
use App\DkPlayer;

use DB;

class LineupsController extends Controller {

	public function createLineup($playerPoolId) {

		$dkPlayers = DkPlayer::where('player_pool_id', $playerPoolId)->where('fpts_razzball', '!=', 0)->get();

		ddAll($dkPlayers);

		foreach ($dkPlayers as $dkPlayer) {
			
			ddAll($dkPlayer);
		}
	}

}