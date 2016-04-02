<?php

/****************************************************************************************
PAGES
****************************************************************************************/

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('pages/home', compact('titleTag'));
});

Route::get('/scrapers', function() {

	$titleTag = 'Scrapers | ';
	
	return View::make('pages/scrapers', compact('titleTag'));
});

/****************************************************************************************
SCRAPERS
****************************************************************************************/

Route::get('/scrapers/dk_salaries', ['as' => 'scrapers.dk_salaries', function() {

	$titleTag = 'DK Salaries - Scrapers | ';

	return View::make('scrapers/dk_salaries', compact('titleTag'));
}]);

Route::post('/scrapers/dk_salaries', 'ScrapersController@scrapeDkSalaries');


/****************************************************************************************
TEAMS
****************************************************************************************/

Route::resource('teams', 'TeamsController');
