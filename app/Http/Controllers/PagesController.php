<?php namespace App\Http\Controllers;

use App\PlayerPool;
use App\ActualLineup;

use DB;

class PagesController extends Controller {

	public function home() {

		$titleTag = '';

		$playerPools = PlayerPool::take(50)->orderBy('date', 'desc')->get();

		foreach ($playerPools as $playerPool) {
			
			$playerPool->time_period_in_url = preg_replace('/ /', '_', strtolower($playerPool->time_period));
			$playerPool->site_in_url = strtolower($playerPool->site);
		}

		return view('pages/home', compact('titleTag', 'playerPools'));
	}

}