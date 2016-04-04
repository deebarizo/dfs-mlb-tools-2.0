<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HelpersTest extends TestCase {

	/** @test */
    public function convert_accent_letters_to_english() {

    	$string = convertAccentLettersToEnglish('Manny Bañuelos');

    	$this->assertContains($string, 'Manny Banuelos'); 

    	$string = convertAccentLettersToEnglish('Félix Hernández');

    	$this->assertContains($string, 'Felix Hernandez'); 
    }

}
