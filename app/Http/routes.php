<?php

use App\ActualLineup;
use App\DkSalary;

/****************************************************************************************
PAGES
****************************************************************************************/

Route::get('/', function() {

	return redirect('/dailies');
});

Route::get('/dailies', 'PagesController@home');

Route::get('/admin', function() {

	$titleTag = 'Admin | ';
	
	return View::make('pages/admin', compact('titleTag'));
});


/****************************************************************************************
DAILIES
****************************************************************************************/

Route::get('/dailies/{date}/{timePeriodInUrl}/{siteInUrl}', 'DailiesController@daily');


/****************************************************************************************
ADMIN
****************************************************************************************/

Route::get('/admin/parsers/dk_salaries', ['as' => 'admin.parsers.dk_salaries', function() {

	$titleTag = 'DK Salaries - Parsers | ';

	return View::make('/admin/parsers/dk_salaries', compact('titleTag'));
}]);

Route::post('/admin/parsers/dk_salaries', 'ParsersController@parseDkSalaries');


Route::get('/admin/parsers/dk_lineups', ['as' => 'admin.parsers.dk_lineups', 'uses' => 'ParsersController@getParseDkLineups']);

Route::post('/admin/parsers/dk_lineups', 'ParsersController@parseDkLineups');


Route::get('/admin/parsers/dk_lineup_players', ['as' => 'admin.parsers.dk_lineup_players', function() {

	$titleTag = 'DK Lineup Players - Parsers | ';

	$numOfUnparsedLineups = ActualLineup::where('raw_text_players_parsed', 0)->count();

	return View::make('/admin/parsers/dk_lineup_players', compact('titleTag', 'numOfUnparsedLineups'));
}]);

Route::post('/admin/parsers/dk_lineup_players', 'ParsersController@parseDkLineupPlayers');


Route::get('/admin/parsers/dk_ownerships', ['as' => 'admin.parsers.dk_ownerships', function() {

	$titleTag = 'DK Ownerships - Parsers | ';

	$numOfUnparsedDkSalaries = DkSalary::where('ownerships_parsed', 0)->count();

	return View::make('/admin/parsers/dk_ownerships', compact('titleTag', 'numOfUnparsedDkSalaries'));
}]);

Route::post('/admin/parsers/dk_ownerships', 'ParsersController@parseDkOwnerships');
