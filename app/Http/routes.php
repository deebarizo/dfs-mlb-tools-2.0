<?php

/****************************************************************************************
PAGES
****************************************************************************************/

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('pages/home', compact('titleTag'));
});

Route::get('/admin', function() {

	$titleTag = 'Admin | ';
	
	return View::make('pages/admin', compact('titleTag'));
});

/****************************************************************************************
SCRAPERS
****************************************************************************************/

Route::get('/admin/parsers/dk_salaries', ['as' => 'admin.parsers.dk_salaries', function() {

	$titleTag = 'DK Salaries - Parsers | ';

	return View::make('/admin/parsers/dk_salaries', compact('titleTag'));
}]);

Route::post('/admin/parsers/dk_salaries', 'ParsersController@parseDkSalaries');



