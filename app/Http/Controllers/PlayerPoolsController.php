<?php namespace App\Http\Controllers;

use App\PlayerPool;

use DB;

class PlayerPoolsController extends Controller {

	public function showPlayerPools() {

		$titleTag = '';

		$playerPools = PlayerPool::take(50)->orderBy('date', 'desc')->get();

		foreach ($playerPools as $playerPool) {
			
			$playerPool->time_period_in_url = preg_replace('/ /', '_', strtolower($playerPool->time_period));
			$playerPool->site_in_url = strtolower($playerPool->site);
		}

		return view('pages/player_pools', compact('titleTag', 'playerPools'));
	}

}