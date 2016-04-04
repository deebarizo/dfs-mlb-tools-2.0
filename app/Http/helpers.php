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
SET TODAY DATE
****************************************************************************************/

function setTodayDate() {

	date_default_timezone_set('America/Chicago'); 
	
	return date('Y-m-d');	
}


/****************************************************************************************
CONVERT ACCENT LETTERS TO ENGLISH
****************************************************************************************/

function convertAccentLettersToEnglish($string) {

	// http://stackoverflow.com/questions/158241/php-replace-umlauts-with-closest-7-bit-ascii-equivalent-in-an-utf-8-string/158265#158265

	return iconv("utf-8", "ascii//TRANSLIT", $string); 
}
	