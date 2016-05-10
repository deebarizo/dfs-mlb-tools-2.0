<?php

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

Route::get('/dailies/{date}/{time_period_in_url}/{site_in_url}', 'DailiesController@daily');


/****************************************************************************************
ADMIN
****************************************************************************************/

Route::get('/admin/parsers/dk_salaries', ['as' => 'admin.parsers.dk_salaries', function() {

	$titleTag = 'DK Salaries - Parsers | ';

	return View::make('/admin/parsers/dk_salaries', compact('titleTag'));
}]);

Route::post('/admin/parsers/dk_salaries', 'ParsersController@parseDkSalaries');



