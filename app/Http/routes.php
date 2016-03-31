<?php

/****************************************************************************************
PAGES
****************************************************************************************/

$router->get('/', function() {

	$titleTag = '';
	
	return View::make('pages/home', compact('titleTag'));
});

$router->get('/scrapers', function() {

	$titleTag = 'Scrapers | ';
	
	return View::make('pages/scrapers', compact('titleTag'));
});

/****************************************************************************************
SCRAPERS
****************************************************************************************/

$router->get('scrapers/dk_salaries', function() {

	return View::make('scrapers/dk_salaries');
});
