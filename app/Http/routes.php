<?php

use App\DkActualLineup;
use App\DkPlayer;

/****************************************************************************************
PAGES
****************************************************************************************/

Route::get('/', function() {

	return redirect('/player_pools');
});

Route::get('/player_pools', 'PlayerPoolsController@showPlayerPools');

Route::get('/admin', function() {

	$titleTag = 'Admin | ';
	
	return View::make('pages/admin', compact('titleTag'));
});


/****************************************************************************************
ADMIN
****************************************************************************************/

Route::get('/admin/parsers/dk_players', ['as' => 'admin.parsers.dk_players', function() {

	$titleTag = 'DK Players - Parsers | ';
	$h2Tag = 'Parsers - DK Players';

	return View::make('/admin/parsers/dk_players', compact('titleTag', 'h2Tag'));
}]);

Route::post('/admin/parsers/dk_players', 'ParsersController@parseDkPlayers');


Route::get('/admin/parsers/dk_actual_lineups', ['as' => 'admin.parsers.dk_actual_lineups', 'uses' => 'ParsersController@showParseDkActualLineups']);

Route::post('/admin/parsers/dk_actual_lineups', 'ParsersController@parseDkActualLineups');


Route::get('/admin/parsers/dk_actual_lineup_players', ['as' => 'admin.parsers.dk_actual_lineup_players', function() {

	$titleTag = 'DK Actual Lineup Players - Parsers | ';
	$h2Tag = 'Parsers - DK Actual Lineup Players';

	$numOfUnparsedDkActualLineups = DkActualLineup::where('raw_text_players_parsed', 0)->count();

	return View::make('/admin/parsers/dk_actual_lineup_players', compact('titleTag', 'h2Tag', 'numOfUnparsedDkActualLineups'));
}]);

Route::post('/admin/parsers/dk_actual_lineup_players', 'ParsersController@parseDkActualLineupPlayers');


Route::get('/admin/parsers/dk_ownerships', ['as' => 'admin.parsers.dk_ownerships', function() {

	$titleTag = 'DK Ownerships - Parsers | ';
	$h2Tag = 'Parsers - DK Ownerships';

	$numOfUnparsedDkPlayers = DkPlayer::where('ownerships_parsed', 0)->count();

	return View::make('/admin/parsers/dk_ownerships', compact('titleTag', 'h2Tag', 'numOfUnparsedDkPlayers'));
}]);

Route::post('/admin/parsers/dk_ownerships', 'ParsersController@parseDkOwnerships');


Route::get('/admin/parsers/projections', ['as' => 'admin.parsers.projections', 'uses' => 'ParsersController@showParseProjections']);

Route::post('/admin/parsers/projections', 'ParsersController@parseProjections');
