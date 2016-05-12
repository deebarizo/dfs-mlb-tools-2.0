<?php namespace App\Http\Controllers;

use App\PlayerPool;
use App\DkSalary;

class DailiesController extends Controller {

	public function daily($date, $timePeriodInUrl, $siteInUrl) {

		$titleTag = 'Daily | ';

		$timePeriod = preg_replace('/_/', ' ', ucwords($timePeriodInUrl));
		$site = strtoupper($siteInUrl);

		$playerPool = PlayerPool::where('date', $date)->where('time_period', $timePeriod)->where('site', $site)->get()[0];

		$dkSalaries = DkSalary::where('player_pool_id', $playerPool->id)->get();

		ddAll($dkSalaries);

		return view('dailies/daily', compact('titleTag', 'dkSalaries'));
	}

}