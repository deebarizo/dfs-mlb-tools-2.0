<?php

/****************************************************************************************
PRINT VARIABLE
****************************************************************************************/

function ddAll($var) {

	echo '<pre>';
	print_r($var);
	echo '</pre>';

	exit();
}

function prf($var) {

    echo '<pre>';
    print_r($var);
    echo '</pre>';
}


/****************************************************************************************
SET ACTIVE TAB
****************************************************************************************/

function setActive($path, $active = 'active') {
	return Request::is($path) ? $active : '';
}


/****************************************************************************************
SET DATES
****************************************************************************************/

function getTodayDate() {

	date_default_timezone_set('America/Chicago'); 
	
	return date('Y-m-d');	
}

function getYesterdayDate() {

	date_default_timezone_set('America/Chicago'); 
	
	return date('Y-m-d', strtotime("-1 days"));;	
}


/****************************************************************************************
CONVERT ACCENT LETTERS TO ENGLISH
****************************************************************************************/

function convertAccentLettersToEnglish($string) {

	// http://stackoverflow.com/questions/158241/php-replace-umlauts-with-closest-7-bit-ascii-equivalent-in-an-utf-8-string/158265#158265
	// http://stackoverflow.com/a/9200854

	// I couldn't figure out a way to test this

	setlocale(LC_ALL, 'en_US.UTF8');

	return iconv("utf-8", "ascii//TRANSLIT", utf8_encode($string)); 
}


/****************************************************************************************
NUMBER FORMAT
****************************************************************************************/

function numFormat($number, $decimalPlaces = 2) {
	$number = number_format(round($number, $decimalPlaces), $decimalPlaces);

	return $number;
}
	